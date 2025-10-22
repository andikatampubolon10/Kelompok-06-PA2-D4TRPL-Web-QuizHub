<?php

namespace App\Http\Controllers;

use App\Models\mata_pelajaran;
use App\Models\Kursus;
use App\Models\siswa;
use App\Models\Materi;
use App\Models\soal;
use App\Models\ujian;
use App\Models\NilaiKursus;
use App\Models\TipeNilai;
use App\Models\jawaban_siswa;
use App\Models\jawaban_soal;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\BobotTipeSoal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;


class DashboardsiswaController extends Controller
{
    public function dashboard(Request $request)
    {
    $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }

        $siswa = Siswa::where('id_user', $user->id)->first();
        if (!$siswa) {
            return redirect()->route('login')->with('error', 'Siswa tidak ditemukan');
        }

        // Ambil kursus yang dimiliki siswa dari pivot kursus_siswa
        // dan eager load relasi yang diperlukan
        $courses = $siswa->kursus()
            ->with(['guru', 'kelas', 'mataPelajaran'])
            ->orderBy('kursus.id_kursus', 'desc')
            ->get();

        return view('Role.Siswa.Course.index', [
            'user'    => $user,
            'siswa'   => $siswa,
            'courses' => $courses,
        ]);
    }

public function tipeujian($id_kursus, Request $request)
{
    $user = auth()->user();
    if (!$user) return redirect()->route('login');

    $siswa = Siswa::where('id_user', $user->id)->first();
    if (!$siswa) return redirect()->route('login')->with('error', 'Siswa tidak ditemukan');

    // Pastikan siswa terdaftar dalam kursus ini
    $isEnrolled = $siswa->kursus()->where('kursus.id_kursus', $id_kursus)->exists();
    if (!$isEnrolled) {
        return redirect()->route('Role.Siswa.Course.index')->with('error', 'Kamu belum terdaftar di kursus ini.');
    }

    // Ambil kursus untuk header halaman
    $kursus = Kursus::with(['guru', 'kelas', 'mataPelajaran'])->findOrFail($id_kursus);

    // Ambil ujian berdasarkan kursus
    $ujians = Ujian::with('tipe_ujian')
        ->where('id_kursus', $id_kursus)
        ->orderBy('id_tipe_ujian')
        ->orderBy('waktu_mulai')
        ->get();

    // Kelompokkan ujian per tipe
    $quiz = $ujians->where('id_tipe_ujian', 1); // Kuis
    $uts  = $ujians->where('id_tipe_ujian', 2); // UTS
    $uas  = $ujians->where('id_tipe_ujian', 3); // UAS

    // Cek apakah ujian dapat dimulai atau tidak
    foreach ($ujians as $ujian) {
        $currentTime = \Carbon\Carbon::now();
        $startTime = \Carbon\Carbon::parse($ujian->waktu_mulai);
        $endTime = \Carbon\Carbon::parse($ujian->waktu_selesai);

        if ($currentTime->isBefore($startTime)) {
            // Jika sebelum waktu mulai, beri notifikasi bahwa ujian belum dimulai
            $ujian->status = 'Belum dimulai';
        } elseif ($currentTime->isAfter($endTime)) {
            // Jika sudah lewat waktu selesai, beri notifikasi bahwa ujian sudah selesai
            $ujian->status = 'Selesai';
        } else {
            // Ujian sedang berlangsung
            $ujian->status = 'Berlangsung';
        }
    }

    $materi = Materi::where('id_kursus', $id_kursus)
        ->orderByDesc('tanggal_materi')
        ->orderByDesc('id_materi')
        ->get();

    return view('Role.Siswa.Course.course_exam', [
        'user'  => $user,
        'siswa' => $siswa,
        'kursus'=> $kursus,
        'quiz'  => $quiz,
        'uts'   => $uts,
        'uas'   => $uas,
        'ujians'=> $ujians, // Mengirimkan data ujian ke view
        'materi' => $materi,
    ]);
}


public function enterUjian(Request $request)
{
    $request->validate([
        'id_ujian'  => 'required|integer',
        'id_kursus' => 'required|integer',
        'password'  => 'required|string',
    ]);

    $ujian  = Ujian::findOrFail($request->id_ujian);
    $kursus = Kursus::findOrFail($request->id_kursus);

    // Validasi waktu ujian
    $now   = Carbon::now();
    $start = $ujian->waktu_mulai ? Carbon::parse($ujian->waktu_mulai) : null;
    $end   = $ujian->waktu_selesai ? Carbon::parse($ujian->waktu_selesai) : null;

    if ($start && $now->lt($start)) {
        return back()->with('error', 'Ujian belum dimulai.');
    }
    if ($end && $now->gt($end)) {
        return back()->with('error', 'Waktu ujian telah berakhir.');
    }

    // ==== VALIDASI PASSWORD ====
    // Normalisasi: hapus spasi/pemisah tak terlihat di kiri/kanan
    $input  = preg_replace('/^\s+|\s+$/u', '', (string) $request->password);
    $stored = preg_replace('/^\s+|\s+$/u', '', (string) ($ujian->password_masuk ?? ''));

    if ($stored === '') {
        return back()->with('error', 'Password ujian belum diset oleh guru/admin.');
    }

    // Deteksi apakah yg tersimpan berupa hash umum (bcrypt/argon)
    $isHashed = Str::startsWith($stored, ['$2y$', '$2a$', '$argon2i$', '$argon2id$']);

    $valid = $isHashed ? Hash::check($input, $stored)
                       : hash_equals($stored, $input);

    if (!$valid) {
        return back()->with('error', 'Password ujian salah.');
    }
    // ============================

    // (Opsional) token sesi ujian
    $token = bin2hex(random_bytes(16));
    session([
        'exam_token' => $token,
        'exam_start' => now()->toISOString(),
    ]);

    return redirect()->route('Siswa.Course.ujian.take', [$kursus->id_kursus, $ujian->id_ujian, $ujian->id_tipe_ujian]);
}


public function gate($id_kursus, $id_ujian, Request $request)
    {
        $user = auth()->user();
        $siswa = Siswa::where('id_user', $user->id)->firstOrFail();

        // pastikan terdaftar di kursus
        $isEnrolled = $siswa->kursus()->where('kursus.id_kursus', $id_kursus)->exists();
        if (!$isEnrolled) {
            return redirect()->route('Siswa.Course.index')->with('error', 'Kamu belum terdaftar di kursus ini.');
        }

        $kursus = Kursus::with(['guru','kelas','mataPelajaran'])->findOrFail($id_kursus);
        $ujian  = Ujian::findOrFail($id_ujian);

        // status waktu (untuk info di gate; penegakan utamanya saat enter)
        $now   = Carbon::now();
        $start = $ujian->waktu_mulai ? Carbon::parse($ujian->waktu_mulai) : null;
        $end   = $ujian->waktu_selesai ? Carbon::parse($ujian->waktu_selesai) : null;

        $status = 'Berlangsung';
        if ($start && $now->lt($start)) $status = 'Belum dimulai';
        if ($end && $now->gt($end))     $status = 'Selesai';

        return view('Role.Siswa.Course.gate', [
            'kursus' => $kursus,
            'ujian'  => $ujian,
            'status' => $status,
        ]);
    }

public function soal($id_kursus, $id_ujian)
{
    $user = auth()->user();
    if (!$user) return redirect()->route('login');

    $siswa = \App\Models\Siswa::where('id_user', $user->id)->first();
    if (!$siswa) return redirect()->route('login')->with('error', 'Siswa tidak ditemukan');

    // Pastikan siswa terdaftar di kursus ini
    $enrolled = $siswa->kursus()->where('kursus.id_kursus', $id_kursus)->exists();
    if (!$enrolled) {
        return redirect()->route('Role.Siswa.Course.index')
            ->with('error', 'Kamu belum terdaftar di kursus ini.');
    }

    // Ambil ujian + info kursus untuk header
    $ujian = \App\Models\Ujian::with(['kursus.mataPelajaran','kursus.kelas','kursus.guru'])
        ->findOrFail($id_ujian);

    if ((int)$ujian->id_kursus !== (int)$id_kursus) {
        abort(404);
    }

    // Ambil soal + pilihan
    $soals = \App\Models\Soal::with(['jawaban_soal','tipe_soal'])
        ->where('id_ujian', $id_ujian)
        ->orderBy('id_soal')
        ->get();

    $letters = ['A','B','C','D','E','F','G'];

    $questions = $soals->map(function ($s) use ($letters) {
        $choices    = [];
        $choiceIds  = [];

        if ((int)$s->id_tipe_soal === 1) {
            // Pilihan Ganda: pakai huruf A–E...
            foreach ($s->jawaban_soal as $i => $jwb) {
                $label = $letters[$i] ?? chr(65 + $i);
                $choices[]              = $jwb->jawaban;
                $choiceIds[$label]      = $jwb->id_jawaban_soal;
            }
        } elseif ((int)$s->id_tipe_soal === 2) {
            // Benar/Salah: default True/False jika tidak ada di DB
            if ($s->jawaban_soal->isNotEmpty()) {
                $choices = $s->jawaban_soal->pluck('jawaban')->values()->all();
                $t = $s->jawaban_soal->first(fn($r) => strcasecmp($r->jawaban, 'True')  === 0 || strcasecmp($r->jawaban, 'Benar') === 0);
                $f = $s->jawaban_soal->first(fn($r) => strcasecmp($r->jawaban, 'False') === 0 || strcasecmp($r->jawaban, 'Salah') === 0);
                $choiceIds = ['T' => optional($t)->id_jawaban_soal, 'F' => optional($f)->id_jawaban_soal];
            } else {
                $choices   = ['True','False'];
                $choiceIds = ['T' => null, 'F' => null];
            }
        } // Isian (3): choices kosong

        return [
            'id'         => $s->id_soal,
            'text'       => $s->soal,
            'tipe_id'    => (int)$s->id_tipe_soal,  // 1=PG, 2=TF, 3=Isian
            'choices'    => $choices,               // array teks
            'choice_ids' => $choiceIds,             // map huruf/T/F -> id_jawaban_soal (opsional)
        ];
    })->values();

    // Durasi (detik). Jika null, default 30 menit
    $durationSeconds = $ujian->durasi ? ((int)$ujian->durasi * 60) : 1800;

    return view('Role.Siswa.Course.exam_take', [
        'kursus'    => $ujian->kursus,
        'ujian'     => $ujian,
        'questions' => $questions,              // di Blade: const QUESTIONS = @json($questions);
        'total'     => $questions->count(),
        'duration'  => $durationSeconds,
    ]);
}

public function submitUjian(Request $request, $id_kursus, $id_ujian) {
    $ujian = Ujian::findOrFail($id_ujian);
    $now = now();

    // Logging the time when the submission is attempted
    Log::info("Attempting to submit exam for course ID: {$id_kursus} and exam ID: {$id_ujian} at {$now}");

    // Mengecek apakah ujian sudah dimulai atau sudah berakhir
    if ($now->lt($ujian->waktu_mulai)) {
        Log::warning("Exam {$id_ujian} has not started yet.");
        return back()->with('error', 'Ujian belum dimulai.');
    }

    if ($now->gt($ujian->waktu_selesai)) {
        Log::warning("Exam {$id_ujian} time has already ended.");
        return back()->with('error', 'Waktu ujian telah berakhir.');
    }

    $request->validate([
        'answers_json' => 'required|string',
    ]);

    $user = auth()->user();
    $siswa = Siswa::where('id_user', $user->id)->firstOrFail();

    // Logging the student’s info
    Log::info("Student ID {$siswa->id_siswa} is submitting answers.");

    $answers = json_decode($request->answers_json, true) ?? [];

    $totalNilaiPG = 0;
    $totalNilaiBS = 0;
    $totalBobotPG = 0;
    $totalBobotBS = 0;

    foreach ($answers as $row) {
        $idSoal = $row['id_soal'] ?? null;
        if (!$idSoal) {
            Log::debug("Skipping answer due to missing 'id_soal'.");
            continue;
        }

        // Mengambil soal berdasarkan id_soal
        $soal = Soal::find($idSoal);
        if (!$soal) {
            Log::debug("Skipping question with ID {$idSoal}, question not found.");
            continue;
        }

        // Mengambil jawaban yang benar untuk soal ini
        $jawabanBenar = jawaban_soal::where('id_soal', $idSoal)->where('benar', 1)->first();
        if (!$jawabanBenar) {
            Log::debug("Skipping question {$idSoal}, no correct answer found.");
            continue;
        }

        $isCorrect = false;

        // Jika soal adalah Pilihan Ganda (A, B, C, D, E)
        if ($soal->tipe_soal->nama_tipe_soal === 'Pilihan Berganda') {
            $mappingJawaban = [
                'A' => 1,
                'B' => 2,
                'C' => 3,
                'D' => 4,
                'E' => 5
            ];

            if (isset($mappingJawaban[$row['jawaban_siswa']]) && $mappingJawaban[$row['jawaban_siswa']] == $jawabanBenar->jawaban) {
                $isCorrect = true;
                $totalNilaiPG += $soal->bobot; // Tambahkan bobot soal PG yang benar
            }

            $totalBobotPG += $soal->bobot; // Menambahkan bobot soal PG
        }

        // Jika soal adalah Benar/Salah
        elseif ($soal->tipe_soal->nama_tipe_soal === 'Benar Salah') {
            $jawabanSiswa = ($row['jawaban_siswa'] == 'T') ? true : false;

            if ($jawabanSiswa == $jawabanBenar->benar) {
                $isCorrect = true;
                $totalNilaiBS += $soal->bobot; // Tambahkan bobot soal BS yang benar
            }

            $totalBobotBS += $soal->bobot; // Menambahkan bobot soal BS
        }

        // Menyimpan atau memperbarui jawaban siswa di tabel jawaban_siswa
        jawaban_siswa::updateOrCreate(
            [
                'id_siswa' => $siswa->id_siswa,
                'id_soal' => $idSoal,
            ],
            [
                'jawaban_siswa' => $row['jawaban_siswa'] ?? null,
                'id_jawaban_soal' => $row['id_jawaban_soal'] ?? null,
            ]
        );
    }

    // Mengambil bobot tipe soal untuk tiap tipe soal berdasarkan id_tipe_soal
    $bobotTipeSoal = BobotTipeSoal::where('id_tipe_soal', $soal->id_tipe_soal)
        ->where('id_ujian', $id_ujian)
        ->first();

    // Log if bobot_tipe_soal is not found
    if (!$bobotTipeSoal) {
        Log::error("Bobot tipe soal for exam ID {$id_ujian} not found.");
        return back()->with('error', 'Bobot tipe soal tidak ditemukan.');
    }

    // Menghitung nilai akhir berdasarkan bobot tipe soal
    $nilaiPG = $totalBobotPG > 0 ? ($totalNilaiPG / $totalBobotPG) * 100 : 0;
    $nilaiBS = $totalBobotBS > 0 ? ($totalNilaiBS / $totalBobotBS) * 100 : 0;

    // Hitung nilai akhir berdasarkan bobot tipe soal
    $nilaiFinal = ($nilaiPG + $nilaiBS) * ($bobotTipeSoal->bobot / 100);
    // Logging the final score
    Log::info("Student ID {$siswa->id_siswa} has final score: {$nilaiFinal}");

    // Redirect ke halaman ujian dengan pesan sukses
    return redirect()
        ->route('Siswa.Course.tipeujian', ['id_kursus' => $id_kursus])
        ->with('success', 'Jawaban berhasil dikumpulkan dan nilai berhasil dihitung.');
}

    public function materi(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $siswa = Siswa::where('id_user', $user->id)->first();

        $idKursus = $request->input('id_kursus');

        $kursus = Kursus::find($idKursus);

        $materi = Materi::where('id_kursus', $idKursus)->get();

        return view('materi.index', compact('materi', 'kursus'));
    }

    public function ujian(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $siswa = Siswa::where('id_user', $user->id)->first();

        $idKursus = $request->input('id_kursus');

        $kursus = Kursus::find($idKursus);

        $ujian = Ujian::where('id_kursus', $idKursus)->get();

        return view('ujian.index', compact('ujian', 'kursus'));
    }

    public function kuis(Request $request, $idUjian)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $siswa = Siswa::where('id_user', $user->id)->first();

        if (!$siswa) {
            return redirect()->route('login')->with('error', 'Siswa tidak ditemukan');
        }

        $ujian = Ujian::find($idUjian);

        if (!$ujian) {
            return redirect()->route('Role.Guru.index')->with('error', 'Ujian tidak ditemukan');
        }

        $currentTime = now();

        if ($currentTime->lt($ujian->waktu_mulai) || $currentTime->gt($ujian->waktu_selesai)) {
            return redirect()->back()->with('error', 'Kuis belum dimulai atau sudah selesai.');
        }

        if ($request->has('password')) {
            $passwordInput = $request->input('password');

            if (password_verify($passwordInput, $ujian->password_masuk)) {
                return view('kuis.start', compact('ujian', 'siswa'));
            } else {
                // Jika password salah
                return redirect()->back()->with('error', 'Password yang dimasukkan salah.');
            }
        }

        return view('kuis.enter_password', compact('ujian'));
    }

    
    public function submitKuis(Request $request, $idUjian)
    {
        $user = auth()->user();

        // Pastikan pengguna sudah login
        if (!$user) {
            return redirect()->route('login');
        }

        // Ambil data siswa berdasarkan user yang sedang login
        $siswa = Siswa::where('id_user', $user->id)->first();

        if (!$siswa) {
            return redirect()->route('login')->with('error', 'Siswa tidak ditemukan');
        }

        // Ambil ujian
        $ujian = Ujian::find($idUjian);

        if (!$ujian) {
            return redirect()->route('Role.Guru.index')->with('error', 'Ujian tidak ditemukan');
        }

        // Simpan jawaban siswa
        foreach ($request->jawaban as $idSoal => $idJawabanSoal) {
            JawabanSiswa::create([
                'jawaban_siswa' => $request->jawaban_siswa[$idSoal], // Jawaban yang dipilih siswa
                'id_soal' => $idSoal,
                'id_siswa' => $siswa->id_siswa,
                'id_jawaban_soal' => $idJawabanSoal,
            ]);
        }

        return redirect()->route('kuis.terimakasih')->with('success', 'Jawaban berhasil disubmit.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\mata_pelajaran;
use App\Models\Kursus;
use App\Models\siswa;
use App\Models\Materi;
use App\Models\soal;
use App\Models\jawaban_soal;
use App\Models\TipeNilai;
use App\Models\Nilai;
use App\Models\ujian;
<<<<<<< Updated upstream
<<<<<<< Updated upstream
use App\Models\Nilai;
use App\Models\TipeNilai;
=======
use Illuminate\Support\Facades\DB;
>>>>>>> Stashed changes
=======
use Illuminate\Support\Facades\DB;
>>>>>>> Stashed changes
use App\Models\jawaban_siswa;
use App\Models\jawaban_soal;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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

        // Ambil kursus yang dimiliki siswa dari pivot `kursus_siswa`
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
    // Ambil waktu sekarang dan pastikan menggunakan timezone yang sama
$currentTime = \Carbon\Carbon::now('UTC'); // Menggunakan UTC
$startTime = \Carbon\Carbon::parse($ujian->waktu_mulai)->setTimezone('Asia/Jakarta');
$endTime = \Carbon\Carbon::parse($ujian->waktu_selesai)->setTimezone('Asia/Jakarta');

// Debugging
Log::debug("Waktu Mulai: " . $startTime . " Waktu Sekarang: " . $currentTime . " Waktu Selesai: " . $endTime);

// Perbandingan waktu
if ($currentTime->isBefore($startTime)) {
    $ujian->status = 'Belum dimulai';
} elseif ($currentTime->isAfter($endTime)) {
    $ujian->status = 'Selesai';
} elseif ($currentTime->between($startTime, $endTime)) {
    $ujian->status = 'Berlangsung';
}

    }


    // Ambil materi untuk kursus ini
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

    return redirect()->route('Siswa.Course.ujian.take', [$kursus->id_kursus, $ujian->id_ujian]);
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

public function soal($id_kursus, $id_ujian, $id_tipe_ujian)
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

    // <CHANGE> Ambil ujian dan validasi id_tipe_ujian
    $ujian = \App\Models\Ujian::with(['kursus.mataPelajaran','kursus.kelas','kursus.guru'])
        ->findOrFail($id_ujian);

    if ((int)$ujian->id_kursus !== (int)$id_kursus) {
        abort(404);
    }

    // <CHANGE> Validasi bahwa id_tipe_ujian sesuai dengan ujian
    if ((int)$ujian->id_tipe_ujian !== (int)$id_tipe_ujian) {
        abort(404, 'Tipe ujian tidak sesuai');
    }

    // ... rest of existing code ...
    
    return view('Role.Siswa.Course.exam_take', [
        'kursus'    => $ujian->kursus,
        'ujian'     => $ujian,
        'questions' => $questions,
        'total'     => $questions->count(),
        'duration'  => $durationSeconds,
        'id_tipe_ujian' => $id_tipe_ujian,  // <CHANGE> Pass id_tipe_ujian to view
    ]);
}

<<<<<<< Updated upstream
<<<<<<< Updated upstream

public function submitUjian(Request $request, $id_kursus, $id_ujian) {
    $ujian = Ujian::findOrFail($id_ujian);
    $now = now();

    if ($now->lt($ujian->waktu_mulai)) {
        return back()->with('error', 'Ujian belum dimulai.');
    }

    if ($now->gt($ujian->waktu_selesai)) {
        return back()->with('error', 'Waktu ujian telah berakhir.');
    }

    $request->validate([
        'answers_json' => 'required|string',
    ]);

    $user = auth()->user();
    $siswa = Siswa::where('id_user', $user->id)->firstOrFail();

    $answers = json_decode($request->answers_json, true) ?? [];

    $totalNilai = 0;
    $totalBobot = 0;

    // Loop untuk setiap jawaban siswa
    foreach ($answers as $row) {
        $idSoal = $row['id_soal'] ?? null;
        if (!$idSoal) continue;

        // Update atau buat entri baru jawaban siswa
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

        // Ambil soal untuk pengecekan
        $soal = Soal::find($idSoal);
        if (!$soal) continue;

        // Cek hanya soal tipe PG dan BS
        if (!in_array($soal->tipe_soal->nama_tipe_soal, ['Pilihan Berganda', 'Benar Salah'])) {
            continue;
        }

        // Bobot soal
        $bobotSoal = $soal->bobot ?? 0;

        $isCorrect = false;
        if ($soal->tipe_soal->nama_tipe_soal === 'Pilihan Berganda') {
            // Cek untuk soal Pilihan Ganda (PG)
            $jawabanBenar = jawaban_soal::where('id_soal', $idSoal)->where('benar', 1)->first();
            if ($jawabanBenar && $row['id_jawaban_soal'] == $jawabanBenar->id_jawaban_soal) {
                $isCorrect = true;
            }
        } elseif ($soal->tipe_soal->nama_tipe_soal === 'Benar Salah') {
            // Cek untuk soal Benar Salah (BS)
            $jawabanBenar = jawaban_soal::where('id_soal', $idSoal)->where('benar', 1)->first();
            if ($jawabanBenar) {
                if (($row['jawaban_siswa'] == 'benar' && $jawabanBenar->benar) || 
                    ($row['jawaban_siswa'] == 'salah' && !$jawabanBenar->benar)) {
                    $isCorrect = true;
                }
            }
        }

        // Jika jawaban benar, tambahkan bobot soal ke nilai total
        if ($isCorrect) {
            $totalNilai += $bobotSoal;
        }
        $totalBobot += $bobotSoal;
    }

    // Hitung persentase nilai
    $nilaiAkhir = $totalBobot > 0 ? ($totalNilai / $totalBobot) * 100 : 0;

    // Simpan nilai ke tabel TipeNilai untuk siswa dan ujian yang sesuai
    TipeNilai::updateOrCreate(
        [
            'id_siswa' => $siswa->id_siswa,
            'id_ujian' => $id_ujian,
        ],
        [
            'nilai' => $nilaiAkhir,
        ]
    );

    // Setelah perhitungan nilai per tipe, hitung nilai total di tabel Nilai
    $nilaiTotal = TipeNilai::where('id_siswa', $siswa->id_siswa)
        ->where('id_ujian', $id_ujian)
        ->sum('nilai');

    // Simpan nilai total ke tabel Nilai
    Nilai::updateOrCreate(
        [
            'id_siswa' => $siswa->id_siswa,
            'id_kursus' => $id_kursus,
        ],
        [
            'nilai_total' => $nilaiTotal,
        ]
    );

    return redirect()
        ->route('Siswa.Course.tipeujian', ['id_kursus' => $id_kursus])
        ->with('success', 'Jawaban berhasil dikumpulkan dan nilai berhasil dihitung.');
}

=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
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

    public function submitUjian(Request $request, $id_kursus, $id_ujian) {
    $id_ujian_from_url = $request->route('id_ujian');
    $ujian = Ujian::find($id_ujian_from_url);
    $now = now();

    if ($now->lt($ujian->waktu_mulai)) {
        return back()->with('error', 'Ujian belum dimulai.');
    }

    if ($now->gt($ujian->waktu_selesai)) {
        return back()->with('error', 'Waktu ujian telah berakhir.');
    }

    $request->validate([
        'answers_json' => 'required|string',
    ]);

    $user = auth()->user();
    $siswa = Siswa::where('id_user', $user->id)->firstOrFail();

    $answers = json_decode($request->answers_json, true) ?? [];

    $totalNilai = 0;
    $totalBobot = 0;

    // Loop untuk setiap jawaban siswa
    foreach ($answers as $row) {
        $idSoal = $row['id_soal'] ?? null;
        if (!$idSoal) continue;

        // Update atau buat entri baru jawaban siswa
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

        // Ambil soal untuk pengecekan
        $soal = Soal::find($idSoal);
        if (!$soal) continue;

        // Cek hanya soal tipe PG dan BS
        if (!in_array($soal->tipe_soal->nama_tipe_soal, ['Pilihan Berganda', 'Benar Salah'])) {
            continue;
        }

        // Bobot soal
        $bobotSoal = $soal->bobot ?? 0;

        $isCorrect = false;
        if ($soal->tipe_soal->nama_tipe_soal === 'Pilihan Berganda') {
            // Cek untuk soal Pilihan Ganda (PG)
            $jawabanBenar = jawaban_soal::where('id_soal', $idSoal)->where('benar', 1)->first();
            if ($jawabanBenar && $row['id_jawaban_soal'] == $jawabanBenar->id_jawaban_soal) {
                $isCorrect = true;
            }
        } elseif ($soal->tipe_soal->nama_tipe_soal === 'Benar Salah') {
            // Cek untuk soal Benar Salah (BS)
            $jawabanBenar = jawaban_soal::where('id_soal', $idSoal)->where('benar', 1)->first();
            if ($jawabanBenar) {
                if (($row['jawaban_siswa'] == 'benar' && $jawabanBenar->benar) || 
                    ($row['jawaban_siswa'] == 'salah' && !$jawabanBenar->benar)) {
                    $isCorrect = true;
                }
            }
        }

        // Jika jawaban benar, tambahkan bobot soal ke nilai total
        if ($isCorrect) {
            $totalNilai += $bobotSoal;
        }
        $totalBobot += $bobotSoal;
    }

    // Hitung persentase nilai
    $nilaiAkhir = $totalBobot > 0 ? ($totalNilai / $totalBobot) * 100 : 0;
    $id_tipe_ujian = $ujian->id_tipe_ujian;


TipeNilai::updateOrCreate(
    [
        'id_siswa' => $siswa->id_siswa,
        'id_ujian' => $id_ujian_from_url,  // Use id_ujian from route
    ],
    [
        'nilai' => $nilaiAkhir,
        'id_tipe_ujian' => $id_tipe_ujian,
    ]
);


    // Setelah perhitungan nilai per tipe, hitung nilai total di tabel Nilai
    $nilaiTotal = TipeNilai::where('id_siswa', $siswa->id_siswa)
        ->where('id_ujian', $id_ujian)
        ->sum('nilai');

    // Simpan nilai total ke tabel Nilai
    Nilai::updateOrCreate(
        [
            'id_siswa' => $siswa->id_siswa,
            'id_kursus' => $id_kursus,
        ],
        [
            'nilai_total' => $nilaiTotal,
        ]
    );

    return redirect()
        ->route('Siswa.Course.tipeujian', ['id_kursus' => $id_kursus])
        ->with('success', 'Jawaban berhasil dikumpulkan dan nilai berhasil dihitung.');
<<<<<<< Updated upstream
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
}

public function exitExam($kursus_id, $ujian_id, Request $request)
{
    // Ambil data ujian berdasarkan ID
    $ujian = Ujian::findOrFail($ujian_id);

    // Validasi password keluar
    $request->validate([
        'password_keluar' => 'required|string',
    ]);

    // <CHANGE> Debug: Log the stored hash and its length
    \Log::info('Stored hash length: ' . strlen($ujian->password_keluar));
    \Log::info('Stored hash: ' . $ujian->password_keluar);
    \Log::info('Input password: ' . $request->password_keluar);

    // Cek apakah password yang dimasukkan cocok dengan hash di database
    if (!Hash::check($request->password_keluar, $ujian->password_keluar)) {
        return response()->json([
            'status' => 'error',
            'message' => 'Password salah. Coba lagi.'
        ], 400);
    }

    // Jika password benar, proses keluar ujian
    return response()->json([
        'status' => 'success',
        'redirect' => route('Siswa.Course.index')
    ]);
}
}


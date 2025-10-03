<?php

namespace App\Http\Controllers;

use App\Models\mata_pelajaran;
use App\Models\Kursus;
use App\Models\siswa;
use App\Models\Materi;
use App\Models\Soal;
use App\Models\Ujian;
use Illuminate\Http\Request;
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

        // (Opsional tapi disarankan) Pastikan siswa memang enroll pada kursus ini
        $isEnrolled = $siswa->kursus()->where('kursus.id_kursus', $id_kursus)->exists();
        if (!$isEnrolled) {
            return redirect()->route('Role.Siswa.Course.index')->with('error', 'Kamu belum terdaftar di kursus ini.');
        }

        // Ambil kursus untuk header halaman
        $kursus = Kursus::with(['guru', 'kelas', 'mataPelajaran'])->findOrFail($id_kursus);

        // Ambil ujian pada kursus ini, beserta tipe_ujian-nya
        $ujians = Ujian::with('tipe_ujian')
            ->where('id_kursus', $id_kursus)
            ->orderBy('id_tipe_ujian')
            ->orderBy('waktu_mulai')
            ->get();

        // Kelompokkan per tipe
        $quiz = $ujians->where('id_tipe_ujian', 1); // Kuis
        $uts  = $ujians->where('id_tipe_ujian', 2); // UTS
        $uas  = $ujians->where('id_tipe_ujian', 3); // UAS

        return view('Role.Siswa.Course.course_exam', [
            'user'  => $user,
            'siswa' => $siswa,
            'kursus'=> $kursus,
            'quiz'  => $quiz,
            'uts'   => $uts,
            'uas'   => $uas,
        ]);
    }

    public function enterUjian(Request $request)
{
    $request->validate([
        'id_ujian'  => 'required|integer|exists:ujian,id_ujian',
        'password'  => 'required|string',
        'id_kursus' => 'required|integer|exists:kursus,id_kursus',
    ]);

    $user  = auth()->user();
    if (!$user) return redirect()->route('login');

    $siswa = Siswa::where('id_user', $user->id)->first();
    if (!$siswa) return back()->with('error', 'Siswa tidak ditemukan');

    // (Opsional) pastikan siswa enroll pada kursus terkait
    $enrolled = $siswa->kursus()->where('kursus.id_kursus', $request->id_kursus)->exists();
    if (!$enrolled) {
        return back()->with('error', 'Kamu belum terdaftar di kursus ini.');
    }

    $ujian = Ujian::where('id_ujian', $request->id_ujian)
        ->where('id_kursus', $request->id_kursus)
        ->firstOrFail();

    if (!password_verify($request->password, $ujian->password_masuk)) {
        return back()->with('error', 'Password ujian salah.')->withInput();
    }

    // TODO: arahkan ke halaman mulai ujian milikmu
    // Misal: route('Role.Siswa.Ujian.start', ['id_ujian' => $ujian->id_ujian])
    return redirect()->route('Siswa.Course.ujian.take', [
    'id_kursus' => $request->id_kursus,
    'id_ujian'  => $ujian->id_ujian,
    ])->with('success', 'Password benar. Silakan mulai ujian.');
}

public function soal($id_kursus, $id_ujian)
{
    $user = auth()->user();
    if (!$user) return redirect()->route('login');

    $siswa = Siswa::where('id_user', $user->id)->first();
    if (!$siswa) return redirect()->route('login')->with('error', 'Siswa tidak ditemukan');

    // Pastikan siswa memang terdaftar di kursus ini
    $enrolled = $siswa->kursus()->where('kursus.id_kursus', $id_kursus)->exists();
    if (!$enrolled) {
        return redirect()->route('Role.Siswa.Course.index')->with('error', 'Kamu belum terdaftar di kursus ini.');
    }

    // Ambil ujian (beserta kursus untuk header)
    $ujian = Ujian::with(['kursus.mataPelajaran','kursus.kelas','kursus.guru'])->findOrFail($id_ujian);
    if ($ujian->id_kursus != $id_kursus) {
        abort(404);
    }

    // Ambil semua soal + pilihan jawaban
    $soals = Soal::with(['jawaban_soal', 'tipe_soal'])
        ->where('id_ujian', $id_ujian)
        ->orderBy('id_soal')
        ->get();

    $questions = $soals->map(function ($s) {
        return [
            'id'       => $s->id_soal,
            'text'     => $s->soal,
            'tipe_id'  => $s->id_tipe_soal,  // Pastikan mengirimkan tipe_id
            'choices'  => ($s->id_tipe_soal !== 3) ? $s->jawaban_soal->pluck('jawaban')->values() : [], // Isian tidak butuh choices
        ];
    });

    // Durasi: pakai kolom 'durasi' kalau ada, default 30 menit (1800 detik)
    $durationSeconds = $ujian->durasi ? (int)$ujian->durasi * 60 : 1800;

    return view('Role.Siswa.Course.exam_take', [
        'kursus'   => $ujian->kursus,
        'ujian'    => $ujian,
        'questions'=> $questions,
        'total'    => $questions->count(),
        'duration' => $durationSeconds,
    ]);
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

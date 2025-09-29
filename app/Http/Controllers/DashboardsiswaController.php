<?php

namespace App\Http\Controllers;

use App\Models\mata_pelajaran;
use App\Models\Kursus;
use App\Models\siswa;
use App\Models\Materi;
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

        $idKelas = $siswa->id_kelas;

        $kursus = Kursus::where('id_kelas', $idKelas)->get();

        if ($request->has('enroll_kursus_id') && $request->has('password')) {
            $kursusId = $request->input('enroll_kursus_id');
            $passwordInput = $request->input('password');

            $kursusToEnroll = Kursus::find($kursusId);

            if ($kursusToEnroll && password_verify($passwordInput, $kursusToEnroll->password)) {
                $siswa->kursus_siswa()->create([
                    'id_kursus' => $kursusToEnroll->id_kursus,
                    'id_siswa' => $siswa->id_siswa
                ]);

                return redirect()->route('Role.Guru.index')->with('success', 'Anda berhasil bergabung dengan kursus!');
            } else {
                return redirect()->back()->with('error', 'Password yang dimasukkan salah!');
            }
        }

        return view('Role.Guru.index', compact('siswa', 'kursus', 'user'));
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

    public function soal($idUjian)
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

        // Temukan ujian berdasarkan ID
        $ujian = Ujian::find($idUjian);

        if (!$ujian) {
            return redirect()->route('Role.Guru.index')->with('error', 'Ujian tidak ditemukan');
        }

        // Verifikasi apakah ujian belum selesai dan masih dalam waktu yang ditentukan
        $currentTime = now();

        if ($currentTime->lt($ujian->waktu_mulai) || $currentTime->gt($ujian->waktu_selesai)) {
            return redirect()->back()->with('error', 'Waktu ujian sudah habis atau belum dimulai.');
        }

        // Ambil soal terkait ujian yang dipilih
        $soals = Soal::where('id_ujian', $idUjian)->get();

        // Acak soal jika diperlukan
        if ($ujian->acak) {
            $soals = $soals->shuffle();
        }

        // Ambil jawaban untuk setiap soal dan acak jawaban jika perlu
        foreach ($soals as $soal) {
            $jawaban = Jawaban::where('id_soal', $soal->id_soal)->get();
            if ($ujian->acak_jawaban) {
                $jawaban = $jawaban->shuffle();
            }
            $soal->jawaban = $jawaban;
        }

        // Kirim soal dan jawaban ke view kuis
        return view('kuis.soal', compact('soals', 'ujian'));
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

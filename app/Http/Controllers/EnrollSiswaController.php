<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\kurikulum;
use App\Models\TahunAjaran;
use App\Models\Semester;
use App\Models\mata_pelajaran;
use App\Models\siswa;
use App\Models\kursus;
use Illuminate\Support\Facades\Hash;

class EnrollSiswaController extends Controller
{
    public function kurikulum()
    {
        // ambil semua kurikulum (urutkan opsional)
        $kurikulums = kurikulum::orderBy('nama_kurikulum')->get();

        return view('Role.Siswa.Enroll.kurikulum', compact('kurikulums'));
    }

    public function tahunAjaran($id_kurikulum)
{
    $kurikulum   = kurikulum::findOrFail($id_kurikulum);

    // ambil hanya TA milik kurikulum tsb
    $tahunAjaran = TahunAjaran::where('id_kurikulum', $id_kurikulum)
        ->orderByDesc('Mulai_Tahun_Ajaran')   // bebas: pakai Nama_Tahun_Ajaran juga boleh
        ->get();

    return view('Role.Siswa.Enroll.tahun_ajaran', compact('kurikulum','tahunAjaran'));
}

public function semester($id_kurikulum, $id_tahun_ajaran)
{
    $kurikulum   = kurikulum::findOrFail($id_kurikulum);
    $tahunAjaran = TahunAjaran::findOrFail($id_tahun_ajaran);

    // Hanya semester yang milik tahun ajaran tersebut
    $semesters = Semester::where('ID_Tahun_Ajaran', $id_tahun_ajaran)
        ->orderBy('id_semester')
        ->get();

    return view('Role.Siswa.Enroll.semester', compact(
        'kurikulum', 'tahunAjaran', 'semesters'
    ));
}

public function mataPelajaran($id_kurikulum, $id_tahun_ajaran, $id_semester)
{
    $kurikulum   = kurikulum::findOrFail($id_kurikulum);
    $tahunAjaran = TahunAjaran::findOrFail($id_tahun_ajaran);
    $semester    = Semester::findOrFail($id_semester);

    // FILTER hanya berdasarkan semester (sesuai struktur tabel)
    $mapels = mata_pelajaran::where('id_semester', $id_semester)
        ->orderBy('nama_mata_pelajaran')
        ->get();

    return view('Role.Siswa.Enroll.mata_pelajaran', compact(
        'kurikulum', 'tahunAjaran', 'semester', 'mapels'
    ));
}

public function kursus($id_kurikulum, $id_tahun_ajaran, $id_semester, $id_mata_pelajaran)
    {
        $kurikulum   = kurikulum::findOrFail($id_kurikulum);
        $tahunAjaran = TahunAjaran::findOrFail($id_tahun_ajaran);
        $semester    = Semester::findOrFail($id_semester);
        $mapel       = mata_pelajaran::findOrFail($id_mata_pelajaran);

        $courses = Kursus::with(['guru', 'kelas', 'mataPelajaran', 'tahun_ajaran'])
            ->where('id_mata_pelajaran', $id_mata_pelajaran)
            ->where('ID_Tahun_Ajaran', $id_tahun_ajaran)
            ->get();

        // ambil daftar id_kursus yang SUDAH di-enroll siswa ini
        $user  = auth()->user();
        $siswa = Siswa::where('id_user', $user->id)->first();
        $enrolledIds = $siswa
            ? $siswa->kursus()->pluck('kursus.id_kursus')->toArray()
            : [];

        return view('Role.Siswa.Enroll.kursus', compact(
            'kurikulum','tahunAjaran','semester','mapel','courses','enrolledIds'
        ));
    }

    // ====== Submit password untuk enroll ======
    public function enroll(Request $request)
    {
        $request->validate([
            'id_kursus' => 'required|integer|exists:kursus,id_kursus',
            'password'  => 'required|string',
        ]);

        $user  = auth()->user();
        $siswa = Siswa::where('id_user', $user->id)->firstOrFail();

        $kursus = Kursus::findOrFail($request->id_kursus);

        // Cek sudah terdaftar?
        $already = $siswa->kursus()
            ->where('kursus.id_kursus', $kursus->id_kursus)
            ->exists();
        if ($already) {
            return back()->with('success', 'Kamu sudah terdaftar di kursus ini.');
        }

        // Verifikasi password (disimpan hash di kolom 'password')
        if (!Hash::check($request->password, $kursus->password)) {
            return back()->with('error', 'Password enroll salah.');
        }

        // Enroll
        $siswa->kursus()->attach($kursus->id_kursus); // tabel pivot kursus_siswa

        return back()->with('success', 'Berhasil bergabung ke kursus!');
    }

}

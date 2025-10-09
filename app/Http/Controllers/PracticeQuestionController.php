<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\kurikulum;
use App\Models\kelas;
use App\Models\TahunAjaran;
use App\Models\Semester;
use App\Models\latihan;
use App\Models\soal;
use App\Models\mata_pelajaran;
use App\Models\jawaban_siswa;

class PracticeQuestionController extends Controller
{
    public function kurikulum()
    {
        // ambil semua kurikulum (urutkan opsional)
        $kurikulums = kurikulum::orderBy('nama_kurikulum')->get();

        return view('Role.Siswa.Latihan.kurikulum', compact('kurikulums'));
    }

     public function tahunAjaran($id_kurikulum)
{
    $kurikulum   = kurikulum::findOrFail($id_kurikulum);

    // ambil hanya TA milik kurikulum tsb
    $tahunAjaran = TahunAjaran::where('id_kurikulum', $id_kurikulum)
        ->orderByDesc('Mulai_Tahun_Ajaran')   // bebas: pakai Nama_Tahun_Ajaran juga boleh
        ->get();

    return view('Role.Siswa.Latihan.tahun_ajaran', compact('kurikulum','tahunAjaran'));
}

public function semester($id_kurikulum, $id_tahun_ajaran)
{
    $kurikulum   = kurikulum::findOrFail($id_kurikulum);
    $tahunAjaran = TahunAjaran::findOrFail($id_tahun_ajaran);

    // Hanya semester yang milik tahun ajaran tersebut
    $semesters = Semester::where('ID_Tahun_Ajaran', $id_tahun_ajaran)
        ->orderBy('id_semester')
        ->get();

    return view('Role.Siswa.Latihan.semester', compact(
        'kurikulum', 'tahunAjaran', 'semesters'
    ));
}

public function kelas($id_kurikulum, $id_tahun_ajaran, $id_semester, $id_mata_pelajaran)
{
    $kurikulum   = \App\Models\kurikulum::findOrFail($id_kurikulum);
    $tahunAjaran = \App\Models\TahunAjaran::findOrFail($id_tahun_ajaran);
    $semester    = \App\Models\Semester::findOrFail($id_semester);
    $mapel       = \App\Models\mata_pelajaran::findOrFail($id_mata_pelajaran);

    // ambil semua kelas; silakan ganti query sesuai kebutuhan filter
    $kelas = \App\Models\kelas::orderBy('nama_kelas')->get();

    return view('Role.Siswa.Latihan.kelas', compact(
        'kurikulum','tahunAjaran','semester','mapel','kelas'
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

    return view('Role.Siswa.Latihan.mata_pelajaran', compact(
        'kurikulum', 'tahunAjaran', 'semester', 'mapels'
    ));
}

public function topikByKelas($id_kurikulum, $id_tahun_ajaran, $id_semester, $id_mata_pelajaran, $id_kelas)
{
    // Fetch the required models
    $kurikulum   = \App\Models\kurikulum::findOrFail($id_kurikulum);
    $tahunAjaran = \App\Models\TahunAjaran::findOrFail($id_tahun_ajaran);
    $semester    = \App\Models\Semester::findOrFail($id_semester);
    $mapel       = \App\Models\mata_pelajaran::findOrFail($id_mata_pelajaran);
    $kelas       = \App\Models\kelas::findOrFail($id_kelas);

    // Fetch the topics (latihan)
    $topiks = \App\Models\latihan::with(['guru', 'mataPelajaran', 'kurikulum', 'kelas'])
        ->where('id_kurikulum', $id_kurikulum)
        ->where('id_mata_pelajaran', $id_mata_pelajaran)
        ->where('id_kelas', $id_kelas)
        ->orderByDesc('id_latihan')
        ->get();

    return view('Role.Siswa.Latihan.topik', compact('kurikulum', 'tahunAjaran', 'semester', 'mapel', 'kelas', 'topiks'));
}



public function viewLatihan($id_kurikulum, $id_tahun_ajaran, $id_semester, $id_mata_pelajaran, $id_kelas, $id_latihan)
{
    // Get the relevant models
    $kurikulum   = kurikulum::findOrFail($id_kurikulum);
    $tahunAjaran = TahunAjaran::findOrFail($id_tahun_ajaran);
    $semester    = Semester::findOrFail($id_semester);
    $mapel       = mata_pelajaran::findOrFail($id_mata_pelajaran);
    $kelas       = kelas::findOrFail($id_kelas);
    $latihan     = latihan::findOrFail($id_latihan);

    // Get the questions related to this topic (latihan)
    $soals = Soal::where('id_latihan', $id_latihan)
        ->orderBy('id_soal')
        ->get();

    return view('Role.Siswa.Latihan.exam_take_latihan', compact('kurikulum', 'tahunAjaran', 'semester', 'mapel', 'kelas', 'latihan', 'soals'));
}

public function submitLatihan($id_kurikulum, $id_tahun_ajaran, $id_semester, $id_mata_pelajaran, $id_kelas, $id_latihan, Request $request)
{
    // Process the submitted answers
    $answers = $request->except('_token');
    
    foreach ($answers as $soalId => $jawaban) {
        // Store the student's answer
        jawaban_siswa::create([
            'jawaban_siswa' => $jawaban,
            'id_soal' => substr($soalId, 5), // Remove "soal_" prefix
            'id_siswa' => auth()->user()->siswa->id_siswa,
            'id_latihan' => $id_latihan,
            'id_jawaban_soal' => $jawaban,  // Adjust this based on your logic
        ]);
    }

    return redirect()->route('Siswa.latihan.kelas.topik', [$id_kurikulum, $id_tahun_ajaran, $id_semester, $id_mata_pelajaran, $id_kelas])->with('success', 'Jawaban berhasil dikumpulkan!');
}



}

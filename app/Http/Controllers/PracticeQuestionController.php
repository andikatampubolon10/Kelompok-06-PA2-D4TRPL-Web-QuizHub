<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\kurikulum;
use App\Models\kelas;
use App\Models\TahunAjaran;
use App\Models\Semester;
use App\Models\jawaban_soal;
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



// app/Http/Controllers/LatihanController.php

public function viewLatihan($id_kurikulum, $id_tahun_ajaran, $id_semester, $id_mata_pelajaran, $id_kelas, $id_latihan)
{
    // Get the relevant models
    $kurikulum   = kurikulum::findOrFail($id_kurikulum);
    $tahunAjaran = TahunAjaran::findOrFail($id_tahun_ajaran);
    $semester    = Semester::findOrFail($id_semester);
    $mapel       = mata_pelajaran::findOrFail($id_mata_pelajaran);
    $kelas       = kelas::findOrFail($id_kelas);
    $latihan     = latihan::findOrFail($id_latihan);

    // <CHANGE> Eager load jawaban_soal dan tipe_soal relasi
    $soals = Soal::with(['jawaban_soal', 'tipe_soal'])
        ->where('id_latihan', $id_latihan)
        ->orderBy('id_soal')
        ->get();

    return view('Role.Siswa.Latihan.exam_take_latihan', compact('kurikulum', 'tahunAjaran', 'semester', 'mapel', 'kelas', 'latihan', 'soals'));
}
// app/Http/Controllers/LatihanController.php (atau controller yang sesuai)

// app/Http/Controllers/LatihanController.php

public function submitLatihan(Request $request, $id_kurikulum, $id_tahun_ajaran, $id_semester, $id_mata_pelajaran, $id_kelas, $id_latihan)
{
    $latihan = Latihan::findOrFail($id_latihan);
    $soals = Soal::where('id_latihan', $latihan->id_latihan)->get();
    $kelas = Kelas::findOrFail($id_kelas);

    $correctAnswers = 0;
    $totalWeight = 0;

    foreach ($soals as $soal) {
        // Dapatkan jawaban yang benar
        $correctAnswer = jawaban_soal::where('id_soal', $soal->id_soal)->where('benar', 1)->first();
        $userAnswer = $request->input('soal_' . $soal->id_soal);

        // Periksa jawaban benar
        if ($correctAnswer && $correctAnswer->id_jawaban_soal == $userAnswer) {
            $correctAnswers++;
            $totalWeight += $soal->bobot;
        }
    }

    // Hitung nilai
    $totalQuestions = count($soals);
    $score = ($correctAnswers / $totalQuestions) * 100;

    return view('Role.Siswa.Latihan.hasil_latihan', [
        'latihan' => $latihan,
        'kelas' => $kelas,
        'score' => $score,
        'correctAnswers' => $correctAnswers,
        'totalQuestions' => $totalQuestions,
        'totalWeight' => $totalWeight,
    ]);
}

// <CHANGE> Method baru untuk menampilkan hasil latihan
public function hasilLatihan($id_kurikulum, $id_tahun_ajaran, $id_semester, $id_mata_pelajaran, $id_kelas, $id_latihan)
{
    $siswa = auth()->user()->siswa;
    
    $kurikulum = kurikulum::findOrFail($id_kurikulum);
    $tahunAjaran = TahunAjaran::findOrFail($id_tahun_ajaran);
    $semester = Semester::findOrFail($id_semester);
    $mapel = mata_pelajaran::findOrFail($id_mata_pelajaran);
    $kelas = kelas::findOrFail($id_kelas);
    $latihan = latihan::findOrFail($id_latihan);

    // Ambil hasil dari session
    $hasil = session('hasil_latihan');

    if (!$hasil) {
        return redirect()->route('latihan.kelas.topik', [
            $id_kurikulum, $id_tahun_ajaran, $id_semester, $id_mata_pelajaran, $id_kelas
        ])->with('error', 'Data hasil latihan tidak ditemukan.');
    }

    // Tentukan grade
    $grade = $this->getGrade($hasil['nilai']);

    return view('Role.Siswa.Latihan.hasil_latihan', compact(
        'kurikulum', 'tahunAjaran', 'semester', 'mapel', 'kelas', 'latihan', 'hasil', 'grade'
    ));
}

private function getGrade($nilai)
{
    if ($nilai >= 90) return ['grade' => 'A', 'label' => 'Excellent', 'color' => 'emerald'];
    if ($nilai >= 80) return ['grade' => 'B', 'label' => 'Very Good', 'color' => 'blue'];
    if ($nilai >= 70) return ['grade' => 'C', 'label' => 'Good', 'color' => 'amber'];
    if ($nilai >= 60) return ['grade' => 'D', 'label' => 'Fair', 'color' => 'orange'];
    return ['grade' => 'F', 'label' => 'Needs Improvement', 'color' => 'red'];
}


}

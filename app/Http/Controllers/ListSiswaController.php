<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\siswa;
use App\Models\kursus;
use App\Models\persentase;
use App\Models\Kelas;
use App\Models\TipeNilai;
use App\Models\NilaiKursus;
use App\Models\Nilai;
use App\Exports\NilaiExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ListSiswaController extends Controller
{
    public function index($id_kursus)
    {
        $user = auth()->user();

        $persentase = Persentase::with('tipePersentase')
            ->where('id_kursus', $id_kursus)
            ->get();

        if ($persentase->isEmpty()) {
return redirect()->back()->with('error', 'Persentase belum didefinisikan. Silakan definisikan persentase terlebih dahulu pada bagian Nilai.');
        }

        $kursus = Kursus::findOrFail($id_kursus);

        if (!$kursus->persentase) {
            return redirect()->back()->with('error', 'Persentase belum didefinisikan. Silakan definisikan persentase terlebih dahulu.');
        }

        $firstPersentase = $persentase->first();
        if ($firstPersentase) {
            $kursus = $firstPersentase->kursus;
        } else {
            return redirect()->back()->with('error', 'Persentase pertama tidak ditemukan');
        }

        $siswa = $kursus->siswa()->with('kelas')->orderBy('nama_siswa')->get();

        $nilai = Nilai::where('id_kursus', $id_kursus)
            ->get()
            ->keyBy('id_siswa');

        return view('Role.Guru.Course.listSiswa', compact('siswa', 'kursus', 'user', 'persentase', 'nilai'));
    }

    public function exportNilai($id_kursus)
    {
        $kursus = Kursus::findOrFail($id_kursus);

        $fileName = $kursus->nama_kursus . '_nilai.xlsx';
        $fileName = str_replace(['/', '\\'], '_', $fileName);

        return Excel::download(new NilaiExport($id_kursus), $fileName);
    }

    public function resetAndRecalculateNilai($id_kursus)
    {
        $persentaseCheck = Persentase::where('id_kursus', $id_kursus)->get();
        if ($persentaseCheck->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Persentase belum diatur. Silahkan atur persentase terlebih dahulu.'
            ], 422);
        }

        DB::beginTransaction();

        try {
            // Hapus nilai lama dan hitung ulang
            NilaiKursus::where('id_kursus', $id_kursus)->delete();
            Nilai::where('id_kursus', $id_kursus)->delete();

            $kursus = Kursus::findOrFail($id_kursus);
            $siswaList = $kursus->siswa;
            $results = [];

            foreach ($siswaList as $siswa) {
                $id_siswa = $siswa->id_siswa;
                $tipeUjianList = TipeNilai::where('id_siswa', $id_siswa)
                    ->select('id_tipe_ujian')
                    ->distinct()
                    ->get()
                    ->pluck('id_tipe_ujian');

                $nilai_per_tipe = [];
                foreach ($tipeUjianList as $id_tipe_ujian) {
                    $nilai_tipe = $this->calculateNilaiKursus($id_kursus, $id_siswa, $id_tipe_ujian);
                    $nilai_per_tipe[$id_tipe_ujian] = $nilai_tipe;
                }

                $nilai_total = $this->calculateNilaiTotal($id_kursus, $id_siswa);
                $results[$id_siswa] = [
                    'nilai_total' => $nilai_total,
                    'nama_siswa' => $siswa->nama_siswa
                ];
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Perhitungan nilai berhasil dilakukan untuk semua siswa',
                'data' => [
                    'hasil' => $results,
                    'jumlah_siswa' => count($results)
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat menghitung nilai: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghitung nilai: ' . $e->getMessage()
            ], 500);
        }
    }

    public function calculateAllNilai($id_kursus)
    {
        $persentaseCheck = Persentase::where('id_kursus', $id_kursus)->get();
        if ($persentaseCheck->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Persentase belum diatur. Silahkan atur persentase terlebih dahulu.'
            ], 422);
        }

        DB::beginTransaction();

        try {
            $kursus = Kursus::findOrFail($id_kursus);

            $siswaList = $kursus->siswa;

            $results = [];

            foreach ($siswaList as $siswa) {
                $id_siswa = $siswa->id_siswa;

                $tipeUjianList = TipeNilai::where('id_siswa', $id_siswa)
                    ->select('id_tipe_ujian')
                    ->distinct()
                    ->get()
                    ->pluck('id_tipe_ujian');

                $nilai_per_tipe = [];

                foreach ($tipeUjianList as $id_tipe_ujian) {
                    $nilai_tipe = $this->calculateNilaiKursus($id_kursus, $id_siswa, $id_tipe_ujian);
                    $nilai_per_tipe[$id_tipe_ujian] = $nilai_tipe;
                }

                $nilai_total = $this->calculateNilaiTotal($id_kursus, $id_siswa);

                $results[$id_siswa] = [
                    'nilai_total' => $nilai_total,
                    'nama_siswa' => $siswa->nama_siswa
                ];
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Perhitungan nilai berhasil dilakukan untuk semua siswa',
                'data' => [
                    'hasil' => $results,
                    'jumlah_siswa' => count($results)
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error saat menghitung nilai: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghitung nilai: ' . $e->getMessage()
            ], 500);
        }
    }

    private function calculateNilaiKursus($id_kursus, $id_siswa, $id_tipe_ujian)
    {
        $nilaiList = TipeNilai::where('id_siswa', $id_siswa)
            ->where('id_tipe_ujian', $id_tipe_ujian)
            ->get();

        $totalNilai = 0;
        $count = $nilaiList->count();

        if ($count > 0) {
            $totalNilai = $nilaiList->sum('nilai') / $count;
        }

        NilaiKursus::updateOrCreate(
            [
                'id_kursus' => $id_kursus,
                'id_siswa' => $id_siswa,
                'id_tipe_ujian' => $id_tipe_ujian,  // Kombinasi ini digunakan untuk pencarian
            ],
            [
                'nilai_tipe_ujian' => $totalNilai,  // Update nilai berdasarkan kombinasi yang ada
            ]
        );

        return $totalNilai;
    }

    private function calculateNilaiTotal($id_kursus, $id_siswa)
    {
        $nilaiKursusList = NilaiKursus::where('id_kursus', $id_kursus)
            ->where('id_siswa', $id_siswa)
            ->get();

        $nilaiTotal = 0;

        foreach ($nilaiKursusList as $nilaiKursus) {
            $persentase = Persentase::where('id_kursus', $id_kursus)
                ->where('id_tipe_ujian', $nilaiKursus->id_tipe_ujian)
                ->first();

            if ($persentase) {
                $nilaiTotal += ($nilaiKursus->nilai_tipe_ujian * $persentase->persentase / 100);
            }
        }

        $tipeNilai = TipeNilai::where('id_siswa', $id_siswa)->first();
        $id_tipe_nilai = $tipeNilai ? $tipeNilai->id_tipe_nilai : 1;

        // Update nilai total pada tabel Nilai
        Nilai::updateOrCreate(
            [
                'id_kursus' => $id_kursus,
                'id_siswa' => $id_siswa,
            ],
            [
                'nilai_total' => $nilaiTotal,
                'id_tipe_nilai' => $id_tipe_nilai, // Pastikan tipe nilai juga diperbarui
            ]
        );

        return $nilaiTotal;
    }
}

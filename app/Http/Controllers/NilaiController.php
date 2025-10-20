<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\Kursus;
use App\Models\Siswa;
use App\Models\TipeNilai;
use App\Models\NilaiKursus;
use App\Models\Persentase;
use App\Models\BobotTipeSoal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NilaiController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $guru = $user->guru; // Automatically load the guru relationship

        // Filter courses by the authenticated guru's id_guru
        $courses = Kursus::where('id_guru', $guru->id_guru)->get();

        return view('Role.Guru.Nilai.index', compact('courses', 'user'));
    }

    public function create()
    {
        $user = auth()->user();
        return view('Role.Guru.Nilai.create', compact('user'));
    }

    /**
     * (Tetap tersedia) Hitung nilai kursus & total berdasarkan TipeNilai yang ada.
     * Tidak dipanggil otomatis saat submit ujian karena kamu hanya butuh sampai tipe_nilai.
     */
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
            $siswaList = Siswa::whereHas('kursus', function ($query) use ($id_kursus) {
                $query->where('id_kursus', $id_kursus);
            })->get();

            $results = [];

            foreach ($siswaList as $siswa) {
                $id_siswa = $siswa->id_siswa;

                $tipeUjianList = TipeNilai::where('id_siswa', $id_siswa)
                    ->select('id_tipe_ujian')
                    ->distinct()
                    ->pluck('id_tipe_ujian');

                foreach ($tipeUjianList as $id_tipe_ujian) {
                    $this->calculateNilaiKursus($id_kursus, $id_siswa, $id_tipe_ujian);
                }

                $nilai_total = $this->calculateNilaiTotal($id_kursus, $id_siswa);

                $results[$id_siswa] = [
                    'nilai_total' => $nilai_total
                ];
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Perhitungan nilai berhasil dilakukan untuk semua siswa',
                'data' => [
                    'hasil' => $results,
                    'jumlah_siswa' => $siswaList->count(),
                ],
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error saat menghitung nilai: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

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
                'id_tipe_ujian' => $id_tipe_ujian,
            ],
            [
                'nilai_tipe_ujian' => $totalNilai,
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

        Nilai::updateOrCreate(
            [
                'id_kursus' => $id_kursus,
                'id_siswa' => $id_siswa,
            ],
            [
                'nilai_total' => $nilaiTotal
            ]
        );

        return $nilaiTotal;
    }

    /**
     * Validasi bobot tipe soal untuk satu ujian = 100%.
     */
    public function validateBobotTipeSoal(int $id_ujian)
    {
        $sum = BobotTipeSoal::where('id_ujian', $id_ujian)->sum('bobot');
        if ((int) $sum !== 100) {
            return response()->json([
                'success' => false,
                'message' => 'Total bobot_tipe_soal harus 100% untuk ujian ini. Saat ini: ' . $sum . '%.',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Bobot valid (100%).',
        ]);
    }

    /**
     * ENDPOINT untuk route:
     * GET /Siswa/courses/{id_kursus}/ujian/{id_ujian}/nilai
     *
     * Mengambil total nilai (PB & BS) dari tabel tipe_nilai untuk siswa yang sedang login.
     * - Trigger/procedure sudah menghitung otomatis saat submit/ubah jawaban_siswa.
     * - Isian/Essay diabaikan dari perhitungan otomatis.
     */
    public function gradeUjianSiswaSelf($id_kursus, $id_ujian)
    {
        $user = auth()->user();
        $siswa = $user->siswa ?? null;

        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Akun ini tidak memiliki profil siswa.',
            ], 403);
        }

        $id_siswa = (int) $siswa->id_siswa;

        // Safety: pastikan bobot_tipe_soal = 100 agar prosedur/trigger memang menghitung
        $sum = (int) BobotTipeSoal::where('id_ujian', $id_ujian)->sum('bobot');
        if ($sum !== 100) {
            return response()->json([
                'success' => false,
                'message' => 'Total bobot_tipe_soal harus 100% untuk ujian ini. Saat ini: ' . $sum . '%.',
            ], 422);
        }

        // Ambil nilai akhir dari tipe_nilai (sudah diisi trigger/procedure)
        $row = TipeNilai::where('id_ujian', $id_ujian)
            ->where('id_siswa', $id_siswa)
            ->first(['nilai', 'id_tipe_ujian', 'updated_at']);

        $total = (int) ($row->nilai ?? 0);

        return response()->json([
            'success' => true,
            'message' => 'Nilai ujian (otomatis PB & BS) berhasil diambil.',
            'data' => [
                'id_kursus'        => (int) $id_kursus,
                'id_ujian'         => (int) $id_ujian,
                'id_siswa'         => $id_siswa,
                'nilai_ujian_total'=> $total,
                'id_tipe_ujian'    => $row->id_tipe_ujian ?? null,
                'updated_at'       => $row->updated_at ?? null,
            ],
        ]);
    }
}

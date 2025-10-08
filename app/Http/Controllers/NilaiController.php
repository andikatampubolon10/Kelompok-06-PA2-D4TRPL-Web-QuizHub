<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\Kursus;
use App\Models\Siswa;
use App\Models\TipeNilai;
use App\Models\NilaiKursus;
use App\Models\Persentase;
use App\Models\TipeUjian;
use App\Models\Ujian;
use App\Models\Soal;
use App\Models\TipeSoal;
use App\Models\BobotTipeSoal;
use App\Models\jawaban_siswa;
use App\Models\jawaban_soal;
use App\Models\JawabanSiswa;
use App\Models\JawabanSoal;
use App\Models\tipe_soal;
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

    public function gradeUjian(Request $request)
    {
        $validated = $request->validate([
            'id_ujian' => 'required|integer|exists:ujian,id_ujian',
            'id_siswa' => 'required|integer|exists:siswa,id_siswa',
        ]);

        $id_ujian = (int) $validated['id_ujian'];
        $id_siswa = (int) $validated['id_siswa'];

        // pastikan bobot_tipe_soal = 100
        $sum = BobotTipeSoal::where('id_ujian', $id_ujian)->sum('bobot');
        if ((int) $sum !== 100) {
            return response()->json([
                'success' => false,
                'message' => 'Total bobot_tipe_soal harus 100% untuk ujian ini. Saat ini: ' . $sum . '%.',
            ], 422);
        }

        return DB::transaction(function () use ($id_ujian, $id_siswa) {
            $ujian = Ujian::findOrFail($id_ujian);

            // hitung skor setiap tipe_soal (0-100) lalu kalikan bobot_tipe_soal (%)
            $detail = $this->hitungNilaiPerTipeSoal($id_ujian, $id_siswa);

            $nilaiAkhirUjian = 0.0;

            foreach ($detail as $tipeSoalId => $row) {
                // row: [nama_tipe_soal, total_bobot, benar_bobot, score_0_100, bobot_tipe_soal, weighted]
                $nilaiAkhirUjian += $row['weighted'];

                // simpan ke tipe_nilai per ujian & tipe_ujian
                TipeNilai::updateOrCreate(
                    [
                        'id_siswa' => $id_siswa,
                        'id_ujian' => $id_ujian,
                        'id_tipe_ujian' => $ujian->id_tipe_ujian,
                    ],
                    [
                        // schema decimal(5,0): bundarkan ke integer
                        'nilai' => round($row['weighted']),
                    ]
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Nilai ujian berhasil dihitung dan disimpan.',
                'data' => [
                    'per_tipe_soal' => $detail,
                    'nilai_ujian_total' => round($nilaiAkhirUjian),
                ],
            ]);
        });
    }

    private function hitungNilaiPerTipeSoal(int $id_ujian, int $id_siswa): array
    {
        // ambil semua soal ujian, kelompokkan per tipe
        $soalPerTipe = Soal::where('id_ujian', $id_ujian)->get()->groupBy('id_tipe_soal');

        // mapping id_tipe_soal -> nama untuk kemudahan
        $tipeMap = tipe_soal::pluck('nama_tipe_soal', 'id_tipe_soal');

        // bobot_tipe_soal (%)
        $bobotPerTipe = BobotTipeSoal::where('id_ujian', $id_ujian)
            ->pluck('bobot', 'id_tipe_soal');

        $result = [];

        foreach ($soalPerTipe as $id_tipe_soal => $listSoal) {
            $namaTipe = $tipeMap[$id_tipe_soal] ?? 'Unknown';
            $totalBobotTipe = (float) $listSoal->sum('bobot');

            // semua jawaban siswa untuk soal ujian ini (agar 1x query per tipe)
            $idsSoal = $listSoal->pluck('id_soal')->all();
            $jawabanSiswa = jawaban_siswa::whereIn('id_soal', $idsSoal)
                ->where('id_siswa', $id_siswa)
                ->get()
                ->keyBy('id_soal');

            $bobotBenar = 0.0;

            foreach ($listSoal as $soal) {
                $jawab = $jawabanSiswa->get($soal->id_soal);
                if (!$jawab) {
                    continue;
                }

                if ($this->isJawabanBenar((string) $namaTipe, (int) $soal->id_soal, $jawab)) {
                    $bobotBenar += (float) $soal->bobot;
                }
            }

            // normalisasi 0..100 untuk tipe ini
            $score = $totalBobotTipe > 0 ? ($bobotBenar / $totalBobotTipe) * 100.0 : 0.0;

            // bobot tipe soal (%)
            $bobotTipe = (float) ($bobotPerTipe[$id_tipe_soal] ?? 0);

            // skor tertimbang kontribusi ke nilai ujian
            $weighted = ($score * $bobotTipe) / 100.0;

            $result[$id_tipe_soal] = [
                'nama_tipe_soal' => $namaTipe,
                'total_bobot' => $totalBobotTipe,
                'benar_bobot' => $bobotBenar,
                'score_0_100' => round($score, 2),
                'bobot_tipe_soal' => $bobotTipe,
                'weighted' => round($weighted, 2),
            ];
        }

        return $result;
    }

    private function isJawabanBenar(string $namaTipe, int $id_soal, jawaban_siswa $row): bool
    {
        // Pilihan Berganda / Benar Salah:
        // - cek flag benar pada jawaban_soal yang dipilih siswa
        // Isian:
        // - bandingkan teks jawaban siswa dengan kunci pada jawaban_soal yang benar (case-insensitive & trim)
        $kunci = null;
        if (!empty($row->id_jawaban_soal)) {
            $kunci = jawaban_soal::find($row->id_jawaban_soal);
        }

        $tipe = mb_strtolower($namaTipe);

        if (in_array($tipe, ['pilihan berganda', 'benar salah'])) {
            if (!$kunci) {
                return false;
            }
            // fleksibel: dukung beberapa nama kolom yang umum dipakai
            $isBenar = $kunci->is_benar ?? $kunci->benar ?? $kunci->isTrue ?? null;
            return $isBenar === true || $isBenar === 1 || $isBenar === '1';
        }

        if ($tipe === 'isian') {
            // coba ambil satu kunci benar untuk soal ini jika id_jawaban_soal tidak menunjuk kunci
            if (!$kunci) {
                $kunci = jawaban_soal::where('id_soal', $id_soal)
                    ->where(function ($q) {
                        $q->where('is_benar', 1)
                            ->orWhere('benar', 1);
                    })
                    ->first();
            }

            if (!$kunci) {
                return false;
            }

            $kunciText = $kunci->jawaban ?? $kunci->teks ?? $kunci->text ?? null;
            if ($kunciText === null) {
                return false;
            }

            $studentText = trim(mb_strtolower((string) $row->jawaban_siswa));
            $answerText = trim(mb_strtolower((string) $kunciText));

            return $studentText === $answerText;
        }

        // default: anggap salah bila tipe tidak dikenali
        return false;
    }
}

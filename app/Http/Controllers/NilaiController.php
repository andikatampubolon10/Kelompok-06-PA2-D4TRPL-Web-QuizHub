<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\Kursus;
use App\Models\Siswa;
use App\Models\TipeNilai;
use App\Models\NilaiKursus;
use App\Models\Persentase;
use App\Models\BobotTipeSoal;
use App\Models\jawaban_siswa;
use App\Models\jawaban_soal;
use App\Models\Ujian;
use App\Models\Soal;
use App\Models\JawabanSiswa;
use App\Models\JawabanSoal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NilaiController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $guru = $user->guru;

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
     * Helper normalisasi teks (lowercase + trim)
     */
    private function norm(?string $s): string
    {
        return trim(mb_strtolower((string) $s));
    }

    /**
     * HITUNG OTOMATIS di CONTROLLER (tanpa trigger/SQL):
     * - Cek jawaban siswa vs jawaban soal (PG & BS saja).
     * - Jika id_jawaban_soal NULL, cocokkan teks jawaban_siswa dengan jawaban opsi.
     * - Hitung skor per tipe berdasarkan bobot soal, bobotkan dengan bobot_tipe_soal.
     * - Simpan (upsert) ke tipe_nilai.
     *
     * @return int Nilai akhir dibulatkan (0..100)
     */
    private function recalcTipeNilaiFromAnswers(int $id_ujian, int $id_siswa): int
    {
        // Pastikan bobot tipe = 100
        $sum = (int) BobotTipeSoal::where('id_ujian', $id_ujian)->sum('bobot');
        if ($sum !== 100) {
            Log::warning('Bobot tipe soal tidak 100; skip hitung.', ['id_ujian' => $id_ujian, 'total_bobot' => $sum]);
            return 0;
        }

        // Ambil semua soal PG & BS untuk ujian ini
        $soalList = Soal::where('id_ujian', $id_ujian)
            ->whereHas('tipe_soal', function ($q) {
                $q->whereIn(DB::raw('LOWER(nama_tipe_soal)'), ['pilihan berganda', 'benar salah']);
            })
            ->get(['id_soal', 'id_tipe_soal', 'bobot']);

        if ($soalList->isEmpty()) {
            // Tidak ada PG/BS → nilai 0
            return 0;
        }

        // Kumpulkan id_soal, dan jawaban siswa untuk soal-soal itu
        $idsSoal   = $soalList->pluck('id_soal')->all();
        $jawabMap  = jawaban_siswa::whereIn('id_soal', $idsSoal)
            ->where('id_siswa', $id_siswa)
            ->get(['id_soal', 'id_jawaban_soal', 'jawaban_siswa'])
            ->keyBy('id_soal');

        // Ambil bobot per tipe soal (%)
        $bobotTipe = BobotTipeSoal::where('id_ujian', $id_ujian)
            ->pluck('bobot', 'id_tipe_soal'); // [id_tipe_soal => bobot%]

        // Ambil jawaban benar per soal (map: id_soal => set opsi benar)
        $kunciPerSoal = jawaban_soal::whereIn('id_soal', $idsSoal)
            ->where(function ($q) {
                $q->where('benar', 1)->orWhere('is_benar', 1);
            })
            ->get(['id_soal', 'id_jawaban_soal', 'jawaban'])
            ->groupBy('id_soal');

        // Akumulasi per tipe soal
        $agg = []; // id_tipe_soal => ['total' => xx, 'benar' => yy]
        foreach ($soalList as $soal) {
            $idSoal  = (int) $soal->id_soal;
            $idTipe  = (int) $soal->id_tipe_soal;
            $bobot   = (float) ($soal->bobot ?? 0);

            if (!isset($agg[$idTipe])) {
                $agg[$idTipe] = ['total' => 0.0, 'benar' => 0.0];
            }
            $agg[$idTipe]['total'] += $bobot;

            $jawab = $jawabMap->get($idSoal);
            if (!$jawab) {
                continue; // belum menjawab → tidak menambah benar
            }

            $isCorrect = false;

            // 1) Jika id_jawaban_soal terisi, cek flag benar pada opsi tsb
            if (!empty($jawab->id_jawaban_soal)) {
                $opt = jawaban_soal::find($jawab->id_jawaban_soal);
                if ($opt) {
                    $flag = $opt->benar ?? $opt->is_benar ?? 0;
                    $isCorrect = ($flag == 1 || $flag === true || $flag === '1');
                }
            }

            // 2) Jika belum terkonfirmasi benar & ada jawaban teks, cocokkan teks dengan jawaban opsi yang benar
            if (!$isCorrect && !empty($jawab->jawaban_siswa)) {
                $jawabNorm = $this->norm($jawab->jawaban_siswa);
                $kunciSet  = $kunciPerSoal->get($idSoal) ?? collect();

                foreach ($kunciSet as $kunci) {
                    if ($this->norm($kunci->jawaban) === $jawabNorm) {
                        $isCorrect = true;
                        break;
                    }
                }
            }

            if ($isCorrect) {
                $agg[$idTipe]['benar'] += $bobot;
            }
        }

        // Hitung skor akhir tertimbang
        $totalWeighted = 0.0;
        foreach ($agg as $idTipe => $row) {
            $totalBobotTipeSoal = (float) $row['total']; // total bobot soal di tipe ini
            $benarBobot         = (float) $row['benar']; // total bobot soal yang benar di tipe ini
            $score0_100         = $totalBobotTipeSoal > 0 ? ($benarBobot / $totalBobotTipeSoal) * 100.0 : 0.0;

            $bobotPersenTipe    = (float) ($bobotTipe[$idTipe] ?? 0);
            $weighted           = $score0_100 * $bobotPersenTipe / 100.0;

            $totalWeighted     += $weighted;
        }

        // Simpan ke tipe_nilai (upsert)
        $ujian = Ujian::find($id_ujian);
        $id_tipe_ujian = $ujian?->id_tipe_ujian;

        if ($id_tipe_ujian) {
            TipeNilai::updateOrCreate(
                [
                    'id_siswa'      => $id_siswa,
                    'id_ujian'      => $id_ujian,
                    'id_tipe_ujian' => $id_tipe_ujian,
                ],
                [
                    'nilai' => (int) round($totalWeighted),
                ]
            );
        }

        return (int) round($totalWeighted);
    }

    /**
     * ENDPOINT untuk route:
     * GET /Siswa/courses/{id_kursus}/ujian/{id_ujian}/nilai
     *
     * Sekarang endpoint ini menghitung ulang di controller (tanpa trigger) lalu mengembalikan nilai total PB & BS.
     */
    public function gradeUjianSiswaSelf($id_kursus, $id_ujian)
    {
        $user  = auth()->user();
        $siswa = $user->siswa ?? null;

        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Akun ini tidak memiliki profil siswa.',
            ], 403);
        }

        $id_siswa = (int) $siswa->id_siswa;

        // Hitung & simpan (controller-based)
        $nilaiTerbaru = $this->recalcTipeNilaiFromAnswers((int) $id_ujian, $id_siswa);

        // Baca kembali dari tipe_nilai (opsional, untuk timestamp)
        $row = TipeNilai::where('id_ujian', $id_ujian)
            ->where('id_siswa', $id_siswa)
            ->first(['nilai', 'id_tipe_ujian', 'updated_at']);

        return response()->json([
            'success' => true,
            'message' => 'Nilai ujian (PB & BS) berhasil dihitung & diambil.',
            'data' => [
                'id_kursus'         => (int) $id_kursus,
                'id_ujian'          => (int) $id_ujian,
                'id_siswa'          => $id_siswa,
                'nilai_ujian_total' => (int) ($row->nilai ?? $nilaiTerbaru ?? 0),
                'id_tipe_ujian'     => $row->id_tipe_ujian ?? (Ujian::find($id_ujian)->id_tipe_ujian ?? null),
                'updated_at'        => $row->updated_at ?? now(),
            ],
        ]);
    }

public function recalcNow(int $id_ujian, int $id_siswa): int
{
    // pakai helper perhitungan controller-only yang sudah kita buat sebelumnya
    return $this->recalcTipeNilaiFromAnswers($id_ujian, $id_siswa);
}

}

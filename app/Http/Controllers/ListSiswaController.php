<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kursus;
use App\Models\Persentase;
use App\Models\Kelas;
use App\Models\TipeNilai;
use App\Models\NilaiKursus;
use App\Models\Nilai;
use App\Models\TipeUjian;
use App\Exports\NilaiExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Excel as ExcelFormat;

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
        $kursus   = Kursus::findOrFail($id_kursus);
        $fileName = str_replace(['/', '\\'], '_', $kursus->nama_kursus) . '_nilai.xlsx';

        $content = Excel::raw(new NilaiExport($id_kursus), ExcelFormat::XLSX);

        return response($content, 200, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Cache-Control'       => 'no-store, no-cache, must-revalidate, max-age=0',
        ]);
    }

    /**
     * Perbaikan: Tambah debug logging detail dan perbaiki query
     * Flow:
     * 1. Ambil nilai per tipe_ujian dari tipe_nilai
     * 2. Hitung rata-rata per tipe_ujian
     * 3. Simpan ke nilai_kursus
     * 4. Kalikan dengan persentase
     * 5. Hitung nilai_total dan simpan ke nilai
     */
    public function resetAndRecalculateNilai($id_kursus)
    {
        try {
            Log::info("[v0] ===== START RESET AND RECALCULATE NILAI =====");
            Log::info("[v0] ID Kursus: {$id_kursus}");

            // Validasi kursus ada
            $kursus = Kursus::findOrFail($id_kursus);
            Log::info("[v0] Kursus ditemukan: {$kursus->nama_kursus}");

            // Validasi persentase sudah diatur
            $persentaseList = Persentase::where('id_kursus', $id_kursus)->get();
            Log::info("[v0] Jumlah persentase: {$persentaseList->count()}");
            
            if ($persentaseList->isEmpty()) {
                Log::warning("[v0] Persentase kosong untuk kursus {$id_kursus}");
                return redirect()->back()->with('error', 'Persentase belum diatur. Silahkan atur persentase terlebih dahulu.');
            }

            foreach ($persentaseList as $p) {
                Log::info("[v0] Persentase - ID Tipe Ujian: {$p->id_tipe_ujian}, Persentase: {$p->persentase}%");
            }

            DB::beginTransaction();

            // Hapus nilai lama
            NilaiKursus::where('id_kursus', $id_kursus)->delete();
            Nilai::where('id_kursus', $id_kursus)->delete();
            Log::info("[v0] Nilai lama dihapus");

            $siswaList = $kursus->siswa;
            Log::info("[v0] Jumlah siswa: {$siswaList->count()}");

            $results = [];

            foreach ($siswaList as $siswa) {
                $id_siswa = $siswa->id_siswa;
                Log::info("[v0] ===== PROCESSING SISWA: {$id_siswa} ({$siswa->nama_siswa}) =====");

                $tipeUjianList = $persentaseList->pluck('id_tipe_ujian')->unique();
                Log::info("[v0] Tipe Ujian dari persentase: " . json_encode($tipeUjianList->toArray()));

                foreach ($tipeUjianList as $id_tipe_ujian) {
                    Log::info("[v0] --- Processing Tipe Ujian: {$id_tipe_ujian} ---");

                    $nilaiPerTipeUjian = TipeNilai::where('id_siswa', $id_siswa)
                        ->where('id_tipe_ujian', $id_tipe_ujian)
                        ->get();

                    Log::info("[v0] Query TipeNilai - Siswa: {$id_siswa}, Tipe Ujian: {$id_tipe_ujian}");
                    Log::info("[v0] Hasil query: " . json_encode($nilaiPerTipeUjian->toArray()));
                    Log::info("[v0] Jumlah nilai ditemukan: {$nilaiPerTipeUjian->count()}");

                    if ($nilaiPerTipeUjian->count() > 0) {
                        $rataRataNilai = $nilaiPerTipeUjian->avg('nilai');
                        Log::info("[v0] Rata-rata nilai: {$rataRataNilai}");

                        $nilaiKursus = NilaiKursus::updateOrCreate(
                            [
                                'id_kursus' => $id_kursus,
                                'id_siswa' => $id_siswa,
                                'id_tipe_ujian' => $id_tipe_ujian,
                            ],
                            [
                                'nilai_tipe_ujian' => round($rataRataNilai, 2),
                            ]
                        );
                        
                        Log::info("[v0] Saved to nilai_kursus - ID: {$nilaiKursus->id_nilai_kursus}, Nilai: {$rataRataNilai}");
                    } else {
                        Log::warning("[v0] Tidak ada nilai untuk siswa {$id_siswa} tipe ujian {$id_tipe_ujian}");
                    }
                }

                $nilaiTotal = $this->calculateNilaiTotal($id_kursus, $id_siswa);

                $results[$id_siswa] = [
                    'nilai_total' => round($nilaiTotal, 2),
                    'nama_siswa' => $siswa->nama_siswa
                ];
                
                Log::info("[v0] Siswa {$id_siswa} - Nilai Total: {$nilaiTotal}");
            }

            DB::commit();
            Log::info("[v0] ===== TRANSACTION COMMITTED =====");

            return redirect()->back()->with('success', 'Perhitungan nilai berhasil dilakukan untuk ' . count($results) . ' siswa');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            Log::error('[v0] Kursus tidak ditemukan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Kursus tidak ditemukan');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('[v0] Error saat menghitung nilai: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghitung nilai: ' . $e->getMessage());
        }
    }

    /**
     * Hitung nilai total berdasarkan nilai_kursus dan persentase
     * Menggunakan logic dari file calculate-nilai-total
     */
    private function calculateNilaiTotal(int $id_kursus, int $id_siswa): float
    {
        try {
            Log::info("[v0] === CALCULATE NILAI TOTAL ===");
            Log::info("[v0] ID Kursus: {$id_kursus}, ID Siswa: {$id_siswa}");

            // Ambil semua komponen nilai per tipe_ujian untuk siswa ini
            $nilaiKursus = NilaiKursus::where('id_kursus', $id_kursus)
                ->where('id_siswa', $id_siswa)
                ->get();

            Log::info("[v0] Jumlah nilai_kursus: {$nilaiKursus->count()}");
            Log::info("[v0] Data nilai_kursus: " . json_encode($nilaiKursus->toArray()));

            // Siapkan peta persentase per tipe_ujian di kursus ini
            $persenMap = Persentase::where('id_kursus', $id_kursus)
                ->pluck('persentase', 'id_tipe_ujian'); // [id_tipe_ujian => persen]

            Log::info("[v0] Peta persentase: " . json_encode($persenMap->toArray()));

            // Hitung total berbobot
            $total = 0.0;
            foreach ($nilaiKursus as $row) {
                $bobot = (float)($persenMap[$row->id_tipe_ujian] ?? 0);
                $kontribusi = ((float)$row->nilai_tipe_ujian) * $bobot / 100.0;
                $total += $kontribusi;
                
                Log::info("[v0] Tipe Ujian {$row->id_tipe_ujian}: {$row->nilai_tipe_ujian} * {$bobot}% = {$kontribusi}");
            }
            $total = round($total, 2);

            Log::info("[v0] Nilai Total Akhir: {$total}");

            $id_tipe_nilai_agregat = $this->pickAggregateTipeNilaiId($id_siswa);
            Log::info("[v0] ID Tipe Nilai Agregat: {$id_tipe_nilai_agregat}");

            // Simpan/Update ke tabel 'nilai' (WAJIB sertakan id_tipe_nilai)
            $nilai = Nilai::updateOrCreate(
                ['id_kursus' => $id_kursus, 'id_siswa' => $id_siswa],
                [
                    'nilai_total'   => $total,
                    'id_tipe_nilai' => $id_tipe_nilai_agregat,
                ]
            );

            Log::info("[v0] Saved to nilai - ID: {$nilai->id_nilai}, Nilai Total: {$total}");

            return $total;

        } catch (\Exception $e) {
            Log::error('[v0] Error calculateNilaiTotal: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Tentukan id_tipe_nilai agregat untuk disimpan di tabel nilai
     * Mengambil id_tipe_nilai pertama dari siswa, atau default ke 1 jika tidak ada
     */
    private function pickAggregateTipeNilaiId(int $id_siswa): int
    {
        try {
            // Coba ambil id_tipe_nilai pertama dari siswa
            $tipeNilai = TipeNilai::where('id_siswa', $id_siswa)
                ->orderBy('id_tipe_nilai')
                ->first();

            if ($tipeNilai) {
                Log::info("[v0] pickAggregateTipeNilaiId - Ditemukan: {$tipeNilai->id_tipe_nilai}");
                return $tipeNilai->id_tipe_nilai;
            }

            // Default ke 1 jika tidak ada
            Log::info("[v0] pickAggregateTipeNilaiId - Tidak ditemukan, menggunakan default: 1");
            return 1;

        } catch (\Exception $e) {
            Log::error('[v0] Error pickAggregateTipeNilaiId: ' . $e->getMessage());
            return 1; // Default fallback
        }
    }
}

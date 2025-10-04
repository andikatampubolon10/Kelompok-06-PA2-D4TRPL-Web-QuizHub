<?php

namespace App\Exports;

use App\Models\kursus_siswa;
use App\Models\KursusSiswa; // pastikan nama model & file-nya CamelCase
use App\Models\Nilai;
use App\Models\TipeNilai;
use App\Models\Ujian;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class NilaiExport implements FromCollection, WithHeadings, WithStyles
{
    protected int|string $id_kursus;

    public function __construct($id_kursus)
    {
        $this->id_kursus = $id_kursus;
    }

    public function collection()
    {
        // Ambil siswa di kursus + relasi siswa (guard null)
        $kursusSiswa = kursus_siswa::where('id_kursus', $this->id_kursus)
            ->with('siswa')
            ->get();

        // Urutkan ujian berdasar tipe (1,2,3) lalu by id_ujian (stabil)
        $ujian = Ujian::where('id_kursus', $this->id_kursus)
            ->orderByRaw("FIELD(id_tipe_ujian, 1, 2, 3)")
            ->orderBy('id_ujian')
            ->get();

        // Bangun baris data
        $rows = $kursusSiswa->map(function ($ks) use ($ujian) {
            $nis         = optional($ks->siswa)->nis ?? '-';
            $namaSiswa   = optional($ks->siswa)->nama_siswa ?? '(Tanpa Nama)';
            $idSiswa     = optional($ks->siswa)->id_siswa;

            // kolom awal
            $row = [
                'nis'         => $nis,
                'nama_siswa'  => $namaSiswa,
            ];

            // nilai per ujian (urut sesuai $ujian di headings)
            foreach ($ujian as $u) {
                $nilai = TipeNilai::where('id_ujian', $u->id_ujian)
                    ->where('id_siswa', $idSiswa)
                    ->value('nilai');

                $row[] = $nilai !== null ? number_format((float)$nilai, 2) : '-';
            }

            // nilai total kursus
            $nilaiTotal = Nilai::where('id_siswa', $idSiswa)
                ->where('id_kursus', $this->id_kursus)
                ->value('nilai_total');

            $row[] = $nilaiTotal !== null ? number_format((float)$nilaiTotal, 2) : '-';

            return $row;
        });

        // sort by kolom ke-2 (nama_siswa) ASC — karena row[] sekarang numerik setelah 2 kolom awal.
        // index 1 = nama_siswa
        $rows = $rows->sortBy(function ($row) {
            return mb_strtolower((string)($row[1] ?? ''), 'UTF-8');
        })->values();

        return $rows; // Collection
    }

    public function headings(): array
    {
        $ujian = Ujian::where('id_kursus', $this->id_kursus)
            ->orderByRaw("FIELD(id_tipe_ujian, 1, 2, 3)")
            ->orderBy('id_ujian')
            ->get();

        $heading = [
            'NIS',
            'Nama Siswa',
        ];

        foreach ($ujian as $u) {
            $heading[] = 'Nilai ' . $u->nama_ujian;
        }

        $heading[] = 'Nilai Total';

        return $heading;
    }

    public function styles(Worksheet $sheet)
    {
        // Hitung kolom terakhir dengan Coordinate (aman > 26 kolom)
        $colCount   = count($this->headings());
        $lastColumn = Coordinate::stringFromColumnIndex($colCount);

        // Header bold + shading
        $headerRange = "A1:{$lastColumn}1";
        $sheet->getStyle($headerRange)->getFont()->setBold(true);
        $sheet->getStyle($headerRange)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('D9EAD3');

        // Auto width setiap kolom
        for ($i = 1; $i <= $colCount; $i++) {
            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($i))->setAutoSize(true);
        }

        // Center horizontal (opsional) sampai baris 1000
        $sheet->getStyle("A1:{$lastColumn}1000")
            ->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        return [];
    }
}

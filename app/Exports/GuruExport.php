<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GuruExport implements FromCollection, WithHeadings, WithStyles
{
    /**
     * Mengambil data (hanya header kolom, tidak ada data)
     */
    public function collection()
    {
        return collect([]); // Tidak ada data, hanya header
    }

    /**
     * Menentukan header (judul kolom) untuk file Excel
     */
    public function headings(): array
    {
        return [
            'Nama Guru',        // Judul kolom pertama
            'NIP',              // Judul kolom kedua
            'Email',            // Judul kolom ketiga
            'Password',         // Judul kolom keempat
            'Mata Pelajaran ( Jika 2 Mapel Gunakan (,) )'    // Judul kolom kelima
        ];
    }

    /**
     * Menyesuaikan lebar kolom menggunakan PhpSpreadsheet
     */
    public function styles(Worksheet $sheet)
    {
        // Mengatur lebar kolom sesuai dengan panjang header
        $sheet->getColumnDimension('A')->setWidth(20); // Lebar kolom 'Nama Guru'
        $sheet->getColumnDimension('B')->setWidth(15); // Lebar kolom 'NIP'
        $sheet->getColumnDimension('C')->setWidth(25); // Lebar kolom 'Email'
        $sheet->getColumnDimension('D')->setWidth(15); // Lebar kolom 'Password'
        $sheet->getColumnDimension('E')->setWidth(40); // Lebar kolom 'Mata Pelajaran'

        // Menebalkan judul kolom (header)
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);

        // Mengatur alignment untuk header
        $sheet->getStyle('A1:E1')->getAlignment()->setHorizontal('center');

        return $sheet;
    }
}

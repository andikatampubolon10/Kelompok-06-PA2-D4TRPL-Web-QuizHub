<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SiswaExport implements FromCollection, WithHeadings, WithStyles
{
    /**
     * Mengambil data siswa (hanya header kolom, tidak ada data)
     */
    public function collection()
    {
        // Tidak mengembalikan data siswa, hanya header
        return collect([]); // Mengembalikan koleksi kosong
    }

    /**
     * Menentukan header (judul kolom) untuk file Excel
     */
    public function headings(): array
    {
        return [
            'NISN',           // Judul kolom pertama
            'Nama',           // Judul kolom kedua
            'Email',          // Judul kolom ketiga
            'Password',       // Judul kolom keempat
            'Kelas'           // Judul kolom kelima
        ];
    }

    /**
     * Menyesuaikan lebar kolom menggunakan PhpSpreadsheet
     */
    public function styles(Worksheet $sheet)
    {
        // Mengatur lebar kolom sesuai dengan panjang header
        $sheet->getColumnDimension('A')->setWidth(20); // Lebar kolom 'NISN'
        $sheet->getColumnDimension('B')->setWidth(30); // Lebar kolom 'Nama'
        $sheet->getColumnDimension('C')->setWidth(25); // Lebar kolom 'Email'
        $sheet->getColumnDimension('D')->setWidth(15); // Lebar kolom 'Password'
        $sheet->getColumnDimension('E')->setWidth(20); // Lebar kolom 'Kelas'

        // Menebalkan judul kolom (header)
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);

        // Mengatur alignment untuk header
        $sheet->getStyle('A1:E1')->getAlignment()->setHorizontal('center');

        return $sheet;
    }
}

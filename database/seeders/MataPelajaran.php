<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\mata_pelajaran;

class MataPelajaranSeeder extends Seeder
{
    public function run()
    {
        $mataPelajaran = [
            ['nama_mata_pelajaran' => 'Matematika'],
            ['nama_mata_pelajaran' => 'Bahasa Indonesia'],
            ['nama_mata_pelajaran' => 'Bahasa Inggris'],
            ['nama_mata_pelajaran' => 'IPA'],
            ['nama_mata_pelajaran' => 'IPS'],
            ['nama_mata_pelajaran' => 'Fisika'],
            ['nama_mata_pelajaran' => 'Kimia'],
            ['nama_mata_pelajaran' => 'Biologi'],
            ['nama_mata_pelajaran' => 'Sejarah'],
            ['nama_mata_pelajaran' => 'Geografi'],
            ['nama_mata_pelajaran' => 'Ekonomi'],
            ['nama_mata_pelajaran' => 'Sosiologi'],
            ['nama_mata_pelajaran' => 'PKN'],
            ['nama_mata_pelajaran' => 'Agama'],
            ['nama_mata_pelajaran' => 'Seni Budaya'],
            ['nama_mata_pelajaran' => 'Penjaskes'],
            ['nama_mata_pelajaran' => 'TIK'],
        ];

        foreach ($mataPelajaran as $mp) {
            mata_pelajaran::firstOrCreate($mp);
        }
    }
}
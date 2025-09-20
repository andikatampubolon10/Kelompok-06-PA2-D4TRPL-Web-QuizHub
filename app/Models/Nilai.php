<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TipeNilai;


class Nilai extends Model
{
    use HasFactory;

    protected $table = 'nilai';

    protected $primaryKey = 'id_nilai';

    protected $fillable = [
        'id_kursus',
        'id_siswa',
        'id_persentase',
        // 'id_tipe_nilai',  // Relasi ke tipe_nilai
        'nilai_total',
    ];

    public function kursus()
    {
        return $this->belongsTo(Kursus::class, 'id_kursus', 'id_kursus','id_kursus');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa','id_siswa');
    }

    public function tipeNilai()
    {
        return $this->belongsTo(TipeNilai::class, 'id_tipe_nilai', 'id_tipe_nilai');
    }
    

    public function persentase()
    {
        return $this->belongsTo(Persentase::class, 'id_persentase');
    }

    // Fungsi untuk menghitung nilai total berdasarkan persentase
    public function hitungNilaiTotal()
    {
        // Mengambil persentase yang sesuai untuk kursus dan tipe ujian
        $persentase = $this->kursus->persentase()->where('id_kursus', $this->id_kursus)->first();

        // Menghitung nilai total berdasarkan persentase
        $nilaiTotal = ($this->tipeNilai->nilai_kuis * $persentase->persentase_kuis / 100) +
                      ($this->tipeNilai->nilai_UTS * $persentase->persentase_UTS / 100) +
                      ($this->tipeNilai->nilai_UAS * $persentase->persentase_UAS / 100);

        $this->nilai_total = $nilaiTotal;
        $this->save();
    }
}

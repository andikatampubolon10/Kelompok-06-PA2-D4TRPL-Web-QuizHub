<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    protected $table = 'soal';
    protected $primaryKey = 'id_soal';
    protected $fillable = [
        'soal',
        'image',
        'image_url',
        'id_ujian',
        'id_tipe_soal',
        'id_latihan',
        'nilai_per_soal',
    ];

    public function ujian()
    {
        return $this->belongsTo(ujian::class, 'id_ujian', 'id_ujian');
    }

    public function latihan()
    {
        return $this->belongsTo(latihan::class, 'id_latihan', 'id_latihan');
    }

    public function jawaban_soal()
    {
        return $this->hasMany(jawaban_soal::class, 'id_soal', 'id_soal', 'id_jawaban_soal');
    }

    public function tipe_soal()
    {
        return $this->belongsTo(tipe_soal::class, 'id_tipe_soal', 'id_tipe_soal', 'id_tipe_soal');
    }

    public function jawaban_siswa()
    {
        return $this->hasMany(jawaban_siswa::class, 'id_soal');
    }
}

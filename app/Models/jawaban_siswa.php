<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class jawaban_siswa extends Model
{

    protected $table = 'jawaban_siswa';

    protected $primaryKey = 'id_jawaban_siswa';

    protected $fillable = [
        'id_jawaban_siswa',
        'jawaban_siswa',
        'id_soal',
        'id_siswa',
        'id_jawaban_soal',
        'nilai_essay_raw',
        'nilai_essay_final',
    ];

    protected $casts = [
        'id_jawaban_siswa' => 'integer',
        'jawaban_siswa' => 'string',
        'nilai_essay_raw' => 'float',
        'nilai_essay_final' => 'float',
        'id_soal' => 'integer',
        'id_siswa' => 'integer',
        'id_jawaban_soal' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function soal()
    {
        return $this->belongsTo(soal::class, 'id_soal', 'id_soal');
    }
    public function siswa()
    {
        return $this->belongsTo(siswa::class, 'id_siswa', 'id_siswa');
    }
    public function jawaban_soal()
    {
        return $this->belongsTo(jawaban_soal::class, 'id_jawaban_soal', 'id_jawaban_soal');
    }

}

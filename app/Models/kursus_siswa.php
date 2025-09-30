<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class kursus_siswa extends Model
{

    protected $table = 'kursus_siswa';

    protected $primaryKey = 'id_kursus_siswa';

    protected $fillable = [
        'id_kursus_siswa',
        'id_siswa',
        'id_kursus',
    ];

    public function siswa()
    {
        return $this->belongsTo(siswa::class, 'id_siswa');
    }

    public function kursus()
    {
        return $this->belongsTo(kursus::class, 'id_kursus');
    }
}

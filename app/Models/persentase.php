<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persentase extends Model
{
    protected $table = 'persentase';
    protected $primaryKey = 'id_persentase';

    protected $fillable = [
        'persentase',
        'id_tipe_persentase',
        'id_kursus',
        'id_tipe_ujian',
    ];

    public function tipePersentase()
    {
        return $this->belongsTo(tipe_persentase::class, 'id_tipe_persentase', 'id_tipe_persentase','id_tipe_persentase','id_tipe_persentase');
    }

    public function kursus()
    {
        return $this->belongsTo(Kursus::class, 'id_kursus','id_kursus','id_kursus');
    }

    public function tipeUjian()
    {
        return $this->belongsTo(tipe_ujian::class, 'id_tipe_ujian','id_tipe_ujian');
    }
}

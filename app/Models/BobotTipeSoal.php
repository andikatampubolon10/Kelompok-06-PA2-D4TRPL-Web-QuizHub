<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BobotTipeSoal extends Model
{
    protected $table = 'bobot_tipe_soal';

    protected $primaryKey = 'id_bobot_tipe_soal';

    protected $fillable = [
        'id_tipe_soal',
        'id_ujian',
        'bobot',
    ];

    public function ujian()
    {
        return $this->belongsTo(Ujian::class, 'id_ujian');
    }

    public function tipe_soal()
    {
        return $this->belongsTo(tipe_soal::class, 'id_tipe_soal');
    }
}

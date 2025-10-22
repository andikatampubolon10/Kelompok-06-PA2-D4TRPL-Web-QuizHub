<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipeNilai extends Model
{
    use HasFactory;

    protected $table = 'tipe_nilai';

    protected $primaryKey = 'id_tipe_nilai';

    protected $fillable = [
        'nilai',            
        'id_tipe_ujian',      
        'id_siswa',
        'id_ujian'           
    ];

    public function tipeUjian()
    {
        return $this->belongsTo(tipe_ujian::class, 'id_tipe_ujian','id_tipe_ujian');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa','id_siswa');
    }

    public function ujian()
{
    return $this->belongsTo(Ujian::class, 'id_ujian');
}
}

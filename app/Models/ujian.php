<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ujian extends Model
{
    protected $table = 'ujian';

    protected $primaryKey = 'id_ujian';

    protected $fillable = [
        'id_ujian',
        'nama_ujian',
        'acak',
        'status_jawaban',
        'grade',
        'password_masuk',
        'password_keluar',
        'waktu_mulai',
        'waktu_selesai',
        'durasi',
        'id_kursus',
        'id_guru',
        'id_tipe_ujian',
    ];

    public function kursus()
    {
        return $this->belongsTo(Kursus::class, 'id_kursus', 'id_kursus', 'id_kursus');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru');
    }

    public function tipe_ujian()
    {
        return $this->belongsTo(tipe_ujian::class, 'id_tipe_ujian','id_tipe_ujian');
    }

    public function tipeNilai()
    {
        return $this->hasMany(TipeNilai::class, 'id_ujian', 'id_ujian');
    }


    public function soal()
    {
        return $this->hasMany(Soal::class, 'id_ujian', 'id_ujian');
    }


    public function bobot_tipe_soal()
    {
        return $this->hasMany(BobotTipeSoal::class, 'id_ujian');
    }


    public function calculateDuration()
    {
        $start = \Carbon\Carbon::parse($this->waktu_mulai);
        $end = \Carbon\Carbon::parse($this->waktu_selesai);
        $this->durasi = $end->diffInMinutes($start); // Durasi dalam menit
        $this->save();
    }
}

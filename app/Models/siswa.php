<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class siswa extends Model
{
    protected $table = 'siswa';

    protected $primaryKey = 'id_siswa';

    protected $fillable = [
        'id_siswa',
        'nama_siswa',
        'nis',
        'status',
        'id_user',
        'id_operator',
        'id_kelas',
    ];

    public function user()
    {
        return $this->belongsTo(user::class, 'id_user');
    }

    public function operator()
    {
        return $this->belongsTo(operator::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }


    public function mata_pelajaran_siswa()
    {
        return $this->hasMany(mata_pelajaran_siswa::class);
    }

    public function kursus_siswa()
    {
        return $this->hasMany(kursus_siswa::class, 'id_siswa');
    }

    public function jawaban_siswa()
    {
        return $this->hasMany(jawaban_siswa::class);
    }

    public function kurikulum_siswa()
    {
        return $this->hasMany(kurikulum_siswa::class);
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'id_siswa', 'id_siswa', 'id_siswa', 'id_siswa');
    }
}

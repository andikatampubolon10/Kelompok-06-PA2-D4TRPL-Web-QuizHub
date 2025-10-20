<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $table = 'guru';
    protected $primaryKey = 'id_guru';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama_guru',
        'nip',
        'status',
        'id_user',
        'id_operator',
        // JANGAN masukkan 'id_guru' dan 'id_mata_pelajaran' di sini
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function operator()
    {
        return $this->belongsTo(Operator::class, 'id_operator', 'id_operator');
    }

    public function kursus()
    {
        return $this->hasMany(Kursus::class, 'id_guru', 'id_guru');
    }

    public function latihan()
    {
        return $this->hasMany(Latihan::class, 'id_guru', 'id_guru');
    }

    public function ujian()
    {
        return $this->hasMany(Ujian::class, 'id_guru', 'id_guru');
    }

    public function materi()
    {
        return $this->hasMany(Materi::class, 'id_guru', 'id_guru');
    }

    // many-to-many ke mata_pelajaran via pivot
    public function mataPelajarans()
    {
        return $this->belongsToMany(
            mata_pelajaran::class,
            'guru_mata_pelajaran',
            'id_guru',
            'id_mata_pelajaran'
        );
    }

    // kalau mau akses baris pivot sebagai model
    public function guruMapel()
    {
        return $this->hasMany(Guru_Mata_Pelajaran::class, 'id_guru', 'id_guru');
    }
}

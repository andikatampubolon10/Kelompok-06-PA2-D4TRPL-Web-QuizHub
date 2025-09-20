<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class mata_pelajaran extends Model
{

    protected $table = 'mata_pelajaran';

    protected $primaryKey = 'id_mata_pelajaran';

    protected $fillable = [
        'id_mata_pelajaran',
        'nama_mata_pelajaran',
        'id_operator',
        'id_kurikulum',
    ];

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    public function kurikulum()
    {
        return $this->belongsTo(kurikulum::class);
    }

    public function mata_pelajaran_siswa()
    {
        return $this->hasMany(mata_pelajaran_siswa::class);
    }

    public function guru()
    {
        return $this->belongsToMany(guru::class, 'guru_mata_pelajaran', 'id_mata_pelajaran', 'id_guru');
    }

    public function guru_mata_pelajaran()
    {
        return $this->hasMany(Guru_Mata_Pelajaran::class, 'id_guru_mata_pelajaran');
    }

    public function kursus()
    {
        return $this->hasMany(Kursus::class, 'id_mata_pelajaran');
    }
}

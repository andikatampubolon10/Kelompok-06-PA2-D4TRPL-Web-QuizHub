<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;

    protected $table = 'semester';

    protected $fillable = [
        'nama_semester',
        'ID_Tahun_Ajaran'
    ];

    public function tahun_ajaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'ID_Tahun_Ajaran', 'ID_Tahun_Ajaran');
    }

    public function operator()
    {
        return $this->belongsTo(operator::class, 'id_operator', 'id_operator');
    }

    public function mata_pelajaran()
    {
        return $this->hasMany(mata_pelajaran::class, 'id_semester', 'id_semester');
    }

    public function kursus()
    {
        return $this->hasMany(kursus::class, 'id_semester', 'id_semester');
    }
}

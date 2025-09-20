<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru_Mata_Pelajaran extends Model
{
    protected $table = 'guru_mata_pelajaran';

    protected $primaryKey = 'id_guru_mata_pelajaran';

    protected $fillable = [
        'id_guru_mata_pelajaran',
        'id_guru',
        'id_mata_pelajaran',
    ];

    public function mataPelajaran()
    {
        return $this->belongsTo(mata_pelajaran::class, 'id_mata_pelajaran');
    }

    // Relasi dengan guru
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru');
    }

    
}

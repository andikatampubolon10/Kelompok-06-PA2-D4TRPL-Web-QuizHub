<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru_Mata_Pelajaran extends Model
{
    protected $table = 'guru_mata_pelajaran';

    // Pivot tanpa primary key & tanpa increment
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    // Hanya dua kolom ini yang boleh diisi
    protected $fillable = ['id_guru', 'id_mata_pelajaran'];

    public function mataPelajaran()
    {
        return $this->belongsTo(mata_pelajaran::class, 'id_mata_pelajaran', 'id_mata_pelajaran');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }
}

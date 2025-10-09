<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class latihan extends Model
{

    protected $table = 'latihan';

    // Define the primary key
    protected $primaryKey = 'id_latihan';

    // Specify the fields that can be mass-assigned
    protected $fillable = [
        'Topik',                    // Add 'Topik' field
        'acak',                      // Existing 'acak' field
        'status_jawaban',            // Existing 'status_jawaban' field
        'grade',                     // Existing 'grade' field
        'id_guru',                   // Existing 'id_guru' field
        'id_kurikulum',              // Add 'id_kurikulum' field
        'id_mata_pelajaran',         // Add 'id_mata_pelajaran' field
        'id_kelas',                  // Add 'id_kelas' field
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru');
    }

    public function soal()
    {
        return $this->hasMany(soal::class);
    }

    public function kurikulum()
    {
        return $this->belongsTo(Kurikulum::class, 'id_kurikulum');
    }

public function mataPelajaran()
    {
        return $this->belongsTo(mata_pelajaran::class, 'id_mata_pelajaran');
    }

    public function Kelas()
    {
        return $this->belongsTo(kelas::class, 'id_kelas');
    }
}

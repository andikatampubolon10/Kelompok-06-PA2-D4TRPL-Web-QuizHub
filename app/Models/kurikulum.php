<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class kurikulum extends Model
{

    protected $table = 'kurikulum'; 

    protected $primaryKey = 'id_kurikulum';

    protected $fillable = [
        'id_kurikulum',
        'nama_kurikulum',
        'status',
        'id_operator',
    ];

    public function operator(){
        return $this->belongsTo(Operator::class, 'id_operator');
    }

    public function kurikulum_siswa(){
        return $this->hasMany(kurikulum_siswa::class);
    }

    public function mata_pelajaran(){
        return $this->hasMany(mata_pelajaran::class);
    }

    public function tahunAjaran()
    {
        return $this->hasMany(TahunAjaran::class, 'id_kurikulum', 'id_kurikulum');
    }

}

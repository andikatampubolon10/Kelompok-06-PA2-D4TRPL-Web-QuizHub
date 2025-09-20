<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kursus extends Model
{
    protected $table = 'kursus';

    protected $primaryKey = 'id_kursus';

    protected $appends = ['foto_url'];

    protected $fillable = [
        'id_kursus',
        'nama_kursus',
        'password',
        'id_guru',
        'image',
        'image_url',
        'id_mata_pelajaran',
        'id_operator',
        'id_kelas',
        'ID_Tahun_Ajaran',
    ];

    public function guru()
    {
        return $this->belongsTo(guru::class, 'id_guru', 'id_guru','id_guru','id_guru');
    }

    public function kelas()
    {
        return $this->belongsTo(kelas::class, 'id_kelas', 'id_kelas');
    }

    public function operator()
    {
        return $this->belongsTo(operator::class, 'id_operator', 'id_operator');
    }

    public function tahun_ajaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'ID_Tahun_Ajaran', 'ID_Tahun_Ajaran', 'ID_Tahun_Ajaran');
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(mata_pelajaran::class, 'id_mata_pelajaran');
    }

    public function kursusSiswa()
    {
        return $this->hasMany(KursusSiswa::class);
    }

    public function ujian()
    {
        return $this->hasMany(Ujian::class, 'id_kursus', 'id_kursus');
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'id_kursus', 'id_kursus');
    }

    public function persentase()
    {
        return $this->hasMany(Persentase::class, 'id_kursus', 'id_kursus', 'id_kursus');
    }

    public function materi()
    {
        return $this->hasMany(Materi::class, 'id_materi', 'id_materi');
    }

    public function tipeNilai()
    {
        return $this->hasMany(TipeNilai::class, 'id_kursus', 'id_kursus');
    }

    public function siswa()
    {
        return $this->belongsToMany(siswa::class, 'kursus_siswa', 'id_kursus', 'id_siswa');
    }
}

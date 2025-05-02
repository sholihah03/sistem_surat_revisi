<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kadaluwarsa extends Model
{
    use HasFactory;

    protected $table = 'tb_kadaluwarsa';
    protected $primaryKey = 'id_kadaluwarsa';

    protected $fillable = [
        'rt_id',
        'nama_kepala_keluarga',
        'path_file_kk',
        'nama_lengkap',
        'no_kk',
        'nik',
        'no_hp',
        'email',
        'rw',
        'nama_jalan',
        'kelurahan',
        'kecamatan',
        'kabupaten_kota',
        'provinsi',
        'kode_pos',
    ];

    public function rtss()
    {
        return $this->belongsTo(Rt::class, 'rt_id', 'id_rt');
    }
}

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
        'rt_alamat',
        'rw_alamat',
        'nama_kepala_keluarga',
        'path_file_kk',
        'nama_lengkap',
        'no_kk',
        'nik',
        'no_hp',
        'email',
        'nama_jalan',
        'kelurahan',
        'kecamatan',
    ];

    public function rtss()
    {
        return $this->belongsTo(Rt::class, 'rt_id', 'id_rt');
    }

    public function rwss()
    {
        return $this->belongsTo(Rt::class, 'rw_id', 'id_rw');
    }
}

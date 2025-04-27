<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Alamat extends Model
{
    use HasFactory;
    protected $table = 'tb_alamat';
    protected $primaryKey = 'id_alamat';

    protected $fillable = [
        'nama_jalan',
        'rt_alamat',
        'rw_alamat',
        'kelurahan',
        'kecamatan',
        'kabupaten_kota',
        'provinsi',
        'kode_pos',
    ];

    public function scanKks()
    {
        return $this->hasMany(ScanKk::class, 'alamat_id', 'id_alamat');
    }
}

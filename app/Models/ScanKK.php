<?php

namespace App\Models;

use App\Models\Alamat;
use App\Models\Wargas;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScanKK extends Model
{
    use HasFactory;
    protected $table = 'tb_scan_kk';
    protected $primaryKey = 'id_scan';

    protected $fillable = [
        'alamat_id',
        'nama_pendaftar',
        'no_hp_pendaftar',
        'email_pendaftar',
        'nama_kepala_keluarga',
        'no_kk_scan',
        'path_file_kk',
        'status_verifikasi',
        'alasan_penolakan',
    ];

    public function alamat()
    {
        return $this->belongsTo(Alamat::class, 'alamat_id', 'id_alamat');
    }
    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class, 'scan_id', 'id_scan');
    }

    public function wargas()
    {
        return $this->hasMany(Wargas::class, 'scan_kk_id', 'id_scan');
    }

    public function rt()
    {
        return $this->belongsTo(Rt::class, 'rt_id', 'id_rt');
    }

    public function rw()
    {
        return $this->belongsTo(Rw::class, 'rw_id', 'id_rw');
    }
}

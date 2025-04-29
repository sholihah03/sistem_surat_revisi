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
        'nama_kepala_keluarga',
        'no_kk_scan',
        'path_file_kk',
        'status_verifikasi',
        'alasan_penolakan',
    ];

    // public function warga()
    // {
    //     return $this->belongsTo(Wargas::class, 'warga_id', 'id_warga');
    // }

    public function alamat()
    {
        return $this->belongsTo(Alamat::class, 'alamat_id', 'id_alamat');
    }
    public function pendaftaran()
    {
        return $this->hasMany(ScanKk::class, 'scan_id', 'id_scan');
    }
}

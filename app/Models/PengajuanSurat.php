<?php

namespace App\Models;

use App\Models\ScanKK;
use App\Models\Wargas;
use App\Models\TujuanSurat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PengajuanSurat extends Model
{
    use HasFactory;

    protected $table = 'tb_pengajuan_surat';
    protected $primaryKey = 'id_pengajuan_surat';

    protected $casts = [
    'waktu_persetujuan_rw' => 'datetime',
    'waktu_persetujuan_rt' => 'datetime',
];

    protected $fillable = [
        'warga_id',
        'tujuan_surat_id',
        'scan_kk_id',
        'status_rt',
        'waktu_persetujuan_rt',
        'status_rw',
        'waktu_persetujuan_rw',
        'is_read',
        'tempat_lahir',
        'tanggal_lahir',
        'pekerjaan',
        'agama',
        'alasan_penolakan_pengajuan',
        'status_perkawinan',
    ];

    public function warga()
    {
        return $this->belongsTo(Wargas::class, 'warga_id', 'id_warga');
    }

    public function pengajuan()
    {
        return $this->hasMany(PengajuanPersyaratan::class, 'pengajuan_surat_id', 'id_pengajuan_surat');
    }

    public function tujuanSurat()
    {
        return $this->belongsTo(TujuanSurat::class, 'tujuan_surat_id', 'id_tujuan_surat');
    }

    public function scanKk()
    {
        return $this->belongsTo(ScanKK::class, 'scan_kk_id', 'id_scan');
    }

    public function getTujuanDisplayAttribute()
    {
        return $this->tujuan_manual ?: '-';
    }

}

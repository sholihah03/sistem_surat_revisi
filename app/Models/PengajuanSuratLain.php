<?php

namespace App\Models;

use App\Models\TujuanSurat;
use App\Models\AnggotaKeluarga;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PengajuanSuratLain extends Model
{
    use HasFactory;

    protected $table = 'tb_pengajuan_surat_lain';
    protected $primaryKey = 'id_pengajuan_surat_lain';

    protected $casts = [
    'waktu_persetujuan_rw_lain' => 'datetime',
    'waktu_persetujuan_rt_lain' => 'datetime',
];

    protected $fillable = [
        'warga_id',
        'scan_kk_id',
        'nomor_surat_pengajuan_lain',
        'status_rt_pengajuan_lain',
        'waktu_persetujuan_rt_lain',
        'status_rw_pengajuan_lain',
        'waktu_persetujuan_rw_lain',
        'is_read',
        'tujuan_manual',
        'tempat_lahir_pengaju_lain',
        'tanggal_lahir_pengaju_lain',
        'pekerjaan_pengaju_lain',
        'agama_pengaju_lain',
        'alasan_penolakan_pengajuan_lain',
        'status_perkawinan_pengaju_lain',
    ];
    public function warga()
    {
        return $this->belongsTo(Wargas::class, 'warga_id', 'id_warga');
    }
    public function scanKk()
    {
        return $this->belongsTo(ScanKK::class, 'scan_kk_id', 'id_scan');
    }

    public function getTujuanDisplayAttribute()
    {
        return $this->tujuanSurat ? $this->tujuanSurat->nama_tujuan : '-';
    }

}

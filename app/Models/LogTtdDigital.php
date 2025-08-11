<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogTtdDigital extends Model
{
    protected $table = 'tb_log_ttd_digital';
    protected $primaryKey = 'id_log_ttd';
    public $timestamps = true;

    protected $fillable = [
        'jenis_penandatangan',
        'rt_id',
        'rw_id',
        'warga_id',
        'pengajuan_surat_id',
        'pengajuan_surat_lain_id',
        'aksi',
        'file_ttd',
        'hash_dokumen',
        'token_verifikasi',
        'ip_address',
        'user_agent',
        'lokasi_approx',
        'status_verifikasi',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array', // otomatis decode/encode JSON
    ];

    /*
    |--------------------------------------------------------------------------
    | Relasi
    |--------------------------------------------------------------------------
    */

    public function rt()
    {
        return $this->belongsTo(Rt::class, 'rt_id', 'id_rt');
    }

    public function rw()
    {
        return $this->belongsTo(Rw::class, 'rw_id', 'id_rw');
    }

    public function warga()
    {
        return $this->belongsTo(Wargas::class, 'warga_id', 'id_warga');
    }

    public function pengajuanSurat()
    {
        return $this->belongsTo(PengajuanSurat::class, 'pengajuan_surat_id', 'id_pengajuan_surat');
    }

    public function pengajuanSuratLain()
    {
        return $this->belongsTo(PengajuanSuratLain::class, 'pengajuan_surat_lain_id', 'id_pengajuan_surat_lain');
    }
}

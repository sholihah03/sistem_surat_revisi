<?php

namespace App\Models;

use App\Models\TujuanSurat;
use App\Models\AnggotaKeluarga;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PengajuanSuratLain extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_surat_lain';
    protected $primaryKey = 'id_pengajuan_surat_lain';

    protected $fillable = [
        'tujuan_surat_id',
        'nomor_surat_pengajuan_lain',
        'status_pengajuan_lain',
        'pekerjaan_pengaju_lain',
        'agama_pengaju_lain',
        'alasan_penolakan_pengajuan_lain',
        'status_perkawinan_pengaju_lain',
    ];

    public function tujuanSurat()
    {
        return $this->belongsTo(TujuanSurat::class, 'tujuan_surat_id', 'id_tujuan_surat');
    }
}

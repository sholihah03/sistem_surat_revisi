<?php

namespace App\Models;

use App\Models\ScanKK;
use App\Models\PengajuanSuratLain;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AnggotaKeluarga extends Model
{
    use HasFactory;

    protected $table = 'tb_anggota_keluarga';
    protected $primaryKey = 'id_keluarga';

    protected $fillable = [
        'scan_kk_id',
        'nama_lengkap_anggota',
        'nik',
        'jenis_kelamin_anggota',
        'tempat_lahir_anggota',
        'tanggal_lahir_anggota',
        'hubungan_keluarga',
    ];

    public function scanKk()
    {
        return $this->belongsTo(ScanKK::class, 'scan_kk_id', 'id_scan');
    }

    public function pengajuanSuratLain()
    {
        return $this->hasMany(PengajuanSuratLain::class, 'keluarga_id', 'id_keluarga');
    }
}

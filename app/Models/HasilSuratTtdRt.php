<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HasilSuratTtdRt extends Model
{
    use HasFactory;

    protected $table = 'tb_hasil_surat_ttd_rt';
    protected $primaryKey = 'id_hasil_surat_ttd_rt';

    protected $fillable = [
        'jenis',
        'pengajuan_surat_id',
        'pengajuan_surat_lain_id',
        'file_surat',
    ];

    // Relasi opsional (jika ingin digunakan)
    public function pengajuanSurat()
    {
        return $this->belongsTo(PengajuanSurat::class, 'pengajuan_surat_id');
    }

    public function pengajuanSuratLain()
    {
        return $this->belongsTo(PengajuanSuratLain::class, 'pengajuan_surat_lain_id');
    }

        // Relasi ke hasil ttd RW untuk surat biasa
    public function hasilSuratTtdRwBiasa()
    {
        return $this->hasOne(HasilSuratTtdRw::class, 'pengajuan_surat_id', 'pengajuan_surat_id');
    }

    // Relasi ke hasil ttd RW untuk surat lain
    public function hasilSuratTtdRwLain()
    {
        return $this->hasOne(HasilSuratTtdRw::class, 'pengajuan_surat_lain_id', 'pengajuan_surat_lain_id');
    }

}

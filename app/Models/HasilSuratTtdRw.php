<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HasilSuratTtdRw extends Model
{
    use HasFactory;

    protected $table = 'tb_hasil_surat_ttd_rw';
    protected $primaryKey = 'id_hasil_surat_ttd_rw';

    protected $fillable = [
        'jenis',
        'pengajuan_id',
        'file_surat',
        'is_read',
        'token',
        'hash_dokumen',
    ];

    // Relasi opsional (jika ingin digunakan)
    public function pengajuanSurat()
    {
        return $this->belongsTo(PengajuanSurat::class, 'pengajuan_id');
    }

    public function pengajuanSuratLain()
    {
        return $this->belongsTo(PengajuanSuratLain::class, 'pengajuan_id');
    }
}

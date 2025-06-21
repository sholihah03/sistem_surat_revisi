<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PengajuanPersyaratan extends Model
{
    use HasFactory;

    protected $table = 'tb_pengajuan_persyaratan';
    protected $primaryKey = 'id_pengajuan_persyaratan';

    protected $fillable = [
        'pengajuan_surat_id',
        'persyaratan_surat_id',
        'dokumen',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(PengajuanSurat::class, 'pengajuan_surat_id', 'id_pengajuan_surat');
    }

    public function persyaratan()
    {
        return $this->belongsTo(PersyaratanSurat::class, 'persyaratan_surat_id', 'id_persyaratan_surat');
    }
}

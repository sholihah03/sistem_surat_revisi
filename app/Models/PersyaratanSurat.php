<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PersyaratanSurat extends Model
{
    use HasFactory;

    protected $table = 'tb_persyaratan_surat';
    protected $primaryKey = 'id_persyaratan_surat';

    protected $fillable = [
        'tujuan_surat_id',
        'nama_persyaratan',
        'keterangan',
    ];

    public function tujuanSurat()
    {
        return $this->belongsTo(TujuanSurat::class, 'tujuan_surat_id', 'id_tujuan_surat');
    }

    public function persyaratan()
    {
        return $this->hasMany(PersyaratanSurat::class, 'persyaratan_surat_id', 'id_persyaratan_surat');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TujuanSurat extends Model
{
    use HasFactory;

    protected $table = 'tb_tujuan_surat';
    protected $primaryKey = 'id_tujuan_surat';

    protected $fillable = [
        'nama_tujuan',
        'deskripsi',
        'nomor_surat',
    ];
}

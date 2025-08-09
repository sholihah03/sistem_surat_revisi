<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KopSurat extends Model
{
    protected $table = 'tb_kop_surat';
    protected $primaryKey = 'id_kop_surat';
    protected $fillable = [
        'nama_jalan',
        'no_kantor',
        'no_telepon',
        'kode_pos',
        'email'
    ];
}

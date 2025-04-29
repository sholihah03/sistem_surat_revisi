<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $table = 'tb_pendaftaran';
    protected $primaryKey = 'id_pendaftaran';

    protected $fillable = [
        'scan_id',
        'nama_lengkap',
        'no_kk',
        'nik',
        'no_hp',
        'email',
        'rw',
        'rt',
    ];

    public function scanKk()
    {
        return $this->belongsTo(ScanKK::class, 'scan_id', 'id_scan');
    }
}

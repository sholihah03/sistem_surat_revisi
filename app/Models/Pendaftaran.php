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
        'nama_lengkap',
        'no_hp',
        'email',
    ];

    public function scanKk()
    {
        return $this->belongsTo(ScanKK::class, 'scan_id', 'id_scan');
    }

    public function rt()
    {
        return $this->belongsTo(Rt::class, 'rt_id', 'id_rt');
    }

    public function rw()
    {
        return $this->belongsTo(Rw::class, 'rw_id', 'id_rw');
    }
}

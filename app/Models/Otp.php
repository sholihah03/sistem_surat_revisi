<?php

namespace App\Models;

use App\Models\Wargas;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Otp extends Model
{
    use HasFactory;

    protected $table = 'tb_otp';
    protected $primaryKey = 'id_otp';

    protected $fillable = [
        'pendaftaran_id',
        'warga_id',
        'rt_id',
        'rw_id',
        'kode_otp',
        'expired_at',
        'is_used',
        'jenis_otp',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id', 'id_pendaftaran');
    }
    public function warga()
    {
        return $this->belongsTo(Wargas::class, 'warga_id', 'id_warga');
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

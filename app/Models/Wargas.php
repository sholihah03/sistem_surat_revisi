<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // karena ada password dan login
use Illuminate\Notifications\Notifiable;

class Wargas extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'tb_wargas';
    protected $primaryKey = 'id_warga';

    protected $fillable = [
        'scan_kk_id',
        'rt_id',
        'rw_id',
        'nama_lengkap',
        'email',
        'no_kk',
        'nik',
        'no_hp',
        'otp_code',
        'otp_expired_at',
        'status_verifikasi',
        'remember_token',
        'password',
        'login',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'status_verifikasi' => 'boolean',
        'login' => 'boolean',
        'otp_expired_at' => 'datetime',
    ];

    public function scan_Kk()
    {
        return $this->belongsTo(ScanKK::class, 'scan_id', 'id_scan');
    }

    public function rt()
    {
        return $this->belongsTo(Rt::class, 'rt_id', 'id_rt');
    }

    public function rw()
    {
        return $this->belongsTo(Rt::class, 'rw_id', 'id_rw');
    }
}

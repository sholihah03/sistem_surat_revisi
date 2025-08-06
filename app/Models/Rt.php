<?php

namespace App\Models;

use App\Models\Rw;
use App\Models\Wargas;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\Authenticatable;

class Rt extends Model implements Authenticatable
{
    use HasFactory, \Illuminate\Auth\Authenticatable;

    protected $table = 'tb_rt';
    protected $primaryKey = 'id_rt';

    protected $fillable = [
        'rw_id',
        'no_rt',
        'nama_lengkap_rt',
        'email_rt',
        'no_hp_rt',
        'password',
        'profile_rt',
        'ttd_digital',
        'ttd_digital_bersih',
    ];

    protected $hidden = ['password'];

    public function rw()
    {
        return $this->belongsTo(Rw::class, 'rw_id', 'id_rw');
    }

    public function wargas()
    {
        return $this->hasMany(Wargas::class, 'rt_id', 'id_rt');
    }

    public function scanKK()
    {
        return $this->hasMany(ScanKK::class, 'rt_id', 'id_rt');
    }

    public function kadaluwarsa()
    {
        return $this->hasMany(Kadaluwarsa::class, 'rt_id', 'id_rt');
    }

    public function rts()
    {
        return $this->hasMany(Rt::class, 'rw_id', 'id_rw');
    }
}

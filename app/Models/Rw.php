<?php

namespace App\Models;

use App\Models\Rt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rw extends Model
{
    use HasFactory;

    protected $table = 'tb_rw';
    protected $primaryKey = 'id_rw';

    protected $fillable = [
        'username',
        'nama_lengkap_rw',
        'password',
        'ttd_digital',
        'ttd_digital_bersih',
        'login',
    ];

    protected $hidden = ['password'];

    public function rts()
    {
        return $this->hasMany(Rt::class, 'rw_id', 'id_rw');
    }
}

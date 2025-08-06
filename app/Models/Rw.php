<?php
namespace App\Models;

use App\Models\Rt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rw extends Model implements Authenticatable
{
    use HasFactory, \Illuminate\Auth\Authenticatable;

    protected $table = 'tb_rw';
    protected $primaryKey = 'id_rw';

    protected $fillable = [
        'no_rw',
        'nama_lengkap_rw',
        'email_rw',
        'no_hp_rw',
        'password',
        'profile_rw',
        'ttd_digital',
        'ttd_digital_bersih',
    ];

    protected $hidden = ['password'];

    // Relasi
    public function rts()
    {
        return $this->hasMany(Rt::class, 'rw_id', 'id_rw');
    }

    public function wargas()
    {
        return $this->hasMany(Wargas::class, 'rw_id', 'id_rw');
    }

    public function scanKK()
    {
        return $this->hasMany(ScanKK::class, 'rw_id', 'id_rw');
    }

    public function kadaluwarsa()
    {
        return $this->hasMany(Kadaluwarsa::class, 'rw_id', 'id_rw');
    }

    // Metode untuk autentikasi
    public function getAuthIdentifierName()
    {
        return 'email_rw';  // Kolom email yang digunakan untuk login
    }

    public function getAuthPassword()
    {
        return $this->password;  // Kolom password
    }
}

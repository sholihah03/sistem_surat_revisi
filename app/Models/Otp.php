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
        'warga_id',
        'kode_otp',
        'expired_at',
        'is_used',
        'jenis_otp',
    ];

    public function warga()
    {
        return $this->belongsTo(Wargas::class, 'warga_id', 'id_warga');
    }
}

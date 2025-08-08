<?php

namespace App\Http\Controllers\Rt;

use App\Models\ScanKK;
use App\Models\Wargas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BankDataController extends Controller
{
public function index()
{
    $rtId = Auth::guard('rt')->user()->id_rt;
    $profile_rt = Auth::guard('rt')->user()->profile_rt;
    $rt = Auth::guard('rt')->user();
    $ttdDigital = $rt->ttd_digital;
    $showModalUploadTtd = empty($ttdDigital);

    // Ambil semua ScanKK yang warga-nya berada di rt yang sama,
    // eager load warga dan alamat scan_kk-nya
    $scanKKs = ScanKK::with(['wargas', 'alamat'])
        ->whereHas('wargas', function ($query) use ($rtId) {
            $query->where('rt_id', $rtId);
        })
        ->orderBy('updated_at', 'desc')
        ->get();

    return view('rt.bankDataKk', compact('scanKKs', 'profile_rt', 'showModalUploadTtd', 'rtId', 'ttdDigital', 'rt'));
}

}

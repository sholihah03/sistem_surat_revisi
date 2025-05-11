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

        $wargas = Wargas::with(['scan_Kk.alamat'])
                    ->where('rt_id', $rtId)
                    ->get();

        return view('rt.bankDataKk', compact('wargas'));
    }
}

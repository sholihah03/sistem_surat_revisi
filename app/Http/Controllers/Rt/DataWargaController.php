<?php

namespace App\Http\Controllers\Rt;

use App\Models\Wargas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DataWargaController extends Controller
{
    public function index(Request $request)
    {
        $rt = Auth::guard('rt')->user();
        $profile_rt = Auth::guard('rt')->user()->profile_rt;
        $ttdDigital = $rt->ttd_digital;
        $showModalUploadTtd = empty($ttdDigital);

        $search = $request->input('search');

        $query = Wargas::with('scan_Kk.alamat')
            ->where('rt_id', $rt->id_rt);

        if ($search) {
            $query->where('nama_lengkap', 'like', '%' . $search . '%');
        }

        $wargas = $query->get();

        return view('rt.dataWarga', compact('wargas', 'profile_rt', 'showModalUploadTtd'));
    }
}

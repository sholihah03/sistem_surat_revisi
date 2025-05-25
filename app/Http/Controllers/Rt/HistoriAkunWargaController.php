<?php

namespace App\Http\Controllers\Rt;

use App\Models\ScanKK;
use App\Models\Kadaluwarsa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HistoriAkunWargaController extends Controller
{
    public function historiVerifikasiAkunWarga(Request $request)
    {
        $profile_rt = Auth::guard('rt')->user()->profile_rt;
        $rt = Auth::guard('rt')->user();
        $ttdDigital = $rt->ttd_digital;
        $showModalUploadTtd = empty($ttdDigital);

        $search = $request->input('search');

        $historiData = ScanKK::with(['alamat', 'wargas', 'pendaftaran'])
            ->whereIn('status_verifikasi', ['disetujui', 'ditolak'])
            ->whereHas('wargas', function ($q) use ($rt) {
                $q->where('rt_id', $rt->id_rt);
            })
            ->orderBy('updated_at', 'desc');

        if ($search) {
            $historiData->where(function ($query) use ($search) {
                $query->whereHas('wargas', function ($q) use ($search) {
                    $q->where('nama_lengkap', 'like', '%' . $search . '%');
                })->orWhereHas('pendaftaran', function ($q) use ($search) {
                    $q->where('nama_lengkap', 'like', '%' . $search . '%');
                });
            })->orWhere('no_kk_scan', 'like', '%' . $search . '%');
        }

        $historiData = $historiData->get();

        return view('rt.historiVerifikasiAkunWarga', compact('historiData', 'profile_rt', 'rt', 'showModalUploadTtd'));
    }

    public function historiKadaluwarsa(Request $request)
    {
        $search = $request->input('search');
        $query = Kadaluwarsa::query()->orderBy('created_at', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%' . $search . '%')
                ->orWhere('nik', 'like', '%' . $search . '%');
            });
        }

        $dataKadaluwarsa = $query->get();
        $profile_rt = Auth::guard('rt')->user()->profile_rt;
        $rt = Auth::guard('rt')->user();
        $ttdDigital = $rt->ttd_digital;
        $showModalUploadTtd = empty($ttdDigital);

        return view('rt.historiAkunKadaluwarsa', compact('dataKadaluwarsa', 'profile_rt', 'rt', 'showModalUploadTtd','ttdDigital'));
    }

}

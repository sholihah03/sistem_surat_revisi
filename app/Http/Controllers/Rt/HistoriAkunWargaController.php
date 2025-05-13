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
            $search = $request->input('search');

            $historiData = ScanKK::with(['alamat', 'wargas', 'pendaftaran'])
                ->whereIn('status_verifikasi', ['disetujui', 'ditolak'])
                ->orderBy('updated_at', 'desc');

                if ($search) {
                    $historiData->where(function ($query) use ($search) {
                        $query->whereHas('wargas', function ($q) use ($search) {
                            $q->where('nama_lengkap', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('pendaftaran', function ($q) use ($search) {
                            $q->where('nama_lengkap', 'like', '%' . $search . '%');
                        });
                    })
                    ->orWhere('no_kk_scan', 'like', '%' . $search . '%');
                }

            $historiData = $historiData->get();

            return view('rt.historiVerifikasiAkunWarga', compact('historiData', 'profile_rt'));
        }

        public function historiKadaluwarsa()
        {
            $dataKadaluwarsa = Kadaluwarsa::orderBy('created_at', 'desc')->get();
            $profile_rt = Auth::guard('rt')->user()->profile_rt;
            return view('rt.historiAkunKadaluwarsa', compact('dataKadaluwarsa', 'profile_rt'));
        }
}

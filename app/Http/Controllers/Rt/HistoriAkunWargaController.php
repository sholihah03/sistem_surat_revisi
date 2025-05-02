<?php

namespace App\Http\Controllers\Rt;

use App\Models\ScanKK;
use App\Models\Kadaluwarsa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HistoriAkunWargaController extends Controller
{
    public function historiVerifikasiAkunWarga(Request $request)
        {

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

            return view('rt.historiVerifikasiAkunWarga', compact('historiData'));
        }

        public function historiKadaluwarsa()
        {
            $dataKadaluwarsa = Kadaluwarsa::orderBy('created_at', 'desc')->get();
            return view('rt.historiAkunKadaluwarsa', compact('dataKadaluwarsa'));
        }
}

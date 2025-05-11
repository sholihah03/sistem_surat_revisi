<?php

namespace App\Http\Controllers\Warga;

use Illuminate\Http\Request;
use App\Models\PengajuanSurat;
use App\Models\PengajuanSuratLain;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(){
        $wargaId = Auth::guard('warga')->id();

        $pengajuanSurat = PengajuanSurat::where(function ($query) use ($wargaId) {
                                    $query->where('warga_id', $wargaId)
                                          ->where('status', '!=', 'Ditolak');
                                })->get();

        $pengajuanSuratLain = PengajuanSuratLain::where(function ($query) use ($wargaId) {
                                        $query->where('warga_id', $wargaId)
                                              ->where('status_pengajuan_lain', '!=', 'Ditolak');
                                    })->get();

        // Handle tujuan_manual
        $pengajuanSuratLain->transform(function($item) {
            $item->tujuan_surat = $item->tujuan_surat ?? $item->tujuan_manual;
            return $item;
        });

        // Gabungkan data
        $pengajuanSurat = $pengajuanSurat->merge($pengajuanSuratLain);

        return view('warga.dashboard', compact('pengajuanSurat'));
    }

}

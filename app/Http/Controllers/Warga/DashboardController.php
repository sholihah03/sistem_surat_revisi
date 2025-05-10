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
        // Ambil data pengajuan surat dari tb_pengajuan_surat berdasarkan warga_id
        $pengajuanSurat = PengajuanSurat::where('warga_id', Auth::guard('warga')->id()) // Gunakan guard warga
                                        ->orWhere('status', '!=', 'Ditolak') // Sesuaikan statusnya jika perlu
                                        ->get();

        $pengajuanSuratLain = PengajuanSuratLain::where('warga_id', Auth::guard('warga')->id()) // Gunakan guard warga
                                                ->orWhere('status_pengajuan_lain', '!=', 'Ditolak') // Sesuaikan statusnya jika perlu
                                                ->get();

        $pengajuanSuratLain->transform(function($item) {
            $item->tujuan_surat = $item->tujuan_surat ?? $item->tujuan_manual; // Jika tujuan_surat kosong, gunakan tujuan_manual
            return $item;
        });
        
        // Gabungkan kedua data pengajuan surat
        $pengajuanSurat = $pengajuanSurat->merge($pengajuanSuratLain);

        // Kirim data ke view dashboard
        return view('warga.dashboard', compact('pengajuanSurat'));
    }
}

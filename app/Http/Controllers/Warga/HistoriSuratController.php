<?php

namespace App\Http\Controllers\Warga;

use Illuminate\Http\Request;
use App\Models\PengajuanSurat;
use App\Models\PengajuanSuratLain;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HistoriSuratController extends Controller
{
//     public function index()
// {
//     // Ambil ID user dari guard 'warga'
//     $wargaId = Auth::guard('warga')->user();

//     // Data surat pengajuan biasa (dengan relasi ke tujuanSurat)
//     $pengajuanBiasa = PengajuanSurat::with('tujuanSurat')
//         ->where('warga_id', $wargaId)
//         ->whereIn('status', ['disetujui', 'ditolak'])
//         ->get();

//     // Data surat pengajuan manual
//     $pengajuanLain = PengajuanSuratLain::where('warga_id', $wargaId)
//         ->whereIn('status_pengajuan_lain', ['disetujui', 'ditolak'])
//         ->get();

//         // dd([
//         //     'warga_id' => $wargaId,
//         //     'pengajuanBiasa' => $pengajuanBiasa,
//         //     'pengajuanLain' => $pengajuanLain
//         // ]);


//     return view('warga.historiSurat', compact('pengajuanBiasa', 'pengajuanLain'));
// }

public function index()
{
    $wargaId = Auth::guard('warga')->user()->id_warga;

    // Debugging: Periksa ID Warga
    // dd(Auth::guard('warga')->user());

    // Ambil data pengajuan surat
    $pengajuanBiasa = PengajuanSurat::where('warga_id', $wargaId)
        ->whereIn('status', ['disetujui', 'ditolak'])
        ->get();

    $pengajuanLain = PengajuanSuratLain::where('warga_id', $wargaId)
        ->whereIn('status_pengajuan_lain', ['disetujui', 'ditolak'])
        ->get();

    // Debugging: Periksa hasil query
    // dd($pengajuanBiasa, $pengajuanLain);

    // Pisahkan berdasarkan status
    $disetujuiBiasa = $pengajuanBiasa->where('status', 'disetujui');
    $ditolakBiasa = $pengajuanBiasa->where('status', 'ditolak');
    $disetujuiLain = $pengajuanLain->where('status_pengajuan_lain', 'disetujui');
    $ditolakLain = $pengajuanLain->where('status_pengajuan_lain', 'ditolak');

    return view('warga.historiSurat', compact('disetujuiBiasa', 'ditolakBiasa', 'disetujuiLain', 'ditolakLain'));
}



}

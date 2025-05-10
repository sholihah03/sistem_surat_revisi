<?php

namespace App\Http\Controllers\Warga;

use Illuminate\Http\Request;
use App\Models\PengajuanSurat;
use App\Models\PengajuanSuratLain;
use App\Http\Controllers\Controller;

class HistoriSuratController extends Controller
{
    public function index()
{
    // Ambil ID user dari guard 'warga'
    $wargaId = auth('warga')->user()->id;

    // Data surat pengajuan biasa (dengan relasi ke tujuanSurat)
    $pengajuanBiasa = PengajuanSurat::with('tujuanSurat')
        ->where('warga_id', $wargaId)
        ->whereIn('status', ['disetujui', 'ditolak'])
        ->get();

    // Data surat pengajuan manual
    $pengajuanLain = PengajuanSuratLain::where('warga_id', $wargaId)
        ->whereIn('status_pengajuan_lain', ['disetujui', 'ditolak'])
        ->get();

    return view('warga.historiSurat', compact('pengajuanBiasa', 'pengajuanLain'));
}

}

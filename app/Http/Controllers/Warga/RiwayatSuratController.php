<?php

namespace App\Http\Controllers\Warga;

use Illuminate\Http\Request;
use App\Models\PengajuanSurat;
use App\Models\PengajuanSuratLain;
use App\Http\Controllers\Controller;

class RiwayatSuratController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user('warga');

        // Pengajuan dari tb_pengajuan_surat (biasa)
        $pengajuanBiasa = PengajuanSurat::with('tujuanSurat')
            ->where('warga_id', $user->id_warga)
            ->get();

        // Pengajuan dari tb_pengajuan_surat_lain (manual)
        $pengajuanLain = PengajuanSuratLain::where('warga_id', $user->id_warga)
            ->get();

        return view('warga.riwayatSurat', compact('pengajuanBiasa', 'pengajuanLain'));
    }
}

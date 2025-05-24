<?php

namespace App\Http\Controllers\Warga;

use Illuminate\Http\Request;
use App\Models\PengajuanSurat;
use App\Models\HasilSuratTtdRw;
use App\Models\PengajuanSuratLain;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HistoriSuratController extends Controller
{
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

        // Ambil data hasil surat dari tb_hasil_surat_ttd_rw yang terkait dengan warga login
        // Pertama dapatkan ID pengajuan warga dari 2 jenis pengajuan
        $idPengajuanBiasa = PengajuanSurat::where('warga_id', $wargaId)->pluck('id_pengajuan_surat')->toArray();
        $idPengajuanLain = PengajuanSuratLain::where('warga_id', $wargaId)->pluck('id_pengajuan_surat_lain')->toArray();

        // Query hasil surat yang terkait dengan pengajuan warga (baik biasa maupun lain)
        $hasilSurat = HasilSuratTtdRw::where(function ($query) use ($idPengajuanBiasa, $idPengajuanLain) {
            $query->where(function ($q) use ($idPengajuanBiasa) {
                $q->where('jenis', 'biasa')->whereIn('pengajuan_id', $idPengajuanBiasa);
            })->orWhere(function ($q) use ($idPengajuanLain) {
                $q->where('jenis', 'lain')->whereIn('pengajuan_id', $idPengajuanLain);
            });
        })->get();


        return view('warga.historiSurat', compact('disetujuiBiasa', 'ditolakBiasa', 'disetujuiLain', 'ditolakLain', 'hasilSurat'));
    }
}

<?php

namespace App\Http\Controllers\Warga;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\PengajuanSurat;
use App\Models\PengajuanSuratLain;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil data user dari guard warga
        $warga = Auth::guard('warga')->user();
        $rt = $warga->rt;
        $rw = $warga->rw;

        $dataBelumLengkap =
        empty($warga->scan_Kk?->no_kk_scan) ||
        empty($warga->rt_id) ||
        empty($warga->rw_id);


        // Ambil pengajuan surat untuk warga tersebut, sekaligus eager load tujuanSurat
        $pengajuanSurat = PengajuanSurat::with('tujuanSurat')
            ->where('warga_id', $warga->id_warga)
            ->get();

        // Ambil pengajuan surat lain untuk warga tersebut
        $pengajuanSuratLain = PengajuanSuratLain::where('warga_id', $warga->id_warga)->get();

        // Beri properti 'tujuan' pada masing-masing item pengajuanSurat (ambil nama_tujuan)
        foreach ($pengajuanSurat as $surat) {
            $surat->tujuan = $surat->tujuanSurat ? $surat->tujuanSurat->nama_tujuan : '-';
            $surat->status_rt_universal = $surat->status_rt;
            $surat->status_rw_universal = $surat->status_rw;
        }

        // Beri properti 'tujuan' pada masing-masing item pengajuanSuratLain (ambil tujuan_manual)
        foreach ($pengajuanSuratLain as $suratLain) {
            $suratLain->tujuan = $suratLain->tujuan_manual ?: '-';
            $suratLain->status_rt_universal = $suratLain->status_rt_pengajuan_lain;
            $suratLain->status_rw_universal = $suratLain->status_rw_pengajuan_lain;
        }

        // Gabungkan koleksi
        $pengajuanSuratGabungan = $pengajuanSurat->concat($pengajuanSuratLain);

        // Urutkan berdasarkan created_at descending
        $pengajuanSuratGabungan = $pengajuanSuratGabungan->sortByDesc('created_at');

        return view('warga.dashboard', compact('warga', 'rt', 'rw', 'pengajuanSuratGabungan', 'dataBelumLengkap'));
    }

    public function panduan()
    {
        $warga = Auth::guard('warga')->user();
        $dataBelumLengkap =
        empty($warga->scan_Kk?->path_file_kk) ||
        empty($warga->rt_id) ||
        empty($warga->rw_id);

        return view('warga.panduan', compact('warga', 'dataBelumLengkap'));
    }


}

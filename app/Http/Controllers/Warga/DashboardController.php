<?php

namespace App\Http\Controllers\Warga;

use Carbon\Carbon;
use App\Models\ScanKK;
use Illuminate\Http\Request;
use App\Models\PengajuanSurat;
use App\Models\PengajuanSuratLain;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
public function index()
{
    $warga = Auth::guard('warga')->user();
    $rt = $warga->rt;
    $rw = $warga->rw;

    $scanKK = ScanKK::where('nama_pendaftar', $warga->nama_lengkap)->first();

    $statusKK = null;
    $alasanPenolakan = null;

    if ($scanKK) {
        $statusKK = $scanKK->status_verifikasi;
        $alasanPenolakan = $scanKK->alasan_penolakan;
    }

    $dataBelumLengkap = (empty($warga->no_kk) && empty($warga->nik) && !$scanKK);

    // Notifikasi pengajuan surat
    $pengajuanSurat = PengajuanSurat::with('tujuanSurat')
        ->where('warga_id', $warga->id_warga)
        ->get();

    $pengajuanSuratLain = PengajuanSuratLain::where('warga_id', $warga->id_warga)->get();

    foreach ($pengajuanSurat as $surat) {
        $surat->tujuan = $surat->tujuanSurat?->nama_tujuan ?? '-';
        $surat->status_rt_universal = $surat->status_rt;
        $surat->status_rw_universal = $surat->status_rw;
    }

    foreach ($pengajuanSuratLain as $suratLain) {
        $suratLain->tujuan = $suratLain->tujuan_manual ?: '-';
        $suratLain->status_rt_universal = $suratLain->status_rt_pengajuan_lain;
        $suratLain->status_rw_universal = $suratLain->status_rw_pengajuan_lain;
    }

    $pengajuanSuratGabungan = $pengajuanSurat
        ->concat($pengajuanSuratLain)
        ->sortByDesc('created_at');

    // Hitung notifikasi baru
    $notifikasi = collect(); // ambil dari model notifikasi kamu

    $totalNotifBaru = $notifikasi->where('is_read', false)->count();

    // Tambahkan notifikasi "status disetujui" ke total jika kurang dari 1 hari
    $showStatusDisetujui = false;
    if ($statusKK === 'disetujui' && $scanKK && $scanKK->updated_at->gt(Carbon::now()->subDay())) {
        $showStatusDisetujui = true;
        $totalNotifBaru++;
    }

    return view('warga.dashboard', compact(
        'warga', 'rt', 'rw',
        'pengajuanSuratGabungan',
        'dataBelumLengkap',
        'statusKK',
        'alasanPenolakan',
        'notifikasi',
        'totalNotifBaru',
        'showStatusDisetujui',
        'scanKK'
    ));
}




    public function panduan()
    {
        $warga = Auth::guard('warga')->user();
        $scanKK = ScanKK::where('nama_pendaftar', $warga->nama_lengkap)->first();

        $statusKK = null;
        $alasanPenolakan = null;

        if ($scanKK) {
            $statusKK = $scanKK->status_verifikasi;
            $alasanPenolakan = $scanKK->alasan_penolakan;
        }

        $dataBelumLengkap = (empty($warga->no_kk) && empty($warga->nik) && !$scanKK);

        // Hitung notifikasi baru
        $notifikasi = collect(); // ambil dari model notifikasi kamu

        $totalNotifBaru = $notifikasi->where('is_read', false)->count();

        // Tambahkan notifikasi "status disetujui" ke total jika kurang dari 1 hari
        $showStatusDisetujui = false;
        if ($statusKK === 'disetujui' && $scanKK && $scanKK->updated_at->gt(Carbon::now()->subDay())) {
            $showStatusDisetujui = true;
            $totalNotifBaru++;
        }

        return view('warga.panduan', compact('warga', 'dataBelumLengkap', 'notifikasi', 'totalNotifBaru', 'showStatusDisetujui', 'scanKK', 'statusKK', 'alasanPenolakan'));
    }


}

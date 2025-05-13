<?php

namespace App\Http\Controllers\Rt;

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
        $pendingCount = ScanKK::where('status_verifikasi', 'pending')->count();
        $profile_rt = Auth::guard('rt')->user()->profile_rt;


        // Kirimkan data ke view
        return view('rt.dashboardRt', compact('pendingCount', 'profile_rt'));
    }

    public function indexMain()
    {
        $rt = Auth::guard('rt')->user(); // RT yang sedang login
        $rtId = $rt->id_rt;
        $profile_rt = Auth::guard('rt')->user()->profile_rt;

        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;

        // Hitung surat masuk untuk bulan ini dari tb_pengajuan_surat
        $jumlahSuratMasuk1 = PengajuanSurat::whereHas('warga', function ($query) use ($rtId) {
            $query->where('rt_id', $rtId);
        })
        ->whereMonth('created_at', $bulanIni)
        ->whereYear('created_at', $tahunIni)
        ->count();

        // Hitung surat masuk untuk bulan ini dari tb_pengajuan_surat_lain
        $jumlahSuratMasuk2 = PengajuanSuratLain::whereHas('warga', function ($query) use ($rtId) {
            $query->where('rt_id', $rtId);
        })
        ->whereMonth('created_at', $bulanIni)
        ->whereYear('created_at', $tahunIni)
        ->count();

        $totalSuratMasuk = $jumlahSuratMasuk1 + $jumlahSuratMasuk2;

        // Hitung surat disetujui untuk bulan ini dari tb_pengajuan_surat
        $jumlahDisetujui1 = PengajuanSurat::whereHas('warga', function ($query) use ($rtId) {
            $query->where('rt_id', $rtId);
        })
        ->where('status', 'disetujui')
        ->whereMonth('created_at', $bulanIni)
        ->whereYear('created_at', $tahunIni)
        ->count();

        // Hitung surat disetujui untuk bulan ini dari tb_pengajuan_surat_lain
        $jumlahDisetujui2 = PengajuanSuratLain::whereHas('warga', function ($query) use ($rtId) {
            $query->where('rt_id', $rtId);
        })
        ->where('status_pengajuan_lain', 'disetujui')
        ->whereMonth('created_at', $bulanIni)
        ->whereYear('created_at', $tahunIni)
        ->count();

        $totalDisetujui = $jumlahDisetujui1 + $jumlahDisetujui2;

        // Hitung surat menunggu untuk bulan ini
        $pendingCount = ScanKK::where('status_verifikasi', 'pending')
                            ->whereHas('wargas', function ($q) use ($rtId) {
                                $q->where('rt_id', $rtId);
                            })
                            ->count();

        // Ambil pengajuan surat terbaru (status apapun)
        $pengajuanSurat = PengajuanSurat::with(['warga', 'tujuanSurat'])
            ->whereHas('warga', function ($query) use ($rtId) {
                $query->where('rt_id', $rtId);
            })
            ->whereMonth('created_at', $bulanIni)
            ->whereYear('created_at', $tahunIni)
            ->latest()
            ->take(10)
            ->get();

        // Ambil pengajuan surat lain terbaru (status apapun)
        $pengajuanSuratLain = PengajuanSuratLain::with(['warga'])
            ->whereHas('warga', function ($query) use ($rtId) {
                $query->where('rt_id', $rtId);
            })
            ->whereMonth('created_at', $bulanIni)
            ->whereYear('created_at', $tahunIni)
            ->latest()
            ->take(10)
            ->get();

        // Gabungkan dan ambil 5 terbaru dari semua status
        $pengajuanTerbaru = $pengajuanSurat
            ->merge($pengajuanSuratLain)
            ->sortByDesc('created_at')
            ->take(5);


        return view('rt.mainRt', compact('pendingCount', 'totalSuratMasuk', 'totalDisetujui', 'pengajuanTerbaru', 'profile_rt'));
    }

}

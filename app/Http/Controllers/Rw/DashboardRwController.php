<?php

namespace App\Http\Controllers\Rw;

use App\Models\Rt;
use Carbon\Carbon;
use App\Models\Wargas;
use Illuminate\Http\Request;
use App\Models\PengajuanSurat;
use App\Models\HasilSuratTtdRt;
use App\Models\PengajuanSuratLain;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardRwController extends Controller
{
    public function index()
    {
        $rw = Auth::guard('rw')->user(); // Ambil user dari guard rw
        $rwId = $rw->id_rw;
        $now = Carbon::now();

        // Ambil semua ID warga yang tinggal di RW ini
        $wargaIds = Wargas::where('rw_id', $rwId)->pluck('id_warga');
        // Ambil daftar RT yang ada di RW ini
        $rts = Rt::where('rw_id', $rwId)->get();
        $ttdDigital = $rw->ttd_digital;
        $statusPengajuanPerRt = [];

        $showModalUploadTtdRw = empty($ttdDigital);

        foreach ($rts as $rt) {
            // Ambil warga yang tinggal di RT ini dan RW ini (pastikan rw juga cocok)
            $wargaIds = Wargas::where('rw_id', $rwId)
                ->where('rt_id', $rt->id_rt)
                ->pluck('id_warga');

            // Jumlah pengajuan surat biasa bulan ini per RT
            $jumlahSuratBiasa = PengajuanSurat::whereIn('warga_id', $wargaIds)
                ->whereMonth('created_at', $now->month)
                ->whereYear('created_at', $now->year)
                ->count();

            // Jumlah pengajuan surat lain bulan ini per RT
            $jumlahSuratLain = PengajuanSuratLain::whereIn('warga_id', $wargaIds)
                ->whereMonth('created_at', $now->month)
                ->whereYear('created_at', $now->year)
                ->count();

            $totalPengajuan = $jumlahSuratBiasa + $jumlahSuratLain;

            // Surat menunggu
            $menungguBiasa = PengajuanSurat::whereIn('warga_id', $wargaIds)
                ->where('status_rt', 'menunggu')
                ->whereMonth('created_at', $now->month)
                ->whereYear('created_at', $now->year)
                ->count();

            $menungguLain = PengajuanSuratLain::whereIn('warga_id', $wargaIds)
                ->where('status_rt_pengajuan_lain', 'menunggu')
                ->whereMonth('created_at', $now->month)
                ->whereYear('created_at', $now->year)
                ->count();

            $totalMenunggu = $menungguBiasa + $menungguLain;

            // Surat disetujui
            $disetujuiBiasa = PengajuanSurat::whereIn('warga_id', $wargaIds)
                ->where('status_rt', 'disetujui')
                ->whereMonth('created_at', $now->month)
                ->whereYear('created_at', $now->year)
                ->count();

            $disetujuiLain = PengajuanSuratLain::whereIn('warga_id', $wargaIds)
                ->where('status_rt_pengajuan_lain', 'disetujui')
                ->whereMonth('created_at', $now->month)
                ->whereYear('created_at', $now->year)
                ->count();

            $totalDisetujui = $disetujuiBiasa + $disetujuiLain;

            // Surat ditolak
            $ditolakBiasa = PengajuanSurat::whereIn('warga_id', $wargaIds)
                ->where('status_rt', 'ditolak')
                ->whereMonth('created_at', $now->month)
                ->whereYear('created_at', $now->year)
                ->count();

            $ditolakLain = PengajuanSuratLain::whereIn('warga_id', $wargaIds)
                ->where('status_rt_pengajuan_lain', 'ditolak')
                ->whereMonth('created_at', $now->month)
                ->whereYear('created_at', $now->year)
                ->count();

            $totalDitolak = $ditolakBiasa + $ditolakLain;

            if ($totalPengajuan > 0) {
                $statusPengajuanPerRt[] = [
                    'no_rt' => $rt->no_rt,
                    'total_pengajuan' => $totalPengajuan,
                    'total_menunggu' => $totalMenunggu,
                    'total_disetujui' => $totalDisetujui,
                    'total_ditolak' => $totalDitolak,
                    'rt_id' => $rt->id_rt,
                ];
            }

        }

        // Hitung jumlah pengajuan dari tb_pengajuan_surat
        $countSuratBiasa = PengajuanSurat::whereIn('warga_id', $wargaIds)
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->count();

        // Hitung jumlah pengajuan dari tb_pengajuan_surat_lain
        $countSuratLain = PengajuanSuratLain::whereIn('warga_id', $wargaIds)
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->count();

        // Total gabungan
        $totalSuratMasuk = $countSuratBiasa + $countSuratLain;

        // ✅ Hitung surat yang disetujui dari dua tabel
        $suratDisetujuiBiasa = PengajuanSurat::whereIn('warga_id', $wargaIds)
            ->where('status_rt', 'disetujui')
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->count();

        $suratDisetujuiLain = PengajuanSuratLain::whereIn('warga_id', $wargaIds)
            ->where('status_rt_pengajuan_lain', 'disetujui')
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->count();

        $totalSuratDisetujui = $suratDisetujuiBiasa + $suratDisetujuiLain;

        $suratBelumTtdRwCount = HasilSuratTtdRt::where(function ($query) {
            $query->where(function ($q) {
                $q->whereNotNull('pengajuan_surat_id')
                ->whereDoesntHave('hasilSuratTtdRwBiasa');
            })
            ->orWhere(function ($q) {
                $q->whereNotNull('pengajuan_surat_lain_id')
                ->whereDoesntHave('hasilSuratTtdRwLain');
            });
        })
        ->where(function ($query) use ($rwId) {
            $query->whereHas('pengajuanSurat.warga.rt', function ($q) use ($rwId) {
                $q->where('rw_id', $rwId);
            })
            ->orWhereHas('pengajuanSuratLain.warga.rt', function ($q) use ($rwId) {
                $q->where('rw_id', $rwId);
            });
        })
        ->count();

        // Urutkan $statusPengajuanPerRt berdasarkan no_rt secara ascending
        usort($statusPengajuanPerRt, function ($a, $b) {
            return intval($a['no_rt']) <=> intval($b['no_rt']);
        });


        return view('rw.mainRw', [
            'totalSuratMasuk' => $totalSuratMasuk,
            'totalSuratDisetujui' => $totalSuratDisetujui,
            'totalWargaTerdaftar' => Wargas::where('rw_id', $rwId)->count(),
            'statusPengajuanPerRt' => $statusPengajuanPerRt,
            'rw' => $rw,
            'suratBelumTtdRwCount' => $suratBelumTtdRwCount,
            'showModalUploadTtdRw' => $showModalUploadTtdRw,
        ]);

    }

}

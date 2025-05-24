<?php

namespace App\Providers;

use App\Models\HasilSuratTtdRw;
use Carbon\Carbon;
use App\Models\ScanKK;
use App\Service\OCRService;
use App\Models\PengajuanSurat;
use App\Models\PengajuanSuratLain;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(OCRService::class, function ($app) {
            return new OCRService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale('id');

        View::composer('*', function ($view) {
            $pendingCount = ScanKK::where('status_verifikasi', 'pending')->count();
            $pendingSuratCount = PengajuanSurat::where('status', 'menunggu')->count();
            $pendingSuratLainCount = PengajuanSuratLain::where('status_pengajuan_lain', 'menunggu')
            ->count();

            $totalNotif = $pendingCount + $pendingSuratCount + $pendingSuratLainCount;

            $view->with([
                'pendingCount' => $pendingCount,
                'pendingSuratCount' => $pendingSuratCount,
                'pendingSuratLainCount' => $pendingSuratLainCount,
                'totalNotif' => $totalNotif,
            ]);
        });

        View::composer('komponen.nav', function ($view) {
            if (Auth::guard('warga')->check()) {
                $warga = Auth::guard('warga')->user();

                $notifikasiBiasa = PengajuanSurat::with('warga')
                    ->whereIn('status', ['disetujui', 'ditolak'])
                    ->where('warga_id', $warga->id_warga)
                    ->orderBy('updated_at', 'desc')
                    ->take(5)
                    ->get();

                $notifikasiLain = PengajuanSuratLain::with('warga')
                    ->whereIn('status_pengajuan_lain', ['disetujui', 'ditolak'])
                    ->where('warga_id', $warga->id_warga)
                    ->orderBy('updated_at', 'desc')
                    ->take(5)
                    ->get();

                // Ambil surat yang sudah selesai dan masuk ke tb_hasil_surat_ttd_rw
                $notifikasiSelesai = HasilSuratTtdRw::with(['pengajuanSurat', 'pengajuanSuratLain'])
                    ->whereHas('pengajuanSurat', function ($query) use ($warga) {
                        $query->where('warga_id', $warga->id_warga);
                    })
                    ->orWhereHas('pengajuanSuratLain', function ($query) use ($warga) {
                        $query->where('warga_id', $warga->id_warga);
                    })
                    ->orderBy('updated_at', 'desc')
                    ->take(5)
                    ->get();

                $notifikasi = $notifikasiBiasa->concat($notifikasiLain)->concat($notifikasiSelesai)->sortByDesc('updated_at')->take(5);

                // Ambil ID notifikasi yang sudah dibaca dari session
                $dibaca = session('notifikasi_dibaca_warga', []);

                // Filter notifikasi agar yang sudah dibaca tidak muncul (atau hilangkan badge-nya)
                $notifikasiBaru = $notifikasi->filter(function($item) use ($dibaca) {
                    return !in_array(($item->id ?? $item->id_hasil_surat_ttd_rw) . $item->updated_at->timestamp, $dibaca);
                });

                $view->with([
                    'notifikasi' => $notifikasi,
                    'notifikasiBaru' => $notifikasiBaru,
                    'totalNotifBaru' => $notifikasiBaru->count(),
                ]);
            }
        });

        View::composer(['rw.mainRw', 'rw.dashboardRw'], function ($view) {
            $profile_rw = Auth::guard('rw')->user()->profile_rw ?? null;
            // Ambil pengajuan biasa yang sudah ada hasilnya
            $pengajuanSuratDisetujuiIds = HasilSuratTtdRw::where('jenis', 'biasa')
                ->pluck('pengajuan_id')->toArray();

            // Ambil pengajuan lain yang sudah ada hasilnya
            $pengajuanSuratLainDisetujuiIds = HasilSuratTtdRw::where('jenis', 'lain')
                ->pluck('pengajuan_id')->toArray();

            $pengajuanSuratBaru = PengajuanSurat::with(['warga.rt'])
                ->where('status', 'disetujui')
                ->whereNotIn('id_pengajuan_surat', $pengajuanSuratDisetujuiIds)
                ->orderBy('updated_at', 'desc')
                ->take(5)
                ->get();

            $pengajuanSuratLainBaru = PengajuanSuratLain::with(['warga.rt'])
                ->where('status_pengajuan_lain', 'disetujui')
                ->whereNotIn('id_pengajuan_surat_lain', $pengajuanSuratLainDisetujuiIds)
                ->orderBy('updated_at', 'desc')
                ->take(5)
                ->get();

            $totalNotifRw = $pengajuanSuratBaru->count() + $pengajuanSuratLainBaru->count();

            $view->with(compact('profile_rw', 'totalNotifRw', 'pengajuanSuratBaru', 'pengajuanSuratLainBaru'));
        });
    }
}

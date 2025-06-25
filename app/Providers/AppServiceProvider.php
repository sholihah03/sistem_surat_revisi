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
            $pendingSuratCount = PengajuanSurat::where('status_rt', 'menunggu')->count();
            $pendingSuratLainCount = PengajuanSuratLain::where('status_rt_pengajuan_lain', 'menunggu')
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

                // Notifikasi belum dibaca (untuk badge dan dropdown atas)
                $notifikasiBiasa = PengajuanSurat::with('warga')
                    ->where(function ($q) {
                        $q->whereIn('status_rt', ['disetujui', 'ditolak'])
                        ->orWhereIn('status_rw', ['disetujui', 'ditolak']);
                    })
                    ->where('warga_id', $warga->id_warga)
                    ->where('is_read', false)
                    ->orderBy('updated_at', 'desc')
                    ->take(5)
                    ->get();

                $notifikasiLain = PengajuanSuratLain::with('warga')
                    ->where(function ($q) {
                        $q->whereIn('status_rt_pengajuan_lain', ['disetujui', 'ditolak'])
                        ->orWhereIn('status_rw_pengajuan_lain', ['disetujui', 'ditolak']);
                    })
                    ->where('warga_id', $warga->id_warga)
                    ->where('is_read', false)
                    ->orderBy('updated_at', 'desc')
                    ->take(5)
                    ->get();

                $notifikasiSelesai = HasilSuratTtdRw::with(['pengajuanSurat', 'pengajuanSuratLain'])
                    ->where(function ($query) use ($warga) {
                        $query->whereHas('pengajuanSurat', function ($q) use ($warga) {
                            $q->where('warga_id', $warga->id_warga);
                        })
                        ->orWhereHas('pengajuanSuratLain', function ($q) use ($warga) {
                            $q->where('warga_id', $warga->id_warga);
                        });
                    })
                    ->where('is_read', false)
                    ->orderBy('updated_at', 'desc')
                    ->take(5)
                    ->get();

                $notifikasiBaru = $notifikasiBiasa->concat($notifikasiLain)->concat($notifikasiSelesai)->sortByDesc('updated_at')->take(5);

                // Semua notifikasi terbaru (untuk isi dropdown)
                $notifikasiBiasaAll = PengajuanSurat::with('warga')
                    ->where(function ($q) {
                        $q->whereIn('status_rt', ['disetujui', 'ditolak'])
                        ->orWhereIn('status_rw', ['disetujui', 'ditolak']);
                    })
                    ->where('warga_id', $warga->id_warga)
                    ->orderBy('updated_at', 'desc')
                    ->take(5)
                    ->get();

                $notifikasiLainAll = PengajuanSuratLain::with('warga')
                    ->where(function ($q) {
                        $q->whereIn('status_rt_pengajuan_lain', ['disetujui', 'ditolak'])
                        ->orWhereIn('status_rw_pengajuan_lain', ['disetujui', 'ditolak']);
                    })
                    ->where('warga_id', $warga->id_warga)
                    ->orderBy('updated_at', 'desc')
                    ->take(5)
                    ->get();

                $notifikasiSelesaiAll = HasilSuratTtdRw::with(['pengajuanSurat', 'pengajuanSuratLain'])
                    ->where(function ($query) use ($warga) {
                        $query->whereHas('pengajuanSurat', function ($q) use ($warga) {
                            $q->where('warga_id', $warga->id_warga);
                        })
                        ->orWhereHas('pengajuanSuratLain', function ($q) use ($warga) {
                            $q->where('warga_id', $warga->id_warga);
                        });
                    })
                    ->orderBy('updated_at', 'desc')
                    ->take(5)
                    ->get();

                $notifikasi = $notifikasiBiasaAll->concat($notifikasiLainAll)->concat($notifikasiSelesaiAll)->sortByDesc('updated_at')->take(5);

                // Total notifikasi belum dibaca (untuk badge merah di icon)
                $totalNotifBaru =
                PengajuanSurat::where('warga_id', $warga->id_warga)
                    ->where('is_read', false)
                    ->where(function ($q) {
                        $q->whereIn('status_rt', ['disetujui', 'ditolak'])
                        ->orWhereIn('status_rw', ['disetujui', 'ditolak']);
                    })
                    ->count()
                + PengajuanSuratLain::where('warga_id', $warga->id_warga)
                    ->where('is_read', false)
                    ->where(function ($q) {
                        $q->whereIn('status_rt_pengajuan_lain', ['disetujui', 'ditolak'])
                        ->orWhereIn('status_rw_pengajuan_lain', ['disetujui', 'ditolak']);
                    })
                    ->count()
                + HasilSuratTtdRw::where(function ($query) use ($warga) {
                    $query->whereHas('pengajuanSurat', function ($q) use ($warga) {
                        $q->where('warga_id', $warga->id_warga);
                    })
                    ->orWhereHas('pengajuanSuratLain', function ($q) use ($warga) {
                        $q->where('warga_id', $warga->id_warga);
                    });
                })->where('is_read', false)->count(); // Sudah final, tidak perlu filter status

                $view->with([
                    'notifikasi' => $notifikasi,
                    'notifikasiBaru' => $notifikasiBaru,
                    'totalNotifBaru' => $totalNotifBaru,
                ]);
            }
        });

        View::composer(['rw.mainRw', 'rw.dashboardRw'], function ($view) {
            $profile_rw = Auth::guard('rw')->user()->profile_rw ?? null;
            // Ambil pengajuan biasa yang sudah ada hasilnya
            $pengajuanSuratDisetujuiIds = HasilSuratTtdRw::where('jenis', 'biasa')
                ->whereNotNull('pengajuan_surat_id')
                ->pluck('pengajuan_surat_id')
                ->toArray();

            // Ambil pengajuan lain yang sudah ada hasilnya
            $pengajuanSuratLainDisetujuiIds = HasilSuratTtdRw::where('jenis', 'lain')
                ->whereNotNull('pengajuan_surat_lain_id')
                ->pluck('pengajuan_surat_lain_id')
                ->toArray();

            $pengajuanSuratBaru = PengajuanSurat::with(['warga.rt'])
                ->where('status_rt', 'disetujui')
                ->where(function($query) {
                    $query->whereNull('status_rw')
                        ->orWhere('status_rw', '!=', 'ditolak');
                })
                ->whereNotIn('id_pengajuan_surat', $pengajuanSuratDisetujuiIds)
                ->orderBy('updated_at', 'desc')
                ->take(5)
                ->get();

            $pengajuanSuratLainBaru = PengajuanSuratLain::with(['warga.rt'])
                ->where('status_rt_pengajuan_lain', 'disetujui')
                ->where(function($query) {
                    $query->whereNull('status_rw_pengajuan_lain')
                        ->orWhere('status_rw_pengajuan_lain', '!=', 'ditolak');
                })
                ->whereNotIn('id_pengajuan_surat_lain', $pengajuanSuratLainDisetujuiIds)
                ->orderBy('updated_at', 'desc')
                ->take(5)
                ->get();

            $totalNotifRw = $pengajuanSuratBaru->count() + $pengajuanSuratLainBaru->count();

            $view->with(compact('profile_rw', 'totalNotifRw', 'pengajuanSuratBaru', 'pengajuanSuratLainBaru'));
        });
    }
}

<?php

namespace App\Providers;

use App\Models\PengajuanSurat;
use App\Models\PengajuanSuratLain;
use App\Models\ScanKK;
use App\Services\OCRService;
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
    }
}

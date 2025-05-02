<?php

namespace App\Providers;

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
            $view->with('pendingCount', $pendingCount);
        });
    }
}

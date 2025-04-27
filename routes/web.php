<?php

use App\Http\Controllers\Warga\RiwayatSuratController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OTPController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DaftarController;
use App\Http\Controllers\UploadKKController;
use App\Http\Controllers\BuatPasswordController;
use App\Http\Controllers\Warga\DashboardController;
use App\Http\Controllers\Warga\FormSuratController;
use App\Http\Controllers\Surat\TemplateSuratController;
use App\Http\Controllers\Warga\PengajuanSuratController;

Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login-post', [LoginController::class, 'login'])->name('login-post');
Route::get('/daftar', [DaftarController::class, 'index'])->name('daftar');
Route::post('/daftar', [DaftarController::class, 'store']);
Route::get('/otp', [OTPController::class, 'index'])->name('otp');
Route::get('/buatPassword', [BuatPasswordController::class, 'index'])->name('buatPassword');
Route::get('/uploadKK', [UploadKKController::class, 'index'])->name('uploadKK');
Route::post('/uploadKK', [UploadKKController::class, 'store'])->name('uploadKK.store');


Route::get('/dashboardWarga', [DashboardController::class, 'index'])->name('dashboardWarga');
Route::get('/pengajuanSuratWarga', [PengajuanSuratController::class, 'index'])->name('pengajuanSuratWarga');
Route::get('/formSuratWarga', [FormSuratController::class, 'index'])->name('formSuratWarga');
Route::get('/riwayatSuratWarga', [RiwayatSuratController::class, 'index'])->name('riwayatSuratWarga');


Route::get('/suratPengantar', [TemplateSuratController::class, 'index'])->name('suratPengantar');



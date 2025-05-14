<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OTPController;
use App\Http\Middleware\AuthenticateRt;
use App\Http\Middleware\AuthenticateRw;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DaftarController;
use App\Http\Middleware\AuthenticateWarga;
use App\Http\Controllers\UploadKKController;
use App\Http\Controllers\Rt\BankDataController;
use App\Http\Controllers\BuatPasswordController;
use App\Http\Controllers\Rt\ProfileRtController;
use App\Http\Controllers\Rt\TtdDigitalController;
use App\Http\Controllers\Rw\DashboardRwController;
use App\Http\Controllers\Rw\TujuanSuratController;
use App\Http\Controllers\UploadKKManualController;
use App\Http\Controllers\Warga\DashboardController;
use App\Http\Controllers\Warga\FormSuratController;
use App\Http\Controllers\Rt\VerifikasiSuratController;
use App\Http\Controllers\Rw\ManajemenAkunRtController;
use App\Http\Controllers\Warga\HistoriSuratController;
use App\Http\Controllers\Warga\RiwayatSuratController;
use App\Http\Controllers\Rt\HistoriAkunWargaController;
use App\Http\Controllers\Surat\TemplateSuratController;
use App\Http\Controllers\Rt\RiwayatSuratWargaController;
use App\Http\Controllers\Warga\PengajuanSuratController;
use App\Http\Controllers\Rt\VerifikasiAkunWargaController;
use App\Http\Controllers\Rw\ManajemenSuratWargaController;
use App\Http\Controllers\Rt\DashboardController as RtDashboardController;

Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login-post', [LoginController::class, 'login'])->name('login-post');
Route::get('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/daftar', [DaftarController::class, 'index'])->name('daftar');
Route::post('/daftar', [DaftarController::class, 'store']);

Route::get('/otp', [OTPController::class, 'index'])->name('otp');
Route::post('/otp/verifikasi', [OTPController::class, 'verifikasi'])->name('otp.verifikasi');
Route::post('/otp/kirim-ulang', [OTPController::class, 'kirimUlang'])->name('otp.kirimUlang');

Route::get('/buatPassword', [BuatPasswordController::class, 'index'])->name('buatPassword');
Route::post('/buatPassword', [BuatPasswordController::class, 'store'])->name('buatPassword.store');

Route::get('/uploadKK', [UploadKKController::class, 'index'])->name('uploadKK');
Route::get('/uploadKKKonfirm', [UploadKKController::class, 'konfirm'])->name('uploadKKKonfirm');
Route::post('/uploadKKproses', [UploadKKController::class, 'proses'])->name('uploadKKproses');
Route::post('/uploadKKsimpan', [UploadKKController::class, 'simpan'])->name('uploadKKsimpan');

Route::get('/uploadKKManual', [UploadKKManualController::class, 'index'])->name('uploadKKManual');
Route::post('/uploadKKManualSimpan', [UploadKKManualController::class, 'uploadKKSimpan'])->name('uploadKKManualSimpan');

Route::get('/suratPengantar', [TemplateSuratController::class, 'index'])->name('suratPengantar');

Route::prefix('warga')->middleware(AuthenticateWarga::class)->group(function () {
    Route::get('/dashboardWarga', [DashboardController::class, 'index'])->name('dashboardWarga');
    Route::get('/pengajuanSuratWarga', [PengajuanSuratController::class, 'index'])->name('pengajuanSuratWarga');
    Route::get('/formPengajuanSuratWarga', [PengajuanSuratController::class, 'formPengajuanSurat'])->name('formPengajuanSurat');
    Route::post('/formPengajuanSuratWargaStore', [PengajuanSuratController::class, 'formPengajuanSuratStore'])->name('formPengajuanSuratStore');
    Route::get('/formPengajuanSuratLain', [PengajuanSuratController::class, 'formPengajuanSuratLain'])->name('formPengajuanSuratLain');
    Route::post('/formPengajuanSuratLainStore', [PengajuanSuratController::class, 'formPengajuanSuratLainStore'])->name('formPengajuanSuratLainStore');
    Route::get('/formSuratWarga', [FormSuratController::class, 'index'])->name('formSuratWarga');
    Route::get('/riwayatSurat', [RiwayatSuratController::class, 'index'])->name('riwayatSurat');
    Route::get('/historiSuratWarga', [HistoriSuratController::class, 'index'])->name('historiSuratWarga');
});

Route::prefix('rt')->middleware(AuthenticateRt::class)->group(function () {
    Route::get('/dashboardRt', [RtDashboardController::class, 'indexMain'])->name('dashboardRt');
    Route::get('/verifikasiAkunWarga', [VerifikasiAkunWargaController::class, 'index'])->name('verifikasiAkunWarga');
    Route::get('/detailVerifikasiAkunWarga/{id}', [VerifikasiAkunWargaController::class, 'detailVerifikasiAkunWarga'])->name('detailVerifikasiAkunWarga');
    Route::post('/verifikasiAkunWarga/{id}/disetujui', [VerifikasiAkunWargaController::class, 'disetujui'])->name('verifikasiAkunWarga.disetujui');
    Route::post('/verifikasiAkunWarga/{id}/ditolak', [VerifikasiAkunWargaController::class, 'ditolak'])->name('verifikasiAkunWarga.ditolak');
    Route::get('/historiVerifikasiAkunWarga', [HistoriAkunWargaController::class, 'historiVerifikasiAkunWarga'])->name('historiVerifikasiAkunWarga');
    Route::get('/historiAkunKadaluwarsa', [HistoriAkunWargaController::class, 'historiKadaluwarsa'])->name('historiAkunKadaluwarsa');
    Route::get('/verifikasiSurat', [VerifikasiSuratController::class, 'index'])->name('verifikasiSurat');
    Route::post('/verifikasiSuratProses', [VerifikasiSuratController::class, 'proses'])->name('verifikasiSuratProses');
    Route::get('/riwayatSuratWarga', [RiwayatSuratWargaController::class, 'index'])->name('riwayatSuratWarga');
    Route::get('/surat/{jenis}/{id}/lihat', [RiwayatSuratWargaController::class, 'tampilkanSurat'])->name('rt.lihatSurat');
    Route::get('/unduh-surat/{jenis}/{id}', [RiwayatSuratWargaController::class, 'unduhSurat'])->name('rt.unduhSurat');
    Route::get('/bankDataKk', [BankDataController::class, 'index'])->name('bankDataKk');
    Route::get('/scanTtdRt', [TtdDigitalController::class, 'index'])->name('scanTtdRt');
    Route::post('/scanTtdRtUpload', [TtdDigitalController::class, 'store'])->name('scanTtdRtUpload');
    Route::get('/profileRt', [ProfileRtController::class, 'index'])->name('profileRt');
    Route::post('/profileRtUpload', [ProfileRtController::class, 'updateProfileRtImage'])->name('uploadProfileRt');
    Route::put('/profileRtUploadData', [ProfileRtController::class, 'updateData'])->name('updateDataRt');
    // // Surat Biasa
    // Route::get('rt/surat-biasa/cetak/{id}', [SuratRtController::class, 'cetakBiasa'])->name('rt.surat-biasa.cetak');
    // Route::get('rt/surat-biasa/unduh/{id}', [SuratRtController::class, 'unduhBiasa'])->name('rt.surat-biasa.unduh');
    // // Surat Lain
    // Route::get('rt/surat-lain/cetak/{id}', [SuratRtController::class, 'cetakLain'])->name('rt.surat-lain.cetak');
    // Route::get('rt/surat-lain/unduh/{id}', [SuratRtController::class, 'unduhLain'])->name('rt.surat-lain.unduh');

});


Route::prefix('rw')->middleware(AuthenticateRw::class)->group(function () {
    // Route::get('/dashboardRw', [DashboardRwController::class, 'index'])->name('dashboardRw');
    Route::get('/dashboardRw', function () {
        return view('rw.mainRw');
    })->name('dashboardRw');
    Route::get('/manajemenAkunRt', [ManajemenAkunRtController::class, 'index'])->name('manajemenAkunRt');
    Route::post('/manajemenAkunRt/store', [ManajemenAkunRtController::class, 'store'])->name('manajemenAkunRt.store');
    Route::post('/manajemenAkunRt/update/{id}', [ManajemenAkunRtController::class, 'update'])->name('manajemenAkunRt.update');
    Route::delete('/manajemenAkunRt/delete/{id}', [ManajemenAkunRtController::class, 'destroy'])->name('manajemenAkunRt.destroy');
    Route::get('/tujuanSurat', [TujuanSuratController::class, 'index'])->name('tujuanSurat');
    Route::post('/tujuanSurat/store', [TujuanSuratController::class, 'store'])->name('tujuanSurat.store');
    Route::put('/tujuanSurat/update/{id}', [TujuanSuratController::class, 'update'])->name('tujuanSurat.update');
    Route::delete('/tujuanSurat/delete/{id}', [TujuanSuratController::class, 'destroy'])->name('tujuanSurat.destroy');
    Route::get('/manajemenSuratWarga', [ManajemenSuratWargaController::class, 'index'])->name('manajemenSuratWarga');
});



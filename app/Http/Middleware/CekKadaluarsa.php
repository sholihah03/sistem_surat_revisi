<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\ScanKK;
use App\Models\Alamat;
use App\Models\Pendaftaran;
use App\Models\Kadaluwarsa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifikasiDataKadaluwarsa;

class CekKadaluarsa
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

public function handle($request, Closure $next)
{
    try {
        $now = Carbon::now();

        Log::info('CekKadaluarsa dijalankan pada ' . $now);

        $expiredScans = ScanKK::where('status_verifikasi', 'pending')
            ->where('created_at', '<=', $now->subHours(24))
            ->get();

        foreach ($expiredScans as $scan) {
            try {
                DB::transaction(function () use ($scan) {
                    Log::info('Memproses scan ID: ' . $scan->id_scan);

                    $alamat = $scan->alamat;

                    Log::info('Alamat data: ' . json_encode($alamat));

                    Kadaluwarsa::create([
                        'rt_alamat' => $alamat?->rt_alamat,
                        'rw_alamat' => $alamat?->rw_alamat,
                        'nama_kepala_keluarga' => $scan->nama_kepala_keluarga,
                        'path_file_kk' => $scan->path_file_kk,
                        'nama_lengkap' => $scan->nama_pendaftar,
                        'no_kk' => $scan->no_kk_scan,
                        'nik' => $scan->nik_pendaftar,
                        'no_hp' => $scan->no_hp_pendaftar,
                        'email' => $scan->email_pendaftar,
                        'nama_jalan' => $alamat?->nama_jalan,
                        'kelurahan' => $alamat?->kelurahan,
                        'kecamatan' => $alamat?->kecamatan,
                    ]);

                    Log::info('Berhasil simpan ke tb_kadaluwarsa');

                    if ($scan->email_pendaftar) {
                        Mail::to($scan->email_pendaftar)->send(
                            new VerifikasiDataKadaluwarsa($scan->nama_pendaftar)
                        );
                        Log::info('Email kadaluwarsa terkirim ke ' . $scan->email_pendaftar);
                    }

                    if ($alamat) {
                        $alamat->delete();
                        Log::info('Alamat ID ' . $alamat->id_alamat . ' berhasil dihapus');
                    }

                    $scan->delete();
                    Log::info('ScanKK ID ' . $scan->id_scan . ' berhasil dihapus');
                });
            } catch (\Exception $e) {
                Log::error('Gagal memproses scan ID: ' . $scan->id_scan . ' | Error: ' . $e->getMessage());
            }
        }

        if ($expiredScans->count() > 0) {
            Log::info('Kadaluarsa Middleware: ' . $expiredScans->count() . ' data diproses pada ' . now());
        }

    } catch (\Exception $e) {
        Log::error('Gagal auto kadaluarsa utama: ' . $e->getMessage());
    }

    return $next($request);
}
}

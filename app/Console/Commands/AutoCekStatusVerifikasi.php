<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ScanKK;
use App\Models\Wargas;

class AutoCekStatusVerifikasi extends Command
{
    protected $signature = 'autocek:statusverifikasi';
    protected $description = 'Otomatis cek status verifikasi KK dan masukkan ke Wargas setiap 10 detik';

    public function handle()
    {
        $this->info('Mulai cek status verifikasi setiap 10 detik...');

        while (true) {
            $scanKks = ScanKK::where('status_verifikasi', 'disetujui')->get();

            foreach ($scanKks as $scanKk) {
                // Cek apakah sudah ada di Wargas
                $warga = Wargas::where('no_kk', $scanKk->no_kk_scan)->first();

                if (!$warga) {
                    Wargas::create([
                        'no_kk' => $scanKk->no_kk_scan,
                        'scan_kk_id' => $scanKk->id,
                        // Data dummy untuk uji coba (nanti bisa diganti data real yang disimpan di session)
                        'nama_lengkap' => 'Nama Dummy',
                        'email' => 'dummy@email.com',
                        'no_hp' => '0812345678',
                        'rw' => '01',
                        'rt' => '01',
                    ]);

                    $this->info('✅ Data no_kk ' . $scanKk->no_kk_scan . ' berhasil masuk ke Wargas.');
                }
            }

            sleep(10); // Tunggu 10 detik sebelum cek lagi
        }
    }
}

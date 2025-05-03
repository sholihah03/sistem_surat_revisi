<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Alamat;
use App\Models\ScanKK;
use App\Models\Kadaluwarsa;
use App\Models\Pendaftaran;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifikasiAkunKadaluwarsa;

class AutoKadaluwarsa extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:kadaluwarsa';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hapus dan pindahkan data yang tidak diverifikasi dalam 24 jam';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        // Ambil semua scan KK yang masih pending dan lebih dari 24 jam
        $expiredScans = ScanKK::where('status_verifikasi', 'pending')
            ->where('created_at', '<=', $now->subHours(24))
            ->get();

        foreach ($expiredScans as $scan) {
            DB::transaction(function () use ($scan) {
                $pendaftaran = $scan->pendaftaran()->first();
                $alamat = $scan->alamat()->first();

                // Simpan ke tb_kadaluwarsa
                Kadaluwarsa::create([
                    'rt_id' => $pendaftaran?->rt_id,
                    'nama_kepala_keluarga' => $scan->nama_kepala_keluarga,
                    'path_file_kk' => $scan->path_file_kk,
                    'nama_lengkap' => $pendaftaran?->nama_lengkap,
                    'no_kk' => $pendaftaran?->no_kk,
                    'nik' => $pendaftaran?->nik,
                    'no_hp' => $pendaftaran?->no_hp,
                    'email' => $pendaftaran?->email,
                    'rw' => $pendaftaran?->rw,
                    'nama_jalan' => $alamat?->nama_jalan,
                    'kelurahan' => $alamat?->kelurahan,
                    'kecamatan' => $alamat?->kecamatan,
                    'kabupaten_kota' => $alamat?->kabupaten_kota,
                    'provinsi' => $alamat?->provinsi,
                    'kode_pos' => $alamat?->kode_pos,
                ]);

                if ($pendaftaran && $pendaftaran->email) {
                    Mail::to($pendaftaran->email)->send(
                        new VerifikasiAkunKadaluwarsa($pendaftaran->nama_lengkap)
                    );
                }

                // Hapus data dari tb_pendaftaran dan tb_alamat
                if ($pendaftaran) $pendaftaran->delete();
                if ($alamat) $alamat->delete();

                // Hapus dari tb_scan_kk (kecuali no_kk_scan disimpan, maka jangan hapus kolom itu)
                $scan->delete();
            });
        }

        $this->info('Proses kadaluwarsa selesai.');
    }
}

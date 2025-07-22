<?php

namespace App\Http\Controllers\Rt;

use App\Models\Otp;
use App\Models\ScanKK;
use App\Models\Wargas;
use App\Models\Kadaluwarsa;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Mail\VerifikasiAkunDitolak;
use App\Http\Controllers\Controller;
use App\Service\NotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifikasiAkunDisetujui;

class VerifikasiAkunWargaController extends Controller
{
    public function index()
    {
        $profile_rt = Auth::guard('rt')->user()->profile_rt;
        $pendingData = ScanKK::with(['alamat', 'pendaftaran'])
        ->where('status_verifikasi', 'pending')
        ->get();

        $rt = Auth::guard('rt')->user();
        $ttdDigital = $rt->ttd_digital;
        $showModalUploadTtd = empty($ttdDigital);


        return view('rt.verifikasiAkunWarga', compact('pendingData', 'profile_rt', 'showModalUploadTtd','ttdDigital'));
    }

    public function detailVerifikasiAkunWarga($id)
    {
        $profile_rt = Auth::guard('rt')->user()->profile_rt;
        $rt_id = Auth::guard('rt')->user();
        $ttdDigital = $rt_id->ttd_digital;
        $showModalUploadTtd = empty($ttdDigital);

        // Ambil hanya data yang sesuai dengan id
        $item = ScanKK::with(['alamat', 'pendaftaran'])
                    ->where('id_scan', $id)
                    ->where('status_verifikasi', 'pending')
                    ->firstOrFail();

        return view('rt.detailVerifikasiAkunWarga', compact('item', 'profile_rt', 'showModalUploadTtd', 'ttdDigital'));
    }

    public function disetujui($id)
    {
        $scan = ScanKK::findOrFail($id);
        $scan->status_verifikasi = 'disetujui';
        $scan->save();

        // Ambil semua pendaftaran yang sesuai
        $pendaftaranList = Pendaftaran::where('scan_id', $scan->id_scan)->get();


        foreach ($pendaftaranList as $pendaftaran) {
            // Cek duplikat ke tb_wargas agar tidak double insert
            $sudahAda = Wargas::where('nik', $pendaftaran->nik)->orWhere('email', $pendaftaran->email)->first();
            if (!$sudahAda) {
                // Buat data warga
                $warga = Wargas::create([
                    'no_kk' => $pendaftaran->no_kk,
                    'nik' => $pendaftaran->nik,
                    'nama_lengkap' => $pendaftaran->nama_lengkap,
                    'email' => $pendaftaran->email,
                    'no_hp' => $pendaftaran->no_hp,
                    'rw_id' => $pendaftaran->rw_id,
                    'rt_id' => $pendaftaran->rt_id,
                    'scan_kk_id' => $scan->id_scan,
                ]);

                // ✅ Generate OTP
                $otp = random_int(100000, 999999);
                $expiredAt = Carbon::now()->addSeconds(60);

                Otp::create([
                    'warga_id' => $warga->id_warga,
                    'kode_otp' => $otp,
                    'expired_at' => $expiredAt,
                    'jenis_otp' => 'register',
                ]);

                Mail::to($warga->email)->send(new VerifikasiAkunDisetujui($warga->nama_lengkap,
                    $otp,
                    route('otp', ['email' => $warga->email])
                ));

                 // Simpan email ke session
                session(['email_warga' => $warga->email]);
            } else {
                // Jika sudah ada, ambil data warga untuk disimpan ke session
                session(['email_warga' => $sudahAda->email]);
            }

            // Hapus data pendaftaran setelah dipindah
            $pendaftaran->delete();
        }

        return redirect()->route('verifikasiAkunWarga')->with('success', 'Akun berhasil disetujui.');
    }

    public function ditolak(Request $request, $id)
    {
        $scan = ScanKK::findOrFail($id);

        $request->validate([
            'alasan_penolakan' => 'required|string|max:255',
        ]);

        // Kirim notifikasi ke salah satu akun pendaftaran
        $pendaftaranList = $scan->pendaftaran;
        $firstPendaftaran = $pendaftaranList->first();
        if ($firstPendaftaran) {
            Mail::to($firstPendaftaran->email)->send(
                new VerifikasiAkunDitolak(
                    $firstPendaftaran->nama_lengkap,
                    $request->alasan_penolakan,
                    route('login')
                )
            );
        }

        // Update ScanKK
        $scan->update([
            'status_verifikasi' => 'ditolak',
            'alasan_penolakan' => $request->alasan_penolakan,
        ]);

        // Update semua pendaftaran yg terkait
        foreach ($pendaftaranList as $pendaftaran) {
            $pendaftaran->update([
                'status' => 'ditolak',
                'alasan_penolakan' => $request->alasan_penolakan,
            ]);
        }

        return redirect()->route('verifikasiAkunWarga')->with('error', 'Akun telah ditolak.');
    }

}

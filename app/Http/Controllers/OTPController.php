<?php

namespace App\Http\Controllers;

use App\Models\Rt;
use App\Models\Rw;
use App\Models\Otp;
use App\Models\Wargas;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Mail\OtpResetPasswordMail;
use App\Mail\KirimUlangOtpRegister;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifikasiAkunDisetujui;

class OTPController extends Controller
{
    public function index(Request $request)
    {
        $email = $request->query('email');  // Pastikan email dikirim sebagai query parameter
        session(['email_warga' => $email]);

        return view('auth.otp-verifikasi');
    }


    public function verifikasi(Request $request)
    {
        $kodeOtp = implode('', $request->input('otp')); // Gabungkan 6 digit input

        $otpData = Otp::where('kode_otp', $kodeOtp)
            ->where('expired_at', '>=', now())
            ->where('jenis_otp', 'register')
            ->where('is_used', false) // hanya yang belum dipakai
            ->latest()
            ->first();

        if ($otpData) {
            // Tandai OTP sebagai sudah dipakai
            $otpData->is_used = true;
            $otpData->save();

            // Simpan kode OTP ke tb_wargas (opsional, sesuai kebutuhan)
            $warga = Wargas::find($otpData->warga_id);
            if ($warga) {
                $warga->otp_code = $otpData->kode_otp;
                $warga->save();
            }

            // Arahkan ke form buat password
            return redirect()->route('buatPassword', ['id_warga' => $otpData->warga_id]);
        }

        return back()->with('error', 'Kode OTP tidak valid atau sudah kadaluarsa.');
    }

    public function indexReset(Request $request){
        return view('auth.otp-lupa-password');
    }

    public function verifikasiReset(Request $request)
    {
        $kodeOtp = implode('', $request->input('otp'));

        $otpData = Otp::where('kode_otp', $kodeOtp)
            ->where('expired_at', '>=', now())
            ->where('jenis_otp', 'reset_password')
            ->where('is_used', false)
            ->latest()
            ->first();

        if (!$otpData) {
            return back()->with('error', 'Kode OTP tidak valid atau sudah kadaluarsa.');
        }

        // Tandai OTP sudah dipakai
        $otpData->is_used = true;
        $otpData->save();

        // Cek role berdasarkan isi kolom ID yang terisi
        if ($otpData->warga_id) {
            session([
                'reset_id' => $otpData->warga_id,
                'reset_role' => 'warga'
            ]);
        } elseif ($otpData->rt_id) {
            session([
                'reset_id' => $otpData->rt_id,
                'reset_role' => 'rt'
            ]);
        } elseif ($otpData->rw_id) {
            session([
                'reset_id' => $otpData->rw_id,
                'reset_role' => 'rw'
            ]);
        }

        return redirect()->route('buatPasswordBaru');
    }

    public function kirimUlang(Request $request)
    {
        // Ambil email & jenis OTP dari session
        $email = session('email_warga') ?? session('email_reset');
        $tipeOtp = session('otp_jenis') ?? 'register'; // Bisa: register / reset_password

        if (!$email) {
            return response()->json(['message' => 'Email tidak ditemukan di sesi.'], 400);
        }

        // Deteksi role dari email
        $user = null;
        $tipe = null;

        // Cek Warga
        $warga = Wargas::where('email', $email)->first();
        if ($warga) {
            $user = $warga;
            $tipe = 'warga';
        }

        // Cek RT
        if (!$user) {
            $rt = Rt::where('email_rt', $email)->first();
            if ($rt) {
                $user = $rt;
                $tipe = 'rt';
            }
        }

        // Cek RW
        if (!$user) {
            $rw = Rw::where('email_rw', $email)->first();
            if ($rw) {
                $user = $rw;
                $tipe = 'rw';
            }
        }

        if (!$user) {
            return response()->json(['message' => 'Akun tidak ditemukan.'], 404);
        }

        // Generate OTP baru
        $otpBaru = random_int(100000, 999999);
        $expiredAt = now()->addSeconds(60);

        $otpData = [
            'kode_otp' => $otpBaru,
            'expired_at' => $expiredAt,
            'jenis_otp' => $tipeOtp,
        ];

        if ($tipe === 'warga') {
            $otpData['warga_id'] = $user->id_warga;
        } elseif ($tipe === 'rt') {
            $otpData['rt_id'] = $user->id_rt;
        } elseif ($tipe === 'rw') {
            $otpData['rw_id'] = $user->id_rw;
        }

        Otp::create($otpData);

        // Nama dinamis
        $nama = $user->nama_lengkap ?? $user->nama_lengkap_rt ?? $user->nama_lengkap_rw;

        // Pilih link tujuan verifikasi
        $urlVerifikasi = $tipeOtp === 'register'
            ? route('otp', ['email' => $email])
            : route('otp.indexReset');

        // Kirim email berdasarkan jenis OTP
        if ($tipeOtp === 'register') {
            Mail::to($email)->send(new VerifikasiAkunDisetujui(
                $nama,
                $otpBaru,
                $urlVerifikasi
            ));
        } else {
            Mail::to($email)->send(new OtpResetPasswordMail(
                $nama,
                $otpBaru,
                $urlVerifikasi
            ));
        }

        return response()->json(['message' => 'Kode OTP telah dikirim ulang.']);
    }



}

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
        $email = $request->query('email');
        session(['email_warga' => $email]);

        // Ambil OTP terbaru dari user
        $otp = Otp::where('jenis_otp', 'register')
            ->whereHas('warga', function ($query) use ($email) {
                $query->where('email', $email);
            })
            ->latest()
            ->first();

        $otpExpired = false;
        if ($otp && $otp->expired_at < now()) {
            session()->flash('error', 'Kode OTP Anda sudah expired. Silakan klik "Kirim Ulang OTP".');
            $otpExpired = true;
        }

        return view('auth.otp-verifikasi', compact('otp', 'otpExpired'));
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
    // 1. Ambil email dari session
    $email = session('email_warga');

    // 2. Jika email di session kosong, cari dari OTP terakhir jenis "register"
    if (!$email) {
        $otpTerakhir = Otp::where('jenis_otp', 'register')
            ->orderByDesc('created_at')
            ->first();

        if ($otpTerakhir && $otpTerakhir->warga_id) {
            $wargaFromOtp = Wargas::find($otpTerakhir->warga_id);
            if ($wargaFromOtp) {
                $email = $wargaFromOtp->email;
            }
        }
    }

    // 3. Jika masih tidak ketemu email
    if (!$email) {
        return response()->json(['message' => 'Email tidak ditemukan di session maupun dari OTP.'], 400);
    }

    // 4. Ambil warga berdasarkan email
    $warga = Wargas::where('email', $email)->first();
    if (!$warga) {
        return response()->json(['message' => 'Data warga tidak ditemukan.'], 404);
    }

    // 5. Buat OTP baru
    $otpBaru = random_int(100000, 999999);
    $expiredAt = now()->addSeconds(120);

    Otp::create([
        'warga_id' => $warga->id_warga,
        'kode_otp' => $otpBaru,
        'expired_at' => $expiredAt,
        'jenis_otp' => 'register',
        'is_used' => false,
    ]);

    $urlVerifikasi = route('otp', ['email' => $email]);

    // 6. Kirim email OTP
    Mail::to($email)->send(new VerifikasiAkunDisetujui(
        $warga->nama_lengkap,
        $otpBaru,
        $urlVerifikasi
    ));

    return response()->json(['message' => 'Kode OTP telah dikirim ulang.']);
}



}

<?php

namespace App\Http\Controllers;

use App\Models\Otp;
use App\Models\Wargas;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Mail\KirimUlangOtpRegister;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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

    public function kirimUlang(Request $request)
    {
        $email = session('email_warga'); // Ambil email dari session

        if (!$email) {
            return response()->json(['message' => 'Email tidak ditemukan di sesi.'], 400);
        }

        $warga = Wargas::where('email', $email)->first();
        if (!$warga) {
            return response()->json(['message' => 'Warga tidak ditemukan.'], 404);
        }

        // Generate OTP baru
        $otpBaru = random_int(100000, 999999);
        $expiredAt = now()->addSeconds(60);

        // Simpan ke database
        Otp::create([
            'warga_id' => $warga->id_warga,
            'kode_otp' => $otpBaru,
            'expired_at' => $expiredAt,
            'jenis_otp' => 'register',
        ]);

        // Kirim email OTP
        Mail::to($warga->email)->send(new \App\Mail\VerifikasiAkunDisetujui(
            $warga->nama_lengkap,
            $otpBaru,
            route('otp', ['email' => $warga->email])
        ));

        return response()->json(['message' => 'Kode OTP telah dikirim ulang.']);
    }


}

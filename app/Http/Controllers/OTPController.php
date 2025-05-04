<?php

namespace App\Http\Controllers;

use App\Models\Otp;
use App\Models\Wargas;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Mail\KirimUlangOtpRegister;
use Illuminate\Support\Facades\Mail;

class OTPController extends Controller
{
    public function index()
    {
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
        // Ambil warga yang terakhir kali dikirimi OTP
        $lastOtp = Otp::where('jenis_otp', 'register')
                      ->where('is_used', false) // Hanya yang belum dipakai
                      ->latest()
                      ->first();

        if (!$lastOtp) {
            return response()->json(['error' => 'Tidak ditemukan OTP yang dapat dikirim ulang.'], 404);
        }

        $warga = Wargas::find($lastOtp->warga_id);
        if (!$warga) {
            return response()->json(['error' => 'Data warga tidak ditemukan.'], 404);
        }

        // Tandai OTP lama yang belum dipakai sebagai tidak berlaku
        $lastOtp->is_used = true;
        $lastOtp->save();

        // Buat OTP baru
        $newOtp = random_int(100000, 999999);
        Otp::create([
            'warga_id'   => $warga->id_warga,
            'kode_otp'   => $newOtp,
            'expired_at' => Carbon::now()->addSeconds(60),
            'jenis_otp'  => 'register',
            'is_used'    => false, // default
        ]);

        // Kirim ulang email
        Mail::to($warga->email)->send(
            new KirimUlangOtpRegister(
                $warga->nama_lengkap,
                $newOtp,
                route('otp')
            )
        );

        return response()->json(['success' => 'OTP baru telah dikirim.']);
    }

}

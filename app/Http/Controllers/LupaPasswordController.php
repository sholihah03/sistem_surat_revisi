<?php

namespace App\Http\Controllers;

use App\Models\Rt;
use App\Models\Rw;
use App\Models\Otp;
use App\Models\Wargas;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Mail\OtpResetPasswordMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class LupaPasswordController extends Controller
{
    public function index()
    {
        return view('auth.email-lupa-password');
    }

    public function kirimOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $email = $request->email;
        $user = null;
        $tipe = null;

        // Cek di warga
        $warga = Wargas::where('email', $email)->first();
        if ($warga) {
            $user = $warga;
            $tipe = 'warga';
        }

        // Cek di RT
        if (!$user) {
            $rt = Rt::where('email_rt', $email)->first();
            if ($rt) {
                $user = $rt;
                $tipe = 'rt';
            }
        }

        // Cek di RW
        if (!$user) {
            $rw = Rw::where('email_rw', $email)->first();
            if ($rw) {
                $user = $rw;
                $tipe = 'rw';
            }
        }

        // Jika tidak ditemukan
        if (!$user) {
            return back()->with('error', 'Email tidak ditemukan di sistem.');
        }

        // Buat OTP
        $kodeOtp = random_int(100000, 999999);
        $expiredAt = Carbon::now()->addMinutes(5);

        $otpData = [
            'kode_otp' => $kodeOtp,
            'expired_at' => $expiredAt,
            'jenis_otp' => 'reset_password',
        ];

        // Simpan berdasarkan role
        if ($tipe === 'warga') {
            $otpData['warga_id'] = $user->id_warga;
        } elseif ($tipe === 'rt') {
            $otpData['rt_id'] = $user->id_rt;
        } elseif ($tipe === 'rw') {
            $otpData['rw_id'] = $user->id_rw;
        }

        Otp::create($otpData);

        // Kirim OTP via email\
        Mail::to($email)->send(new OtpResetPasswordMail(
            $user->nama_lengkap ?? $user->nama_lengkap_rt ?? $user->nama_lengkap_rw,
            $kodeOtp,
            route('otp.indexReset')
        ));

        // Simpan session role & email
        session([
            'email_reset' => $email,
            'tipe_user' => $tipe,
        ]);

        return view('auth.otp-lupa-password'); // silakan copy view otp-verifikasi dan rename
    }

    public function formPasswordBaru()
    {
        if (!session()->has('reset_id') || !session()->has('reset_role')) {
            return redirect()->route('login')->with('error', 'Session tidak valid.');
        }

        return view('auth.buat-password-baru');
    }


    public function simpanPasswordBaru(Request $request)
{
    $request->validate([
        'password' => 'required|min:6|max:6|confirmed',
    ], [
        'password.required' => 'Password baru wajib diisi.',
        'password.min' => 'Password harus terdiri dari 6 karakter.',
        'password.max' => 'Password maksimal 6 karakter.',
        'password.confirmed' => 'Konfirmasi password tidak sesuai.',
    ]);

    $id = session('reset_id');
    $role = session('reset_role');

    if (!$id || !$role) {
        return redirect()->route('login')->with('error', 'Session tidak ditemukan.');
    }

    // Cek pengguna berdasarkan role
    if ($role === 'warga') {
        $user = Wargas::find($id);
    } elseif ($role === 'rt') {
        $user = Rt::find($id);
    } elseif ($role === 'rw') {
        $user = Rw::find($id);
    } else {
        return redirect()->route('login')->with('error', 'Role tidak valid.');
    }

    if (!$user) {
        return redirect()->route('login')->with('error', 'Pengguna tidak ditemukan.');
    }

    // Simpan password baru (hash)
    $user->password = Hash::make($request->password);
    $user->save();

    // Hapus session
    session()->forget(['reset_id', 'reset_role', 'email_reset', 'otp_jenis']);

    return redirect()->route('login')->with('success_buat_password', 'Password berhasil diubah. Silakan login.');
}
}

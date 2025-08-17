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
    $request->validate([
        'email' => 'required|email',
        'role' => 'required|in:warga,rt,rw',
    ]);

    $email = $request->email;
    $role = $request->role;
    $user = null;

    $errors = [];

    if ($role === 'warga') {
        $emailAda = Wargas::where('email', $email)->first();

        if (!$emailAda) {
            $errors[] = 'Email tidak terdaftar di data warga.';
        } else {
            $user = $emailAda;
        }

    } elseif ($role === 'rt') {
        $request->validate(['no_rt' => 'required']);
        $emailAda = Rt::where('email_rt', $email)->first();
        $rtAda = Rt::where('no_rt', $request->no_rt)->first();

        if (!$emailAda) {
            $errors[] = 'Email tidak terdaftar di data RT.';
        }

        if (!$rtAda) {
            $errors[] = 'No RT tidak ditemukan di data RT.';
        }

        if ($emailAda && $rtAda) {
            $user = Rt::where('email_rt', $email)->where('no_rt', $request->no_rt)->first();
            if (!$user) {
                $errors[] = 'Email dan No RT tidak cocok.';
            }
        }

    } elseif ($role === 'rw') {
        $request->validate(['no_rw' => 'required']);
        $emailAda = Rw::where('email_rw', $email)->first();
        $rwAda = Rw::where('no_rw', $request->no_rw)->first();

        if (!$emailAda) {
            $errors[] = 'Email tidak terdaftar di data RW.';
        }

        if (!$rwAda) {
            $errors[] = 'No RW tidak ditemukan di data RW.';
        }

        if ($emailAda && $rwAda) {
            $user = Rw::where('email_rw', $email)->where('no_rw', $request->no_rw)->first();
            if (!$user) {
                $errors[] = 'Email dan No RW tidak cocok.';
            }
        }
    }

    if (!empty($errors)) {
        return back()->with('error', implode(' ', $errors));
    }

    // Buat dan simpan OTP
    $kodeOtp = random_int(100000, 999999);
    $expiredAt = now()->addMinutes(5);

    $otpData = [
        'kode_otp' => $kodeOtp,
        'expired_at' => $expiredAt,
        'jenis_otp' => 'reset_password',
    ];

    if ($role === 'warga') {
        $otpData['warga_id'] = $user->id_warga;
    } elseif ($role === 'rt') {
        $otpData['rt_id'] = $user->id_rt;
    } elseif ($role === 'rw') {
        $otpData['rw_id'] = $user->id_rw;
    }

    Otp::create($otpData);

    Mail::to($email)->send(new OtpResetPasswordMail(
        $user->nama_lengkap ?? $user->nama_lengkap_rt ?? $user->nama_lengkap_rw,
        $kodeOtp,
        route('otp.indexReset')
    ));

    session([
        'email_reset' => $email,
        'tipe_user' => $role,
    ]);

    return view('auth.otp-lupa-password');
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

    // Ambil user sesuai role
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

    $newPassword = $request->password;

    // Cek apakah password sudah digunakan di tb_wargas
    $usedInWarga = Wargas::all()->contains(function ($w) use ($newPassword) {
        return $w->password && Hash::check($newPassword, $w->password);
    });

    // Cek apakah password sudah digunakan di tb_rt
    $usedInRt = Rt::all()->contains(function ($r) use ($newPassword) {
        return $r->password && Hash::check($newPassword, $r->password);
    });

    // Cek apakah password sudah digunakan di tb_rw
    $usedInRw = Rw::all()->contains(function ($rw) use ($newPassword) {
        return $rw->password && Hash::check($newPassword, $rw->password);
    });

    if ($usedInWarga || $usedInRt || $usedInRw) {
        return back()->withInput()->with('error', 'Password ini sudah pernah digunakan. Silakan gunakan password lain.');
    }

    // Simpan password baru
    $user->password = Hash::make($newPassword);
    $user->save();

    // Hapus session setelah reset
    session()->forget(['reset_id', 'reset_role', 'email_reset', 'otp_jenis']);

    return redirect()->route('login')->with('success_buat_password', 'Password berhasil diubah. Silakan login.');
}


    // public function simpanPasswordBaru(Request $request)
    // {
    //     $request->validate([
    //         'password' => 'required|min:6|max:6|confirmed',
    //     ], [
    //         'password.required' => 'Password baru wajib diisi.',
    //         'password.min' => 'Password harus terdiri dari 6 karakter.',
    //         'password.max' => 'Password maksimal 6 karakter.',
    //         'password.confirmed' => 'Konfirmasi password tidak sesuai.',
    //     ]);

    //     $id = session('reset_id');
    //     $role = session('reset_role');

    //     if (!$id || !$role) {
    //         return redirect()->route('login')->with('error', 'Session tidak ditemukan.');
    //     }

    //     // Cek pengguna berdasarkan role
    //     if ($role === 'warga') {
    //         $user = Wargas::find($id);
    //     } elseif ($role === 'rt') {
    //         $user = Rt::find($id);
    //     } elseif ($role === 'rw') {
    //         $user = Rw::find($id);
    //     } else {
    //         return redirect()->route('login')->with('error', 'Role tidak valid.');
    //     }

    //     if (!$user) {
    //         return redirect()->route('login')->with('error', 'Pengguna tidak ditemukan.');
    //     }

    //     // Simpan password baru (hash)
    //     $user->password = Hash::make($request->password);
    //     $user->save();

    //     // Hapus session
    //     session()->forget(['reset_id', 'reset_role', 'email_reset', 'otp_jenis']);

    //     return redirect()->route('login')->with('success_buat_password', 'Password berhasil diubah. Silakan login.');
    // }
}

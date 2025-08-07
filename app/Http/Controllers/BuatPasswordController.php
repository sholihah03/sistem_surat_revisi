<?php

namespace App\Http\Controllers;

use App\Models\Rt;
use App\Models\Rw;
use App\Models\Otp;
use App\Models\Wargas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class BuatPasswordController extends Controller
{
    public function index(Request $request)
    {
        // Ambil id_warga dari session atau query string
        $id_warga = $request->query('id_warga'); // misalnya dari ?id=123
        return view('auth.buat-password', compact('id_warga'));
    }

public function store(Request $request)
{
    $request->validate([
        'password' => 'required|string|confirmed|min:6|max:6',
        'id_warga' => 'required|exists:tb_wargas,id_warga',
    ], [
        'password.required' => 'Password baru wajib diisi.',
        'password.min' => 'Password harus terdiri dari 6 karakter.',
        'password.max' => 'Password maksimal 6 karakter.',
        'password.confirmed' => 'Konfirmasi password tidak sesuai.',
    ]);

    $warga = Wargas::find($request->id_warga);

    if (!$warga || !$warga->otp_code) {
        return redirect()->back()->with('error', 'Kode OTP belum diverifikasi.');
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
    $warga->password = Hash::make($newPassword);
    $warga->save();

    return redirect()->route('login')->with('success_buat_password', 'Password berhasil dibuat. Silakan login.');
}

}

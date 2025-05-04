<?php

namespace App\Http\Controllers;

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
        ]);

        $warga = Wargas::find($request->id_warga);

        // Cek apakah warga sudah melakukan OTP
        if (!$warga || !$warga->otp_code) {
            return redirect()->back()->with('error', 'Kode OTP belum diverifikasi.');
        }

        // Cek apakah OTP sudah kadaluarsa atau sudah digunakan
        $otpData = Otp::where('kode_otp', $warga->otp_code)
                      ->where('warga_id', $warga->id_warga)
                      ->where('expired_at', '>=', now())
                      ->where('is_used', true) // Pastikan OTP sudah digunakan
                      ->first();

        if (!$otpData) {
            return redirect()->back()->with('error', 'Kode OTP tidak valid atau sudah kadaluarsa.');
        }

        // Simpan password baru
        $warga->password = Hash::make($request->password);
        $warga->save();

        return redirect()->route('login')->with('success_buat_password', 'Password berhasil dibuat. Silakan login.');
    }

}

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

        // Jika kode OTP sudah terisi, lanjutkan untuk menyimpan password
        $warga->password = Hash::make($request->password);
        $warga->save();

        return redirect()->route('login')->with('success_buat_password', 'Password berhasil dibuat. Silakan login.');
    }

}

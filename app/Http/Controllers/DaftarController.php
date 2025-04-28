<?php

namespace App\Http\Controllers;

use App\Models\ScanKK;
use App\Models\Wargas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DaftarController extends Controller
{
    public function index()
    {
        return view('auth.daftar');
    }

    // public function store(Request $request)
    // {
    //     // Validasi data pendaftaran
    //     $validated = $request->validate([
    //         'nama_lengkap' => 'required|string|max:255',
    //         'no_kk' => 'required|numeric',
    //         'no_hp' => 'required|numeric',
    //         'email' => 'required|email',
    //         'rw' => 'required|numeric',
    //         'rt' => 'required|numeric',
    //     ]);

    //     // Cek apakah no_kk sudah ada di tabel Wargas atau ScanKK
    //     $existingWarga = Wargas::where('no_kk', $request->no_kk)->first();
    //     $existingScanKK = ScanKK::where('no_kk_scan', $request->no_kk)->first();

    //     if ($existingWarga || $existingScanKK) {
    //         return redirect()->route('buatPassword');
    //     }

    //     // Jika no_kk belum terdaftar, simpan data ke tb_wargas
    //     $warga = Wargas::create([
    //         'nama_lengkap' => $request->nama_lengkap,
    //         'no_kk' => $request->no_kk,
    //         'no_hp' => $request->no_hp,
    //         'email' => $request->email,
    //         'rw' => $request->rw,
    //         'rt' => $request->rt,
    //     ]);

    //     return redirect()->route('uploadKK');
    // }

    public function store(Request $request)
    {
        // Validasi input yang diterima
        $request->validate([
            'no_kk' => 'required|numeric',
            'nik' => 'required|numeric',
            'nama_lengkap' => 'required',
            'email' => 'required|email|unique:tb_wargas,email',
            'no_hp' => 'required|numeric',
            'rw' => 'required|numeric',
            'rt' => 'required|numeric',
        ]);

        // Mengecek apakah no_kk sudah terdaftar di ScanKK
        $scanKK = ScanKK::where('no_kk_scan', $request->no_kk)->first();

        if ($scanKK) {
            // Jika status_verifikasi belum disetujui, arahkan ke halaman uploadKK
            if ($scanKK->status_verifikasi == 'ditolak') {
                return redirect()->route('uploadKK');
            }

            // Jika status_verifikasi sudah disetujui, arahkan ke halaman otp
            if ($scanKK->status_verifikasi == 'disetujui') {
                return redirect()->route('otp');
            }
        } else {
            // Jika no_kk belum ada di ScanKK, arahkan ke halaman uploadKK
            return redirect()->route('uploadKK');
        }

        // Mengecek apakah email sudah terdaftar di Wargas
        $warga = Wargas::where('email', $request->email)->first();

        if ($warga) {
            // Jika sudah terdaftar, arahkan ke halaman login
            return redirect()->route('login');
        }

            // Mengecek apakah nama_lengkap sudah terdaftar di Wargas
        $wargaByName = Wargas::where('nama_lengkap', $request->nama_lengkap)->first();

        if ($wargaByName) {
            // Jika nama lengkap sudah terdaftar, arahkan ke halaman login
            return redirect()->route('login');
        }

        // Menyimpan data ke tabel Wargas jika semua kondisi terpenuhi
        // Pertama, kita tunggu status verifikasi di ScanKK
        $scanKK = ScanKK::where('no_kk_scan', $request->no_kk)->first();

        if ($scanKK && $scanKK->status_verifikasi == 'disetujui') {
            // Jika status_verifikasi sudah disetujui, simpan data ke tabel Wargas
            $warga = Wargas::create([
                'no_kk' => $request->no_kk,
                'nik' => $request->nik,
                'nama_lengkap' => $request->nama_lengkap,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'rw' => $request->rw,
                'rt' => $request->rt,
                'scan_id' => $scanKK->id, // Mengambil ID dari ScanKK
            ]);

            return redirect()->route('otp'); // Setelah data disimpan, arahkan ke OTP
        }

        // Jika kondisi di atas tidak terpenuhi, arahkan kembali ke uploadKK untuk menunggu verifikasi
        return redirect()->route('uploadKK');
    }




}

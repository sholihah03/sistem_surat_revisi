<?php

namespace App\Http\Controllers;

use App\Models\ScanKK;
use App\Models\Wargas;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DaftarController extends Controller
{
    // public function index()
    // {
    //     return view('auth.daftar');
    // }

    public function index()
    {
        // Mengambil data RT dari tabel tb_rt (misalnya hanya mengambil kolom no_rt)
        $dataRT = DB::table('tb_rt')->select('no_rt')->get();

        return view('auth.daftar', compact('dataRT'));
    }

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
            // Jika no_kk sudah terdaftar, arahkan ke halaman login
            return redirect()->route('otp');
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

        // // Menyimpan data ke tabel Wargas jika semua kondisi terpenuhi
        // // Pertama, kita tunggu status verifikasi di ScanKK
        // $scanKK = ScanKK::where('no_kk_scan', $request->no_kk)->first();

        // if ($scanKK && $scanKK->status_verifikasi == 'disetujui') {
        //     // Jika status_verifikasi sudah disetujui, simpan data ke tabel Wargas
        //     $warga = Wargas::create([
        //         'no_kk' => $request->no_kk,
        //         'nik' => $request->nik,
        //         'nama_lengkap' => $request->nama_lengkap,
        //         'email' => $request->email,
        //         'no_hp' => $request->no_hp,
        //         'rw' => $request->rw,
        //         'rt' => $request->rt,
        //         'scan_id' => $scanKK->id, // Mengambil ID dari ScanKK
        //     ]);

        //     return redirect()->route('otp'); // Setelah data disimpan, arahkan ke OTP
        // }
        // Simpan ke tabel tb_pendaftaran
        Pendaftaran::create([
            // 'scan_id' => $scanKK->id_scan,
            'no_kk' => $request->no_kk,
            'nik' => $request->nik,
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'rw' => $request->rw,
            'rt' => $request->rt,
        ]);

        // Jika kondisi di atas tidak terpenuhi, arahkan kembali ke uploadKK untuk menunggu verifikasi
        return redirect()->route('uploadKK');
    }




}

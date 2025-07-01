<?php

namespace App\Http\Controllers;

use App\Models\Rt;
use App\Models\ScanKK;
use App\Models\Wargas;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotifikasiVerifikasiAkun;
use Illuminate\Support\Facades\Validator;
use App\Mail\KirimOTPYangKartuKeluargaSudahAda;

class DaftarController extends Controller
{
    public function index()
    {
        // Mengambil data RT dari tabel tb_rt (misalnya hanya mengambil kolom no_rt)
        $dataRT = DB::table('tb_rt')->select('id_rt', 'no_rt', 'nama_lengkap_rt')->get();
        $dataRW = DB::table('tb_rw')->select('id_rw', 'no_rw', 'nama_lengkap_rw')->get();

        return view('auth.daftar', compact('dataRT','dataRW'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_kk' => 'required|numeric',
            'nik' => 'required|numeric',
            'nama_lengkap' => 'required',
            'email' => 'required|email|unique:tb_wargas,email',
            'no_hp' => 'required|numeric',
            'rw' => 'required|numeric',
            'rt' => 'required|numeric',
        ]);

        $scanKK = ScanKK::where('no_kk_scan', $request->no_kk)->first();

        // Cek data duplikat di Wargas
        if (Wargas::where('email', $request->email)->exists()) {
            return back()->withErrors(['daftar_error' => 'Email sudah terdaftar'])->withInput();
        }

        if (Wargas::where('nik', $request->nik)->exists()) {
            return back()->withErrors(['daftar_error' => 'NIK sudah terdaftar'])->withInput();
        }

        if (Wargas::where('no_hp', $request->no_hp)->exists()) {
            return back()->withErrors(['daftar_error' => 'Nomor WhatsApp sudah terdaftar'])->withInput();
        }

        if (Wargas::where('nama_lengkap', $request->nama_lengkap)->exists()) {
            return back()->withErrors(['daftar_error' => 'Nama lengkap sudah terdaftar'])->withInput();
        }

        $rt = DB::table('tb_rt')->where('id_rt', $request->rt)->first();
        $rw = DB::table('tb_rw')->where('id_rw', $request->rw)->first();

        // ✅ Jika KK sudah discan → langsung ke tb_wargas
        if ($scanKK) {
            $warga = Wargas::create([
                'scan_kk_id' => $scanKK->id_scan,
                'rt_id' => $rt->id_rt,
                'rw_id' => $rw->id_rw,
                'nama_lengkap' => $request->nama_lengkap,
                'no_kk' => $request->no_kk,
                'nik' => $request->nik,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'status_verifikasi' => false,
            ]);

            // ✅ Simpan OTP untuk warga baru
            $otp = rand(100000, 999999);
            DB::table('tb_otp')->insert([
                'warga_id' => $warga->id_warga,
                'kode_otp' => $otp,
                'expired_at' => now()->addSeconds(60),
                'created_at' => now(),
            ]);

            // ✅ Kirim OTP via email
            Mail::to($warga->email)->send(new KirimOTPYangKartuKeluargaSudahAda($otp, $warga->nama_lengkap));

            return redirect()->route('otp')->with('success', 'Kode OTP telah dikirim ke email Anda.');
        }

        // ✅ Cek NIK duplikat di tb_pendaftaran
        if (Pendaftaran::where('nik', $request->nik)->exists()) {
            return back()->withErrors(['daftar_error' => 'NIK sudah pernah didaftarkan dan sedang menunggu proses verifikasi.'])->withInput();
        }

        // ❌ Jika KK belum discan → simpan ke tb_pendaftaran
        Pendaftaran::create([
            'no_kk' => $request->no_kk,
            'nik' => $request->nik,
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'rw_id' => $rw->id_rw,
            'rt_id' => $rt->id_rt,
        ]);

        return redirect()->route('uploadKK')->with('info', 'Data Anda sedang diverifikasi. Silakan upload Kartu Keluarga.');
    }



}

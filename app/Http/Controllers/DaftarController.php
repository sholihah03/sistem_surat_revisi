<?php

namespace App\Http\Controllers;

use App\Mail\OTPAkun;
use App\Models\Rt;
use App\Models\ScanKK;
use App\Models\Wargas;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
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
        'nama_lengkap' => 'required',
        'email' => 'required|email',
        'no_hp' => 'required|numeric',
    ]);

    if (Wargas::where('nama_lengkap', $request->nama_lengkap)->exists()) {
        return back()->withErrors(['daftar_error' => 'Nama lengkap sudah terdaftar sebagai warga.'])->withInput();
    }

    if (Wargas::where('email', $request->email)->exists()) {
        return back()->withErrors(['daftar_error' => 'Email sudah terdaftar sebagai warga.'])->withInput();
    }

    // Cek jika sudah ada di tb_pendaftaran
    if (Pendaftaran::where('nama_lengkap', $request->nama_lengkap)->exists()) {
        return back()->withErrors(['daftar_error' => 'Nama Lengkap sudah digunakan'])->withInput();
    }

    if (Pendaftaran::where('email', $request->email)->exists()) {
        return back()->withErrors(['daftar_error' => 'Email sudah terdaftar'])->withInput();
    }

    // Cek jika sudah ada di tb_pendaftaran
    $pendaftaranLama = Pendaftaran::where('email', $request->email)->first();

    if ($pendaftaranLama) {
        // Cari OTP terkait pendaftaran lama
        $otpLama = DB::table('tb_otp')
            ->where('pendaftaran_id', $pendaftaranLama->id_pendaftaran)
            ->latest()
            ->first();

        if ($otpLama && Carbon::parse($otpLama->created_at)->diffInSeconds(now()) < 120) {
            return back()->withErrors(['daftar_error' => 'Anda sudah mendaftar. Silakan cek email Anda untuk verifikasi OTP.'])->withInput();
        }

        // Jika OTP expired dan belum diverifikasi, hapus pendaftaran lama
        $pendaftaranLama->delete();
        DB::table('tb_otp')->where('pendaftaran_id', $pendaftaranLama->id_pendaftaran)->delete();
    }

    // Simpan ke tb_pendaftaran
    $pendaftaran = Pendaftaran::create([
        'nama_lengkap' => $request->nama_lengkap,
        'email' => $request->email,
        'no_hp' => $request->no_hp,
        'status' => 'otp_pending',
    ]);

    // Buat OTP dan simpan
    $otp = rand(100000, 999999);

    DB::table('tb_otp')->insert([
        'pendaftaran_id' => $pendaftaran->id_pendaftaran,
        'kode_otp' => $otp,
        'expired_at' => now()->addMinutes(2),
        'created_at' => now(),
        'jenis_otp' => 'register',
        'is_used' => false,
    ]);

    $link = route('otp', ['email' => $request->email]);

    // Kirim OTP via email
    Mail::to($request->email)->send(new OTPAkun(
        $request->nama_lengkap,
        $otp,
        $link
    ));

    // Simpan ID pendaftaran ke sesi
    session(['id_pendaftaran' => $pendaftaran->id_pendaftaran]);

    return redirect()->route('otp')->with('success', 'Kode OTP telah dikirim ke email Anda.');
}


}

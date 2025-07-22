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
            'email' => 'required|email',
            'no_hp' => 'required|numeric',
            'rw' => 'required|numeric',
            'rt' => 'required|numeric',
        ]);

        $rt = DB::table('tb_rt')->where('id_rt', $request->rt)->first();
        $rw = DB::table('tb_rw')->where('id_rw', $request->rw)->first();

        // Ambil data KK dari ScanKK dan data pendaftaran terbaru berdasarkan no KK
        $scanKK = ScanKK::where('no_kk_scan', $request->no_kk)->latest()->first();
        $pendaftaranKK = Pendaftaran::where('no_kk', $request->no_kk)->latest()->first();

        // Ambil waktu terakhir update dari keduanya
        $waktuScanKK = $scanKK ? $scanKK->updated_at : null;
        $waktuPendaftaran = $pendaftaranKK ? $pendaftaranKK->created_at : null;

        // Cek Email Unik di Wargas
        if (Wargas::where('email', $request->email)->exists()) {
            return back()->withErrors(['daftar_error' => 'Email sudah digunakan oleh akun yang aktif.'])->withInput();
        }

        // Cek Email Aktif di Pendaftaran
        $emailAktif = Pendaftaran::where('email', $request->email)
            ->where('status', '!=', 'ditolak')
            ->exists();

        if ($emailAktif) {
            return back()->withErrors(['daftar_error' => 'Email ini sedang digunakan dalam proses pendaftaran lain.'])->withInput();
        }

        // ✅ Cek apakah NIK sudah terdaftar di tb_wargas
        if (Wargas::where('nik', $request->nik)->exists()) {
            return back()->withErrors(['daftar_error' => 'NIK sudah terdaftar'])->withInput();
        }

        // ✅ Cek NIK aktif di pendaftaran (selain yang ditolak)
        $nikAktif = Pendaftaran::where('nik', $request->nik)
            ->where('status', '!=', 'ditolak')
            ->exists();

        if ($nikAktif) {
            return back()->withErrors([
                'daftar_error' => 'NIK ini sedang dalam proses verifikasi sebelumnya.'
            ])->withInput();
        }

        // ✅ Jika KK disetujui dan data scan lebih baru dari pendaftaran → masuk ke tb_wargas + OTP
        if ($scanKK && $scanKK->status_verifikasi === 'disetujui' && (
            !$waktuPendaftaran || $waktuScanKK->gt($waktuPendaftaran)
        )) {
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

            $otp = rand(100000, 999999);
            DB::table('tb_otp')->insert([
                'warga_id' => $warga->id_warga,
                'kode_otp' => $otp,
                'expired_at' => now()->addSeconds(120),
                'created_at' => now(),
            ]);

            Mail::to($warga->email)->send(new KirimOTPYangKartuKeluargaSudahAda($otp, $warga->nama_lengkap));
            return redirect()->route('otp')->with('success', 'Kode OTP telah dikirim ke email Anda.');
        }

        // ✅ Jika pernah ditolak dengan data yang sama
        $pendaftaranDitolak = Pendaftaran::where('nik', $request->nik)
            ->where('status', 'ditolak')
            ->get();

        foreach ($pendaftaranDitolak as $data) {
            $semuaDataSama =
                strtolower($data->no_kk) === strtolower($request->no_kk) &&
                strtolower($data->nama_lengkap) === strtolower($request->nama_lengkap) &&
                strtolower($data->email) === strtolower($request->email) &&
                strtolower($data->no_hp) === strtolower($request->no_hp) &&
                $data->rt_id == $request->rt &&
                $data->rw_id == $request->rw;

            if ($semuaDataSama) {
                return back()->withErrors([
                    'daftar_error' => 'Pendaftaran Anda sebelumnya ditolak dengan data yang sama. Silakan ubah data Anda untuk mendaftar ulang.'
                ])->withInput();
            }
        }

        // ✅ Jika sampai sini berarti KK belum disetujui atau pendaftaran lebih baru → wajib upload ulang
        $pendaftaran = Pendaftaran::create([
            'scan_id' => $scanKK ? $scanKK->id_scan : null,
            'no_kk' => $request->no_kk,
            'nik' => $request->nik,
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'rw_id' => $rw->id_rw,
            'rt_id' => $rt->id_rt,
            'status' => 'pending',
        ]);

        session(['id_pendaftaran' => $pendaftaran->id_pendaftaran]);

        return redirect()->route('uploadKK')->with('info', 'Kartu Keluarga Anda belum disetujui atau masih diproses. Silakan upload ulang untuk verifikasi.');
    }

}

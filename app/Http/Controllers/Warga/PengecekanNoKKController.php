<?php

namespace App\Http\Controllers\Warga;

use App\Models\ScanKK;
use App\Models\Wargas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PengecekanNoKKController extends Controller
{
    public function index()
    {
        return view('warga.inputNoKKdanNIK');
    }

public function cek(Request $request)
{
    $request->validate([
        'no_kk' => 'required|digits:16',
        'nik'   => 'required|digits:16',
    ], [
        'no_kk.required' => 'Nomor KK wajib diisi.',
        'no_kk.digits'   => 'Nomor KK harus terdiri dari 16 digit.',
        'nik.required'   => 'NIK wajib diisi.',
        'nik.digits'     => 'NIK harus terdiri dari 16 digit.',
    ]);

    $noKK = $request->no_kk;
    $nik  = $request->nik;

    // Cek apakah No KK sudah ada di tabel scan KK
    $scanKK = ScanKK::where('no_kk_scan', $noKK)->first();

    if (!$scanKK) {
        // Jika No KK belum ada → arahkan ke halaman upload KK
        return redirect()->route('uploadKK')
            ->with('nik_pendaftar', $nik)
        ->with('info', 'No KK belum ada di sistem. Silakan upload KK terlebih dahulu.');
    }

    // Tambahkan pengecekan status pending di sini
    if ($scanKK->status_verifikasi === 'pending') {
        return redirect()->back()->with('error_pending_kk',
            'Data diri Anda sedang diverifikasi oleh RT. Anda akan mendapatkan informasi melalui email setelah proses selesai.'
        );
    }

    // Ambil user yang sedang login
    /** @var \App\Models\Wargas $user */
    $user = Auth::guard('warga')->user();

    // Cek apakah user sudah pernah mendaftarkan NIK
    if ($user->nik) {
        return redirect()->back()->with('error', 'Anda sudah pernah mendaftarkan NIK.');
    }

    // Ambil warga lain yang punya No KK yang sama dan sudah memiliki rt_id dan rw_id
    $wargaDenganKK = Wargas::where('no_kk', $noKK)
        ->whereNotNull('rt_id')
        ->whereNotNull('rw_id')
        ->first();

    // Simpan data ke user
    $user->nik = $nik;
    $user->scan_kk_id = $scanKK->id_scan;
    $user->no_kk = $noKK;

    // Jika ditemukan warga lain dengan No KK sama, ambil rt_id dan rw_id-nya
    if ($wargaDenganKK) {
        $user->rt_id = $wargaDenganKK->rt_id;
        $user->rw_id = $wargaDenganKK->rw_id;
    }

    $user->save();

    return redirect()->route('dashboardWarga')->with('statusLengkap', 'Data berhasil dilengkapi');
}

}

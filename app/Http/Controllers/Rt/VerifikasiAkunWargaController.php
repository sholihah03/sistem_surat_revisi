<?php

namespace App\Http\Controllers\Rt;

use App\Models\ScanKK;
use App\Models\Wargas;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VerifikasiAkunWargaController extends Controller
{
    public function index(){
        return view('rt.verifikasiAkunWarga');
    }

    public function verifikasiAkunWarga()
    {
        $pendingData = ScanKK::with('alamat')
            ->where('status_verifikasi', 'pending')
            ->get();

        return view('rt.verifikasiAkunWarga', compact('pendingData'));
    }

    public function disetujui($id)
    {
        $scan = ScanKK::findOrFail($id);
        $scan->status_verifikasi = 'disetujui';
        $scan->save();

        // Ambil semua pendaftaran yang sesuai
    $pendaftaranList = Pendaftaran::where('scan_id', $scan->id)->get();

    foreach ($pendaftaranList as $pendaftaran) {
        // Cek duplikat ke tb_wargas agar tidak double insert
        $sudahAda = Wargas::where('nik', $pendaftaran->nik)->orWhere('email', $pendaftaran->email)->first();
        if (!$sudahAda) {
            Wargas::create([
                'no_kk' => $pendaftaran->no_kk,
                'nik' => $pendaftaran->nik,
                'nama_lengkap' => $pendaftaran->nama_lengkap,
                'email' => $pendaftaran->email,
                'no_hp' => $pendaftaran->no_hp,
                'rw' => $pendaftaran->rw,
                'rt' => $pendaftaran->rt,
                'scan_kk_id' => $scan->id_scan,
            ]);
        }

        // Hapus data pendaftaran setelah dipindah
        $pendaftaran->delete();
    }

        return redirect()->back()->with('success', 'Akun berhasil disetujui.');
    }

    public function ditolak(Request $request, $id)
    {
        $scan = ScanKK::findOrFail($id);
        $scan->status_verifikasi = 'ditolak';
        $scan->alasan_penolakan = 'Data tidak valid'; // Bisa pakai input dari form
        $scan->save();

        // Hapus data terkait dari tb_pendaftaran
        $scan->pendaftaran()->delete();

        return redirect()->back()->with('error', 'Akun telah ditolak.');
    }

}

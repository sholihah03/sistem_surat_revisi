<?php

namespace App\Http\Controllers\Rt;

use App\Models\ScanKK;
use App\Models\Wargas;
use App\Models\Kadaluwarsa;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use App\Mail\VerifikasiAkunDitolak;
use App\Http\Controllers\Controller;
use App\Service\NotificationService;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifikasiAkunDisetujui;

class VerifikasiAkunWargaController extends Controller
{
    public function index(){
        $pendingData = ScanKK::with(['alamat', 'pendaftaran'])
        ->where('status_verifikasi', 'pending')
        ->get();


        return view('rt.verifikasiAkunWarga', compact('pendingData'));
    }


    public function detailVerifikasiAkunWarga()
    {
        $pendingData = ScanKK::with(['alamat', 'pendaftaran'])
        ->where('status_verifikasi', 'pending')
        ->get();


        return view('rt.detailVerifikasiAkunWarga', compact('pendingData'));
    }

    public function disetujui($id)
    {
        $scan = ScanKK::findOrFail($id);
        $scan->status_verifikasi = 'disetujui';
        $scan->save();

        // Ambil semua pendaftaran yang sesuai
    // $pendaftaranList = Pendaftaran::where('scan_id', $scan->id)->get();
    $pendaftaranList = Pendaftaran::where('scan_id', $scan->id_scan)->get();


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
                'rw_id' => $pendaftaran->rw_id,
                'rt_id' => $pendaftaran->rt_id,
                'scan_kk_id' => $scan->id_scan,
            ]);
        }
        Mail::to($pendaftaran->email)->send(new VerifikasiAkunDisetujui($pendaftaran->nama_lengkap));

        // Hapus data pendaftaran setelah dipindah
        $pendaftaran->delete();
    }

        return redirect()->route('verifikasiAkunWarga')->with('success', 'Akun berhasil disetujui.');
    }



        public function ditolak(Request $request, $id)
        {
            $scan = ScanKK::findOrFail($id);

            // Validasi alasan penolakan
            $request->validate([
                'alasan_penolakan' => 'required|string|max:255',
            ]);

            $pendaftaran = $scan->pendaftaran->first(); // Ambil 1 orang untuk notifikasi
            if ($pendaftaran) {
                Mail::to($pendaftaran->email)->send(
                    new VerifikasiAkunDitolak($pendaftaran->nama_lengkap, $request->alasan_penolakan)
                );
            }

            $scan->status_verifikasi = 'ditolak';
            $scan->alasan_penolakan = $request->alasan_penolakan; // Ambil alasan dari form
            $scan->save();

            return redirect()->route('verifikasiAkunWarga')->with('error', 'Akun telah ditolak.');
        }


}

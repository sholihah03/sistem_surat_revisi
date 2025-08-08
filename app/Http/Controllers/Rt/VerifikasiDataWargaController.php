<?php

namespace App\Http\Controllers\Rt;

use App\Models\Otp;
use App\Models\ScanKK;
use App\Models\Wargas;
use App\Models\Kadaluwarsa;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Mail\VerifikasiDataDitolak;
use App\Http\Controllers\Controller;
use App\Service\NotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifikasiAkunDisetujui;
use App\Mail\VerifikasiDataDisetujui;

class VerifikasiDataWargaController extends Controller
{
    public function index()
    {
        $profile_rt = Auth::guard('rt')->user()->profile_rt;

        $pendingData = ScanKK::with(['alamat'])
            ->where('status_verifikasi', 'pending')
            ->where('created_at', '>=', now()->subHours(24))
            ->orderBy('created_at', 'desc')
            ->get();

        $rt = Auth::guard('rt')->user();
        $ttdDigital = $rt->ttd_digital;
        $showModalUploadTtd = empty($ttdDigital);


        return view('rt.verifikasiAkunWarga', compact('pendingData', 'profile_rt', 'showModalUploadTtd','ttdDigital'));
    }

    public function detailVerifikasiAkunWarga($id)
    {
        $profile_rt = Auth::guard('rt')->user()->profile_rt;
        $rt_id = Auth::guard('rt')->user();
        $ttdDigital = $rt_id->ttd_digital;
        $showModalUploadTtd = empty($ttdDigital);

        // Ambil hanya data yang sesuai dengan id
        $item = ScanKK::with(['alamat'])
                    ->where('status_verifikasi', 'pending')
                    ->where('id_scan', $id)
                    ->firstOrFail();

        return view('rt.detailVerifikasiAkunWarga', compact('item', 'profile_rt', 'showModalUploadTtd', 'ttdDigital'));
    }
public function disetujui($id)
{
    $scan = ScanKK::with('alamat')->findOrFail($id);

    // Ubah status verifikasi menjadi disetujui
    $scan->status_verifikasi = 'disetujui';
    $scan->save();

    // Coba cari warga berdasarkan nama yang cocok
    $namaPendaftar = $scan->nama_pendaftar ?? $scan->nama_kepala_keluarga;
    $warga = Wargas::where('nama_lengkap', $namaPendaftar)->first();

    if ($warga) {
        $rt_id = null;
        $rw_id = null;

        if ($scan->alamat) {
            $nomor_rt = $scan->alamat->rt_alamat;
            $nomor_rw = $scan->alamat->rw_alamat;

            $rt = DB::table('tb_rt')->where('no_rt', $nomor_rt)->first();
            if ($rt) {
                $rt_id = $rt->id_rt;
            }

            $rw = DB::table('tb_rw')->where('no_rw', $nomor_rw)->first();
            if ($rw) {
                $rw_id = $rw->id_rw;
            }
        }

        // Update data warga
        $warga->update([
            'scan_kk_id' => $scan->id_scan,
            'nik'        => $scan->nik_pendaftar,
            'no_kk'      => $scan->no_kk_scan,
            'email'      => $scan->email_pendaftar ?? $warga->email,
            'no_hp'      => $scan->no_hp_pendaftar ?? $warga->no_hp,
            'rt_id'      => $rt_id,
            'rw_id'      => $rw_id,
        ]);

        // ✅ Kirim email notifikasi
        if ($warga->email) {
            $loginUrl = route('login'); // Ganti dengan route login warga
            Mail::to($warga->email)->send(new VerifikasiDataDisetujui(
                $warga->nama_lengkap,
                $nomor_rt,
                $nomor_rw,
                $loginUrl
            ));
        }
    } else {
        return redirect()->route('verifikasiAkunWarga')
            ->with('error', 'Data warga dengan nama tersebut tidak ditemukan.');
    }

    return redirect()->route('verifikasiAkunWarga')->with('success', 'Akun berhasil disetujui dan email notifikasi telah dikirim.');
}


    public function ditolak(Request $request, $id)
    {
        $scan = ScanKK::findOrFail($id);

    $request->validate([
        'alasan_penolakan' => 'required|string|max:255',
    ]);

    // Jika perlu, bisa kirim email notifikasi ke pendaftar jika ada
    if ($scan->email_pendaftar) {
        Mail::to($scan->email_pendaftar)->send(
            new VerifikasiDataDitolak(
                $scan->nama_pendaftar ?? $scan->nama_kepala_keluarga,
                $request->alasan_penolakan,
                route('login')
            )
        );
    }

    // Update status verifikasi dan alasan
    $scan->update([
        'status_verifikasi' => 'ditolak',
        'alasan_penolakan' => $request->alasan_penolakan,
    ]);

    return redirect()->route('verifikasiAkunWarga')->with('error', 'Akun telah ditolak.');
}

}

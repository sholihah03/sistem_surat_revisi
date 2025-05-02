<?php

namespace App\Http\Controllers\Rt;

use App\Models\ScanKK;
use App\Models\Wargas;
use App\Models\Kadaluwarsa;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Service\NotificationService;

class VerifikasiAkunWargaController extends Controller
{
    public function index(){
        $pendingData = ScanKK::with(['alamat', 'pendaftaran'])
        ->where('status_verifikasi', 'pending')
        ->get();


        return view('rt.verifikasiAkunWarga', compact('pendingData'));
    }

    // public function index()
    // {
    //     $pendingData = ScanKK::with(['alamat', 'pendaftaran'])
    //         ->where('status_verifikasi', 'pending')
    //         ->get();

    //     // Hitung jumlah data pending
    //     $pendingCount = $pendingData->count();

    //     // Kirim notifikasi ke RT jika ada data verifikasi pending
    //     foreach ($pendingData as $data) {
    //         $rt = $data->pendaftaran->first()->rt; // Ambil data RT dari relasi

    //         if ($rt) {
    //             $rtEmail = $rt->email;       // Email RT dari tabel `rt`
    //             $rtNoHp = $rt->no_hp;        // No HP RT dari tabel `rt`
    //             $webLink = route('verifikasiAkunWarga');

    //             // Format nomor HP ke awalan '62'
    //             if (substr($rtNoHp, 0, 1) == '0') {
    //                 $rtNoHp = '62' . substr($rtNoHp, 1);
    //             }

    //             // Kirim email & WA
    //             $subject = "Notifikasi Verifikasi Warga Baru";
    //             $content = "Ada warga baru yang perlu diverifikasi. Klik <a href='{$webLink}'>di sini</a>.";
    //             NotificationService::kirimEmailBrevo($rtEmail, $subject, $content);

    //             $message = "Ada warga baru yang perlu diverifikasi. Klik link ini: {$webLink}";
    //             NotificationService::kirimWAWablas($rtNoHp, $message);
    //         }
    //     }

    //     return view('rt.verifikasiAkunWarga', compact('pendingData', 'pendingCount'));
    // }


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
                'rw' => $pendaftaran->rw,
                'rt' => $pendaftaran->rt,
                'scan_kk_id' => $scan->id_scan,
            ]);
        }

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

            $scan->status_verifikasi = 'ditolak';
            $scan->alasan_penolakan = $request->alasan_penolakan; // Ambil alasan dari form
            $scan->save();

            // Hapus data terkait dari tb_pendaftaran
            // $scan->pendaftaran()->delete();

            return redirect()->route('verifikasiAkunWarga')->with('error', 'Akun telah ditolak.');
        }

        public function historiVerifikasiAkunWarga(Request $request)
        {

            $search = $request->input('search');

            $historiData = ScanKK::with(['alamat', 'wargas', 'pendaftaran'])
                ->whereIn('status_verifikasi', ['disetujui', 'ditolak'])
                ->orderBy('updated_at', 'desc');

            if ($search) {
                $historiData->where(function ($query) use ($search) {
                    $query->whereHas('wargas', function ($q) use ($search) {
                        $q->where('nama_lengkap', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('pendaftaran', function ($q) use ($search) {
                        $q->where('nama_lengkap', 'like', '%' . $search . '%');
                    })
                    ->orWhere('no_kk_scan', 'like', '%' . $search . '%');
                });
            }

            $historiData = $historiData->get();

            return view('rt.historiVerifikasiAkunWarga', compact('historiData'));
        }

        public function historiKadaluwarsa()
        {
            $dataKadaluwarsa = Kadaluwarsa::orderBy('created_at', 'desc')->get();
            return view('rt.historiAkunKadaluwarsa', compact('dataKadaluwarsa'));
        }


}

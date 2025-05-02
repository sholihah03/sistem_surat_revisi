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
    public function index()
    {
        // Mengambil data RT dari tabel tb_rt (misalnya hanya mengambil kolom no_rt)
        $dataRT = DB::table('tb_rt')->select('id_rt', 'no_rt', 'nama_lengkap_rt')->get();

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
        // Menyimpan data pendaftaran dengan id_rt yang sesuai
        $rt = DB::table('tb_rt')->where('no_rt', $request->rt)->first(); // Ambil id_rt berdasarkan no_rt

        Pendaftaran::create([
            'no_kk' => $request->no_kk,
            'nik' => $request->nik,
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'rw' => $request->rw,
            'rt_id' => $request->rt,
        ]);

        // Jika kondisi di atas tidak terpenuhi, arahkan kembali ke uploadKK untuk menunggu verifikasi
        return redirect()->route('uploadKK');
    }




}

// namespace App\Http\Controllers;

// use App\Models\ScanKK;
// use App\Models\Rt;

// use App\Models\Wargas;
// use App\Models\Pendaftaran;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Mail;
// use App\Mail\NotifikasiVerifikasiAkun;

// class DaftarController extends Controller
// {
//     public function index()
//     {
//         $dataRT = DB::table('tb_rt')->select('id_rt', 'no_rt', 'nama_lengkap_rt')->get();
//         return view('auth.daftar', compact('dataRT'));
//     }

//     public function store(Request $request)
//     {
//         // Validasi input
//         $request->validate([
//             'no_kk' => 'required|numeric',
//             'nik' => 'required|numeric',
//             'nama_lengkap' => 'required',
//             'email' => 'required|email|unique:tb_wargas,email',
//             'no_hp' => 'required|numeric',
//             'rw' => 'required|numeric',
//             'rt' => 'required|numeric',
//         ]);

//         // Cek apakah data sudah pernah daftar
//         if (ScanKK::where('no_kk_scan', $request->no_kk)->exists()) {
//             return redirect()->route('otp');
//         }

//         if (Wargas::where('email', $request->email)->exists() ||
//             Wargas::where('nama_lengkap', $request->nama_lengkap)->exists()) {
//             return redirect()->route('login');
//         }

//         // Pastikan no_rt memiliki 3 digit (misal: 001)
//         $request->merge([
//             'rt' => str_pad($request->rt, 3, '0', STR_PAD_LEFT)
//         ]);

//         // Ambil data RT berdasarkan no_rt dan trim spasi
//         $rt = DB::table('tb_rt')
//             ->whereRaw('TRIM(no_rt) = ?', [$request->rt])
//             ->first();

//         if (!$rt || !$rt->email_rt) {
//             // Email RT tidak ditemukan
//             return redirect()->route('error')->with('error', 'Email RT tidak ditemukan.');
//         }

//         // Simpan pendaftaran dengan id_rt (bukan no_rt)
//         $pendaftaran = Pendaftaran::create([
//             'no_kk' => $request->no_kk,
//             'nik' => $request->nik,
//             'nama_lengkap' => $request->nama_lengkap,
//             'email' => $request->email,
//             'no_hp' => $request->no_hp,
//             'rw' => $request->rw,
//             'rt_id' => $rt->id_rt,
//         ]);

//         // Kirim notifikasi ke RT
//         $batasWaktu = now()->addDay()->format('d-m-Y H:i');
//         $link = route('verifikasiAkunWarga');

//         Mail::to($rt->email_rt)->send(new NotifikasiVerifikasiAkun(
//             $pendaftaran->nama_lengkap,
//             $batasWaktu,
//             $link
//         ));

//         return redirect()->route('uploadKK');
//     }

// }


<?php

namespace App\Http\Controllers;

use App\Models\Alamat;
use App\Models\ScanKK;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadKKManualController extends Controller
{
    public function index()
    {
        $filePath = session('failed_kk_path');

        // Jika tidak ada path file, redirect kembali
        if (!$filePath || !Storage::exists($filePath)) {
            return redirect()->route('uploadKK')->with('error', 'Tidak ada file untuk ditampilkan.');
        }

        // Ambil URL untuk ditampilkan di view
        $url = Storage::url($filePath); // Akan menghasilkan URL publik seperti /storage/uploads/kk/...

        return view('auth.upload-kk-manual', ['kkImageUrl' => $url]);
    }

    public function uploadKKSimpan(Request $request)
    {
        // Ambil path dari session
        $fotoKkPath = session('failed_kk_path');

        // Validasi input
        $request->validate([
            'nama_kepala_keluarga' => 'required|string|max:255', // validasi nama kepala keluarga
            'no_kk' => 'required|string|unique:tb_scan_kk,no_kk_scan|max:20', // validasi no KK
            'nama_jalan' => 'required|string|max:255', // validasi alamat
            'provinsi' => 'required|string|max:255',
            'kabupaten_kota' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'desa' => 'required|string|max:255',
            'rt_alamat' => 'required|string|max:5',
            'rw_alamat' => 'required|string|max:5',
            'kode_pos' => 'required|string|max:5',
        ],[
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'max' => ':attribute maksimal :max karakter.',
            'unique' => ':attribute sudah terdaftar.',
        ]);

        // Cek jika path tidak ada (misalnya session expired)
        if (!$fotoKkPath || !Storage::exists($fotoKkPath)) {
            return redirect()->back()->withErrors(['foto_kk' => 'File KK tidak ditemukan di sistem. Silakan upload ulang.']);
        }

        // Simpan data alamat
        $alamat = Alamat::create([
            'nama_jalan' => $request->nama_jalan,
            'rt_alamat' => $request->rt_alamat,
            'rw_alamat' => $request->rw_alamat,
            'kelurahan' => $request->desa,
            'kecamatan' => $request->kecamatan,
            'kabupaten_kota' => $request->kabupaten_kota,
            'provinsi' => $request->provinsi,
            'kode_pos' => $request->kode_pos,
        ]);

        // Simpan data scan KK
        ScanKK::create([
            'alamat_id' => $alamat->id_alamat,
            'nama_kepala_keluarga' => $request->nama_kepala_keluarga,
            'no_kk_scan' => $request->no_kk,
            'path_file_kk' => $fotoKkPath,
            'status_verifikasi' => 'pending', // status awal
            'alasan_penolakan' => null, // null jika belum ditolak
        ]);

        // Hapus path dari session agar tidak digunakan ulang
        session()->forget('failed_kk_path');

        return redirect()->route('login')->with('success_upload_kk', 'Data berhasil disimpan.');
    }

}

<?php

namespace App\Http\Controllers\Rt;

use App\Models\ScanKK;
use Illuminate\Http\Request;
use App\Models\PengajuanSurat;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PengajuanSuratLain;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RiwayatSuratWargaController extends Controller
{
    public function indexx(Request $request){
        return view('rt.templateSurat');
    }

    public function index(Request $request)
    {
        $profile_rt = Auth::guard('rt')->user()->profile_rt;
        $rt_id = Auth::guard('rt')->user()->id_rt;

        // Ambil pengajuan surat biasa, menggunakan relasi warga untuk rt_id
        $pengajuanBiasa = PengajuanSurat::with(['warga', 'tujuanSurat'])
            ->whereHas('warga', function($query) use ($rt_id) {
                $query->where('rt_id', $rt_id); // Pastikan rt_id ada di tabel tb_wargas
            })
            ->whereIn('status', ['disetujui', 'ditolak'])
            ->get();

        // Ambil pengajuan surat lain, menggunakan relasi warga untuk rt_id
        $pengajuanLain = PengajuanSuratLain::with('warga')
            ->whereHas('warga', function($query) use ($rt_id) {
                $query->where('rt_id', $rt_id); // Pastikan rt_id ada di tabel tb_wargas
            })
            ->whereIn('status_pengajuan_lain', ['disetujui', 'ditolak'])
            ->get();

        return view('rt.riwayatSuratWarga', compact('profile_rt', 'pengajuanBiasa', 'pengajuanLain'));
    }

    public function unduhSurat($jenis, $id)
    {
        if ($jenis === 'biasa') {
            $pengajuan = PengajuanSurat::with(['warga', 'tujuanSurat'])->findOrFail($id);
            $status = $pengajuan->status;
        } else {
            $pengajuan = PengajuanSuratLain::with('warga')->findOrFail($id);
            $status = $pengajuan->status_pengajuan_lain;
        }

        if ($status !== 'disetujui') {
            abort(403, 'Surat belum disetujui');
        }

        // Ambil data RT
        $rt = Auth::guard('rt')->user();

        // Ambil path untuk tanda tangan digital bersih
        $ttdPath = Storage::path($rt->ttd_digital_bersih);

        // Encode gambar menjadi base64
        $ttdBase64 = base64_encode(file_get_contents($ttdPath));

        $data = [
            'pengajuan' => $pengajuan,
            'rt' => $rt,
            'rw' => Auth::guard('rw')->user(),
            'ttd' => $ttdBase64, // Kirimkan data base64 gambar
            'jenis' => $jenis,
        ];

        $pdf = PDF::loadView('rt.suratPengantar', $data)->setPaper('a4');
        return $pdf->download('surat-pengantar-'.$pengajuan->warga->nama_lengkap.'.pdf');
    }
}

<?php

namespace App\Http\Controllers\Rt;

use App\Models\ScanKK;
use Illuminate\Http\Request;
use App\Models\PengajuanSurat;
use App\Models\HasilSuratTtdRt;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PengajuanSuratLain;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RiwayatSuratWargaController extends Controller
{
    public function indexx(Request $request){
        return view('rt.suratPengantar');
    }

    public function index(Request $request)
    {
        $profile_rt = Auth::guard('rt')->user()->profile_rt;
        $rt_id = Auth::guard('rt')->user()->id_rt;

        // Ambil pengajuan surat biasa yang sudah selesai (disetujui/ditolak)
        $pengajuanBiasa = PengajuanSurat::with(['warga', 'tujuanSurat'])
            ->whereHas('warga', function($query) use ($rt_id) {
                $query->where('rt_id', $rt_id);
            })
            ->whereIn('status', ['disetujui', 'ditolak'])
            ->get();

        // Ambil pengajuan surat lain yang sudah selesai
        $pengajuanLain = PengajuanSuratLain::with('warga')
            ->whereHas('warga', function($query) use ($rt_id) {
                $query->where('rt_id', $rt_id);
            })
            ->whereIn('status_pengajuan_lain', ['disetujui', 'ditolak'])
            ->get();

        // Ambil semua hasil surat dari tb_hasil_surat_ttd_rt yang ada untuk RT ini
        // Bisa ambil semua hasil surat terkait pengajuan di RT ini, filter by jenis dan pengajuan_id
        $hasilSurat = HasilSuratTtdRt::whereIn('jenis', ['biasa', 'lain'])
            ->whereIn('pengajuan_id', $pengajuanBiasa->pluck('id_pengajuan_surat')->merge($pengajuanLain->pluck('id_pengajuan_surat_lain')))
            ->get()
            ->keyBy(function($item) {
                // kunci berdasarkan jenis + pengajuan_id supaya gampang cari di view
                return $item->jenis.'-'.$item->pengajuan_id;
            });

        return view('rt.riwayatSuratWarga', compact('profile_rt', 'pengajuanBiasa', 'pengajuanLain', 'hasilSurat'));
    }

    public function lihatHasilSurat($id)
    {
        $profile_rt = Auth::guard('rt')->user()->profile_rt;
        $hasilSurat = HasilSuratTtdRt::findOrFail($id);

        // Pastikan file surat ada dan dapat diakses
        if (!Storage::exists($hasilSurat->file_surat)) {
            abort(404, 'File surat tidak ditemukan');
        }

        // Jika file surat PDF, kita bisa tampilkan pakai iframe atau embed
        $fileUrl = Storage::url($hasilSurat->file_surat);

        return view('rt.hasilSurat', compact('hasilSurat', 'fileUrl', 'profile_rt'));
    }

    public function unduhHasilSurat($id)
    {
        $hasilSurat = HasilSuratTtdRt::findOrFail($id);

        $filePath = $hasilSurat->file_surat; // Simpan path file surat di kolom file_surat
        if (!Storage::exists($filePath)) {
            abort(404, "File surat tidak ditemukan");
        }

        return Storage::download($filePath);
    }
}

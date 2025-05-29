<?php

namespace App\Http\Controllers\Warga;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\PengajuanSurat;
use App\Models\HasilSuratTtdRw;
use App\Models\PengajuanSuratLain;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RiwayatSuratController extends Controller
{
    public function index(Request $request)
    {
        $warga = Auth::guard('warga')->user();
        $user = $request->user('warga');

        // Ambil semua surat hasil tanda tangan RW milik user
        $suratSelesai = HasilSuratTtdRw::where(function($query) use ($user) {
            $query->whereHas('pengajuanSurat', function($q) use ($user) {
                $q->where('warga_id', $user->id_warga);
            })
            ->orWhereHas('pengajuanSuratLain', function($q) use ($user) {
                $q->where('warga_id', $user->id_warga);
            });
        })
        ->where('created_at', '>=', Carbon::now()->subMonth()) // hanya yang disetujui dalam 1 bulan terakhir
        ->get();

        // Pengajuan dari tb_pengajuan_surat (biasa)
        $pengajuanBiasa = PengajuanSurat::with('tujuanSurat')
        ->where('warga_id', $user->id_warga)
        ->where(function($query) {
            $query->where('status', 'diproses')
                ->orWhere(function($query2) {
                    $query2->whereIn('status', ['disetujui', 'ditolak'])
                            ->where('updated_at', '>=', now()->subDays(30));
                });
        })
        ->get()
        ->map(function ($item) {
            $item->disetujui_rw = HasilSuratTtdRw::where('jenis', 'biasa')
                ->where('pengajuan_id', $item->id_pengajuan_surat)
                ->exists();
            return $item;
        });

        // Pengajuan dari tb_pengajuan_surat_lain (manual)
        $pengajuanLain = PengajuanSuratLain::where('warga_id', $user->id_warga)
        ->where(function($query) {
            $query->where('status_pengajuan_lain', 'diproses')
                ->orWhere(function($query2) {
                    $query2->whereIn('status_pengajuan_lain', ['disetujui', 'ditolak'])
                            ->where('updated_at', '>=', now()->subDays(30));
                });
        })
        ->get()
        ->map(function ($item) {
            $item->disetujui_rw = HasilSuratTtdRw::where('jenis', 'lain')
                ->where('pengajuan_id', $item->id_pengajuan_surat_lain)
                ->exists();
            return $item;
        });

        return view('warga.riwayatSurat', compact('pengajuanBiasa', 'pengajuanLain', 'suratSelesai', 'warga'));
    }

    public function showPdf($id)
    {
        $warga = Auth::guard('warga')->user();
        // Cari data surat hasil tanda tangan RW berdasarkan id
        $surat = HasilSuratTtdRw::findOrFail($id);

        // Misal file PDF ada di storage path
        $fileUrl = Storage::url($surat->file_surat);

        // Render view khusus untuk menampilkan iframe PDF
        return view('warga.suratPdf', compact('surat', 'fileUrl', 'warga'));
    }

}

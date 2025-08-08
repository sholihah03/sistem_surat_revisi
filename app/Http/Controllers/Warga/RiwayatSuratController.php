<?php

namespace App\Http\Controllers\Warga;

use Carbon\Carbon;
use App\Models\ScanKK;
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

        $scanKK = ScanKK::where('nama_pendaftar', $warga->nama_lengkap)->first();

        $statusKK = null;
        $alasanPenolakan = null;

        if ($scanKK) {
            $statusKK = $scanKK->status_verifikasi;
            $alasanPenolakan = $scanKK->alasan_penolakan;
        }

        $dataBelumLengkap = (empty($warga->no_kk) && empty($warga->nik) && !$scanKK);

        // Hitung notifikasi baru
        $notifikasi = collect(); // ambil dari model notifikasi kamu

        $totalNotifBaru = $notifikasi->where('is_read', false)->count();

        // Tambahkan notifikasi "status disetujui" ke total jika kurang dari 1 hari
        $showStatusDisetujui = false;
        if ($statusKK === 'disetujui' && $scanKK && $scanKK->updated_at->gt(Carbon::now()->subDay())) {
            $showStatusDisetujui = true;
            $totalNotifBaru++;
        }

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
        ->orderBy('created_at', 'desc')
        ->get();

        // Pengajuan dari tb_pengajuan_surat (biasa)
        $pengajuanBiasa = PengajuanSurat::with('tujuanSurat')
        ->where('warga_id', $user->id_warga)
        ->where(function($query) {
            $query->whereNull('status_rt')
                ->orWhere('status_rt', 'menunggu')
                ->orWhere(function($query2) {
                    $query2->whereIn('status_rt', ['disetujui', 'ditolak'])
                            ->where('updated_at', '>=', now()->subDays(30));
                });
        })
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($item) {
            $item->disetujui_rw = HasilSuratTtdRw::where('jenis', 'biasa')
                ->where('pengajuan_surat_id', $item->id_pengajuan_surat)
                ->exists();
            return $item;
        });

        // Pengajuan dari tb_pengajuan_surat_lain (manual)
        $pengajuanLain = PengajuanSuratLain::where('warga_id', $user->id_warga)
        ->where(function($query) {
            $query->whereNull('status_rt_pengajuan_lain')
                ->orWhere('status_rt_pengajuan_lain', 'menunggu')
                ->orWhere(function($query2) {
                    $query2->whereIn('status_rt_pengajuan_lain', ['disetujui', 'ditolak'])
                            ->where('updated_at', '>=', now()->subDays(30));
                });
        })
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($item) {
            $item->disetujui_rw = HasilSuratTtdRw::where('jenis', 'lain')
                ->where('pengajuan_surat_lain_id', $item->id_pengajuan_surat_lain)
                ->exists();
            return $item;
        });


        return view('warga.riwayatSurat', compact('pengajuanBiasa', 'pengajuanLain', 'suratSelesai', 'warga', 'dataBelumLengkap', 'statusKK', 'alasanPenolakan', 'totalNotifBaru', 'showStatusDisetujui'));
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

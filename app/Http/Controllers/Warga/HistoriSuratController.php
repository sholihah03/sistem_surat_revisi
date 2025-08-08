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

class HistoriSuratController extends Controller
{
    public function index()
    {
        $wargaId = Auth::guard('warga')->user()->id_warga;
        $warga = Auth::guard('warga')->user();

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

        // Ambil data pengajuan surat dengan urutan terbaru di atas
        $pengajuanBiasa = PengajuanSurat::where('warga_id', $wargaId)
            ->where(function ($q) {
                $q->whereIn('status_rt', ['disetujui', 'ditolak'])
                ->orWhereIn('status_rw', ['disetujui', 'ditolak']);
            })
            ->orderBy('created_at', 'desc') // <--- tambah orderBy
            ->get();

        $pengajuanLain = PengajuanSuratLain::where('warga_id', $wargaId)
            ->where(function ($q) {
                $q->whereIn('status_rt_pengajuan_lain', ['disetujui', 'ditolak'])
                ->orWhereIn('status_rw_pengajuan_lain', ['disetujui', 'ditolak']);
            })
            ->orderBy('created_at', 'desc') // <--- tambah orderBy
            ->get();

        // Pisahkan berdasarkan status (tidak perlu diubah)
        $disetujuiBiasa = $pengajuanBiasa->filter(function ($item) {
            return $item->status_rt === 'disetujui' || $item->status_rw === 'disetujui';
        });
        $ditolakBiasa = $pengajuanBiasa->filter(function ($item) {
            return $item->status_rt === 'ditolak' || $item->status_rw === 'ditolak';
        });

        $disetujuiLain = $pengajuanLain->filter(function ($item) {
            return $item->status_rt_pengajuan_lain === 'disetujui' || $item->status_rw_pengajuan_lain === 'disetujui';
        });
        $ditolakLain = $pengajuanLain->filter(function ($item) {
            return $item->status_rt_pengajuan_lain === 'ditolak' || $item->status_rw_pengajuan_lain === 'ditolak';
        });

        // Ambil data hasil surat dan urutkan terbaru di atas
        $idPengajuanBiasa = PengajuanSurat::where('warga_id', $wargaId)->pluck('id_pengajuan_surat')->toArray();
        $idPengajuanLain = PengajuanSuratLain::where('warga_id', $wargaId)->pluck('id_pengajuan_surat_lain')->toArray();

        $hasilSurat = HasilSuratTtdRw::where(function ($query) use ($idPengajuanBiasa, $idPengajuanLain) {
            $query->where(function ($q) use ($idPengajuanBiasa) {
                $q->where('jenis', 'biasa')->whereIn('pengajuan_surat_id', $idPengajuanBiasa);
            })->orWhere(function ($q) use ($idPengajuanLain) {
                $q->where('jenis', 'lain')->whereIn('pengajuan_surat_lain_id', $idPengajuanLain);
            });
        })
        ->orderBy('created_at', 'desc') // <--- tambah orderBy untuk hasil surat juga
        ->get();

        return view('warga.historiSurat', compact(
            'disetujuiBiasa', 'ditolakBiasa', 'disetujuiLain', 'ditolakLain',
            'hasilSurat', 'warga', 'totalNotifBaru', 'showStatusDisetujui',
            'dataBelumLengkap', 'statusKK', 'alasanPenolakan', 'scanKK'
        ));
    }

}

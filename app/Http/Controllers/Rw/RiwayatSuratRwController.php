<?php

namespace App\Http\Controllers\Rw;

use App\Models\Wargas;
use Illuminate\Http\Request;
use App\Models\PengajuanSurat;
use App\Models\HasilSuratTtdRw;
use App\Models\PengajuanSuratLain;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RiwayatSuratRwController extends Controller
{
    public function index(Request $request)
    {
        $profile_rw = Auth::guard('rw')->user()->profile_rw;
        $id_rw = Auth::guard('rw')->user()->id_rw;
        $rw = Auth::guard('rw')->user();
        $ttdDigital = $rw->ttd_digital;
        $showModalUploadTtdRw = empty($ttdDigital);
        $keyword = $request->input('search');

        $warga_ids = Wargas::whereHas('rt', function ($query) use ($id_rw) {
            $query->where('rw_id', $id_rw);
        })->pluck('id_warga');

        // 1. Surat yang disetujui (dari hasil surat RW)
        // $hasilSuratDisetujui = HasilSuratTtdRw::with([
        //     'pengajuanSurat.warga', 'pengajuanSuratLain.warga', 'pengajuanSurat.pengajuan.persyaratan'])
        $hasilSuratDisetujui = HasilSuratTtdRw::with([
            'pengajuanSurat.warga',
            'pengajuanSurat.tujuanSurat',
            'pengajuanSurat.pengajuan.persyaratan',
            'pengajuanSuratLain.warga'
        ])
            ->where(function ($query) use ($warga_ids, $keyword) {
                $query->where('jenis', 'biasa')
                    ->whereHas('pengajuanSurat', function ($q) use ($warga_ids, $keyword) {
                        $q->whereIn('warga_id', $warga_ids);
                        if ($keyword) {
                            $q->whereHas('warga', function ($qw) use ($keyword) {
                                $qw->where('nama_lengkap', 'like', '%' . $keyword . '%');
                            })->orWhereHas('tujuanSurat', function ($qt) use ($keyword) {
                                $qt->where('nama_tujuan', 'like', '%' . $keyword . '%');
                            });
                        }
                    });
            })->orWhere(function ($query) use ($warga_ids, $keyword) {
                $query->where('jenis', 'lain')
                    ->whereHas('pengajuanSuratLain', function ($q) use ($warga_ids, $keyword) {
                        $q->whereIn('warga_id', $warga_ids);
                        if ($keyword) {
                            $q->whereHas('warga', function ($qw) use ($keyword) {
                                $qw->where('nama_lengkap', 'like', '%' . $keyword . '%');
                            })->orWhere('tujuan_manual', 'like', '%' . $keyword . '%');
                        }
                    });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // 2. Surat yang ditolak (tidak ada di tb_hasil_surat_ttd_rw)
        $pengajuanDitolakBiasa = PengajuanSurat::with(['warga', 'tujuanSurat', 'pengajuan.persyaratan'])
            ->where('status_rw', 'ditolak')
            ->whereIn('warga_id', $warga_ids)
            ->when($keyword, function ($query) use ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->whereHas('warga', function ($qw) use ($keyword) {
                        $qw->where('nama_lengkap', 'like', '%' . $keyword . '%');
                    })->orWhereHas('tujuanSurat', function ($qt) use ($keyword) {
                        $qt->where('nama_tujuan', 'like', '%' . $keyword . '%');
                    });
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $pengajuanDitolakLain = PengajuanSuratLain::with(['warga', 'pengajuan.persyaratan'])
            ->where('status_rw_pengajuan_lain', 'ditolak')
            ->whereIn('warga_id', $warga_ids)
            ->when($keyword, function ($query) use ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->whereHas('warga', function ($qw) use ($keyword) {
                        $qw->where('nama_lengkap', 'like', '%' . $keyword . '%');
                    })->orWhere('tujuan_manual', 'like', '%' . $keyword . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

//             dd([
//     'DitolakBiasa' => $pengajuanDitolakBiasa->count(),
//     'DitolakLain' => $pengajuanDitolakLain->count()
// ]);

// dd($hasilSuratDisetujui->toArray());



        return view('rw.riwayatSurat', compact(
            'hasilSuratDisetujui',
            'pengajuanDitolakBiasa',
            'pengajuanDitolakLain',
            'profile_rw',
            'ttdDigital',
            'showModalUploadTtdRw'
        ));
    }


    public function lihatHasilSuratRw($id)
    {
        $rw = Auth::guard('rw')->user();
        $ttdDigital = $rw->ttd_digital;
        $showModalUploadTtdRw = empty($ttdDigital);
        $hasilSuratDisetujui = HasilSuratTtdRw::findOrFail($id);

        if (!Storage::exists($hasilSuratDisetujui->file_surat)) {
            abort(404, 'File surat tidak ditemukan');
        }

        $fileUrl = Storage::url($hasilSuratDisetujui->file_surat);

        return view('rw.hasilSuratRW', compact('hasilSuratDisetujui', 'fileUrl', 'ttdDigital', 'showModalUploadTtdRw'));
    }
}

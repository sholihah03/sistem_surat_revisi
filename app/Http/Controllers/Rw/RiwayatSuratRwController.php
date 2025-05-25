<?php

namespace App\Http\Controllers\Rw;

use App\Models\Wargas;
use Illuminate\Http\Request;
use App\Models\HasilSuratTtdRw;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RiwayatSuratRwController extends Controller
{
    public function index(Request $request)
    {
        $profile_rw = Auth::guard('rw')->user()->profile_rw;
        $id_rw = Auth::guard('rw')->user()->id_rw;
        $keyword = $request->input('search');

        // Ambil semua ID warga yang berada di RW ini
        $warga_ids = Wargas::whereHas('rt', function ($query) use ($id_rw) {
            $query->where('rw_id', $id_rw);
        })->pluck('id_warga');

        $hasilSurat = HasilSuratTtdRw::where(function ($query) use ($warga_ids, $keyword) {
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
        ->with(['pengajuanSurat.warga', 'pengajuanSuratLain.warga'])
        ->latest()
        ->get();

        return view('rw.riwayatSurat', compact('hasilSurat', 'profile_rw'));
    }

    public function lihatHasilSuratRw($id)
    {
        $hasilSurat = HasilSuratTtdRw::findOrFail($id);

        if (!Storage::exists($hasilSurat->file_surat)) {
            abort(404, 'File surat tidak ditemukan');
        }

        $fileUrl = Storage::url($hasilSurat->file_surat);

        return view('rw.hasilSuratRW', compact('hasilSurat', 'fileUrl'));
    }
}

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
    public function index()
    {
        $profile_rw = Auth::guard('rw')->user()->profile_rw;
        $id_rw = Auth::guard('rw')->user()->id_rw;

        // Ambil semua ID warga yang berada di RW ini
        $warga_ids = Wargas::whereHas('rt', function ($query) use ($id_rw) {
            $query->where('rw_id', $id_rw);
        })->pluck('id_warga');

        // Ambil semua hasil surat RW yang berkaitan dengan warga tersebut
        $hasilSurat = HasilSuratTtdRw::where(function ($query) use ($warga_ids) {
            $query->where('jenis', 'biasa')
                ->whereHas('pengajuanSurat', function ($q) use ($warga_ids) {
                    $q->whereIn('warga_id', $warga_ids);
                });
        })->orWhere(function ($query) use ($warga_ids) {
            $query->where('jenis', 'lain')
                ->whereHas('pengajuanSuratLain', function ($q) use ($warga_ids) {
                    $q->whereIn('warga_id', $warga_ids);
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

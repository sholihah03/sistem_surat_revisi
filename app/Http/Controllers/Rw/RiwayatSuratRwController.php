<?php

namespace App\Http\Controllers\Rw;

use App\Models\HasilSuratTtdRw;
use App\Models\Wargas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RiwayatSuratRwController extends Controller
{
    public function index()
    {
        $profile_rw = Auth::guard('rw')->user()->profile_rw;
        $id_rw = Auth::guard('rw')->user()->id_rw;

        $warga_ids = Wargas::whereHas('rt', function ($query) use ($id_rw) {
            $query->where('rw_id', $id_rw);
        })->pluck('id_warga');

        // Ambil data hasil surat untuk warga yang berada di RW ini, dengan relasi pengajuan dan warga
        $hasilSurat = HasilSuratTtdRw::whereIn('pengajuan_id', function($query) use ($warga_ids) {
            $query->select('id_hasil_surat_ttd_rw')
                ->from('tb_pengajuan_surat')   // asumsikan nama tabel pengajuanSurat 'pengajuan_surats'
                ->whereIn('warga_id', $warga_ids);
        })
        ->orWhereIn('pengajuan_id', function($query) use ($warga_ids) {
            $query->select('id_hasil_surat_ttd_rw')
                ->from('tb_pengajuan_surat_lain') // nama tabel pengajuanSuratLain
                ->whereIn('warga_id', $warga_ids);
        })
        ->with(['pengajuanSurat.warga', 'pengajuanSuratLain.warga']) // pastikan relasi di model ada
        ->get();

        return view('rw.riwayatSurat', compact('hasilSurat', 'profile_rw'));
    }

}

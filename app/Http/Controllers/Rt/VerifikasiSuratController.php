<?php

namespace App\Http\Controllers\Rt;

use Illuminate\Http\Request;
use App\Models\PengajuanSurat;
use App\Models\PengajuanSuratLain;
use App\Http\Controllers\Controller;

class VerifikasiSuratController extends Controller
{
    public function index()
    {
        $pengajuanSurat = PengajuanSurat::with('warga', 'tujuanSurat')->where('status', 'menunggu')->get();
        $pengajuanSuratLain = PengajuanSuratLain::with('warga')->where('status_pengajuan_lain', 'menunggu')->get();

        return view('rt.verifikasiSuratWarga', compact('pengajuanSurat', 'pengajuanSuratLain'));
    }

    public function proses(Request $request)
    {
        $id = $request->id;
        $jenis = $request->jenis;
        $aksi = $request->aksi;

        if ($jenis == 'biasa') {
            $data = PengajuanSurat::findOrFail($id);
            if ($aksi == 'setuju') {
                $data->status = 'disetujui';
            } else {
                $data->status = 'ditolak';
                $data->alasan_penolakan_pengajuan = $request->alasan_penolakan;
            }
            $data->save();
        } else {
            $data = PengajuanSuratLain::findOrFail($id);
            if ($aksi == 'setuju') {
                $data->status_pengajuan_lain = 'disetujui';
                $data->nomor_surat_pengajuan_lain = $request->nomor_surat;
            } else {
                $data->status_pengajuan_lain = 'ditolak';
                $data->alasan_penolakan_pengajuan_lain = $request->alasan_penolakan;
            }
            $data->save();
        }

        return redirect()->back()->with('success', 'Pengajuan berhasil diproses.');
    }
}

<?php

namespace App\Http\Controllers\Rt;

use App\Models\Rw;
use Illuminate\Http\Request;
use App\Models\PengajuanSurat;
use App\Models\PengajuanSuratLain;
use App\Http\Controllers\Controller;
use App\Mail\NotifikasiVerifikasiRw;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class VerifikasiSuratController extends Controller
{
    public function index()
    {
        $profile_rt = Auth::guard('rt')->user()->profile_rt;
        $pengajuanSurat = PengajuanSurat::with('warga', 'tujuanSurat')->where('status', 'menunggu')->get();
        $pengajuanSuratLain = PengajuanSuratLain::with('warga')->where('status_pengajuan_lain', 'menunggu')->get();

        return view('rt.verifikasiSuratWarga', compact('pengajuanSurat', 'pengajuanSuratLain', 'profile_rt'));
    }

    public function proses(Request $request)
    {
        $id = $request->id;
        $jenis = $request->jenis;
        $aksi = $request->aksi;

        $rt = Auth::guard('rt')->user();
        $rw = Rw::find($rt->rw_id); // pastikan relasi rw_id tersedia

        // if ($jenis == 'biasa') {
        //     $data = PengajuanSurat::findOrFail($id);
        //     if ($aksi == 'setuju') {
        //         $data->status = 'disetujui';
        //     } else {
        //         $data->status = 'ditolak';
        //         $data->alasan_penolakan_pengajuan = $request->alasan_penolakan;
        //     }
        //     $data->save();
        // } else {
        //     $data = PengajuanSuratLain::findOrFail($id);
        //     if ($aksi == 'setuju') {
        //         $data->status_pengajuan_lain = 'disetujui';
        //         $data->nomor_surat_pengajuan_lain = $request->nomor_surat;
        //     } else {
        //         $data->status_pengajuan_lain = 'ditolak';
        //         $data->alasan_penolakan_pengajuan_lain = $request->alasan_penolakan;
        //     }
        //     $data->save();
        // }
        if ($jenis == 'biasa') {
        $data = PengajuanSurat::with('warga', 'tujuanSurat')->findOrFail($id);

        if ($aksi == 'setuju') {
            $data->status = 'disetujui';
            $data->save();

            $jenisSurat = $data->tujuanSurat->nama_tujuan ?? 'Surat Tidak Diketahui';

            Mail::to($rw->email_rw)->send(
                new NotifikasiVerifikasiRw($rw->nama_lengkap_rw, $jenisSurat, $data->warga->nama_lengkap)
            );
        } else {
            $data->status = 'ditolak';
            $data->alasan_penolakan_pengajuan = $request->alasan_penolakan;
            $data->save();
        }

        } else {
            $data = PengajuanSuratLain::with('warga')->findOrFail($id);

            if ($aksi == 'setuju') {
                $data->status_pengajuan_lain = 'disetujui';
                $data->nomor_surat_pengajuan_lain = $request->nomor_surat;
                $data->save();

                $jenisSurat = $data->tujuan_manual ?? 'Surat Lain';

                Mail::to($rw->email_rw)->send(
                    new NotifikasiVerifikasiRw($rw->nama_lengkap_rw, $jenisSurat, $data->warga->nama_lengkap)
                );
            } else {
                $data->status_pengajuan_lain = 'ditolak';
                $data->alasan_penolakan_pengajuan_lain = $request->alasan_penolakan;
                $data->save();
            }
        }

        return redirect()->back()->with('success', 'Pengajuan berhasil diproses.');
    }
}

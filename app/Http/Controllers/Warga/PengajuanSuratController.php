<?php

namespace App\Http\Controllers\Warga;

use App\Models\TujuanSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotifikasiPengajuanSuratKeRT;

class PengajuanSuratController extends Controller
{
    public function index(Request $request)
    {
        $query = TujuanSurat::query();

        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('nama_tujuan', 'like', '%' . $request->search . '%')
                ->orWhere('nomor_surat', 'like', '%' . $request->search . '%');
            });
        }

        $tujuanSurat = $query->get();

        return view('warga.pengajuanSurat', compact('tujuanSurat'));
    }

    public function formPengajuanSurat(Request $request)
    {
        $tujuan = $request->query('tujuan');
        $nomor = $request->query('nomor');
        $warga = Auth::guard('warga')->user(); // asumsinya pakai guard 'warga'

        // Ambil alamat dari relasi melalui ScanKK
        $alamat = $warga->scan_Kk?->alamat;

        return view('warga.formSuratTujuanPopuler', compact('tujuan', 'warga', 'alamat', 'nomor'));
    }
    

    public function formPengajuanSuratStore(Request $request)
    {
        $validated = $request->validate([
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'status_perkawinan' => 'required|in:kawin,belum,janda,duda',
            'agama' => 'required|string|max:255',
            'pekerjaan' => 'required|string|max:255',
            'tujuan_surat_id' => 'required|exists:tb_tujuan_surat,id_tujuan_surat',
            'scan_kk_id' => 'required|exists:tb_scan_kk,id_scan',
        ]);

        DB::table('tb_pengajuan_surat')->insert([
            'warga_id' => auth('warga')->id(),
            'tujuan_surat_id' => $validated['tujuan_surat_id'],
            'scan_kk_id' => $validated['scan_kk_id'],
            'status' => 'menunggu',
            'tempat_lahir' => $validated['tempat_lahir'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'agama' => $validated['agama'],
            'pekerjaan' => $validated['pekerjaan'],
            'status_perkawinan' => $validated['status_perkawinan'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $rtEmail = $request->user('warga')->rt->email_rt ?? null;
        if ($rtEmail) {
            Mail::to($rtEmail)->send(new NotifikasiPengajuanSuratKeRT(
                $request->user('warga')->nama_lengkap
            ));
        }

        return redirect()->route('pengajuanSuratWarga')->with('success_form', 'Pengajuan surat berhasil dikirim.');
    }

    public function formPengajuanSuratLain(Request $request)
    {
        $warga = Auth::guard('warga')->user(); // asumsinya pakai guard 'warga'

        // Ambil alamat dari relasi melalui ScanKK
        $alamat = $warga->scan_Kk?->alamat;

        return view('warga.formSuratTujuanLainnya', compact( 'warga', 'alamat'));
    }

    public function formPengajuanSuratLainStore(Request $request)
    {
        $validated = $request->validate([
            'tempat_lahir_pengaju_lain' => 'required|string|max:255',
            'tanggal_lahir_pengaju_lain' => 'required|date',
            'status_perkawinan_pengaju_lain' => 'required|in:kawin,belum,janda,duda',
            'agama_pengaju_lain' => 'required|string|max:255',
            'pekerjaan_pengaju_lain' => 'required|string|max:255',
            'tujuan_manual' => 'required|string|max:255',
            'scan_kk_id' => 'required|exists:tb_scan_kk,id_scan',
        ]);

        DB::table('tb_pengajuan_surat_lain')->insert([
            'warga_id' => auth('warga')->id(),
            'scan_kk_id' => $validated['scan_kk_id'],
            'status_pengajuan_lain' => 'menunggu',
            'tujuan_manual' => $validated['tujuan_manual'],
            'tempat_lahir_pengaju_lain' => $validated['tempat_lahir_pengaju_lain'],
            'tanggal_lahir_pengaju_lain' => $validated['tanggal_lahir_pengaju_lain'],
            'agama_pengaju_lain' => $validated['agama_pengaju_lain'],
            'pekerjaan_pengaju_lain' => $validated['pekerjaan_pengaju_lain'],
            'status_perkawinan_pengaju_lain' => $validated['status_perkawinan_pengaju_lain'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $rtEmail = $request->user('warga')->rt->email_rt ?? null;

        if ($rtEmail) {
            Mail::to($rtEmail)->send(new NotifikasiPengajuanSuratKeRT(
                $request->user('warga')->nama_lengkap
            ));
        }

        return redirect()->route('pengajuanSuratWarga')->with('success_form', 'Pengajuan surat berhasil dikirim.');
    }


}

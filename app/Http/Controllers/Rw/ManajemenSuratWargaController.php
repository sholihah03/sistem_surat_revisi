<?php

namespace App\Http\Controllers\Rw;

use Carbon\Carbon;
use App\Models\Wargas;
use Illuminate\Support\Str;
use App\Mail\SuratDitolakRw;
use Illuminate\Http\Request;
use App\Mail\SuratDisetujuiRw;
use App\Models\PengajuanSurat;
use App\Models\HasilSuratTtdRt;
use App\Models\HasilSuratTtdRw;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PengajuanSuratLain;
use App\Mail\SuratDitolakRwUntukRt;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\SuratDisetujuiRwUntukRt;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ManajemenSuratWargaController extends Controller
{
    public function index()
    {
        $profile_rw = Auth::guard('rw')->user()->profile_rw;
        $idRwLogin = Auth::guard('rw')->user()->id_rw;
        $rw = Auth::guard('rw')->user();
        $ttdDigital = $rw->ttd_digital;
        $showModalUploadTtdRw = empty($ttdDigital);

        $surats = HasilSuratTtdRt::where(function ($query) {
        $query->where('jenis', 'biasa')
                ->whereDoesntHave('hasilSuratTtdRwBiasa');
        })
        ->orWhere(function ($query) {
            $query->where('jenis', 'lain')
                ->whereDoesntHave('hasilSuratTtdRwLain');
        })
        ->where(function ($query) use ($idRwLogin) {
            $query->whereHas('pengajuanSurat', function ($q) use ($idRwLogin) {
                $q->where('status_rw', '!=', 'ditolak') // pengecualian untuk yang ditolak
                ->whereHas('warga.rt', function ($sub) use ($idRwLogin) {
                    $sub->where('rw_id', $idRwLogin);
                });
            })
            ->orWhereHas('pengajuanSuratLain', function ($q) use ($idRwLogin) {
                $q->where('status_rw_pengajuan_lain', '!=', 'ditolak') // pengecualian juga
                ->whereHas('warga.rt', function ($sub) use ($idRwLogin) {
                    $sub->where('rw_id', $idRwLogin);
                });
            });
        })

        ->with([
            'pengajuanSuratLain.warga.rt',
            'pengajuanSuratLain.warga.scan_Kk.alamat',
            'pengajuanSurat.warga.rt',
            'pengajuanSurat.warga.scan_Kk.alamat',
            'pengajuanSurat.pengajuan.persyaratan',
        ])
        ->get();

        return view('rw.manajemenSuratWarga', compact('profile_rw', 'surats', 'ttdDigital', 'showModalUploadTtdRw'));
    }

    public function setujui(Request $request)
    {
        Carbon::setLocale('id');
        $jenis = $request->jenis;
        $pengajuanSuratId = $request->pengajuan_surat_id;
        $pengajuanSuratLainId = $request->pengajuan_surat_lain_id;

        if ($jenis === 'biasa') {
            $suratRt = HasilSuratTtdRt::where('pengajuan_surat_id', $pengajuanSuratId)
                ->where('jenis', $jenis)
                ->first();

            $pengajuan = PengajuanSurat::with(['warga.rt.rw', 'warga.scan_KK.alamat', 'tujuanSurat'])
                ->find($pengajuanSuratId);
        } else {
            $suratRt = HasilSuratTtdRt::where('pengajuan_surat_lain_id', $pengajuanSuratLainId)
                ->where('jenis', $jenis)
                ->first();

            $pengajuan = PengajuanSuratLain::with(['warga.rt.rw', 'warga.scan_KK.alamat'])
                ->find($pengajuanSuratLainId);
        }

        if (!$suratRt) {
            return back()->with('error', 'Surat dari RT belum tersedia.');
        }

        if (!$pengajuan) {
            return back()->with('error', 'Data pengajuan tidak ditemukan.');
        }

        // Update status RW pada tabel pengajuan
        if ($jenis === 'biasa') {
            $pengajuan->status_rw = 'disetujui';
            $pengajuan->waktu_persetujuan_rw = now();
        } else {
            $pengajuan->status_rw_pengajuan_lain = 'disetujui';
            $pengajuan->waktu_persetujuan_rw_pengajuan_lain = now();
        }
        $pengajuan->save();

        $rt = $pengajuan->warga->rt;
        $rw = $rt->rw;

        $nomorSurat = ($jenis === 'biasa')
            ? ($pengajuan->tujuanSurat->nomor_surat ?? '-')
            : ($pengajuan->nomor_surat_pengajuan_lain ?? '-');

        $tujuan = ($jenis === 'biasa')
            ? ($pengajuan->tujuanSurat->nama_tujuan ?? '-')
            : ($pengajuan->tujuan_manual ?? '-');

        $nik = $pengajuan->warga->nik;
        $maskedNIK = substr($nik, 0, 6) . '******' . substr($nik, -4);

        $token = Str::random(40);
        $verificationUrl = route('verifikasi.surat', ['token' => $token]);
        $waktuTtdRt = $suratRt->created_at ?? now();

        $qrContent = "SURAT|"
            . "NS:" . $nomorSurat . "|"
            . "TUJUAN:" . strtoupper($tujuan) . "|"
            . "NIK:" . $maskedNIK . "|"
            . "NAMA:" . strtoupper($pengajuan->warga->nama_lengkap) . "|"
            . "TTD RT:" . Carbon::parse($waktuTtdRt)->translatedFormat('d F Y') . "|"
            . "TTD RW:" . Carbon::now()->translatedFormat('d F Y') . "|"
            . "VERIF_URL:" . $verificationUrl;

        $qrPng = QrCode::format('png')->size(300)->generate($qrContent);
        $qrBase64 = 'data:image/png;base64,' . base64_encode($qrPng);

        $ttdRwBase64 = base64_encode(file_get_contents(Storage::path($rw->ttd_digital_bersih)));
        $ttdRtBase64 = base64_encode(file_get_contents(Storage::path($rt->ttd_digital_bersih)));

        $waktuTtd = now();

        // Generate sementara untuk hash
        $tempPdf = Pdf::loadView('rw.suratPengantarRw', [
            'pengajuan' => $pengajuan,
            'rt' => $rt,
            'rw' => $rw,
            'ttd_rt' => $ttdRtBase64,
            'ttd_rw' => $ttdRwBase64,
            'jenis' => $jenis,
            'tanggal_disetujui_rw' => $waktuTtd,
            'qr_code_base64' => $qrBase64,
            'hash_dokumen' => '',
            'waktuTtd' => $waktuTtd,
        ])->setPaper('a4');

        $pdfContent = $tempPdf->output();
        $hashDokumen = hash('sha256', $pdfContent);

        // Final PDF dengan hash aktual
        $finalPdf = Pdf::loadView('rw.suratPengantarRw', [
            'pengajuan' => $pengajuan,
            'rt' => $rt,
            'rw' => $rw,
            'ttd_rt' => $ttdRtBase64,
            'ttd_rw' => $ttdRwBase64,
            'jenis' => $jenis,
            'tanggal_disetujui_rw' => $waktuTtd,
            'qr_code_base64' => $qrBase64,
            'hash_dokumen' => $hashDokumen,
            'waktuTtd' => $waktuTtd,
        ])->setPaper('a4');

        $pdfContentFinal = $finalPdf->output();

        $filename = 'surat-ttd-rtrw-' . $pengajuan->id . '-' . str_replace(' ', '-', strtolower($pengajuan->warga->nama_lengkap)) . '-' . time() . '.pdf';
        $filepath = 'public/hasil_surat/ttd_rw/' . $filename;

        Storage::put($filepath, $pdfContentFinal);

        HasilSuratTtdRw::updateOrCreate(
        [
            'jenis' => $jenis,
            'pengajuan_surat_id' => $jenis === 'biasa' ? $pengajuanSuratId : null,
            'pengajuan_surat_lain_id' => $jenis === 'lain' ? $pengajuanSuratLainId : null,
        ],
        [
            'file_surat' => $filepath,
            'token' => $token,
            'hash_dokumen' => $hashDokumen,
            'waktu_ttd' => $waktuTtd,
        ]
    );

        if (!empty($pengajuan->warga->email)) {
            Mail::to($pengajuan->warga->email)->send(new SuratDisetujuiRw($pengajuan));
        }

        if (!empty($rt->email_rt)) {
            Mail::to($rt->email_rt)->send(new SuratDisetujuiRwUntukRt($pengajuan));
        }

        return back()->with('success', 'Surat berhasil disetujui RW.');
    }

    public function tolak(Request $request)
    {
        $pengajuanSuratId = $request->pengajuan_surat_id;
        $pengajuanSuratLainId = $request->pengajuan_surat_lain_id;
        $jenis = $request->jenis;
        $alasan = $request->alasan_penolakan;

        if ($jenis === 'biasa') {
            $pengajuan = PengajuanSurat::with('warga.rt')->find($pengajuanSuratId);
            if ($pengajuan) {
                $pengajuan->status_rw = 'ditolak';
                $pengajuan->waktu_persetujuan_rw = now();
                $pengajuan->alasan_penolakan_pengajuan = $alasan;
                $pengajuan->save();

                if (!empty($pengajuan->warga->email)) {
                    Mail::to($pengajuan->warga->email)->send(new SuratDitolakRw($pengajuan));
                }

                if (!empty($pengajuan->warga->rt->email_rt)) {
                    Mail::to($pengajuan->warga->rt->email_rt)->send(new SuratDitolakRwUntukRt($pengajuan));
                }
            }
        } else {
            $pengajuan = PengajuanSuratLain::with('warga.rt')->find($pengajuanSuratLainId);
            if ($pengajuan) {
                $pengajuan->status_rw_pengajuan_lain = 'ditolak';
                $pengajuan->waktu_persetujuan_rw_lain = now();
                $pengajuan->alasan_penolakan_pengajuan_lain = $alasan;
                $pengajuan->save();

                if (!empty($pengajuan->warga->email)) {
                    Mail::to($pengajuan->warga->email)->send(new SuratDitolakRw($pengajuan));
                }

                if (!empty($pengajuan->warga->rt->email_rt)) {
                    Mail::to($pengajuan->warga->rt->email_rt)->send(new SuratDitolakRwUntukRt($pengajuan));
                }
            }
        }

        return back()->with('success', 'Pengajuan surat berhasil ditolak.');
    }

    public function verifikasiSurat($token)
    {
        $rw = Auth::guard('rw')->user();
        $ttdDigital = $rw->ttd_digital;
        $showModalUploadTtdRw = empty($ttdDigital);
        // Cari surat berdasarkan token
        $hasilSurat = HasilSuratTtdRw::where('token', $token)->first();

        if (!$hasilSurat) {
            abort(404, 'Surat tidak ditemukan atau token tidak valid.');
        }

        // Ambil data pengajuan berdasarkan jenis
        if ($hasilSurat->jenis === 'biasa') {
        $pengajuan = PengajuanSurat::with([
            'warga.rt.rw',
            'warga.scan_KK.alamat',
            'tujuanSurat'
            ])->where('id_pengajuan_surat', $hasilSurat->pengajuan_surat_id)->first();
        } else {
            $pengajuan = PengajuanSuratLain::with([
                'warga.rt.rw',
                'warga.scan_KK.alamat'
            ])->where('id_pengajuan_surat_lain', $hasilSurat->pengajuan_surat_lain_id)->first();
        }

        if (!$pengajuan) {
        abort(404, 'Data pengajuan tidak ditemukan.');
        }

        $rt = $pengajuan->warga->rt;
        $rw = $rt->rw;

        // Ambil tanda tangan digital RT & RW
        $ttd_rw = base64_encode(Storage::get($rw->ttd_digital_bersih));
        $ttd_rt = base64_encode(Storage::get($rt->ttd_digital_bersih));

        // Tanggal persetujuan RW (gunakan kolom di hasilSurat atau lainnya)
        $tanggal_disetujui_rw = $hasilSurat->created_at;

        // Tampilkan view verifikasi dengan data surat
        return view('rw.verifikasiSurat', compact('hasilSurat', 'pengajuan', 'rt', 'rw', 'ttd_rt', 'ttd_rw', 'tanggal_disetujui_rw', 'showModalUploadTtdRw'));
    }
}

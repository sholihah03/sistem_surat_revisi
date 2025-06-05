<?php

namespace App\Http\Controllers\Rw;

use Carbon\Carbon;
use App\Models\Wargas;
use App\Models\HasilSurat;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\SuratDisetujuiRw;
use App\Models\PengajuanSurat;
use App\Models\HasilSuratTtdRt;
use App\Models\HasilSuratTtdRw;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PengajuanSuratLain;
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

        $surats = HasilSuratTtdRt::whereDoesntHave('hasilSuratTtdRw', function ($query) {
            $query->whereColumn('jenis', 'tb_hasil_surat_ttd_rt.jenis');
        })
        ->where(function ($query) use ($idRwLogin) {
            // Filter pengajuanSurat biasa yang relasi warga->rt->rw_id = $idRwLogin
            $query->whereHas('pengajuanSurat.warga.rt', function ($q) use ($idRwLogin) {
                $q->where('rw_id', $idRwLogin);
            })
            // Atau filter pengajuanSuratLain yang relasi warga->rt->rw_id = $idRwLogin
            ->orWhereHas('pengajuanSuratLain.warga.rt', function ($q) use ($idRwLogin) {
                $q->where('rw_id', $idRwLogin);
            });
        })
        ->with([
            'pengajuanSuratLain.warga.rt',
            'pengajuanSuratLain.warga.scan_Kk.alamat',
            'pengajuanSurat.warga.rt',
            'pengajuanSurat.warga.scan_Kk.alamat'
        ])
        ->get();

        return view('rw.manajemenSuratWarga', compact('profile_rw', 'surats'));
    }


public function setujui(Request $request)
{
    Carbon::setLocale('id');
    $pengajuanId = $request->pengajuan_id;
    $jenis = $request->jenis;

    $suratRt = HasilSuratTtdRt::where('pengajuan_id', $pengajuanId)
                ->where('jenis', $jenis)
                ->first();

    if (!$suratRt) {
        return back()->with('error', 'Surat dari RT belum tersedia.');
    }

    if ($jenis === 'biasa') {
        $pengajuan = PengajuanSurat::with(['warga.rt.rw', 'warga.scan_KK.alamat', 'tujuanSurat'])->find($pengajuanId);
    } else {
        $pengajuan = PengajuanSuratLain::with(['warga.rt.rw', 'warga.scan_KK.alamat'])->find($pengajuanId);
    }

    if (!$pengajuan) {
        return back()->with('error', 'Data pengajuan tidak ditemukan.');
    }

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

    $qrContent = "SURAT|"
        . "NS:" . $nomorSurat . "|"
        . "TUJUAN:" . strtoupper($tujuan) . "|"
        . "NIK:" . $maskedNIK . "|"
        . "NAMA:" . strtoupper($pengajuan->warga->nama_lengkap) . "|"
        . "TGL:" . Carbon::now()->translatedFormat('d F Y') . "|"
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
            'pengajuan_id' => $pengajuanId,
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


    public function verifikasiSurat($token)
    {
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
            ])->where('id_pengajuan_surat', $hasilSurat->pengajuan_id)->first();
        } else {
            $pengajuan = PengajuanSuratLain::with([
                'warga.rt.rw',
                'warga.scan_KK.alamat'
            ])->where('id_pengajuan_surat_lain', $hasilSurat->pengajuan_id)->first();
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
        return view('rw.verifikasiSurat', compact('hasilSurat', 'pengajuan', 'rt', 'rw', 'ttd_rt', 'ttd_rw', 'tanggal_disetujui_rw'));
    }
}

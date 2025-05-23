<?php

namespace App\Http\Controllers\Rw;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Wargas;
use App\Models\HasilSurat;
use Illuminate\Http\Request;
use App\Models\PengajuanSurat;
use App\Models\HasilSuratTtdRt;
use App\Models\HasilSuratTtdRw;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PengajuanSuratLain;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class ManajemenSuratWargaController extends Controller
{
    public function index()
{
    $profile_rw = Auth::guard('rw')->user()->profile_rw;
    $idRwLogin = Auth::guard('rw')->user()->id_rw;

    $surats = HasilSuratTtdRt::whereDoesntHave('hasilSuratTtdRw')
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

        // Ambil data pengajuan berdasarkan jenis
        if ($jenis === 'biasa') {
            $pengajuan = PengajuanSurat::with(['warga.rt.rw', 'warga.scan_KK.alamat'])->find($pengajuanId);
        } else {
            $pengajuan = PengajuanSuratLain::with(['warga.rt.rw', 'warga.scan_KK.alamat'])->find($pengajuanId);
        }

        if (!$pengajuan) {
            return back()->with('error', 'Data pengajuan tidak ditemukan.');
        }

        // Ambil data RT dan RW dari relasi warga
        $rt = $pengajuan->warga->rt;
        $rw = $rt->rw;

        // Contoh ambil data nomor surat tergantung jenis
        $nomorSurat = ($jenis === 'biasa')
            ? ($pengajuan->tujuanSurat->nomor_surat ?? '-')
            : ($pengajuan->nomor_surat_pengajuan_lain ?? '-');

        $namaTujuan = $pengajuan->warga->nama_lengkap;
        $tanggalDisetujui = Carbon::now()->translatedFormat('d F Y');

        // Buat string konten QR code, misal multiline
        $nik = $pengajuan->warga->nik;
        $maskedNIK = substr($nik, 0, 6) . '******' . substr($nik, -4);
        $eol = chr(10); // newline universal

        // Generate token unik
        $token = Str::random(40);

        // URL verifikasi surat dengan token
        $verificationUrl = route('verifikasi.surat', ['token' => $token]);

        $qrContent = "=== SURAT PENGANTAR ===" . $eol . $eol .
            "Nomor: " . $nomorSurat . $eol . $eol .
            "Tanggal: " . Carbon::now()->translatedFormat('d F Y') . $eol . $eol .
            "PEMOHON:" . $eol .
            "Nama: " . $pengajuan->warga->nama_lengkap . $eol . $eol .
            "NIK: " . $maskedNIK . $eol .
            "RT/RW: " . $rt->no_rt . "/" . $rw->no_rw . $eol . $eol .
            "DISAHKAN OLEH:" . $eol . $eol .
            "Ketua RT " . $rt->no_rt . " - " . $rt->nama_lengkap_rt . $eol . $eol .
            "Ketua RW " . $rw->no_rw . " - " . $rw->nama_lengkap_rw . $eol . $eol .
            "Verifikasi surat: " . $verificationUrl;


        $qrPng = QrCode::format('png')->size(300)->generate($qrContent);
        $qrBase64 = 'data:image/png;base64,' . base64_encode($qrPng);


        // === Data untuk PDF ===
        $pdfData = [
            'pengajuan' => $pengajuan,
            'rt' => $rt,
            'rw' => $rw,
            'ttd_rt' => base64_encode(file_get_contents(Storage::path($rt->ttd_digital_bersih))),
            'ttd_rw' => base64_encode(file_get_contents(Storage::path($rw->ttd_digital_bersih))),
            'jenis' => $jenis,
            'tanggal_disetujui_rw' => Carbon::now(),
            'qr_code_base64' => $qrBase64,
        ];

        // === Generate & Simpan PDF ke Storage ===
        // $pdf = Pdf::loadView('rw.suratPengantarRw', $pdfData)->setPaper('a4');

        $filename = 'surat-ttd-rtrw-' . $pengajuan->id . '-' . str_replace(' ', '-', strtolower($pengajuan->warga->nama_lengkap)) . '-' . time() . '.pdf';
        $filepath = 'public/hasil_surat/ttd_rw/' . $filename;
        // Simpan ke tb_hasil_surat_ttd_rw dan dapatkan data hasil surat
        $hasilSurat = HasilSuratTtdRw::updateOrCreate(
            [
                'jenis' => $jenis,
                'pengajuan_id' => $pengajuanId,
            ],
            [
                'file_surat' => $filepath,
                'token' => $token,
            ]
        );

        // Masukkan hasil surat ke data view PDF
        $pdfData['hasilSurat'] = $hasilSurat;

        // Generate PDF dan simpan
        $pdf = Pdf::loadView('rw.suratPengantarRw', $pdfData)->setPaper('a4');
        Storage::put($filepath, $pdf->output());

        return back()->with('success', 'Surat berhasil disetujui RW.');

    }

    public function verifikasiSurat($token)
    {
        // Cari surat berdasarkan token
        $hasilSurat = HasilSuratTtdRw::where('token', $token)->first();

        if (!$hasilSurat) {
            abort(404, 'Surat tidak ditemukan atau token tidak valid.');
        }

        // Ambil data pengajuan dan relasi yg diperlukan
        $pengajuan = null;
        if ($hasilSurat->jenis === 'biasa') {
            $pengajuan = PengajuanSurat::with(['warga.rt.rw', 'warga.scan_KK.alamat'])->find($hasilSurat->pengajuan_id);
        } else {
            $pengajuan = PengajuanSuratLain::with(['warga.rt.rw', 'warga.scan_KK.alamat'])->find($hasilSurat->pengajuan_id);
        }

        if (!$pengajuan) {
            abort(404, 'Data pengajuan tidak ditemukan.');
        }

        $rt = $pengajuan->warga->rt;
        $rw = $rt->rw;

        // Tampilkan view verifikasi dengan data surat
        return view('rw.verifikasiSurat', compact('hasilSurat', 'pengajuan', 'rt', 'rw'));
    }



}

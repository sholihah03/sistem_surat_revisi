<?php

namespace App\Http\Controllers\Rw;

use Carbon\Carbon;
use App\Models\Wargas;
use Illuminate\Support\Str;
use App\Mail\SuratDitolakRw;
use Illuminate\Http\Request;
use App\Models\LogTtdDigital;
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
        ->orderBy('created_at', 'desc')
        ->get();

        return view('rw.manajemenSuratWarga', compact('profile_rw', 'surats', 'ttdDigital', 'showModalUploadTtdRw'));
    }

public function setujui(Request $request)
{
    Carbon::setLocale('id');

    $jenis = $request->jenis;
    $pengajuanSuratId = $request->pengajuan_surat_id;
    $pengajuanSuratLainId = $request->pengajuan_surat_lain_id;

    // Ambil data surat tanda tangan RT sesuai jenis pengajuan
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

    $waktuTtd = now();
    $ttdRwBase64 = base64_encode(file_get_contents(Storage::path($rw->ttd_digital_bersih)));
    $ttdRtBase64 = base64_encode(file_get_contents(Storage::path($rt->ttd_digital_bersih)));

    // Buat QR sementara kosong
    $qrBase64 = '';
    $hashDokumen = '';

    // Render PDF final (sementara)
    $pdf = Pdf::loadView('rw.suratPengantarRw', [
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

    // Simpan PDF sementara ke storage
    $filename = 'surat-ttd-rtrw-' . $pengajuan->id . '-' . time() . '.pdf';
    $filepath = 'public/hasil_surat/ttd_rw/' . $filename;
    Storage::put($filepath, $pdf->output());

    // Hitung hash dari file yang disimpan
    $hashDokumen = hash('sha256', file_get_contents(Storage::path($filepath)));

    // Generate QR dari hash
    $verificationUrl = route('verifikasi.surat.hash', ['hash' => $hashDokumen]);
    $qrPng = QrCode::format('png')->size(300)->generate($verificationUrl);
    $qrBase64 = 'data:image/png;base64,' . base64_encode($qrPng);

    // Render PDF final dengan QR dan hash
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

    // Overwrite file PDF dengan versi final
    Storage::put($filepath, $finalPdf->output());

    // Simpan data ke DB
    HasilSuratTtdRw::updateOrCreate(
        [
            'jenis' => $jenis,
            'pengajuan_surat_id' => $jenis === 'biasa' ? $pengajuanSuratId : null,
            'pengajuan_surat_lain_id' => $jenis === 'lain' ? $pengajuanSuratLainId : null,
        ],
        [
            'file_surat' => $filepath,
            'hash_dokumen' => $hashDokumen,
            'waktu_ttd' => $waktuTtd,
        ]
    );

    return back()->with('success', 'Surat berhasil disetujui RW');
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

// App\Http\Controllers\Rw\ManajemenSuratWargaController.php

// public function verifikasiSuratHash(Request $request)
// {
//     $hashInput = $request->hash;

//     // Cari di tabel hasil surat RW
//     $hasilSurat = HasilSuratTtdRw::where('hash_dokumen', $hashInput)->first();

//     if (!$hasilSurat) {
//         return view('verifikasiSurat.hasilQrCode', [
//             'status' => 'invalid',
//             'pesan' => 'Hash tidak ditemukan di database. Dokumen tidak valid.'
//         ]);
//     }

//     // Cek file fisik
//     $pdfPath = storage_path('app/' . $hasilSurat->file_surat);
//     if (!file_exists($pdfPath)) {
//         return view('verifikasiSurat.hasilQrCode', [
//             'status' => 'invalid',
//             'pesan' => 'File surat tidak ditemukan di server.'
//         ]);
//     }

//     // Hitung hash dari file saat ini
//     $currentHash = hash('sha256', file_get_contents($pdfPath));

//     if ($currentHash !== $hasilSurat->hash_dokumen) {
//         return view('verifikasiSurat.hasilQrCode', [
//             'status' => 'invalid',
//             'pesan' => 'Dokumen telah diubah. Hash tidak sesuai.'
//         ]);
//     }

//     // Ambil data pengajuan untuk ditampilkan
//     if ($hasilSurat->jenis === 'biasa') {
//         $pengajuan = PengajuanSurat::with(['warga.rt.rw', 'warga.scan_KK.alamat', 'tujuanSurat'])
//             ->find($hasilSurat->pengajuan_surat_id);
//     } else {
//         $pengajuan = PengajuanSuratLain::with(['warga.rt.rw', 'warga.scan_KK.alamat'])
//             ->find($hasilSurat->pengajuan_surat_lain_id);
//     }

//     if (!$pengajuan) {
//         return view('verifikasiSurat.hasilQrCode', [
//             'status' => 'invalid',
//             'pesan' => 'Data pengajuan tidak ditemukan di sistem.'
//         ]);
//     }

//     // Tampilkan hasil verifikasi sukses
//     return view('verifikasiSurat.hasilQrCode', [
//         'status' => 'valid',
//         'pengajuan' => $pengajuan,
//         'hasilSurat' => $hasilSurat
//     ]);
// }

public function verifikasiSuratHash(Request $request)
{
    $hashInput = $request->hash;

    // Cari di tabel hasil surat RW
    $hasilSurat = HasilSuratTtdRw::where('hash_dokumen', $hashInput)->first();

    if (!$hasilSurat) {
        return view('verifikasiSurat.hasilQrCode', [
            'status' => 'invalid',
            'pesan' => 'Hash tidak ditemukan di database. Dokumen tidak valid.'
        ]);
    }

    // Ambil file dari storage
    $pdfPath = Storage::path($hasilSurat->file_surat);
    if (!file_exists($pdfPath)) {
        return view('verifikasiSurat.hasilQrCode', [
            'status' => 'invalid',
            'pesan' => 'File surat tidak ditemukan di server.'
        ]);
    }

    // Hitung hash dari file yang tersimpan
    $currentHash = hash('sha256', file_get_contents($pdfPath));

    if ($currentHash !== $hasilSurat->hash_dokumen) {
        return view('verifikasiSurat.hasilQrCode', [
            'status' => 'invalid',
            'pesan' => 'Dokumen telah diubah. Hash tidak sesuai.'
        ]);
    }

    // Jika valid
    return view('verifikasiSurat.hasilQrCode', [
        'status' => 'valid',
        'hasilSurat' => $hasilSurat
    ]);
}

}

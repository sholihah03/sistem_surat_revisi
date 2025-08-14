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
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ManajemenSuratWargaController extends Controller
{
    // Menampilkan surat yang belum ditandatangani RW
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
                        $q->where('status_rw', '!=', 'ditolak')
                          ->whereHas('warga.rt', function ($sub) use ($idRwLogin) {
                              $sub->where('rw_id', $idRwLogin);
                          });
                    })
                    ->orWhereHas('pengajuanSuratLain', function ($q) use ($idRwLogin) {
                        $q->where('status_rw_pengajuan_lain', '!=', 'ditolak')
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

    // Menyetujui surat oleh RW
public function setujui(Request $request)
{
    Carbon::setLocale('id');

    $jenis = $request->jenis;
    $pengajuanId = $jenis === 'biasa' ? $request->pengajuan_surat_id : $request->pengajuan_surat_lain_id;

    $pengajuan = $jenis === 'biasa'
        ? PengajuanSurat::with(['warga.rt.rw', 'warga.scan_KK.alamat', 'tujuanSurat'])->findOrFail($pengajuanId)
        : PengajuanSuratLain::with(['warga.rt.rw', 'warga.scan_KK.alamat'])->findOrFail($pengajuanId);

    $rt = $pengajuan->warga->rt;
    $rw = $rt->rw;
    $waktuTtd = now();

    $ttdRtBase64 = base64_encode(Storage::get($rt->ttd_digital_bersih));
    $ttdRwBase64 = base64_encode(Storage::get($rw->ttd_digital_bersih));

    // 1️⃣ Render PDF sementara tanpa QR untuk hitung hash final
    $pdfTemp = Pdf::loadView('rw.suratPengantarRw', [
        'pengajuan' => $pengajuan,
        'rt' => $rt,
        'rw' => $rw,
        'ttd_rt' => $ttdRtBase64,
        'ttd_rw' => $ttdRwBase64,
        'jenis' => $jenis,
        'tanggal_disetujui_rw' => $waktuTtd,
        'qr_code_base64' => '', // kosong dulu
        'hash_dokumen' => '',   // kosong dulu
        'waktuTtd' => $waktuTtd,
    ])->setPaper('a4')->output();

    // 2️⃣ Hitung hash final dari PDF tanpa QR
    $hashFinal = hash('sha256', $pdfTemp);

    // 3️⃣ Generate QR code dari hash final
    $verificationUrl = route('verifikasi.surat.hash', ['hash' => $hashFinal]);
    $qrBase64 = base64_encode(QrCode::format('png')->size(150)->generate($verificationUrl));
    $qrDataUri = 'data:image/png;base64,' . $qrBase64;

    // 4️⃣ Render PDF akhir dengan QR dan hash final
    $finalPdf = Pdf::loadView('rw.suratPengantarRw', [
        'pengajuan' => $pengajuan,
        'rt' => $rt,
        'rw' => $rw,
        'ttd_rt' => $ttdRtBase64,
        'ttd_rw' => $ttdRwBase64,
        'jenis' => $jenis,
        'tanggal_disetujui_rw' => $waktuTtd,
        'qr_code_base64' => $qrDataUri,
        'hash_dokumen' => $hashFinal,
        'waktuTtd' => $waktuTtd,
    ])->setPaper('a4')->output();

    // 5️⃣ Simpan PDF
    $filename = 'surat-ttd-rtrw-' . $pengajuan->id . '-' . time() . '.pdf';
    $filepath = 'public/hasil_surat/ttd_rw/' . $filename;
    Storage::put($filepath, $finalPdf);

    // 6️⃣ Update status pengajuan
    if ($jenis === 'biasa') {
        $pengajuan->status_rw = 'disetujui';
        $pengajuan->waktu_persetujuan_rw = $waktuTtd;
    } else {
        $pengajuan->status_rw_pengajuan_lain = 'disetujui';
        $pengajuan->waktu_persetujuan_rw_pengajuan_lain = $waktuTtd;
    }
    $pengajuan->save();

    // 7️⃣ Simpan hasil surat ke DB
    HasilSuratTtdRw::updateOrCreate(
        [
            'jenis' => $jenis,
            'pengajuan_surat_id' => $jenis === 'biasa' ? $pengajuanId : null,
            'pengajuan_surat_lain_id' => $jenis === 'lain' ? $pengajuanId : null,
        ],
        [
            'file_surat' => $filepath,
            'hash_dokumen' => $hashFinal,
            'waktu_ttd' => $waktuTtd,
            'token' => Str::uuid(),
        ]
    );

    return back()->with('success', 'Surat berhasil disetujui RW dan QR code valid.');
}




    // Tampilkan verifikasi surat via token
    public function verifikasiSurat($token)
    {
        $rw = Auth::guard('rw')->user();
        $ttdDigital = $rw->ttd_digital;
        $showModalUploadTtdRw = empty($ttdDigital);

        $hasilSurat = HasilSuratTtdRw::where('token', $token)->firstOrFail();

        if ($hasilSurat->jenis === 'biasa') {
            $pengajuan = PengajuanSurat::with(['warga.rt.rw','warga.scan_KK.alamat','tujuanSurat'])
                ->findOrFail($hasilSurat->pengajuan_surat_id);
        } else {
            $pengajuan = PengajuanSuratLain::with(['warga.rt.rw','warga.scan_KK.alamat'])
                ->findOrFail($hasilSurat->pengajuan_surat_lain_id);
        }

        $rt = $pengajuan->warga->rt;
        $rw = $rt->rw;

        $ttd_rw = base64_encode(Storage::get($rw->ttd_digital_bersih));
        $ttd_rt = base64_encode(Storage::get($rt->ttd_digital_bersih));
        $tanggal_disetujui_rw = $hasilSurat->created_at;

        return view('rw.verifikasiSurat', compact('hasilSurat', 'pengajuan', 'rt', 'rw', 'ttd_rt', 'ttd_rw', 'tanggal_disetujui_rw', 'showModalUploadTtdRw'));
    }

    // Verifikasi surat via hash (QR code)
public function verifikasiSuratHash(Request $request)
{
    $hashInput = $request->hash;

    $hasilSuratRw = HasilSuratTtdRw::where('hash_dokumen', $hashInput)->first();

    if (!$hasilSuratRw) {
        return view('verifikasiSurat.hasilQrCode', [
            'status' => 'invalid',
            'pesan' => 'Hash tidak ditemukan di database. Dokumen tidak valid.'
        ]);
    }

    // Ambil data pengajuan
    if ($hasilSuratRw->jenis === 'biasa') {
        $pengajuan = PengajuanSurat::with(['warga.rt.rw','warga.scan_KK.alamat','tujuanSurat'])
            ->find($hasilSuratRw->pengajuan_surat_id);

        $hasilSuratRt = HasilSuratTtdRt::where('pengajuan_surat_id', $hasilSuratRw->pengajuan_surat_id)
            ->where('jenis', 'biasa')
            ->first();
    } else {
        $pengajuan = PengajuanSuratLain::with(['warga.rt.rw','warga.scan_KK.alamat'])
            ->find($hasilSuratRw->pengajuan_surat_lain_id);

        $hasilSuratRt = HasilSuratTtdRt::where('pengajuan_surat_lain_id', $hasilSuratRw->pengajuan_surat_lain_id)
            ->where('jenis', 'lain')
            ->first();
    }

    return view('verifikasiSurat.hasilQrCode', [
        'status' => 'valid',
        'pengajuan' => $pengajuan,
        'hasilSuratRw' => $hasilSuratRw,
        'hasilSuratRt' => $hasilSuratRt
    ]);
}


}

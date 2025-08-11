<?php

namespace App\Http\Controllers\Rt;

use App\Models\Rw;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\LogTtdDigital;
use App\Models\PengajuanSurat;
use App\Models\HasilSuratTtdRt;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PengajuanSuratLain;
use App\Http\Controllers\Controller;
use App\Mail\NotifikasiVerifikasiRw;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\NotifikasiStatusPengajuanKeWarga;

class VerifikasiSuratController extends Controller
{
    public function index()
    {
        $profile_rt = Auth::guard('rt')->user()->profile_rt;
        $rt_id = Auth::guard('rt')->user();
        $ttdDigital = $rt_id->ttd_digital;
        $showModalUploadTtd = empty($ttdDigital);

        $pengajuanSurat = PengajuanSurat::with(['warga', 'tujuanSurat', 'pengajuan.persyaratan'])
    ->where('status_rt', 'menunggu')->orderBy('created_at', 'desc') ->get();
        $pengajuanSuratLain = PengajuanSuratLain::with('warga')->where('status_rt_pengajuan_lain', 'menunggu')->orderBy('created_at', 'desc') ->get();

        return view('rt.verifikasiSuratWarga', compact('pengajuanSurat', 'pengajuanSuratLain', 'profile_rt', 'showModalUploadTtd','ttdDigital'));
    }

public function proses(Request $request)
{
    Carbon::setLocale('id');
    $id = $request->pengajuan_surat_id ?? $request->pengajuan_surat_lain_id ?? $request->id;
    $jenis = $request->jenis;
    $aksi = $request->aksi;

    $rt = Auth::guard('rt')->user();
    $rw = Rw::find($rt->rw_id);

    // 🔹 Ambil data pengajuan sesuai jenis
    if ($jenis == 'biasa') {
        $data = PengajuanSurat::with('warga', 'tujuanSurat')->findOrFail($id);
        $jenisSurat = $data->tujuanSurat->nama_tujuan ?? 'Surat Tidak Diketahui';
        $linkDetail = route('riwayatSurat', ['id' => $data->id]);
    } else {
        $data = PengajuanSuratLain::with('warga')->findOrFail($id);
        $jenisSurat = $data->tujuan_manual ?? 'Surat Lain';
        $linkDetail = route('riwayatSurat', ['id' => $data->id]);
    }

    if ($aksi == 'setuju') {

        // 1️⃣ Validasi hash tanda tangan RT
        $pathTtd = Storage::path($rt->ttd_digital);
        $currentHashTtd = hash_file('sha256', $pathTtd);

        $lastLog = LogTtdDigital::where('rt_id', $rt->id_rt)
            ->whereIn('aksi', ['upload_ttd', 'edit_ttd'])
            ->latest()
            ->first();

        if (!$lastLog || $currentHashTtd !== $lastLog->hash_dokumen) {
            return back()->with('error', 'Tanda tangan digital tidak valid atau telah berubah. Silakan upload ulang.');
        }

        // 2️⃣ Update status persetujuan
        if ($jenis == 'biasa') {
            $data->status_rt = 'disetujui';
            $data->waktu_persetujuan_rt = now();
        } else {
            $data->status_rt_pengajuan_lain = 'disetujui';
            $data->waktu_persetujuan_rt_lain = now();
            $data->nomor_surat_pengajuan_lain = $request->nomor_surat;
        }
        $data->save();

        // 3️⃣ Generate PDF dan simpan
        $pdfData = [
            'pengajuan' => $data,
            'rt' => $rt,
            'rw' => $rw,
            'ttd' => base64_encode(file_get_contents(Storage::path($rt->ttd_digital_bersih))),
            'jenis' => $jenis,
        ];

        $pdf = Pdf::loadView('rt.suratPengantar', $pdfData)->setPaper('a4');
        $pdfContent = $pdf->output();
        $filename = 'surat-ttd-rt-' . $data->id . '-' .
                    str_replace(' ', '-', strtolower($data->warga->nama_lengkap)) .
                    '-' . time() . '.pdf';
        $filepath = 'public/hasil_surat/ttd_rt/' . $filename;
        Storage::put($filepath, $pdfContent);

        $hashDokumen = hash('sha256', $pdfContent);

        // 4️⃣ Simpan ke hasil surat
        HasilSuratTtdRt::updateOrCreate(
            [
                'jenis' => $jenis,
                'pengajuan_surat_id' => $jenis == 'biasa' ? $data->id_pengajuan_surat : null,
                'pengajuan_surat_lain_id' => $jenis == 'lain' ? $data->id_pengajuan_surat_lain : null,
            ],
            [
                'file_surat' => $filepath,
            ]
        );

        // 5️⃣ Simpan log tanda tangan dokumen
        LogTtdDigital::create([
            'jenis_penandatangan' => 'rt',
            'rt_id' => $rt->id_rt,
            'pengajuan_surat_id' => $jenis == 'biasa' ? $data->id_pengajuan_surat : null,
            'pengajuan_surat_lain_id' => $jenis == 'lain' ? $data->id_pengajuan_surat_lain : null,
            'aksi' => 'sign_dokumen',
            'file_ttd' => $rt->ttd_digital,
            'hash_dokumen' => $hashDokumen,
            'token_verifikasi' => Str::random(40),
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'metadata' => [
                'filename_pdf' => $filename,
                'jenis_surat' => $jenis,
                'nama_warga' => $data->warga->nama_lengkap,
            ],
        ]);

        // 6️⃣ Kirim email notifikasi
        Mail::to($rw->email_rw)->send(
            new NotifikasiVerifikasiRw($rw->nama_lengkap_rw, $jenisSurat, $data->warga->nama_lengkap)
        );

        Mail::to($data->warga->email)->send(
            new NotifikasiStatusPengajuanKeWarga(
                $data->warga->nama_lengkap,
                $jenisSurat,
                'disetujui',
                null,
                $linkDetail
            )
        );

    } else {
        // ❌ Proses penolakan
        if ($jenis == 'biasa') {
            $data->status_rt = 'ditolak';
            $data->waktu_persetujuan_rt = now();
            $data->alasan_penolakan_pengajuan = $request->alasan_penolakan;
        } else {
            $data->status_rt_pengajuan_lain = 'ditolak';
            $data->waktu_persetujuan_rt_lain = now();
            $data->alasan_penolakan_pengajuan_lain = $request->alasan_penolakan;
        }
        $data->save();

        Mail::to($data->warga->email)->send(
            new NotifikasiStatusPengajuanKeWarga(
                $data->warga->nama_lengkap,
                $jenisSurat,
                'ditolak',
                $jenis == 'biasa' ? $data->alasan_penolakan_pengajuan : $data->alasan_penolakan_pengajuan_lain,
                $linkDetail
            )
        );
    }

    return redirect()->back()->with('success', 'Pengajuan berhasil diproses.');
}


    public function getNotifikasi()
    {
        $warga = Auth::guard('warga')->user(); // ganti ke guard warga sesuai setupmu

        // Ambil 5 notifikasi terbaru untuk warga yang login, status sudah 'disetujui' atau 'ditolak'
        $notifikasiBiasa = PengajuanSurat::with('warga')
            ->whereIn('status_rt', ['disetujui', 'ditolak'])
            ->where('warga_id', $warga->id)  // filter berdasarkan warga login
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        $notifikasiLain = PengajuanSuratLain::with('warga')
            ->whereIn('status_rt_pengajuan_lain', ['disetujui', 'ditolak'])
            ->where('warga_id', $warga->id)  // filter berdasarkan warga login
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        // Gabungkan dan sort
        $notifikasi = $notifikasiBiasa->concat($notifikasiLain)->sortByDesc('updated_at')->take(5);

        return $notifikasi;
    }

}

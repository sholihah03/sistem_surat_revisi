<?php

namespace App\Http\Controllers\Rt;

use App\Models\Rw;
use Illuminate\Http\Request;
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

        if ($jenis == 'biasa') {
        $data = PengajuanSurat::with('warga', 'tujuanSurat')->findOrFail($id);
        $jenisSurat = $data->tujuanSurat->nama_tujuan ?? 'Surat Tidak Diketahui';
        $linkDetail = route('riwayatSurat', ['id' => $data->id]); // sesuaikan route

        if ($aksi == 'setuju') {
            $data->status = 'disetujui';
            $data->save();

            // --- Generate dan simpan surat hasil tanda tangan RT ---
            $pdfData = [
                'pengajuan' => $data,
                'rt' => $rt,
                'rw' => $rw,
                'ttd' => base64_encode(file_get_contents(Storage::path($rt->ttd_digital_bersih))),
                'jenis' => 'biasa',
            ];

            $pdf = Pdf::loadView('rt.suratPengantar', $pdfData)->setPaper('a4');
            $filename = 'surat-ttd-rt-' . $data->id . '-' . str_replace(' ', '-', strtolower($data->warga->nama_lengkap)) . '-' . time() . '.pdf';
            Storage::put('public/hasil_surat/ttd_rt/' . $filename, $pdf->output());

            // Simpan data hasil surat ke tb_hasil_surat_ttd_rt
            HasilSuratTtdRt::updateOrCreate(
                [
                    'jenis' => 'biasa',
                    'pengajuan_id' => $data->id_pengajuan_surat,
                ],
                [
                    'file_surat' => 'public/hasil_surat/ttd_rt/' . $filename,
                ]
            );

            Mail::to($rw->email_rw)->send(
                new NotifikasiVerifikasiRw($rw->nama_lengkap_rw, $jenisSurat, $data->warga->nama_lengkap)
            );

            // Email ke warga
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
            $data->status = 'ditolak';
            $data->alasan_penolakan_pengajuan = $request->alasan_penolakan;
            $data->save();

            // Email ke warga dengan alasan penolakan
            Mail::to($data->warga->email)->send(
                new NotifikasiStatusPengajuanKeWarga(
                    $data->warga->nama_lengkap,
                    $jenisSurat,
                    'ditolak',
                    $data->alasan_penolakan_pengajuan,
                    $linkDetail
                )
            );
        }

        } else {
            $data = PengajuanSuratLain::with('warga')->findOrFail($id);
            $jenisSurat = $data->tujuan_manual ?? 'Surat Lain';
            $linkDetail = route('riwayatSurat', ['id' => $data->id]); // sesuaikan route

            if ($aksi == 'setuju') {
                $data->status_pengajuan_lain = 'disetujui';
                $data->nomor_surat_pengajuan_lain = $request->nomor_surat;
                $data->save();

                // Generate dan simpan surat hasil tanda tangan RT untuk surat lain
                $pdfData = [
                    'pengajuan' => $data,
                    'rt' => $rt,
                    'rw' => $rw,
                    'ttd' => base64_encode(file_get_contents(Storage::path($rt->ttd_digital_bersih))),
                    'jenis' => 'lain',
                ];

                $pdf = Pdf::loadView('rt.suratPengantarLain', $pdfData)->setPaper('a4');
                $filename = 'surat-ttd-rt-' . $data->id . '-' . str_replace(' ', '-', strtolower($data->warga->nama_lengkap)) . '-' . time() . '.pdf';
                Storage::put('public/hasil_surat/ttd_rt/' . $filename, $pdf->output());

                HasilSuratTtdRt::updateOrCreate(
                    [
                        'jenis' => 'lain',
                        'pengajuan_id' => $data->id_pengajuan_surat_lain,
                    ],
                    [
                        'file_surat' => 'public/hasil_surat/ttd_rt/' . $filename,
                    ]
                );

                Mail::to($rw->email_rw)->send(
                    new NotifikasiVerifikasiRw($rw->nama_lengkap_rw, $jenisSurat, $data->warga->nama_lengkap)
                );

                 // Email ke warga
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
                $data->status_pengajuan_lain = 'ditolak';
                $data->alasan_penolakan_pengajuan_lain = $request->alasan_penolakan;
                $data->save();

                // Email ke warga dengan alasan penolakan
                Mail::to($data->warga->email)->send(
                    new NotifikasiStatusPengajuanKeWarga(
                        $data->warga->nama_lengkap,
                        $jenisSurat,
                        'ditolak',
                        $data->alasan_penolakan_pengajuan_lain,
                        $linkDetail
                    )
                );
            }
        }

        return redirect()->back()->with('success', 'Pengajuan berhasil diproses.');
    }

    public function getNotifikasi()
    {
        $warga = Auth::guard('warga')->user(); // ganti ke guard warga sesuai setupmu

        // Ambil 5 notifikasi terbaru untuk warga yang login, status sudah 'disetujui' atau 'ditolak'
        $notifikasiBiasa = PengajuanSurat::with('warga')
            ->whereIn('status', ['disetujui', 'ditolak'])
            ->where('warga_id', $warga->id)  // filter berdasarkan warga login
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        $notifikasiLain = PengajuanSuratLain::with('warga')
            ->whereIn('status_pengajuan_lain', ['disetujui', 'ditolak'])
            ->where('warga_id', $warga->id)  // filter berdasarkan warga login
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        // Gabungkan dan sort
        $notifikasi = $notifikasiBiasa->concat($notifikasiLain)->sortByDesc('updated_at')->take(5);

        return $notifikasi;
    }

}

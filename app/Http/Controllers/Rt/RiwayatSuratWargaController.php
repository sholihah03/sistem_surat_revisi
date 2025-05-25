<?php

namespace App\Http\Controllers\Rt;

use Carbon\Carbon;
use App\Models\ScanKK;
use Illuminate\Http\Request;
use App\Models\PengajuanSurat;
use App\Models\HasilSuratTtdRt;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PengajuanSuratLain;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RiwayatSuratWargaController extends Controller
{
    public function indexx(Request $request){
        return view('rt.suratPengantar');
    }

    public function index(Request $request)
    {
        $rt_id = Auth::guard('rt')->user()->id_rt;
        $rt = Auth::guard('rt')->user();
        $profile_rt = Auth::guard('rt')->user()->profile_rt;
        $ttdDigital = $rt->ttd_digital;
        $showModalUploadTtd = empty($ttdDigital);

        $search = $request->input('search');

        $pengajuanBiasa = PengajuanSurat::with(['warga', 'tujuanSurat'])
            ->whereHas('warga', function($query) use ($rt_id) {
                $query->where('rt_id', $rt_id);
            })
            ->whereIn('status', ['disetujui', 'ditolak']);

        $pengajuanLain = PengajuanSuratLain::with('warga')
            ->whereHas('warga', function($query) use ($rt_id) {
                $query->where('rt_id', $rt_id);
            })
            ->whereIn('status_pengajuan_lain', ['disetujui', 'ditolak']);

        if ($search) {
            $months = [
                'januari', 'februari', 'maret', 'april', 'mei', 'juni',
                'juli', 'agustus', 'september', 'oktober', 'november', 'desember'
            ];

            $searchLower = strtolower($search);
            $parts = explode(' ', $searchLower);

            $tanggal = null;
            $bulan = null;
            $tahun = null;

            foreach ($parts as $part) {
                if (is_numeric($part)) {
                    if (strlen($part) == 4) {
                        $tahun = $part;
                    } else {
                        $tanggal = $part;
                    }
                } elseif (in_array($part, $months)) {
                    $bulan = $part;
                }
            }

            if ($tanggal && $bulan && $tahun) {
                // tanggal + bulan + tahun lengkap
                try {
                    $dateString = $tanggal.' '.$bulan.' '.$tahun;
                    $parsedTanggal = Carbon::createFromFormat('j F Y', $dateString)->startOfDay();

                    $pengajuanBiasa = $pengajuanBiasa->whereDate('updated_at', $parsedTanggal->toDateString());
                    $pengajuanLain = $pengajuanLain->whereDate('updated_at', $parsedTanggal->toDateString());
                } catch (\Exception $e) {
                    // fallback ke keyword biasa jika gagal parse
                }
            } elseif ($tanggal && $bulan) {
                // tanggal + bulan tanpa tahun
                $bulanIndex = array_search($bulan, $months) + 1;
                $pengajuanBiasa = $pengajuanBiasa->whereDay('updated_at', $tanggal)
                                            ->whereMonth('updated_at', $bulanIndex);
                $pengajuanLain = $pengajuanLain->whereDay('updated_at', $tanggal)
                                            ->whereMonth('updated_at', $bulanIndex);
            } elseif ($bulan && $tahun) {
                // bulan + tahun
                $bulanIndex = array_search($bulan, $months) + 1;
                $pengajuanBiasa = $pengajuanBiasa->whereYear('updated_at', $tahun)
                                            ->whereMonth('updated_at', $bulanIndex);
                $pengajuanLain = $pengajuanLain->whereYear('updated_at', $tahun)
                                            ->whereMonth('updated_at', $bulanIndex);
            } elseif ($bulan) {
                // bulan saja
                $bulanIndex = array_search($bulan, $months) + 1;
                $pengajuanBiasa = $pengajuanBiasa->whereMonth('updated_at', $bulanIndex);
                $pengajuanLain = $pengajuanLain->whereMonth('updated_at', $bulanIndex);
            } elseif ($tahun) {
                // tahun saja
                $pengajuanBiasa = $pengajuanBiasa->whereYear('updated_at', $tahun);
                $pengajuanLain = $pengajuanLain->whereYear('updated_at', $tahun);
            }

            // Jika tidak ada filter tanggal/bulan/tahun cocok, anggap sebagai keyword biasa
            if (!($bulan || $tahun || ($tanggal && $bulan && $tahun))) {
                $pengajuanBiasa = $pengajuanBiasa->where(function($q) use ($search) {
                    $q->whereHas('warga', function($q2) use ($search) {
                        $q2->where('nama_lengkap', 'like', "%{$search}%");
                    })
                    ->orWhereHas('tujuanSurat', function($q3) use ($search) {
                        $q3->where('nama_tujuan', 'like', "%{$search}%");
                    });
                });

                $pengajuanLain = $pengajuanLain->where(function($q) use ($search) {
                    $q->whereHas('warga', function($q2) use ($search) {
                        $q2->where('nama_lengkap', 'like', "%{$search}%");
                    })
                    ->orWhere('tujuan_manual', 'like', "%{$search}%");
                });
            }
        }


        $pengajuanBiasa = $pengajuanBiasa->get();
        $pengajuanLain = $pengajuanLain->get();

        // Ambil hasil surat seperti biasa, sesuaikan id pengajuan
        $hasilSurat = HasilSuratTtdRt::whereIn('jenis', ['biasa', 'lain'])
            ->whereIn('pengajuan_id', $pengajuanBiasa->pluck('id_pengajuan_surat')->merge($pengajuanLain->pluck('id_pengajuan_surat_lain')))
            ->get()
            ->keyBy(function($item) {
                return $item->jenis.'-'.$item->pengajuan_id;
            });

        $profile_rt = Auth::guard('rt')->user()->profile_rt;

        return view('rt.riwayatSuratWarga', compact('profile_rt', 'pengajuanBiasa', 'pengajuanLain', 'hasilSurat', 'showModalUploadTtd','ttdDigital'));
    }


    public function lihatHasilSurat($id)
    {
        $profile_rt = Auth::guard('rt')->user()->profile_rt;
        $hasilSurat = HasilSuratTtdRt::findOrFail($id);
        $rt = Auth::guard('rt')->user();
        $ttdDigital = $rt->ttd_digital;
        $showModalUploadTtd = empty($ttdDigital);

        // Pastikan file surat ada dan dapat diakses
        if (!Storage::exists($hasilSurat->file_surat)) {
            abort(404, 'File surat tidak ditemukan');
        }

        // Jika file surat PDF, kita bisa tampilkan pakai iframe atau embed
        $fileUrl = Storage::url($hasilSurat->file_surat);

        return view('rt.hasilSurat', compact('hasilSurat', 'fileUrl', 'profile_rt', 'showModalUploadTtd','ttdDigital'));
    }

    public function unduhHasilSurat($id)
    {
        $hasilSurat = HasilSuratTtdRt::findOrFail($id);

        $filePath = $hasilSurat->file_surat; // Simpan path file surat di kolom file_surat
        if (!Storage::exists($filePath)) {
            abort(404, "File surat tidak ditemukan");
        }

        return Storage::download($filePath);
    }
}

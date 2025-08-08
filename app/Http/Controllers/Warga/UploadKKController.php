<?php

namespace App\Http\Controllers\Warga;

use App\Models\ScanKK;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotifikasiVerifikasiDataWarga;
use Illuminate\Support\Facades\Storage;
use App\Models\Alamat; // Tambahkan model Alamat


class UploadKKController extends Controller
{
    public function index()
    {
        // Mengambil data RT dari tabel tb_rt (misalnya hanya mengambil kolom no_rt)
        $dataRT = DB::table('tb_rt')->select('id_rt', 'no_rt', 'nama_lengkap_rt')->get();
        $dataRW = DB::table('tb_rw')->select('id_rw', 'no_rw', 'nama_lengkap_rw')->get();
        return view('warga.upload-kk', compact('dataRT','dataRW'));
    }

    public function konfirm(Request $request)
    {
        return view('warga.upload-kkKonfir', [
            'no_kk' => $request->query('no_kk'),
            'nama_kepala_keluarga' => $request->query('nama_kepala_keluarga'),
            'path' => $request->query('path'),
            'alamatData' => $request->query('alamatData'),
            'nik_pendaftar' => $request->query('nik_pendaftar'),
        ]);
    }

    public function proses(Request $request)
    {
        $validated = $request->validate([
            'path_file_kk' => 'required|mimes:jpeg,png,jpg|max:5120',
        ]);

        $file = $request->file('path_file_kk');
        $filename = uniqid('kk_') . '.' . $file->getClientOriginalExtension();

        $path = $file->storeAs('public/uploads/kk', $filename);
        $fullImagePath = storage_path('app/' . $path);

        $outputTxtPath = storage_path('app/public/uploads/kk_text/' . pathinfo($filename, PATHINFO_FILENAME));
        if (!file_exists(dirname($outputTxtPath))) {
            mkdir(dirname($outputTxtPath), 0777, true);
        }

        exec('tesseract ' . escapeshellarg($fullImagePath) . ' ' . escapeshellarg($outputTxtPath) . ' -l ind');
        $ocrText = @file_get_contents($outputTxtPath . '.txt') ?: '';

        $ocrText = str_replace(['_', '-', '|', '—', '–', ':'], ':', $ocrText);
        $ocrText = preg_replace('/\s+/', ' ', $ocrText);

        $no_kk = $this->extractNoKK($ocrText);
        $nama_kepala_keluarga = $this->extractNamaKepalaKeluarga($ocrText);
        $alamatData = $this->extractAlamatData($ocrText);

        if (empty($no_kk) || empty($nama_kepala_keluarga)) {
            // Simpan path file ke session
            session(['failed_kk_path' => $path]);

            // Hitung jumlah kegagalan dari session
            $failCount = session('upload_kk_fail', 0) + 1;
            session(['upload_kk_fail' => $failCount]);

            if ($failCount >= 3) {
                // Reset count agar tidak berulang terus
                session()->forget('upload_kk_fail');

                return redirect()->route('uploadKKManual') // Ganti dengan rute halaman manual
                    ->with('error_gagal_unggah', 'Anda telah gagal mengunggah KK sebanyak 3 kali. Silakan isi data secara manual.');
            }

                return redirect()->back()->with('error', 'Gagal membaca data KK. Silakan unggah ulang dengan gambar yang lebih jelas.');
            }

        // Reset hitungan jika berhasil
        session()->forget('upload_kk_fail');

        // return redirect()->route('uploadKKKonfirm', compact('no_kk', 'nama_kepala_keluarga', 'path', 'alamatData'));
        return redirect()->route('uploadKKKonfirm', [
    'no_kk' => $no_kk,
    'nama_kepala_keluarga' => $nama_kepala_keluarga,
    'path' => $path,
    'alamatData' => $alamatData,
    'nik_pendaftar' => $request->nik_pengupload, // <- tambah ini
]);

    }

    public function simpan(Request $request)
    {

        // Ambil data warga yang sedang login
        $warga = auth()->guard('warga')->user();
        // Validasi
        $request->validate([
            'no_kk_scan' => 'required|string|max:50',
            'nama_kepala_keluarga' => 'required|string|max:100',
            'path' => 'required|string',
            'nama_jalan' => 'nullable|string|max:255',
            'provinsi' => 'nullable|string|max:100',
            'kabupaten_kota' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'desa' => 'nullable|string|max:100',
            'rt_alamat' => 'nullable|string|max:10',
            'rw_alamat' => 'nullable|string|max:10',
            'kode_pos' => 'nullable|string|max:10',
        ],[
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'max' => ':attribute maksimal :max karakter.',
            'unique' => ':attribute sudah terdaftar.',
        ]);

        $path_file_kk = $request->input('path'); // Path dari file KK yang sudah diunggah dan di-OCR sebelumnya

        // Simpan data alamat
        $alamat = new Alamat();
        $alamat->nama_jalan = $request->nama_jalan;
        $alamat->provinsi = $request->provinsi;
        $alamat->kabupaten_kota = $request->kabupaten_kota;
        $alamat->kecamatan = $request->kecamatan;
        $alamat->kelurahan = $request->desa;
        $alamat->rt_alamat = $request->rt_alamat;
        $alamat->rw_alamat = $request->rw_alamat;
        $alamat->kode_pos = $request->kode_pos;
        $alamat->save();  // Simpan alamat ke tb_alamat

        // Ambil alamat_id yang baru disimpan
        $alamat_id = $alamat->id_alamat;

        // Simpan data ke tb_scan_kk
        $scan = new ScanKK();
        $scan->no_kk_scan = $request->no_kk_scan;
        $scan->nik_pendaftar = $request->nik_pendaftar;
        $scan->nama_pendaftar = $warga->nama_lengkap ?? null;
        $scan->no_hp_pendaftar = $warga->no_hp ?? null;
        $scan->email_pendaftar = $warga->email ?? null;
        $scan->nama_kepala_keluarga = $request->nama_kepala_keluarga;
        $scan->path_file_kk = $path_file_kk; // Menyimpan path file KK yang sudah di-upload
        $scan->alamat_id = $alamat_id;  // Menyimpan alamat_id ke tb_scan_kk
        $scan->save(); // Simpan scan data ke tb_scan_kk

        // Setelah data berhasil disimpan di tb_scan_kk, ambil id_scan yang baru disimpan
        $scan_id = $scan->id_scan;

        // Cari RT berdasarkan nomor RT dari input
        $rt = DB::table('tb_rt')->where('no_rt', $request->rt_alamat)->first();

if ($rt && $rt->email_rt) {
    // Kirim email notifikasi ke email RT
Mail::to($rt->email_rt)->send(new NotifikasiVerifikasiDataWarga($scan, $alamat, $scan_id, $rt->rt_nomor));

}


        return redirect()->route('dashboardWarga')->with('success_upload_kk', 'Data berhasil disimpan.');
    }

    private function extractNoKK($text)
    {
        preg_match('/\b\d{16}\b/', $text, $matches);
        return $matches[0] ?? '';
    }

    private function extractNamaKepalaKeluarga($text)
    {
        if (preg_match('/Nama Kepala Keluarga\s*[:\-]?\s*([A-Za-z\s\.,]+)/i', $text, $matches)) {
            $nama = trim($matches[1]);
            $potongan = preg_split('/\b(Desa|Kelurahan|RW|RT)\b/i', $nama);
            return trim($potongan[0]);
        }
        return '';
    }

    private function extractAlamatData($text)
    {
        $data = [];

        // Nama jalan
        if (preg_match('/Alamat\s*[:\-]?\s*(.*?)\s*(Kecamatan|RT\/RW)/i', $text, $matches)) {
            $data['nama_jalan'] = $this->cleanText($matches[1]);
        }

        // RT/RW
        if (preg_match('/RT\/RW\s*[:\-]?\s*(\d{1,3})[\/\s]*(\d{1,3})/i', $text, $matches)) {
            $data['rt'] = $matches[1];
            $data['rw'] = $matches[2];
        }

        // Kelurahan/Desa
        if (preg_match('/(Desa|Kelurahan)\s*[:\-]?\s*(.*?)\s*(Kecamatan|Alamat|RT\/RW)/i', $text, $matches)) {
            $data['kelurahan'] = $this->cleanText($matches[2]);
        }

        // Kecamatan
        if (preg_match('/Kecamatan\s*[:\-]?\s*(.*?)\s*(Kabupaten|Kota|Kode Pos|Provinsi)/i', $text, $matches)) {
            $data['kecamatan'] = $this->cleanText($matches[1]);
        }

        // Kabupaten/Kota
        if (preg_match('/(Kabupaten|Kota)\s*[:\-]?\s*(.*?)\s*(Kode Pos|Provinsi|$)/i', $text, $matches)) {
            $data['kabupaten_kota'] = $this->cleanText($matches[2]);
        }

        // Provinsi
        if (preg_match('/Provinsi\s*[:\-]?\s*(.*?)($|\n)/i', $text, $matches)) {
            $data['provinsi'] = $this->cleanText($matches[1]);
        }

        // Kode Pos
        if (preg_match('/Kode Pos\s*[:\-]?\s*(\d{5})/i', $text, $matches)) {
            $data['kode_pos'] = $matches[1];
        }

        return $data;
    }

    private function cleanText($text)
    {
        $words = explode(' ', trim($text));
        $filteredWords = [];

        foreach ($words as $word) {
            // Tetap simpan kata jika:
            // - Semua huruf kapital
            // - atau hanya huruf biasa
            if (preg_match('/^[A-Z0-9\/]+$/', $word)) {
                $filteredWords[] = $word;
            }
        }

        // Gabungkan lagi hasilnya
        return implode(' ', $filteredWords);
    }
}

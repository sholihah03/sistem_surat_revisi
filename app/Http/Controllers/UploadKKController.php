<?php

namespace App\Http\Controllers;

use App\Models\ScanKK;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Alamat; // Tambahkan model Alamat

class UploadKKController extends Controller
{
    public function index()
    {
        return view('auth.upload-kk');
    }

    public function konfirm(Request $request)
{
    return view('auth.upload-kkKonfir', [
        'no_kk' => $request->query('no_kk'),
        'nama_kepala_keluarga' => $request->query('nama_kepala_keluarga'),
        'path' => $request->query('path'),
        'alamatData' => $request->query('alamatData'),
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
            Storage::delete($path);
            return redirect()->back()->with('error', 'Gagal membaca data KK. Silakan unggah ulang dengan gambar yang lebih jelas.');
        }

        return redirect()->route('uploadKKKonfirm', compact('no_kk', 'nama_kepala_keluarga', 'path', 'alamatData'));
    }

    public function simpan(Request $request)
    {
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
        $scan->nama_kepala_keluarga = $request->nama_kepala_keluarga;
        $scan->path_file_kk = $path_file_kk; // Menyimpan path file KK yang sudah di-upload
        $scan->alamat_id = $alamat_id;  // Menyimpan alamat_id ke tb_scan_kk
        $scan->save(); // Simpan scan data ke tb_scan_kk

        return redirect()->route('login')->with('success', 'Data berhasil disimpan.');
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

        // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'file_kk' => 'required|mimes:jpeg,png,jpg|max:5120',
    //     ]);

    //     $file = $request->file('file_kk');
    //     $filename = $file->getClientOriginalName();

    //     $path = $file->storeAs('public/uploads/kk', $filename);
    //     $fullImagePath = storage_path('app/' . $path);

    //     $textFolder = storage_path('app/public/uploads/kk_text');
    //     if (!is_dir($textFolder)) {
    //         mkdir($textFolder, 0777, true);
    //     }

    //     $textFilenameWithoutExt = pathinfo($filename, PATHINFO_FILENAME);
    //     $outputPath = $textFolder . '/' . $textFilenameWithoutExt;

    //     $command = 'tesseract ' . escapeshellarg($fullImagePath) . ' ' . escapeshellarg($outputPath) . ' -l ind';
    //     exec($command);

    //     $ocrResultPath = $outputPath . '.txt';
    //     $ocrText = file_exists($ocrResultPath) ? file_get_contents($ocrResultPath) : '';

    //     $ocrText = str_replace(['_', '-', '|', '—', '–', ':'], ':', $ocrText);
    //     $ocrText = preg_replace('/\s+/', ' ', $ocrText);

    //     $no_kk_scan = $this->extractNoKK($ocrText);
    //     $nama_kepala_keluarga = $this->extractNamaKepalaKeluarga($ocrText);

    //     if (empty($no_kk_scan)) {
    //         preg_match('/No\s*[:\-]?\s*(\d{16})/', $ocrText, $matches);
    //         $no_kk_scan = $matches[1] ?? '';
    //     }

    //     if (empty($nama_kepala_keluarga)) {
    //         preg_match('/Nama Kepala Keluarga\s*[:\-]?\s*([A-Za-z\s\.,]+)/i', $ocrText, $matches);
    //         $nama_kepala_keluarga = trim($matches[1] ?? '');
    //     }

    //     if (empty($no_kk_scan) || empty($nama_kepala_keluarga)) {
    //         Storage::delete($path);
    //         if (file_exists($ocrResultPath)) {
    //             unlink($ocrResultPath);
    //         }

    //         return redirect()->back()->with('error', 'Gagal membaca data KK. Silakan upload foto yang lebih jelas.');
    //     }

    //     // ===== Simpan ke ScanKK =====
    //     // Simpan ScanKK dengan alamat_id sebagai null dulu
    //     $scanKK = ScanKK::create([
    //         'nama_kepala_keluarga' => $nama_kepala_keluarga,
    //         'no_kk_scan' => $no_kk_scan,
    //         'path_file_kk' => $path,
    //         'status_verifikasi' => 'pending', // Atau sesuaikan sesuai kebutuhan
    //         'alasan_penolakan' => null,
    //     ]);

    //     // ===== Ekstrak Data Alamat dari OCR Text =====
    //     $alamatData = $this->extractAlamatData($ocrText);

    //     // Periksa jika alamat kecuali provinsi kosong
    //     $isAlamatIncomplete = empty($alamatData['nama_jalan']) || empty($alamatData['rt']) || empty($alamatData['rw']) ||
    //                             empty($alamatData['kelurahan']) || empty($alamatData['kecamatan']) || empty($alamatData['kabupaten_kota']) ||
    //                             empty($alamatData['kode_pos']);

    //     // Jika data alamat tidak lengkap (kecuali provinsi), tampilkan modal error
    //     if ($isAlamatIncomplete) {
    //         Storage::delete($path);
    //         if (file_exists($ocrResultPath)) {
    //             unlink($ocrResultPath);
    //         }

    //         return redirect()->back()->with('error', 'Data alamat tidak lengkap. Silakan upload foto yang lebih jelas.');
    //     }

    //     // ===== Simpan ke tb_alamat =====
    //     $alamat = Alamat::create([
    //         'nama_jalan' => $alamatData['nama_jalan'] ?? '',
    //         'rt_alamat' => $alamatData['rt'] ?? '',
    //         'rw_alamat' => $alamatData['rw'] ?? '',
    //         'kelurahan' => $alamatData['kelurahan'] ?? '',
    //         'kecamatan' => $alamatData['kecamatan'] ?? '',
    //         'kabupaten_kota' => $alamatData['kabupaten_kota'] ?? '',
    //         'provinsi' => strlen($alamatData['provinsi'] ?? '') > 255 ? '' : $alamatData['provinsi'],
    //         'kode_pos' => $alamatData['kode_pos'] ?? '',
    //     ]);

    //     // ===== Update ScanKK dengan alamat_id =====
    //     $scanKK->update([
    //         'alamat_id' => $alamat->id_alamat,
    //     ]);

    //     // ===== Update tb_pendaftaran dengan id_scan =====
    //     Pendaftaran::where('no_kk', $no_kk_scan)->update([
    //         'scan_id' => $scanKK->id_scan,
    //     ]);

    //     return redirect()->route('login')->with('success', 'Upload KK berhasil!');
    // }
}

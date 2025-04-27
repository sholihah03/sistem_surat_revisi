<?php

namespace App\Http\Controllers;

use App\Models\ScanKK;
use App\Models\Alamat; // Tambahkan model Alamat
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadKKController extends Controller
{
    public function index()
    {
        return view('auth.upload-kk');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'file_kk' => 'required|mimes:jpeg,png,jpg|max:5120',
        ]);

        $file = $request->file('file_kk');
        $filename = $file->getClientOriginalName();

        $path = $file->storeAs('uploads/kk', $filename);
        $fullImagePath = storage_path('app/' . $path);

        $textFolder = storage_path('app/uploads/kk_text');
        if (!is_dir($textFolder)) {
            mkdir($textFolder, 0777, true);
        }

        $textFilenameWithoutExt = pathinfo($filename, PATHINFO_FILENAME);
        $outputPath = $textFolder . '/' . $textFilenameWithoutExt;

        $command = 'tesseract ' . escapeshellarg($fullImagePath) . ' ' . escapeshellarg($outputPath) . ' -l ind';
        exec($command);

        $ocrResultPath = $outputPath . '.txt';
        $ocrText = file_exists($ocrResultPath) ? file_get_contents($ocrResultPath) : '';

        $ocrText = str_replace(['_', '-', '|', '—', '–', ':'], ':', $ocrText);
        $ocrText = preg_replace('/\s+/', ' ', $ocrText);

        $no_kk_scan = $this->extractNoKK($ocrText);
        $nama_kepala_keluarga = $this->extractNamaKepalaKeluarga($ocrText);

        if (empty($no_kk_scan)) {
            preg_match('/No\s*[:\-]?\s*(\d{16})/', $ocrText, $matches);
            $no_kk_scan = $matches[1] ?? '';
        }

        if (empty($nama_kepala_keluarga)) {
            preg_match('/Nama Kepala Keluarga\s*[:\-]?\s*([A-Za-z\s\.,]+)/i', $ocrText, $matches);
            $nama_kepala_keluarga = trim($matches[1] ?? '');
        }

        if (empty($no_kk_scan) || empty($nama_kepala_keluarga)) {
            Storage::delete($path);
            if (file_exists($ocrResultPath)) {
                unlink($ocrResultPath);
            }

            return redirect()->back()->with('error', 'Gagal membaca data KK. Silakan upload foto yang lebih jelas.');
        }

        // ===== Simpan ke ScanKK =====
        // Simpan ScanKK dengan alamat_id sebagai null dulu
        $scanKK = ScanKK::create([
            'nama_kepala_keluarga' => $nama_kepala_keluarga,
            'no_kk_scan' => $no_kk_scan,
            'path_file_kk' => $path,
            'status_verifikasi' => 'pending', // Atau sesuaikan sesuai kebutuhan
            'alasan_penolakan' => null,
        ]);

        // ===== Ekstrak Data Alamat dari OCR Text =====
        $alamatData = $this->extractAlamatData($ocrText);

        // Periksa jika alamat kecuali provinsi kosong
        $isAlamatIncomplete = empty($alamatData['nama_jalan']) || empty($alamatData['rt']) || empty($alamatData['rw']) ||
                              empty($alamatData['kelurahan']) || empty($alamatData['kecamatan']) || empty($alamatData['kabupaten_kota']) ||
                              empty($alamatData['kode_pos']);

        // Jika data alamat tidak lengkap (kecuali provinsi), tampilkan modal error
        if ($isAlamatIncomplete) {
            Storage::delete($path);
            if (file_exists($ocrResultPath)) {
                unlink($ocrResultPath);
            }

            return redirect()->back()->with('error', 'Data alamat tidak lengkap. Silakan upload foto yang lebih jelas.');
        }

        // ===== Simpan ke tb_alamat =====
        $alamat = Alamat::create([
            'nama_jalan' => $alamatData['nama_jalan'] ?? '',
            'rt_alamat' => $alamatData['rt'] ?? '',
            'rw_alamat' => $alamatData['rw'] ?? '',
            'kelurahan' => $alamatData['kelurahan'] ?? '',
            'kecamatan' => $alamatData['kecamatan'] ?? '',
            'kabupaten_kota' => $alamatData['kabupaten_kota'] ?? '',
            'provinsi' => strlen($alamatData['provinsi'] ?? '') > 255 ? '' : $alamatData['provinsi'],
            'kode_pos' => $alamatData['kode_pos'] ?? '',
        ]);

        // ===== Update ScanKK dengan alamat_id =====
        $scanKK->update([
            'alamat_id' => $alamat->id_alamat,
        ]);

        return redirect()->route('login')->with('success', 'Upload KK berhasil!');
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

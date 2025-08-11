<?php

namespace App\Http\Controllers\Rt;

use App\Models\Rt;
use Illuminate\Http\Request;
use App\Models\LogTtdDigital;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TtdDigitalController extends Controller
{
public function store(Request $request)
{
    $request->validate([
        'ttd_digital' => 'required|image|mimes:jpg,jpeg,png|max:2048',
    ], [
        'ttd_digital.max' => 'Gagal mengunggah tanda tangan. Ukuran file melebihi batas maksimal 2 MB.',
        'ttd_digital.required' => 'File tanda tangan wajib diunggah.',
        'ttd_digital.image' => 'File tanda tangan harus berupa gambar.',
        'ttd_digital.mimes' => 'Format tanda tangan harus jpg, jpeg, atau png.',
    ]);

    $user = auth()->guard('rt')->user();
    $file = $request->file('ttd_digital');

    // Simpan file asli
    $originalName = uniqid() . '.' . $file->getClientOriginalExtension();
    $originalPath = $file->storeAs('public/ttd_rt/asli', $originalName);

    // Path untuk hasil bersih
    $cleanFilename = pathinfo($originalName, PATHINFO_FILENAME) . '.png';
    $cleanDir = 'public/ttd_rt/bersih';
    $cleanPath = $cleanDir . '/' . $cleanFilename;
    if (!Storage::exists($cleanDir)) {
        Storage::makeDirectory($cleanDir);
    }

    $this->makeTransparentBackground(
        Storage::path($originalPath),
        Storage::path($cleanPath)
    );

    // Hitung hash SHA-256 file asli
    $hashFileTtd = hash_file('sha256', Storage::path($originalPath));

    // Update path di tb_rt
    $rt = Rt::find($user->id_rt);
    $aksi = $rt->ttd_digital ? 'edit_ttd' : 'upload_ttd'; // deteksi edit / upload baru
    $rt->update([
        'ttd_digital' => $originalPath,
        'ttd_digital_bersih' => $cleanPath,
    ]);

    // Simpan log
    LogTtdDigital::create([
        'jenis_penandatangan' => 'rt',
        'rt_id' => $rt->id_rt,
        'aksi' => $aksi,
        'file_ttd' => $originalPath,
        'hash_dokumen' => $hashFileTtd,
        'ip_address' => $request->ip(),
        'user_agent' => $request->header('User-Agent'),
        'metadata' => [
            'filename_original' => $file->getClientOriginalName(),
            'storage_path_asli' => $originalPath,
            'storage_path_bersih' => $cleanPath,
        ],
    ]);

    return redirect()->back()->with('ttdSuccess', 'Tanda tangan berhasil disimpan.');
}

    /**
     * Mengubah gambar ke PNG dan latar belakang jadi transparan
     */
private function makeTransparentBackground($sourcePath, $outputPath)
{
    $ext = strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION));

    switch ($ext) {
        case 'png':
            $image = imagecreatefrompng($sourcePath);
            break;
        case 'jpg':
        case 'jpeg':
            $image = imagecreatefromjpeg($sourcePath);
            break;
        default:
            throw new \Exception("Format tidak didukung: $ext");
    }

    $width = imagesx($image);
    $height = imagesy($image);

    // Buat transparansi
    $transparent = imagecreatetruecolor($width, $height);
    imagesavealpha($transparent, true);
    $alpha = imagecolorallocatealpha($transparent, 0, 0, 0, 127);
    imagefill($transparent, 0, 0, $alpha);

    for ($x = 0; $x < $width; $x++) {
        for ($y = 0; $y < $height; $y++) {
            $rgb = imagecolorat($image, $x, $y);
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;

            if ($r > 230 && $g > 230 && $b > 230) {
                imagesetpixel($transparent, $x, $y, $alpha);
            } else {
                $color = imagecolorallocate($transparent, $r, $g, $b);
                imagesetpixel($transparent, $x, $y, $color);
            }
        }
    }

    // ✅ Target ukuran tetap
    $targetWidth = 2048;
    $targetHeight = 1653;

    // Hitung proporsional scaling
    $scale = min($targetWidth / $width, $targetHeight / $height);
    $resizedWidth = (int)($width * $scale);
    $resizedHeight = (int)($height * $scale);

    // Buat canvas final
    $finalImage = imagecreatetruecolor($targetWidth, $targetHeight);
    imagesavealpha($finalImage, true);
    $bg = imagecolorallocatealpha($finalImage, 0, 0, 0, 127);
    imagefill($finalImage, 0, 0, $bg);

    // Posisikan di tengah
    $x = (int)(($targetWidth - $resizedWidth) / 2);
    $y = (int)(($targetHeight - $resizedHeight) / 2);

    imagecopyresampled(
        $finalImage,
        $transparent,
        $x, $y,
        0, 0,
        $resizedWidth, $resizedHeight,
        $width, $height
    );

    imagepng($finalImage, $outputPath);

    imagedestroy($image);
    imagedestroy($transparent);
    imagedestroy($finalImage);
}



}

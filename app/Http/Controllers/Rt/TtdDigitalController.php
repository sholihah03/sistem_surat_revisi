<?php

namespace App\Http\Controllers\Rt;

use App\Models\Rt;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TtdDigitalController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ttd_digital' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'ttd_digital.max' => 'Gagal mengunggah tanda tangan. Ukuran file melebihi batas maksimal 2 MB.',
            'ttd_digital.required' => 'File tanda tangan wajib diunggah.',
            'ttd_digital.image' => 'File tanda tangan harus berupa gambar.',
            'ttd_digital.mimes' => 'Format tanda tangan harus jpg, jpeg, atau png.',
        ]);

        $user = auth()->guard('rt')->user();

        $file = $request->file('ttd_digital');

        // Simpan file asli ke folder "asli"
        $originalName = uniqid() . '.' . $file->getClientOriginalExtension();
        $originalPath = $file->storeAs('public/ttd_rt/asli', $originalName);

        // Siapkan path dan nama file untuk hasil bersih
        $cleanFilename = pathinfo($originalName, PATHINFO_FILENAME) . '.png';
        $cleanDir = 'public/ttd_rt/bersih';
        $cleanPath = $cleanDir . '/' . $cleanFilename;

        // ✅ Buat folder jika belum ada
        if (!Storage::exists($cleanDir)) {
            Storage::makeDirectory($cleanDir);
        }

        try {
            $this->makeTransparentBackground(
                Storage::path($originalPath),
                Storage::path($cleanPath)
            );
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal memproses gambar: ' . $e->getMessage()], 500);
        }

        // Simpan ke DB
        $rt = Rt::find($user->id_rt);
        $rt->update([
            'ttd_digital' => $originalPath,
            'ttd_digital_bersih' => $cleanPath,
        ]);

        return redirect()->back()->with('ttdSuccess', 'Tanda tangan berhasil disimpan.');
    }

    /**
     * Mengubah gambar ke PNG dan latar belakang jadi transparan
     */
//     private function makeTransparentBackground($sourcePath, $outputPath)
// {
//     $ext = strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION));

//     switch ($ext) {
//         case 'png':
//             $image = imagecreatefrompng($sourcePath);
//             break;
//         case 'jpg':
//         case 'jpeg':
//             $image = imagecreatefromjpeg($sourcePath);
//             break;
//         default:
//             throw new \Exception("Format tidak didukung: $ext");
//     }

//     $width = imagesx($image);
//     $height = imagesy($image);

//     // Buat background transparan dari tanda tangan (hilangkan putih)
//     $transparent = imagecreatetruecolor($width, $height);
//     imagesavealpha($transparent, true);
//     $alpha = imagecolorallocatealpha($transparent, 0, 0, 0, 127);
//     imagefill($transparent, 0, 0, $alpha);

//     for ($x = 0; $x < $width; $x++) {
//         for ($y = 0; $y < $height; $y++) {
//             $rgb = imagecolorat($image, $x, $y);
//             $r = ($rgb >> 16) & 0xFF;
//             $g = ($rgb >> 8) & 0xFF;
//             $b = $rgb & 0xFF;

//             if ($r > 230 && $g > 230 && $b > 230) {
//                 imagesetpixel($transparent, $x, $y, $alpha);
//             } else {
//                 $color = imagecolorallocate($transparent, $r, $g, $b);
//                 imagesetpixel($transparent, $x, $y, $color);
//             }
//         }
//     }

//     // Target ukuran akhir: seperti gambar contoh
//     $targetWidth = 768;
//     $targetHeight = 951;

//     // Hitung skala proporsional
//     $scale = min($targetWidth / $width, $targetHeight / $height);
//     $newWidth = (int)($width * $scale);
//     $newHeight = (int)($height * $scale);

//     // Buat canvas baru berukuran target dengan background transparan
//     $resized = imagecreatetruecolor($targetWidth, $targetHeight);
//     imagesavealpha($resized, true);
//     $alpha2 = imagecolorallocatealpha($resized, 0, 0, 0, 127);
//     imagefill($resized, 0, 0, $alpha2);

//     // Hitung posisi tengah
//     $dstX = (int)(($targetWidth - $newWidth) / 2);
//     $dstY = (int)(($targetHeight - $newHeight) / 2);

//     // Copy dan resize ke tengah
//     imagecopyresampled($resized, $transparent, $dstX, $dstY, 0, 0, $newWidth, $newHeight, $width, $height);

//     imagepng($resized, $outputPath);

//     imagedestroy($image);
//     imagedestroy($transparent);
//     imagedestroy($resized);
// }

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

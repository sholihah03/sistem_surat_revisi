<?php
namespace App\Http\Controllers\Warga;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PengajuanSurat;
use App\Models\PengajuanSuratLain;
use App\Models\HasilSuratTtdRw;
use Illuminate\Support\Facades\Auth;

class NotificationWargaController extends Controller
{
    public function markAsRead(Request $request)
    {
        $id = $request->input('id');
        $type = $request->input('type');

        if ($type === 'biasa') {
            PengajuanSurat::where('id_pengajuan_surat', $id)->update(['is_read' => true]);
        } elseif ($type === 'lain') {
            PengajuanSuratLain::where('id_pengajuan_surat_lain', $id)->update(['is_read' => true]);
        } elseif ($type === 'hasil') {
            HasilSuratTtdRw::where('id_hasil_surat_ttd_rw', $id)->update(['is_read' => true]);
        }
        return response()->json(['success' => true]);
    }
}


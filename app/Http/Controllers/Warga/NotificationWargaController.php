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
        $notifId = $request->id;
        $type = $request->type;

        switch ($type) {
            case 'pengajuan_surat':
                $notif = PengajuanSurat::find($notifId);
                break;
            case 'pengajuan_surat_lain':
                $notif = PengajuanSuratLain::find($notifId);
                break;
            case 'hasil_surat':
                $notif = HasilSuratTtdRw::find($notifId);
                break;
            default:
                return response()->json(['status' => 'error', 'message' => 'Tipe notifikasi tidak valid']);
        }

        if ($notif && $notif->warga_id == Auth::guard('warga')->user()->id_warga) {
            $notif->is_read = true;
            $notif->save();
            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'error', 'message' => 'Notifikasi tidak ditemukan']);
    }
}


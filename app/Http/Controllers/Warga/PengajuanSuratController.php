<?php

namespace App\Http\Controllers\Warga;

use App\Models\TujuanSurat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PengajuanSuratController extends Controller
{
    public function index(Request $request)
    {
        $query = TujuanSurat::query();

        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('nama_tujuan', 'like', '%' . $request->search . '%')
                ->orWhere('nomor_surat', 'like', '%' . $request->search . '%');
            });
        }

        $tujuanSurat = $query->get();

        return view('warga.pengajuanSurat', compact('tujuanSurat'));
    }

    public function formPengajuanSurat(){
        return view('warga.formPengajuanSurat');
    }
}

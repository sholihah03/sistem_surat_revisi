<?php

namespace App\Http\Controllers\Rt;

use App\Models\ScanKK;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RiwayatSuratWargaController extends Controller
{
    public function index(Request $request)
    {
        return view('rt.riwayatSuratWarga');
    }
}

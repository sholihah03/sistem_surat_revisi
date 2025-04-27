<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RiwayatSuratController extends Controller
{
    public function index(){
        return view('warga.riwayatSurat');
    }
}

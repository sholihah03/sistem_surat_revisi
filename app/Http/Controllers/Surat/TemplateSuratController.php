<?php

namespace App\Http\Controllers\Surat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TemplateSuratController extends Controller
{
    public function index(){
        $rw = Auth::guard('rw')->user();
        $ttdDigital = $rw->ttd_digital;
        $showModalUploadTtdRw = empty($ttdDigital);

        return view('surat.tempalteSuratPengantar', compact('ttdDigital', 'showModalUploadTtdRw'));
    }
}

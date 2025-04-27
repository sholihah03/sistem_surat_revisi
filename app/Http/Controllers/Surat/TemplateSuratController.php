<?php

namespace App\Http\Controllers\Surat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TemplateSuratController extends Controller
{
    public function index(){
        return view('surat.templateSurat');
    }
}

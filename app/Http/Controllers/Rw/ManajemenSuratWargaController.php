<?php

namespace App\Http\Controllers\Rw;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ManajemenSuratWargaController extends Controller
{
    public function index()
    {
        $profile_rw = Auth::guard('rw')->user()->profile_rw;
        return view('rw.manajemenSuratWarga', compact('profile_rw'));
    }
}

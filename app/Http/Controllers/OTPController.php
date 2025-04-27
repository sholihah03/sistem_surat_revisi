<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OTPController extends Controller
{
    public function index()
    {
        return view('auth.otp-verifikasi');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BuatPasswordController extends Controller
{
    public function index()
    {
        return view('auth.buat-password');
    }
}

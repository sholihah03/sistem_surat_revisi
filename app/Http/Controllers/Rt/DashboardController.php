<?php

namespace App\Http\Controllers\Rt;

use App\Models\ScanKK;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $pendingCount = ScanKK::where('status_verifikasi', 'pending')->count();

        // Kirimkan data ke view
        return view('rt.dashboardRt', compact('pendingCount'));
    }
}

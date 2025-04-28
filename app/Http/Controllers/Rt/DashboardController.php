<?php

namespace App\Http\Controllers\Rt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        return view('rt.dashboardRt');
    }
}

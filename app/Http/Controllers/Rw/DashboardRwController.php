<?php

namespace App\Http\Controllers\Rw;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardRwController extends Controller
{
    public function index(){
        return view('rw.dashboardRw');
    }
}

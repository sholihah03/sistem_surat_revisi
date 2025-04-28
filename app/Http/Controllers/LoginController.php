<?php

namespace App\Http\Controllers;

use App\Models\Rt;
use App\Models\Rw;
use App\Models\Wargas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Coba cari di Warga
        $warga = Wargas::where('nama_lengkap', $request->nama_lengkap)
                      ->where('email', $request->email)
                      ->first();

        if ($warga && Hash::check($request->password, $warga->password)) {
            Session::put('login_data', $warga);
            Session::put('login_as', 'warga');
            return redirect()->route('dashboardWarga');
        }

        // Coba cari di RT
        $rt = Rt::where('nama_lengkap_rt', $request->nama_lengkap)
                ->where('email_rt', $request->email)
                ->first();

        if ($rt && Hash::check($request->password, $rt->password)) {
            Session::put('login_data', $rt);
            Session::put('login_as', 'rt');
            return redirect()->route('dashboardRt');
        }

        // Coba cari di RW
        $rw = Rw::where('nama_lengkap_rw', $request->nama_lengkap)
                ->where('email_rw', $request->email)
                ->first();

        if ($rw && Hash::check($request->password, $rw->password)) {
            Session::put('login_data', $rw);
            Session::put('login_as', 'rw');
            return redirect()->route('dashboardRw');
        }

        return back()->withErrors(['login_error' => 'Nama Lengkap, Email, atau Password salah!']);
    }

    public function logoutRw()
    {
        Session::flush();
        return redirect()->route('login');
    }
}

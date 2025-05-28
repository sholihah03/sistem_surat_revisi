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

        if ($warga) {
            if (Hash::check($request->password, $warga->password)) {
                Auth::guard('warga')->login($warga);
                return redirect()->route('dashboardWarga');
            } else {
                return back()->withErrors(['login_error' => 'Password salah, silakan coba lagi!'])->withInput();
            }
        }

        // Coba cari di RT
        $rt = Rt::where('nama_lengkap_rt', $request->nama_lengkap)
                ->where('email_rt', $request->email)
                ->first();

        if ($rt) {
            if (Hash::check($request->password, $rt->password)) {
                Auth::guard('rt')->login($rt);
                return redirect()->route('dashboardRt');
            } else {
                return back()->withErrors(['login_error' => 'Password salah, silakan coba lagi!'])->withInput();
            }
        }

        // Coba cari di RW
        $rw = Rw::where('nama_lengkap_rw', $request->nama_lengkap)
                ->where('email_rw', $request->email)
                ->first();

        if ($rw) {
            if (Hash::check($request->password, $rw->password)) {
                Auth::guard('rw')->login($rw);
                return redirect()->route('dashboardRw');
            } else {
                return back()->withErrors(['login_error' => 'Password salah, silakan coba lagi!'])->withInput();
            }
        }

        // Jika tidak ditemukan di ketiga tabel
        return back()->withErrors(['login_error' => 'Akun belum terdaftar, silakan daftar terlebih dahulu!'])->withInput();
    }



    public function logout(Request $request)
    {
        if (Auth::guard('warga')->check()) {
            Auth::guard('warga')->logout();
        } elseif (Auth::guard('rt')->check()) {
            Auth::guard('rt')->logout();
        } elseif (Auth::guard('rw')->check()) {
            Auth::guard('rw')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

}

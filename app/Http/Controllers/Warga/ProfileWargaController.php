<?php

namespace App\Http\Controllers\Warga;

use App\Models\Wargas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileWargaController extends Controller
{
    public function index()
    {
        $warga = Auth::guard('warga')->user();
        $alamat = $warga->scan_Kk?->alamat;

        return view('warga.profileWarga', compact('warga', 'alamat'));
    }

    public function uploadFoto(Request $request)
    {
        $request->validate([
            'profile_foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $warga = Auth::guard('warga')->user();

        $file = $request->file('profile_foto');
        $filename = 'warga_' . $warga->id_warga . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('public/profile_warga', $filename);

        Wargas::where('id_warga', $warga->id_warga)->update([
            'profile_warga' => $filename,
        ]);

        return redirect()->back()->with('success', 'Foto profil berhasil diunggah.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'no_hp' => 'required|string|max:15',
            'email' => 'required|email|max:100',
        ]);

        $warga = Auth::guard('warga')->user();

        Wargas::where('id_warga', $warga->id_warga)->update([
            'no_hp' => '62' . $request->no_hp,
            'email' => $request->email,
        ]);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::guard('warga')->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password lama tidak sesuai.');
        }

        $user->password = bcrypt($request->new_password);
        $user->save();

        return back()->with('success', 'Password berhasil diperbarui.');
    }

}

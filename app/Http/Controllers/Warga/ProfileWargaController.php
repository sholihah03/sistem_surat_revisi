<?php

namespace App\Http\Controllers\Warga;

use App\Models\Wargas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
        /** @var \App\Models\Wargas $user */
        $user = auth()->guard('warga')->user();

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ], [
            'current_password.required' => 'Password lama wajib diisi.',
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min' => 'Password baru minimal 6 karakter.',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Tambahkan validasi custom setelah validasi utama
        $validator->after(function ($validator) use ($request, $user) {
            if (!Hash::check($request->current_password, $user->password)) {
                $validator->errors()->add('current_password', 'Password lama salah.');
            }
        });

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password berhasil diperbarui.');
    }

}

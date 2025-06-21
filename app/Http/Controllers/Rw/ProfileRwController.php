<?php

namespace App\Http\Controllers\Rw;

use App\Models\Rw;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileRwController extends Controller
{
    public function index()
    {
        $rw = Auth::guard('rw')->user();
        $profile_rw = Auth::guard('rw')->user()->profile_rw;
        $ttdDigital = $rw->ttd_digital;
        $showModalUploadTtdRw = empty($ttdDigital);
        return view('rw.profileRw', compact('rw', 'profile_rw', 'ttdDigital', 'showModalUploadTtdRw'));
    }

    public function updateData(Request $request)
    {
        $request->validate([
            'no_hp_rw' => 'required|string|max:15',
            'email_rw' => 'required|email',
        ]);

        $rw = Auth::guard('rw')->user();
        Rw::where('id_rw', $rw->id_rw)->update([
            'no_hp_rw' => '62' . $request->no_hp_rw,
            'email_rw' => $request->email_rw,
        ]);

        return redirect()->back()->with('dataSuccess', 'Data berhasil diperbarui.');
    }

    public function updateProfileRwImage(Request $request)
    {
        $request->validate([
            'profile_rw' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::guard('rw')->user();

        // Hapus foto lama jika ada
        if ($user->profile_rw) {
            Storage::delete('public/profile_rw/' . $user->profile_rw);
        }

        // Simpan file baru
        $filename = time() . '.' . $request->file('profile_rw')->extension();
        $request->file('profile_rw')->storeAs('public/profile_rw', $filename);

        // Update kolom di database
        $rw = Rw::find($user->id_rw);
        $rw->update([
            'profile_rw' => $filename,
        ]);

        return redirect()->back()->with('uploadSuccess', 'Foto profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $rw = auth()->guard('rw')->user(); // pastikan auth sudah login RW

        if (!Hash::check($request->current_password, $rw->password)) {
            return back()->withErrors(['current_password' => 'Password lama salah.']);
        }

        $rw->password = Hash::make($request->new_password);
        $rw->save();

        return back()->with('passwordUbahSuccess', 'Password berhasil diperbarui.');
    }
}

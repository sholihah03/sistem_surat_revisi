<?php

namespace App\Http\Controllers\Rt;

use App\Models\Rt;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileRtController extends Controller
{
    public function index()
    {
        $rt = Auth::guard('rt')->user();
        $profile_rt = Auth::guard('rt')->user()->profile_rt;
        $ttdDigital = $rt->ttd_digital;
        $showModalUploadTtd = empty($ttdDigital);
        // dd(Auth::guard('rt')->user());
        return view('rt.profileRt', compact('rt', 'profile_rt', 'ttdDigital', 'showModalUploadTtd'));
    }

    public function updateData(Request $request)
    {
        $request->validate([
            'no_hp_rt' => 'required|string|max:15',
            'email_rt' => 'required|email',
        ]);

        $rt = Auth::guard('rt')->user();
        Rt::where('id_rt', $rt->id_rt)->update([
            'no_hp_rt' => '62' . $request->no_hp_rt,
            'email_rt' => $request->email_rt,
        ]);

        return redirect()->back()->with('dataSuccess', 'Data berhasil diperbarui.');
    }

    public function updateProfileRtImage(Request $request)
    {
        $request->validate([
            'profile_rt' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::guard('rt')->user();

        // Hapus foto lama jika ada
        if ($user->profile_rt) {
            Storage::delete('public/profile_rt/' . $user->profile_rt);
        }

        // Simpan file baru
        $filename = time() . '.' . $request->file('profile_rt')->extension();
        $request->file('profile_rt')->storeAs('public/profile_rt', $filename);

        // Update kolom di database
        $rt = Rt::find($user->id_rt);
        $rt->update([
            'profile_rt' => $filename,
        ]);

        return redirect()->back()->with('uploadSuccess', 'Foto profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        /** @var \App\Models\Rt $rt */
        $rt = auth()->guard('rt')->user();

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
        $validator->after(function ($validator) use ($request, $rt) {
            if (!Hash::check($request->current_password, $rt->password)) {
                $validator->errors()->add('current_password', 'Password lama salah.');
            }
        });

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Update password
        $rt->password = Hash::make($request->new_password);
        $rt->save();

        return back()->with('passwordSuccess', 'Password berhasil diperbarui.');
    }

}

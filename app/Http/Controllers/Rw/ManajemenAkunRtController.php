<?php

namespace App\Http\Controllers\Rw;

use App\Models\Rt;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class ManajemenAkunRtController extends Controller
{

    // public function index(){
    //     return view('rw.manajemenAkunRt');
    // }
    public function index()
    {
        $rts = Rt::all();
        return view('rw.manajemenAkunRt', compact('rts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_rt' => 'required|string|max:255',
            'nama_lengkap_rt' => 'required|string|max:255',
            'no_hp_rt' => 'required|string|max:255', // perbaiki: nama_hp_rt -> no_hp_rt
            'email_rt' => 'required|email|max:255',
        ]);

        Rt::create([
            'rw_id' => auth()->user()->id_rw,
            'no_rt' => $request->no_rt,
            'nama_lengkap_rt' => $request->nama_lengkap_rt,
            'no_hp_rt' => $request->no_hp_rt, // <-- AMBIL DARI REQUEST USER
            'email_rt' => $request->email_rt,
            'password' => Hash::make('passworddefault'),
        ]);

        return redirect()->back()->with('success', 'Akun RT berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $rt = Rt::findOrFail($id);

        $request->validate([
            'no_rt' => 'required|string|max:255',
            'nama_lengkap_rt' => 'required|string|max:255',
            'no_hp_rt' => 'required|string|max:255',
            'email_rt' => 'required|email|max:255',
        ]);

        $rt->update([
            'no_rt' => $request->no_rt,
            'nama_lengkap_rt' => $request->nama_lengkap_rt,
            'no_hp_rt' => $request->no_hp_rt,
            'email_rt' => $request->email_rt,
        ]);

        return redirect()->back()->with('success', 'Akun RT berhasil diupdate.');
    }

    public function destroy($id)
    {
        $rt = Rt::findOrFail($id);
        $rt->delete();

        return redirect()->back()->with('success', 'Akun RT berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers\Rw;

use App\Models\Rt;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class ManajemenAkunRtController extends Controller
{
    public function index(Request $request)
    {
        $rw = Auth::guard('rw')->user();
        $profile_rw = Auth::guard('rw')->user()->profile_rw;
        $no_rw = Auth::guard('rw')->user()->no_rw;
        $query = Rt::query();
        $ttdDigital = $rw->ttd_digital;

        $showModalUploadTtdRw = empty($ttdDigital);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('no_rt', 'like', "%{$search}%")
                  ->orWhere('nama_lengkap_rt', 'like', "%{$search}%");
        }

        $rts = $query->get();

        return view('rw.manajemenAkunRt', compact('rts', 'profile_rw', 'no_rw', 'ttdDigital', 'showModalUploadTtdRw'));
    }

    public function akunIndex(Request $request)
    {
        $rw = Auth::guard('rw')->user();
        $rwId = $rw->id_rw;
        $no_rw = $rw->no_rw;

        $ttdDigital = $rw->ttd_digital;
        $showModalUploadTtdRw = empty($ttdDigital);

        $search = $request->input('search');

        $rtQuery = Rt::with('wargas') // eager load relasi warga
            ->where('rw_id', $rwId);

        if ($search) {
            $rtQuery->where(function ($q) use ($search) {
                $q->where('no_rt', 'like', "%$search%")
                ->orWhere('nama_lengkap_rt', 'like', "%$search%")
                ->orWhereHas('wargas', function ($w) use ($search) {
                    $w->where('nama_lengkap', 'like', "%$search%");
                });
            });
        }

        $rtList = $rtQuery->get();

        return view('rw.akunRT', compact('rtList', 'no_rw', 'ttdDigital', 'showModalUploadTtdRw'));
    }


    public function store(Request $request)
{
    $request->validate([
        'no_rt' => 'required|string|max:255',
        'nama_lengkap_rt' => 'required|string|max:255',
        'no_hp_rt' => 'required|string|max:255',
        'email_rt' => 'required|email|max:255',
        'password' => 'required|min:5',
    ]);

    // Mengambil data pengguna yang sedang login menggunakan Auth Guard
    $rw = Auth::guard('rw')->user();  // Mengambil data RW yang sedang login

    if (!$rw) {
        return redirect()->route('login')->withErrors(['msg' => 'Silakan login dulu']);
    }

    // Menambahkan data RT menggunakan ID RW yang sedang login
    Rt::create([
        'rw_id' => $rw->id_rw,
        'no_rt' => $request->no_rt,
        'nama_lengkap_rt' => $request->nama_lengkap_rt,
        'no_hp_rt' => '62' . $request->no_hp_rt,
        'email_rt' => $request->email_rt,
        'password' => Hash::make($request->password), // Enkripsi password
    ]);

    // Menambahkan pesan sukses ke flash session
    session()->flash('success', 'Akun RT berhasil ditambahkan!');

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
            'no_hp_rt' => '62' . $request->no_hp_rt,
            'email_rt' => $request->email_rt,
        ]);
        // Menambahkan pesan sukses ke flash session
        session()->flash('success', 'Akun RT berhasil diperbarui!');

        return redirect()->back()->with('success', 'Akun RT berhasil diupdate.');
    }

    public function destroy($id)
    {
        $rt = Rt::findOrFail($id);
        $rt->delete();

        // Menambahkan pesan sukses ke flash session
        session()->flash('success', 'Akun RT berhasil dihapus!');

        return redirect()->route('manajemenAkunRt')->with('success', 'Akun RT berhasil dihapus.');
    }
}

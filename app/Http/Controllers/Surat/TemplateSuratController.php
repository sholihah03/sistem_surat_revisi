<?php

namespace App\Http\Controllers\Surat;

use App\Models\KopSurat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TemplateSuratController extends Controller
{
    public function index(){
        $rw = Auth::guard('rw')->user();
        $ttdDigital = $rw->ttd_digital;
        $showModalUploadTtdRw = empty($ttdDigital);

        $kopSurat = KopSurat::first();

        return view('surat.tempalteSuratPengantar', compact('ttdDigital', 'showModalUploadTtdRw', 'kopSurat'));
    }

        public function store(Request $request)
    {
        $request->validate([
            'nama_jalan' => 'required|string|max:255',
            'no_kantor'  => 'required|string|max:255',
            'no_telepon' => 'required|string|max:50',
            'kode_pos'   => 'required|string|max:10',
            'email'      => 'required|email|max:255',
        ]);

        KopSurat::create($request->all());

        return redirect()->back()->with('success', 'Kop surat berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_jalan' => 'required|string|max:255',
            'no_kantor'  => 'required|string|max:255',
            'no_telepon' => 'required|string|max:50',
            'kode_pos'   => 'required|string|max:10',
            'email'      => 'required|email|max:255',
        ]);

        $kopSurat = KopSurat::findOrFail($id);
        $kopSurat->update($request->all());

        return redirect()->back()->with('success', 'Kop surat berhasil diperbarui.');
    }


}

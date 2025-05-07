<?php

namespace App\Http\Controllers;

use App\Models\TujuanSurat;
use Illuminate\Http\Request;

class TujuanSuratController extends Controller
{
    public function index()
    {
        $tujuanSurat = TujuanSurat::all(); // Mengambil semua data tujuan surat
        return view('rw.tujuanSurat', compact('tujuanSurat'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_tujuan' => 'required|string|max:255',
            'nomor_surat' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:255',
        ]);

        TujuanSurat::create($request->all());

        return redirect()->route('tujuanSurat.index')->with('success', 'Tujuan Surat berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $tujuanSurat = TujuanSurat::findOrFail($id);
        return response()->json($tujuanSurat);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_tujuan' => 'required|string|max:255',
            'nomor_surat' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:255',
        ]);

        $tujuanSurat = TujuanSurat::findOrFail($id);
        $tujuanSurat->update($request->all());

        return redirect()->route('tujuanSurat.index')->with('success', 'Tujuan Surat berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $tujuanSurat = TujuanSurat::findOrFail($id);
        $tujuanSurat->delete();

        return redirect()->route('tujuanSurat.index')->with('success', 'Tujuan Surat berhasil dihapus.');
    }
}

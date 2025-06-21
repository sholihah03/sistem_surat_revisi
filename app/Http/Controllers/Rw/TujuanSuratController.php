<?php

namespace App\Http\Controllers\Rw;

use App\Models\TujuanSurat;
use Illuminate\Http\Request;
use App\Models\PersyaratanSurat;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TujuanSuratController extends Controller
{
    public function index(Request $request)
    {
        $profile_rw = Auth::guard('rw')->user()->profile_rw;
        $rw = Auth::guard('rw')->user();
        $ttdDigital = $rw->ttd_digital;
        $showModalUploadTtdRw = empty($ttdDigital);
        $query = TujuanSurat::query();

        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('nama_tujuan', 'like', '%' . $request->search . '%')
                  ->orWhere('nomor_surat', 'like', '%' . $request->search . '%');
            });
        }

        $tujuanSurat = $query->with('persyaratan')->get();

        return view('rw.tujuanSurat', compact('tujuanSurat', 'profile_rw', 'ttdDigital', 'showModalUploadTtdRw'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_tujuan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'nomor_surat' => 'required|string|max:100',
            'status_populer' => 'required|boolean',
            'persyaratan.*' => 'nullable|string|max:255',
            'keterangan.*' => 'nullable|in:janda,duda,kawin,belum',
        ]);

        $tujuanSurat = TujuanSurat::create([
            'nama_tujuan' => $request->nama_tujuan,
            'deskripsi' => $request->deskripsi,
            'nomor_surat' => $request->nomor_surat,
            'status_populer' => $request->status_populer,
        ]);

        // Simpan persyaratan jika ada
        if ($request->has('persyaratan') && is_array($request->persyaratan)) {
            foreach ($request->persyaratan as $index => $item) {
                if (!empty($item)) {
                    PersyaratanSurat::create([
                        'tujuan_surat_id' => $tujuanSurat->id_tujuan_surat,
                        'nama_persyaratan' => $item,
                        'keterangan' => $request->keterangan[$index] ?? null,
                    ]);
                }
            }
        }


        return redirect()->back()->with('success', 'Tujuan surat berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $tujuan = TujuanSurat::findOrFail($id);
        $tujuan->update([
            'nama_tujuan' => $request->nama_tujuan,
            'deskripsi' => $request->deskripsi,
            'nomor_surat' => $request->nomor_surat,
            'status_populer' => $request->status_populer,
        ]);

        // Hapus persyaratan yang ditandai dihapus
        if ($request->has('persyaratan_deleted')) {
            PersyaratanSurat::whereIn('id_persyaratan_surat', $request->persyaratan_deleted)->delete();
        }

        // Update atau tambah persyaratan baru
        $ids = $request->persyaratan_id ?? [];
        $names = $request->persyaratan;
        $keterangans = $request->keterangan;

        foreach ($names as $index => $name) {
            $keterangan = $keterangans[$index] ?? null;
            if (isset($ids[$index])) {
                // Update
                PersyaratanSurat::where('id_persyaratan_surat', $ids[$index])
                    ->update(['nama_persyaratan' => $name, 'keterangan' => $keterangan,]);
            } else {
                // Tambah baru
                PersyaratanSurat::create([
                    'tujuan_surat_id' => $id,
                    'nama_persyaratan' => $name,
                    'keterangan' => $keterangan,
                ]);
            }
        }

        return redirect()->route('tujuanSurat')->with('success', 'Data berhasil diperbarui!');
    }



    public function destroy($id)
    {
        $tujuanSurat = TujuanSurat::findOrFail($id);
        $tujuanSurat->delete();

        return redirect()->back()->with('success', 'Tujuan surat berhasil dihapus.');
    }
}

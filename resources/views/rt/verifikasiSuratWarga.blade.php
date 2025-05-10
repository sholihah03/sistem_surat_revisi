@extends('rt.dashboardRt')

@section('content')
<div class="container mx-auto p-4 mb-6 pt-20">
    <h1 class="text-2xl font-bold mb-6">Verifikasi Surat Warga</h1>

    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full text-sm text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3">Nama</th>
                    <th class="p-3">Tujuan</th>
                    <th class="p-3">Jenis</th>
                    <th class="p-3">Nomor Surat</th>
                    <th class="p-3">Tempat/ Tanggal Lahir</th>
                    <th class="p-3">Nomor KTP</th>
                    <th class="p-3">Status Perkawinan</th>
                    <th class="p-3">Kebangsaan/ Agama</th>
                    <th class="p-3">Pekerjaan</th>
                    <th class="p-3">Alamat</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $jumlahData = $pengajuanSurat->count() + $pengajuanSuratLain->count();
                @endphp

                @if ($jumlahData === 0)
                    <tr>
                        <td colspan="5" class="p-4 text-center text-gray-500">Data pengajuan belum ada.</td>
                    </tr>
                @else
                    @foreach($pengajuanSurat as $surat)
                    <tr class="border-t">
                        <td class="p-3">{{ $surat->warga->nama_lengkap }}</td>
                        <td class="p-3">{{ $surat->tujuanSurat->nama_tujuan ?? '-' }}</td>
                        <td class="p-3">Biasa</td>
                        <td class="p-3">{{ $surat->tujuanSurat->nomor_surat }}</td>
                        <td class="p-3">{{ $surat->tempat_lahir }}, {{ $surat->tanggal_lahir}}</td>
                        <td class="p-3">{{ $surat->warga->nik }}</td>
                        <td class="p-3">{{ $surat->status_perkawinan }}</td>
                        <td class="p-3">{{ $surat->agama }}</td>
                        <td class="p-3">{{ $surat->pekerjaan }}</td>
                        <td class="p-3">{{ $surat->warga->scan_kk->alamat->nama_jalan }}</td>
                        <td class="p-3">{{ ucfirst($surat->status) }}</td>
                        <td class="p-3 flex gap-2">
                            <button onclick="showModal('setuju', {{ $surat->id_pengajuan_surat }}, 'biasa')" class="px-3 py-1 bg-green-500 text-white rounded">Setujui</button>
                            <button onclick="showModal('tolak', {{ $surat->id_pengajuan_surat }}, 'biasa')" class="px-3 py-1 bg-red-500 text-white rounded">Tolak</button>
                        </td>
                    </tr>
                    @endforeach

                    @foreach($pengajuanSuratLain as $surat)
                    <tr class="border-t">
                        <td class="p-3">{{ $surat->warga->nama_lengkap }}</td>
                        <td class="p-3">{{ $surat->tujuan_manual }}</td>
                        <td class="p-3">Lain</td>
                        <td class="p-3">{{ $surat->nomor_surat_pengajuan_lain ?? 'Belum Memiliki Nomor Surat' }}</td>
                        <td class="p-3">{{ $surat->tempat_lahir_pengaju_lain }}, {{ $surat->tanggal_lahir_pengaju_lain }}</td>
                        <td class="p-3">{{ $surat->warga->nik }}</td>
                        <td class="p-3">{{ $surat->status_perkawinan_pengaju_lain }}</td>
                        <td class="p-3">{{ $surat->agama_pengaju_lain }}</td>
                        <td class="p-3">{{ $surat->pekerjaan_pengaju_lain }}</td>
                        <td class="p-3">{{ $surat->warga->scan_kk->alamat->nama_jalan }}</td>
                        <td class="p-3">{{ ucfirst($surat->status_pengajuan_lain) }}</td>
                        <td class="p-3 flex gap-2">
                            <button onclick="showModal('setuju', {{ $surat->id_pengajuan_surat_lain }}, 'lain')" class="px-3 py-1 bg-green-500 text-white rounded">Setujui</button>
                            <button onclick="showModal('tolak', {{ $surat->id_pengajuan_surat_lain }}, 'lain')" class="px-3 py-1 bg-red-500 text-white rounded">Tolak</button>
                        </td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
    <div class="bg-white p-6 rounded shadow w-full max-w-md">
        <form id="modalForm" method="POST" action="{{ route('verifikasiSuratProses') }}">
            @csrf
            <input type="hidden" name="id" id="formId">
            <input type="hidden" name="jenis" id="formJenis">
            <input type="hidden" name="aksi" id="formAksi">

            <div id="inputNomorSurat" class="mb-4 hidden">
                <label class="block mb-1">Nomor Surat</label>
                <input type="text" name="nomor_surat" class="w-full border rounded px-3 py-2">
            </div>

            <div id="inputAlasanTolak" class="mb-4 hidden">
                <label class="block mb-1">Alasan Penolakan</label>
                <textarea name="alasan_penolakan" class="w-full border rounded px-3 py-2"></textarea>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 rounded">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function showModal(aksi, id, jenis) {
    document.getElementById('formId').value = id;
    document.getElementById('formAksi').value = aksi;
    document.getElementById('formJenis').value = jenis;

    document.getElementById('inputNomorSurat').classList.add('hidden');
    document.getElementById('inputAlasanTolak').classList.add('hidden');

    if (aksi === 'setuju' && jenis === 'lain') {
        document.getElementById('inputNomorSurat').classList.remove('hidden');
    } else if (aksi === 'tolak') {
        document.getElementById('inputAlasanTolak').classList.remove('hidden');
    }

    document.getElementById('modal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('modal').classList.add('hidden');
}
</script>
@endsection

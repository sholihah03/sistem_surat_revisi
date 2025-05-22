@extends('rw.dashboardRw')

@section('content')
<h1 class="text-2xl pt-20 md:text-3xl font-bold text-gray-800 mb-6">Manajemen Surat Warga</h1>

@if(session('success'))
<div id="successModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 sm:p-8 relative w-[90%] max-w-md sm:max-w-lg text-center animate-scale">
        <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>
        <div class="flex justify-center mb-6">
            <img src="https://img.icons8.com/color/96/000000/ok--v1.png" alt="Success Icon" class="w-20 h-20">
        </div>
        <h2 class="text-2xl font-bold mb-4 text-gray-800 whitespace-nowrap">
            {{ session('success') }}
        </h2>
        <button onclick="closeModal()" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 rounded-lg">
            Tutup
        </button>
    </div>
</div>
@endif

<div class="overflow-x-auto max-h-[500px] overflow-y-auto border rounded-lg shadow">
    <table class="min-w-full bg-white border rounded shadow">
        <thead class="bg-green-100 sticky top-0 z-10">
            <tr class="text-left">
                <th class="px-4 py-2">No</th>
                <th class="px-4 py-2">Rt</th>
                <th class="px-4 py-2">Nama</th>
                <th class="px-4 py-2">Jenis</th>
                <th class="px-4 py-2">Tujuan</th>
                <th class="px-4 py-2">Nomor Surat</th>
                <th class="px-4 py-2">Nomor KTP</th>
                <th class="px-4 py-2">Pekerjaan</th>
                <th class="px-4 py-2">Alamat</th>
                <th class="px-4 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($surats as $index => $surat)
                @php
                    $isSuratBiasa = $surat->jenis === 'biasa';
                    $pengajuan = $isSuratBiasa ? $surat->pengajuanSurat : $surat->pengajuanSuratLain;
                    $warga = $pengajuan->warga ?? null;
                @endphp
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $index + 1 }}</td>
                    <td class="px-4 py-2">{{ $warga->rt->no_rt ?? '-' }}</td>
                    <td class="px-4 py-2">{{ $warga->nama_lengkap ?? '-' }}</td>
                    <td class="px-4 py-2">{{ ucfirst($surat->jenis) }}</td>
                    <td class="px-4 py-2">{{ $isSuratBiasa ? ($pengajuan->tujuanSurat->nama_tujuan ?? '-') : ($pengajuan->tujuan_manual ?? '-') }}</td>
                    <td class="px-4 py-2">
                        {{ $isSuratBiasa ? ($pengajuan->tujuanSurat->nomor_surat ?? '-') : ($pengajuan->nomor_surat_pengajuan_lain ?? '-') }}
                    </td>
                    <td class="px-4 py-2">{{ $warga->nik ?? '-' }}</td>
                    <td class="px-4 py-2">
                        {{ $isSuratBiasa ? ($pengajuan->pekerjaan ?? '-') : ($pengajuan->pekerjaan_pengaju_lain ?? '-') }}
                    </td>
                    <td class="px-4 py-2">
                        @if($isSuratBiasa)
                            {{ $pengajuan->alamat ?? $warga->scan_KK->alamat->nama_jalan ?? '-' }}
                        @else
                            {{ $pengajuan->alamat_pengaju_lain ?? $warga->scan_KK->alamat->nama_jalan ?? '-' }}
                        @endif
                    </td>
                    <td class="px-4 py-2">
                        <form method="POST" action="{{ route('rw.setujuiSurat') }}">
                            @csrf
                            <input type="hidden" name="pengajuan_id" value="{{ $surat->pengajuan_id }}">
                            <input type="hidden" name="jenis" value="{{ $surat->jenis }}">
                            <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                                Setujui
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center text-gray-500 py-4"><strong>Belum ada surat yang masuk</strong></td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
<script>
    function closeModal() {
        const modal = document.getElementById('successModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endsection

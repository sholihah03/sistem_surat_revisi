@extends('rw.dashboardRw')

@section('content')
<h1 class="text-2xl pt-20 md:text-3xl font-bold text-gray-800 mb-6">Riwayat Surat Warga</h1>

@if($hasilSurat->isEmpty())
    <p class="text-center text-gray-500 py-4">Belum ada surat yang disetujui dan tersimpan.</p>
@else
<div class="overflow-x-auto max-h-[500px] overflow-y-auto border rounded-lg shadow">
    <table class="min-w-full bg-white border rounded shadow">
        <thead class="bg-blue-100 sticky top-0 z-10">
            <tr class="text-left">
                <th class="px-4 py-2">No</th>
                <th class="px-4 py-2">Nama Warga</th>
                <th class="px-4 py-2">Jenis Surat</th>
                <th class="px-4 py-2">Nama Tujuan Surat</th>
                <th class="px-4 py-2">Nomor Surat</th>
                <th class="px-4 py-2">Tanggal Disetujui</th>
                <th class="px-4 py-2">Hasil Surat</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($hasilSurat as $item)
            <tr>
                <td class="px-4 py-2">{{ $no++ }}</td>
                <td class="px-4 py-2">
                    @if($item->jenis === 'biasa')
                        {{ $item->pengajuanSurat->warga->nama_lengkap ?? '-' }}
                    @else
                        {{ $item->pengajuanSuratLain->warga->nama_lengkap ?? '-' }}
                    @endif
                </td>
                <td class="px-4 py-2">{{ ucfirst($item->jenis) }}</td>
                <td class="px-4 py-2">{{ $item->created_at->format('d M Y') }}</td>
                <td class="px-4 py-2">
                    <a href="{{ asset('storage/' . $item->file_surat) }}" target="_blank" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded mr-2">Lihat</a>
                    <a href="{{ asset('storage/' . $item->file_surat) }}" download class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded">Unduh</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
@endsection

@extends('rt.dashboardRt')

@section('content')
<div class="container mx-auto p-4 mb-6 pt-20">
    <h1 class="text-2xl font-bold mb-6">Histori Akun Kadaluwarsa</h1>

    <div class="overflow-x-auto bg-white rounded-lg shadow-md">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-gray-700">No</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Nama Warga</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Nama Kepala Keluarga</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-700">NIK</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-700">No KK</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-700">No HP</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Email</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Alamat</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Tgl Kadaluwarsa</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($dataKadaluwarsa as $index => $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2">{{ $index + 1 }}</td>
                    <td class="px-4 py-2">{{ $item->nama_lengkap }}</td>
                    <td class="px-4 py-2">{{ $item->nama_kepala_keluarga }}</td>
                    <td class="px-4 py-2">{{ $item->nik }}</td>
                    <td class="px-4 py-2">{{ $item->no_kk }}</td>
                    <td class="px-4 py-2">{{ $item->no_hp }}</td>
                    <td class="px-4 py-2">{{ $item->email }}</td>
                    <td class="px-4 py-2">
                        {{ $item->nama_jalan }}, RW {{ $item->rw }}, Kel. {{ $item->kelurahan }},
                        Kec. {{ $item->kecamatan }}, {{ $item->kabupaten_kota }}, {{ $item->provinsi }},
                        {{ $item->kode_pos }}
                    </td>
                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-4 py-4 text-center text-gray-500">Tidak ada data kadaluwarsa.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

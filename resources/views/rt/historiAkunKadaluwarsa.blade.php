@extends('rt.dashboardRt')

@section('content')
<div class="container mx-auto p-4 mb-6 pt-20">
    <h1 class="text-2xl font-bold mb-2">Histori Akun Expired</h1>
    <p class="text-lg text-gray-600 mb-6">Berikut adalah daftar akun warga yang telah melewati masa aktifnya dan dinyatakan expired.</p>

    <form method="GET" action="{{ route('historiAkunKadaluwarsa') }}" class="relative w-full max-w-sm mb-6">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8" />
                <line x1="21" y1="21" x2="16.65" y2="16.65" />
            </svg>
        </span>
        <input type="text" name="search" id="searchInput"
            value="{{ request('search') }}"
            placeholder="Cari nama warga atau NIK..."
            class="pl-10 pr-4 py-2 border rounded w-full focus:outline-none focus:ring-2 focus:ring-green-400 text-sm" />
    </form>

    <div class="bg-white bg-opacity-80 p-4 md:p-6 rounded-xl w-full">
        <div class="overflow-x-auto bg-white rounded-lg">
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
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Tgl Expired</th>
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
                        <td colspan="9" class="border border-gray-300 px-4 py-4 text-center text-gray-500">Tidak Ada Data Expired.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

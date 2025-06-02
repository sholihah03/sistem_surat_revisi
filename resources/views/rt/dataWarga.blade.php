@extends('rt.dashboardRt')

@section('content')
<div class="container mx-auto p-4 mb-6 pt-20">
    <h1 class="text-2xl font-bold mb-2">Data Warga</h1>
    <p class="text-gray-600 text-lg mb-6">
        Halaman ini menampilkan daftar warga yang berada dalam wilayah RT Anda. Gunakan kolom pencarian di bawah untuk mencari warga berdasarkan nama.
    </p>

    <form method="GET" action="{{ route('dataWarga') }}" class="relative w-full max-w-sm mb-6">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8" />
                <line x1="21" y1="21" x2="16.65" y2="16.65" />
            </svg>
        </span>
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Cari nama warga..."
            class="pl-10 pr-4 py-2 border rounded w-full max-w-md focus:outline-none focus:ring-2 focus:ring-green-400 text-sm"
        />
    </form>

    <div class="bg-white bg-opacity-80 p-4 md:p-6 rounded-xl w-full">
        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <table class="min-w-full text-sm text-left table-fixed">
                <thead class="bg-gray-100 sticky top-0 z-10">
                    <tr>
                        <th class="p-3 w-12">No</th>
                        <th class="p-3 w-40">Nama</th>
                        <th class="p-3 w-40">Nama Kepala Keluarga</th>
                        <th class="p-3 w-40">Nomor KK</th>
                        <th class="p-3 w-48">Nomor Induk Kependudukan</th>
                        <th class="p-3 w-64">Alamat</th>
                    </tr>
                </thead>
            </table>
            <div class="max-h-[280px] overflow-y-auto">
                <table class="min-w-full text-sm text-left table-fixed">
                    <tbody>
                        @forelse ($wargas as $index => $warga)
                            <tr class="border-b">
                                <td class="p-3 w-12">{{ $index + 1 }}</td>
                                <td class="p-3 w-40">{{ $warga->nama_lengkap }}</td>
                                <td class="p-3 w-40">{{ $warga->scan_Kk->nama_kepala_keluarga ?? '-' }}</td>
                                <td class="p-3 w-40">{{ $warga->scan_Kk->no_kk_scan ?? $warga->no_kk }}</td>
                                <td class="p-3 w-48">{{ $warga->nik }}</td>
                                <td class="p-3 w-64">
                                    @if ($warga->scan_Kk && $warga->scan_Kk->alamat)
                                        {{ $warga->scan_Kk->alamat->nama_jalan }},
                                        RT {{ $warga->scan_Kk->alamat->rt_alamat }},
                                        RW {{ $warga->scan_Kk->alamat->rw_alamat }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center p-3 text-gray-500">Tidak ada data warga.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

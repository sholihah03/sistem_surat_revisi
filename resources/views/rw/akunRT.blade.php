@extends('rw.dashboardRw')

@section('content')
<h1 class="text-2xl pt-20 md:text-3xl font-bold text-gray-800 mb-2">Akun RT</h1>
<p class="text-gray-600 mb-6 text-lg">
    Halaman ini menampilkan daftar akun Ketua RT yang berada di lingkungan RW {{ $no_rw }}, beserta data warga yang berada di bawah masing-masing RT tersebut. Gunakan fitur pencarian untuk mempermudah menemukan data berdasarkan nomor RT, nama Ketua RT, atau nama warga.
</p>

<form method="GET" action="{{ route('akunRT') }}" class="relative w-full sm:w-80 mb-4">
    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
            viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8" />
            <line x1="21" y1="21" x2="16.65" y2="16.65" />
        </svg>
    </span>
    <input type="text" name="search" id="searchInput"
        value="{{ request('search') }}"
        placeholder="Cari no RT, nama RT, atau nama warga..."
        class="pl-10 pr-4 py-2 border rounded w-full focus:outline-none focus:ring-2 focus:ring-green-400" />
</form>


<!-- Tabel Daftar Akun RT -->
<div class="overflow-x-auto max-h-[500px] overflow-y-auto border rounded-lg shadow">
    <table class="min-w-full bg-white border rounded shadow">
        <thead class="bg-green-100 sticky top-0 z-10">
            <tr class="text-left">
                <th class="px-4 py-2">No</th>
                <th class="px-4 py-2">Rt</th>
                <th class="px-4 py-2">Nama RT</th>
                <th class="px-4 py-2">Nomer WhatsApp</th>
                <th class="px-4 py-2">Email</th>
                <th class="px-4 py-2">Nama Warga</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($rtList as $rt)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $no++ }}</td>
                    <td class="px-4 py-2">RT {{ $rt->no_rt }}</td>
                    <td class="px-4 py-2">{{ $rt->nama_lengkap_rt }}</td>
                    <td class="px-4 py-2">{{ $rt->no_hp_rt }}</td>
                    <td class="px-4 py-2">{{ $rt->email_rt }}</td>
                    <td class="px-4 py-2">
                        @if($rt->wargas->count() > 0)
                            <ul class="list-disc list-inside">
                                @foreach($rt->wargas as $warga)
                                    <li>{{ $warga->nama_lengkap }}</li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-gray-400">Belum ada warga</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

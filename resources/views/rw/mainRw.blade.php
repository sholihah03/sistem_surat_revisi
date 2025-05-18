{{-- <main class="flex-1 p-4 md:p-8 overflow-x-auto"> --}}
@extends('rw.dashboardRw')

@section('content')
    <h1 class="text-2xl pt-20 md:text-3xl font-bold text-gray-800 mb-6">Selamat Datang, Admin RW!</h1>

    <!-- Kartu Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="p-4 md:p-6 bg-blue-100 rounded-xl shadow border-l-4 border-blue-500">
            <h2 class="text-base md:text-lg font-semibold mb-2">📑 Total Surat Masuk</h2>
            <p class="text-xl md:text-2xl font-bold text-blue-700">{{ $totalSuratMasuk }}</p>
        </div>
    <div class="p-4 md:p-6 bg-green-100 rounded-xl shadow border-l-4 border-green-500">
        <h2 class="text-base md:text-lg font-semibold mb-2">✅ Surat Disetujui</h2>
        <p class="text-xl md:text-2xl font-bold text-green-700">{{ $totalSuratDisetujui }}</p>
    </div>
    <div class="p-4 md:p-6 bg-yellow-100 rounded-xl shadow border-l-4 border-yellow-500">
        <h2 class="text-base md:text-lg font-semibold mb-2">👥 Total Warga Terdaftar</h2>
        <p class="text-xl md:text-2xl font-bold text-yellow-700">{{ $totalWargaTerdaftar }}</p>
    </div>
    </div>

    <!-- Daftar Pengajuan Terbaru -->
    <div class="bg-white bg-opacity-80 p-4 md:p-6 rounded-xl shadow w-full overflow-x-auto">
        <h2 class="text-lg md:text-xl font-bold text-gray-800 mb-4">🏘️ Status Pengajuan dari RT</h2>
        <div class="overflow-x-auto">
            <div class="min-w-full inline-block align-middle">
                <div class="overflow-x-auto border rounded-lg">
                <table class="min-w-full text-xs md:text-sm text-gray-700">
                    <thead class="bg-gray-100">
                    <tr>
                        <th class="px-2 md:px-4 py-2 text-center">Rt</th>
                        <th class="px-2 md:px-4 py-2 text-center">Jumlah Surat Masuk</th>
                        <th class="px-2 md:px-4 py-2 text-center">Surat Disetujui</th>
                        <th class="px-2 md:px-4 py-2 text-center">Surat Ditolak</th>
                        <th class="px-2 md:px-4 py-2 text-center">Aksi</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($statusPengajuanPerRt as $status)
                        <tr class="hover:bg-gray-50">
                            <td class="px-2 md:px-4 py-2 whitespace-nowrap text-center align-middle">RT {{ $status['no_rt'] }}</td>
                            <td class="px-2 md:px-4 py-2 whitespace-nowrap text-center align-middle">{{ $status['total_pengajuan'] }}</td>
                            <td class="px-2 md:px-4 py-2 whitespace-nowrap text-center align-middle">{{ $status['total_disetujui'] }}</td>
                            <td class="px-2 md:px-4 py-2 whitespace-nowrap text-center align-middle">{{ $status['total_ditolak'] }}</td>
                            <td class="px-2 md:px-4 py-2 text-center align-middle">
                                <a href="#" class="text-green-500 hover:underline">Detail</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifikasi -->
    <div class="bg-yellow-100 p-4 rounded-lg shadow text-yellow-800 flex items-center">
        <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span><strong>Info:</strong> Ada 5 surat yang perlu ditandatangani hari ini!</span>
    </div>
{{-- </main> --}}
@endsection

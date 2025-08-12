@extends('rw.dashboardRw')

@section('content')
    <h1 class="text-2xl pt-20 md:text-3xl font-bold text-gray-800 mb-6">Selamat Datang, Admin RW {{ $rw->no_rw }}!</h1>

    <!-- Kartu Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <a href="{{ route('riwayatSuratRw') }}" class="block p-4 md:p-6 bg-blue-100 rounded-xl shadow border-l-4 border-blue-500 hover:bg-blue-200 transition">
            <h2 class="text-base md:text-lg font-semibold mb-2">📑 Total Surat Masuk</h2>
            <p class="text-xl md:text-2xl font-bold text-blue-700">{{ $totalSuratMasuk }}</p>
        </a>
        <a href="{{ route('riwayatSuratRw') }}" class="block p-4 md:p-6 bg-green-100 rounded-xl shadow border-l-4 border-green-500 hover:bg-green-200 transition">
            <h2 class="text-base md:text-lg font-semibold mb-2">✅ Surat Disetujui</h2>
            <p class="text-xl md:text-2xl font-bold text-green-700">{{ $totalSuratDisetujui }}</p>
        </a>
        <a href="{{ route('akunRT') }}" class="block p-4 md:p-6 bg-yellow-100 rounded-xl shadow border-l-4 border-yellow-500 hover:bg-yellow-200 transition">
            <h2 class="text-base md:text-lg font-semibold mb-2">👥 Total Warga Terdaftar</h2>
            <p class="text-xl md:text-2xl font-bold text-yellow-700">{{ $totalWargaTerdaftar }}</p>
        </a>
    </div>

    <!-- Daftar Pengajuan Terbaru -->
    <div class="bg-white bg-opacity-80 p-4 mb-6 md:p-6 rounded-xl shadow w-full">
        <h2 class="text-lg md:text-xl font-bold text-gray-800 mb-4">
            🏘️ Status Pengajuan dari RT - Bulan {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
        </h2>

        <div class="overflow-x-auto">
            <div class="min-w-full inline-block align-middle">
                <div class="overflow-x-auto border rounded-lg max-h-[400px] overflow-y-auto">
                    <table class="min-w-full text-xs md:text-sm text-gray-700 border border-gray-300">
                        <thead class="bg-gray-100 sticky top-0 z-10">
                            <tr>
                                <th class="px-2 md:px-4 py-2 text-center">Rt</th>
                                <th class="px-2 md:px-4 py-2 text-center">Jumlah Surat Masuk</th>
                                <th class="px-2 md:px-4 py-2 text-center">Surat Menunggu Proses</th>
                                <th class="px-2 md:px-4 py-2 text-center">Surat Disetujui</th>
                                <th class="px-2 md:px-4 py-2 text-center">Surat Ditolak</th>
                                <th class="px-2 md:px-4 py-2 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($statusPengajuanPerRt as $status)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-2 md:px-4 py-2 whitespace-nowrap text-center align-middle">RT {{ $status['no_rt'] }}</td>
                                    <td class="px-2 md:px-4 py-2 whitespace-nowrap text-center align-middle">{{ $status['total_pengajuan'] }}</td>
                                    <td class="px-2 md:px-4 py-2 whitespace-nowrap text-center align-middle">{{ $status['total_menunggu'] }}</td>
                                    <td class="px-2 md:px-4 py-2 whitespace-nowrap text-center align-middle">{{ $status['total_disetujui'] }}</td>
                                    <td class="px-2 md:px-4 py-2 whitespace-nowrap text-center align-middle">{{ $status['total_ditolak'] }}</td>
                                    <td class="px-2 md:px-4 py-2 text-center align-middle">
                                        <a href="{{ route('riwayatSuratRw') }}" class="text-green-500 hover:underline">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-2 md:px-4 py-3 text-center text-gray-500">
                                        <p>Belum ada data pengajuan.</p>
                                    </td>
                                </tr>
                            @endforelse
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
                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span><strong>Info:</strong> Ada {{ $suratBelumTtdRwCount }} surat yang perlu ditandatangani hari ini!</span>
    </div>

    @if ($showModalUploadTtdRw)
        <!-- Modal Upload TTD Digital -->
        <div id="modalUploadTtd" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
            <div class="relative bg-white rounded-xl shadow-lg w-full max-w-md p-6">
                <!-- Tombol Close -->
                <button onclick="document.getElementById('modalUploadTtd').style.display='none'"
                        class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 text-xl">
                    &times;
                </button>

                <h2 class="text-xl font-semibold mb-4 text-gray-800">Lengkapi Profil Anda</h2>
                <p class="text-gray-600 mb-6">
                    Anda belum mengunggah tanda tangan digital. Silakan lengkapi untuk melanjutkan proses administrasi surat.
                </p>
                <div class="flex justify-end">
                    <a href="{{ route('profileRw') }}"
                       class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        Unggah Sekarang
                    </a>
                </div>
            </div>
        </div>
    @endif
@endsection
@push('scripts')
    @if ($showModalUploadTtdRw)
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modal = document.getElementById('modalUploadTtd');
                if (modal) {
                    modal.style.display = 'flex'; // Tampilkan modal otomatis
                }
            });
        </script>
    @endif
@endpush

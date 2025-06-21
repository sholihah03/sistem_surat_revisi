@extends('rt.dashboardRt')

@section('content')
    <h1 class="text-2xl pt-20 md:text-3xl font-bold text-gray-800 mb-6">Selamat Datang, Ketua RT {{ $rt->no_rt }}!</h1>

    <!-- Kartu Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="p-4 md:p-6 bg-green-100 rounded-xl shadow border-l-4 border-green-500">
            <h2 class="text-base md:text-lg font-semibold mb-2">📑 Surat Masuk Dalam Sebulan</h2>
            <p class="text-xl md:text-2xl font-bold text-green-700">{{ $totalSuratMasuk }}</p>
        </div>
        <div class="p-4 md:p-6 bg-blue-100 rounded-xl shadow border-l-4 border-blue-500">
            <h2 class="text-base md:text-lg font-semibold mb-2">✅ Surat Disetujui Dalam Sebulan</h2>
            <p class="text-xl md:text-2xl font-bold text-blue-700">{{ $totalDisetujui }}</p>
        </div>
        <div class="p-4 md:p-6 bg-yellow-100 rounded-xl shadow border-l-4 border-yellow-500">
            <h2 class="text-base md:text-lg font-semibold mb-2">👥 Warga Belum Diverifikasi</h2>
            <p class="text-xl md:text-2xl font-bold text-yellow-700">{{ $pendingCount }}</p>
        </div>
    </div>

    <!-- Informasi Riwayat Surat -->
    <div class="bg-yellow-50 border-l-4 border-yellow-400 text-yellow-800 p-4 rounded-md mb-4">
        <p class="text-sm md:text-base">
            📌 <strong>Catatan:</strong> Untuk melihat data verifikasi surat dari bulan sebelumnya, silakan kunjungi halaman <a href="{{ route('riwayatSuratWarga') }}" class="text-blue-600 hover:underline font-semibold">Riwayat Surat</a>.
        </p>
    </div>

    <!-- Daftar Pengajuan Terbaru -->
    <div class="bg-white bg-opacity-80 p-4 md:p-6 rounded-xl shadow w-full">
        <h2 class="text-lg md:text-xl font-bold text-gray-800 mb-4">
            📋 Pengajuan Surat Terbaru - Bulan {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
        </h2>

        <div class="overflow-x-auto">
            <div class="max-h-[300px] rounded-lg overflow-y-auto">
                <table class="min-w-full text-xs md:text-sm text-gray-700 border border-gray-300">
                    <thead class="bg-gray-100 sticky top-0 z-10">
                        <tr>
                            <th class="px-2 md:px-4 py-2 text-center">Tanggal</th>
                            <th class="px-2 md:px-4 py-2 text-center">Nama Warga</th>
                            <th class="px-2 md:px-4 py-2 text-center">Tujuan Surat</th>
                            <th class="px-2 md:px-4 py-2 text-center">Status</th>
                            <th class="px-2 md:px-4 py-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($pengajuanTerbaru as $pengajuan)
                            <tr class="hover:bg-gray-50">
                                <td class="px-2 md:px-4 py-2 whitespace-nowrap text-center">{{ $pengajuan->created_at->format('Y-m-d') }}</td>
                                <td class="px-2 md:px-4 py-2 whitespace-nowrap text-center">{{ $pengajuan->warga->nama_lengkap }}</td>
                                <td class="px-2 md:px-4 py-2 whitespace-nowrap text-center">
                                    @if ($pengajuan instanceof \App\Models\PengajuanSurat)
                                        {{ $pengajuan->tujuanSurat->nama_tujuan ?? '-' }}
                                    @else
                                        {{ $pengajuan->tujuan_manual }}
                                    @endif
                                </td>
                                <td class="px-2 md:px-4 py-2 whitespace-nowrap text-center">
                                    @if ($pengajuan->status_rt === 'menunggu' || $pengajuan->status_rt_pengajuan_lain === 'menunggu')
                                        <span class="bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded text-xs font-semibold">
                                            Menunggu
                                        </span>
                                    @elseif ($pengajuan->status_rt === 'disetujui' || $pengajuan->status_rt_pengajuan_lain === 'disetujui')
                                        <span class="bg-green-100 text-green-800 px-2 py-0.5 rounded text-xs font-semibold">
                                            Disetujui
                                        </span>
                                    @elseif ($pengajuan->status_rt === 'ditolak' || $pengajuan->status_rt_pengajuan_lain === 'ditolak')
                                        <span class="bg-red-100 text-red-800 px-2 py-0.5 rounded text-xs font-semibold">
                                            Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td class="px-2 md:px-4 py-2 whitespace-nowrap text-center">
                                    @if ($pengajuan->status_rt === 'menunggu' || $pengajuan->status_rt_pengajuan_lain === 'menunggu')
                                        <a href="{{ route('verifikasiSurat') }}" class="text-blue-500 hover:underline">Verifikasi</a>
                                    @else
                                        <a href="{{ route('riwayatSuratWarga') }}" class="text-blue-500 hover:underline">Riwayat Surat</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="border border-gray-300 px-2 md:px-4 py-3 text-center text-gray-500">
                                    <p>Belum ada data pengajuan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

        @if ($showModalUploadTtd)
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
                    <a href="{{ route('profileRt') }}"
                       class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        Unggah Sekarang
                    </a>
                </div>
            </div>
        </div>
    @endif
@endsection
@push('scripts')
    @if ($showModalUploadTtd)
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

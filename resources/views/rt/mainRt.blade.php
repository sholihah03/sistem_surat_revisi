@extends('rt.dashboardRt')

@section('content')
    <h1 class="text-2xl pt-20 md:text-3xl font-bold text-gray-800 mb-6">Selamat Datang, Ketua RT!</h1>

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

    <!-- Daftar Pengajuan Terbaru -->
    <div class="bg-white bg-opacity-80 p-4 md:p-6 rounded-xl shadow w-full">
        <h2 class="text-lg md:text-xl font-bold text-gray-800 mb-4">
            📋 Pengajuan Surat Terbaru - Bulan {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
        </h2>

        <div class="overflow-x-auto">
            <!-- Tambahkan scroll vertikal di sini -->
            <div class="max-h-[300px] overflow-y-auto">
                <table class="min-w-full text-xs md:text-sm text-gray-700">
                    <thead class="bg-gray-100 sticky top-0 z-10">
                        <tr>
                            <th class="px-2 md:px-4 py-2 text-left">Tanggal</th>
                            <th class="px-2 md:px-4 py-2 text-left">Nama Warga</th>
                            <th class="px-2 md:px-4 py-2 text-left">Tujuan Surat</th>
                            <th class="px-2 md:px-4 py-2 text-left">Status</th>
                            <th class="px-2 md:px-4 py-2 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($pengajuanTerbaru as $pengajuan)
                            <tr class="hover:bg-gray-50">
                                <td class="px-2 md:px-4 py-2 whitespace-nowrap">{{ $pengajuan->created_at->format('Y-m-d') }}</td>
                                <td class="px-2 md:px-4 py-2 whitespace-nowrap">{{ $pengajuan->warga->nama_lengkap }}</td>
                                <td class="px-2 md:px-4 py-2 whitespace-nowrap">
                                    @if ($pengajuan instanceof \App\Models\PengajuanSurat)
                                        {{ $pengajuan->tujuanSurat->nama_tujuan ?? '-' }}
                                    @else
                                        {{ $pengajuan->tujuan_manual }}
                                    @endif
                                </td>
                                <td class="px-2 md:px-4 py-2 whitespace-nowrap">
                                    @if ($pengajuan->status === 'menunggu' || $pengajuan->status_pengajuan_lain === 'menunggu')
                                        <span class="bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded text-xs font-semibold">
                                            Menunggu
                                        </span>
                                    @elseif ($pengajuan->status === 'disetujui' || $pengajuan->status_pengajuan_lain === 'disetujui')
                                        <span class="bg-green-100 text-green-800 px-2 py-0.5 rounded text-xs font-semibold">
                                            Disetujui
                                        </span>
                                    @elseif ($pengajuan->status === 'ditolak' || $pengajuan->status_pengajuan_lain === 'ditolak')
                                        <span class="bg-red-100 text-red-800 px-2 py-0.5 rounded text-xs font-semibold">
                                            Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td class="px-2 md:px-4 py-2">
                                    @if ($pengajuan->status === 'menunggu' || $pengajuan->status_pengajuan_lain === 'menunggu')
                                        <a href="{{ route('verifikasiSurat') }}" class="text-blue-500 hover:underline">Verifikasi</a>
                                    @else
                                        <a href="{{ route('riwayatSuratWarga') }}" class="text-blue-500 hover:underline">Riwayat Surat</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

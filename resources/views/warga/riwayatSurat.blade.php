<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Riwayat Surat</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    .custom-carousel-img {
      height: 260px;
      object-fit: cover;
    }

    @media (min-width: 768px) {
      .custom-carousel-img {
        height: 380px;
      }
    }

    .carousel-text h5 {
      font-size: 1rem;
    }

    .carousel-text p {
      font-size: 0.75rem;
    }

    @media (min-width: 768px) {
      .carousel-text h5 {
        font-size: 1.5rem;
      }

      .carousel-text p {
        font-size: 0.875rem;
      }
    }
  </style>
</head>
{{-- <body class="min-h-screen bg-gradient-to-br from-blue-100 via-white to-pink-100"> --}}
<body class="min-h-screen bg-yellow-50">
    @include('komponen.nav')

    <!-- Breadcrumb -->
    <nav class="max-w-7xl mx-auto px-4 pt-6 text-sm text-gray-600">
    <ol class="flex items-center space-x-2">
        <li><a href="{{ route('dashboardWarga') }}" class="text-blue-600 no-underline">Home</a></li>
        <li>/</li>
        <li class="text-gray-800 font-medium">Riwayat Surat</li>
    </ol>
    </nav>

    <!-- Main Container -->
    <div class="max-w-7xl mx-auto mt-6 px-4 py-6 bg-white bg-opacity-90 rounded-lg">

        <h2 class="text-2xl font-bold text-gray-800 mb-4">📄 Riwayat Pengajuan Surat</h2>
        <div class="alert alert-warning bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 p-4 mb-4" role="alert">
            ⏳ <strong>Catatan:</strong> Data proses dan data surat yang telah selesai akan otomatis hilang dari halaman ini setelah 1 bulan dari tanggal persetujuan RW.
            Untuk melihat surat lama, silakan buka menu <a href="{{ route('historiSuratWarga') }}" class="text-blue-600">Histori Surat</a>.
        </div>

        <!-- Surat Selesai -->
        <div class="overflow-x-auto bg-white rounded-lg shadow p-4 mb-6">
            <h3 class="text-xl font-semibold text-gray-700 mb-4">Surat yang Selesai</h3>
            @if ($suratSelesai->isEmpty())
                <p class="text-gray-500 italic">Belum ada surat yang selesai.</p>
            @else
            <div class="overflow-x-auto max-h-[255px] overflow-y-auto">
                <table class="table-auto w-full text-left text-sm text-gray-700">
                    <thead class="bg-gray-100 text-gray-800 text-center">
                        <tr>
                            <th class="px-4 py-2 border border-gray-300">Jenis Surat</th>
                            <th class="px-4 py-2 border border-gray-300">Tanggal Selesai</th>
                            <th class="px-4 py-2 border border-gray-300">Tujuan</th>
                            <th class="px-4 py-2 border border-gray-300">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach($suratSelesai as $surat)
                        <tr class="hover:bg-gray-50 transition duration-200">
                            <td class="px-4 py-2 border border-gray-300">{{ ucfirst($surat->jenis) }}</td>
                            <td class="px-4 py-2 border border-gray-300">{{ $surat->updated_at->translatedFormat('d F Y') }}</td>
                            <td class="px-4 py-2 border border-gray-300">
                            @if($surat->jenis === 'biasa')
                                {{ $surat->pengajuanSurat->tujuanSurat->nama_tujuan ?? '-' }}
                            @else
                                {{ $surat->pengajuanSuratLain->tujuan_manual ?? '-' }}
                            @endif
                            </td>
                            <td class="px-4 py-2 border border-gray-300">
                                <a href="{{ route('surat.pdf', $surat->id_hasil_surat_ttd_rw) }}" class="text-blue-600 hover:underline">
                                    Lihat PDF
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        <!-- Proses Surat -->
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-xl font-semibold text-gray-700 mb-4">Proses Surat</h3>

            @if ($pengajuanBiasa->isEmpty() && $pengajuanLain->isEmpty())
                <p class="text-gray-500 italic">Belum ada pengajuan surat yang sedang diproses.</p>
            @else
                @php
                    $totalPengajuan = $pengajuanBiasa->count() + $pengajuanLain->count();
                @endphp

                <div @class([
                    'space-y-6',
                    'overflow-y-auto max-h-[330px]' => $totalPengajuan > 3
                ])>
                    {{-- Pengajuan Biasa --}}
                    @foreach ($pengajuanBiasa as $item)
                        <div class="flex items-start space-x-4">
                            <div class="w-1/12 h-2 mt-2 rounded-full {{
                                $item->status === 'disetujui' ? 'bg-blue-500' :
                                ($item->status === 'ditolak' ? 'bg-red-500' : 'bg-yellow-500') }}">
                            </div>
                            <div class="w-full">
                                <h4 class="text-lg font-semibold text-gray-800">{{ $item->tujuanSurat->nama_tujuan ?? '-' }}</h4>
                                <ul class="space-y-1 text-gray-700">
                                <li>✅ Diserahkan ke RT: {{ $item->created_at->translatedFormat('d F Y') }}</li>
                                @if ($item->status === 'disetujui')
                                    <li>✅ RT Menyetujui: {{ $item->updated_at->translatedFormat('d F Y') }}</li>
                                    <li>✅ Diserahkan ke RW: {{ $item->updated_at->translatedFormat('d F Y') }}</li>
                                    @if ($item->disetujui_rw)
                                        <li>✅ RW Menyetujui: {{ \Carbon\Carbon::parse($item->updated_at)->translatedFormat('d F Y') }}</li>
                                    @else
                                        <li>⏳ Menunggu Persetujuan RW</li>
                                    @endif
                                @elseif ($item->status === 'ditolak')
                                    <li>❌ Ditolak oleh RT pada {{ $item->created_at->translatedFormat('d F Y') }}</li>
                                    <li class="text-red-600 font-semibold">Alasan: {{ $item->alasan_penolakan_pengajuan }}</li>
                                @endif
                                </ul>
                            </div>
                        </div>
                    @endforeach

                    {{-- Pengajuan Lain --}}
                    @foreach ($pengajuanLain as $itemLain)
                        <div class="flex items-start space-x-4">
                            <div class="w-1/12 h-2 mt-2 rounded-full {{
                                $itemLain->status_pengajuan_lain === 'disetujui' ? 'bg-blue-500' :
                                ($itemLain->status_pengajuan_lain === 'ditolak' ? 'bg-red-500' : 'bg-yellow-500') }}">
                            </div>
                            <div class="w-full">
                                <h4 class="text-lg font-semibold text-gray-800">{{ $itemLain->tujuan_manual }}</h4>
                                <ul class="space-y-1 text-gray-700">
                                <li>✅ Diserahkan ke RT: {{ $itemLain->created_at->translatedFormat('d F Y') }}</li>
                                @if ($itemLain->status_pengajuan_lain === 'disetujui')
                                    <li>✅ RT Menyetujui: {{ $itemLain->updated_at->translatedFormat('d F Y') }}</li>
                                    <li>✅ Diserahkan ke RW: {{ $itemLain->updated_at->translatedFormat('d F Y') }}</li>
                                    @if ($itemLain->disetujui_rw)
                                        <li>✅ RW Menyetujui: {{ \Carbon\Carbon::parse($itemLain->updated_at)->translatedFormat('d F Y') }}</li>
                                    @else
                                        <li>⏳ Menunggu Persetujuan RW</li>
                                    @endif
                                @elseif ($itemLain->status_pengajuan_lain === 'ditolak')
                                    <li>❌ Ditolak oleh RT</li>
                                    <li class="text-red-600 font-semibold">Alasan: {{ $itemLain->alasan_penolakan_pengajuan_lain }}</li>
                                @endif
                                </ul>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

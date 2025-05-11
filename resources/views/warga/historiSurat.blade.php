<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Histori Pengajuan Surat</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-100 via-white to-pink-100">

    @include('komponen.nav')

    <!-- Breadcrumb -->
    <nav class="max-w-7xl mx-auto px-4 pt-6 text-sm text-gray-600">
        <ol class="flex items-center space-x-2">
            <li><a href="{{ route('dashboardWarga') }}" class="text-blue-600 no-underline">Home</a></li>
            <li>/</li>
            <li class="text-gray-800 font-medium">Histori Surat</li>
        </ol>
    </nav>
    <div class="max-w-7xl mx-auto mt-6 px-4 py-6 bg-white bg-opacity-90 rounded-lg">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">📜 Histori Pengajuan Surat</h1>

        {{-- Surat Disetujui --}}
        <div class="mb-8 bg-white p-4 rounded shadow">
            <h2 class="text-xl font-semibold text-green-600 mb-4">✅ Surat Disetujui</h2>
            @if($disetujuiBiasa->isEmpty() && $disetujuiLain->isEmpty())
                <p class="text-gray-500 italic">Belum ada surat yang disetujui.</p>
            @else
                <ul class="space-y-4">
                    @foreach ($disetujuiBiasa as $item)
                        <li class="border p-4 rounded-md">
                            <h3 class="font-semibold text-gray-800">{{ $item->id }} - {{ $item->tujuanSurat ? $item->tujuanSurat->nama_tujuan : 'Tidak ada tujuan surat' }}</h3>
                            <p class="text-sm text-gray-600">Tanggal Pengajuan: {{ $item->created_at->format('Y-m-d') }}</p>
                            <p class="text-sm text-gray-600">Disetujui pada {{ $item->updated_at->format('Y-m-d') }}</p>
                        </li>
                    @endforeach

                    @foreach ($disetujuiLain as $item)
                        <li class="border p-4 rounded-md">
                            <h3 class="font-semibold text-gray-800">{{ $item->tujuan_manual }}</h3>
                            <p class="text-sm text-gray-600">Tanggal Pengajuan: {{ $item->created_at->format('Y-m-d') }}</p>
                            <p class="text-sm text-gray-600">Disetujui pada {{ $item->updated_at->format('Y-m-d') }}</p>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- Surat Ditolak --}}
        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-xl font-semibold text-red-600 mb-4">❌ Surat Ditolak</h2>
            @if($ditolakBiasa->isEmpty() && $ditolakLain->isEmpty())
            <p class="text-gray-500 italic">Belum ada surat yang ditolak.</p>
            @else
            <ul class="space-y-4">
                @foreach ($ditolakBiasa as $item)
                <li class="border p-4 rounded-md">
                    <h3 class="font-semibold text-gray-800">{{ $item->tujuanSurat->nama_tujuan ?? '-' }}</h3>
                    <p class="text-sm text-gray-600">Tanggal Pengajuan: {{ $item->created_at->format('Y-m-d') }}</p>
                    <p class="text-sm text-gray-600">Ditolak pada {{ $item->updated_at->format('Y-m-d') }}</p>
                    <p class="text-sm text-red-600">Alasan Penolakan: {{ $item->alasan_penolakan_pengajuan }}</p>
                </li>
                @endforeach

                @foreach ($ditolakLain as $item)
                <li class="border p-4 rounded-md">
                    <h3 class="font-semibold text-gray-800">{{ $item->tujuan_manual }}</h3>
                    <p class="text-sm text-gray-600">Tanggal Pengajuan: {{ $item->created_at->format('Y-m-d') }}</p>
                    <p class="text-sm text-gray-600">Ditolak pada {{ $item->updated_at->format('Y-m-d') }}</p>
                    <p class="text-sm text-red-600">Alasan Penolakan: {{ $item->alasan_penolakan_pengajuan_lain }}</p>
                </li>
                @endforeach
            </ul>
            @endif
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Histori Pengajuan Surat</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-100 via-white to-pink-100">

    {{-- Include Nav --}}
    @include('komponen.nav')

    <!-- Breadcrumb -->
    <nav class="max-w-7xl mx-auto px-4 pt-6 text-sm text-gray-600">
        <ol class="flex flex-wrap items-center gap-2">
        <li><a href="{{ route('dashboardWarga') }}" class="text-blue-600 no-underline">Home</a></li>
        <li>/</li>
        <li class="text-gray-800 font-medium">Histori Surat</li>
        </ol>
    </nav>

    <div class="max-w-7xl mx-auto mt-6 px-4 py-6 bg-white bg-opacity-90 rounded-lg shadow">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6 text-center md:text-left">📜 Histori Pengajuan Surat</h1>

        <!-- Nav tabs -->
        <nav class="mb-6 border-b border-gray-300">
            <ul class="flex flex-wrap justify-center md:justify-start space-x-4 text-sm sm:text-base text-gray-600">
                <li>
                    <button id="tab-1-btn" class="pb-2 border-b-4 border-blue-600 font-semibold focus:outline-none">
                        Disetujui & Ditolak
                    </button>
                </li>
                <li>
                    <button id="tab-2-btn" class="pb-2 border-b-4 border-transparent font-semibold focus:outline-none">
                        Surat Selesai & Download
                    </button>
                </li>
            </ul>
        </nav>

        <!-- Tab contents -->
        <div id="tab-1">
            {{-- Surat Disetujui --}}
            <div class="mb-8 bg-white p-4 rounded shadow">
                <h2 class="text-lg sm:text-xl font-semibold text-green-600 mb-4">✅ Surat Disetujui</h2>
                @if($disetujuiBiasa->isEmpty() && $disetujuiLain->isEmpty())
                    <p class="text-gray-500 italic">Belum ada surat yang disetujui.</p>
                @else
                <div class="{{ count($disetujuiBiasa) + count($disetujuiLain) > 3 ? 'max-h-96 overflow-y-auto pr-2' : '' }}">
                    <ul class="space-y-4">
                        @foreach ($disetujuiBiasa as $item)
                        <li class="border p-4 rounded-md">
                            <h3 class="font-semibold text-gray-800">{{ $item->tujuanSurat ? $item->tujuanSurat->nama_tujuan : 'Tidak ada tujuan surat' }}</h3>
                            <p class="text-sm text-gray-600">Tanggal Pengajuan: {{ $item->created_at->translatedFormat('d F Y') }}</p>
                            <p class="text-sm text-gray-600">Disetujui pada {{ $item->updated_at->translatedFormat('d F Y') }}</p>
                        </li>
                        @endforeach
                        @foreach ($disetujuiLain as $item)
                        <li class="border p-4 rounded-md">
                            <h3 class="font-semibold text-gray-800">{{ $item->tujuan_manual }}</h3>
                            <p class="text-sm text-gray-600">Tanggal Pengajuan: {{ $item->created_at->translatedFormat('d F Y') }}</p>
                            <p class="text-sm text-gray-600">Disetujui pada {{ $item->updated_at->translatedFormat('d F Y') }}</p>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>

            {{-- Surat Ditolak --}}
            <div class="bg-white p-4 rounded shadow">
                <h2 class="text-lg sm:text-xl font-semibold text-red-600 mb-4">❌ Surat Ditolak</h2>
                @if($ditolakBiasa->isEmpty() && $ditolakLain->isEmpty())
                <p class="text-gray-500 italic">Belum ada surat yang ditolak.</p>
                @else
                <div class="{{ count($ditolakBiasa) + count($ditolakLain) > 3 ? 'max-h-96 overflow-y-auto pr-2' : '' }}">
                    <ul class="space-y-4">
                        @foreach ($ditolakBiasa as $item)
                        <li class="border p-4 rounded-md">
                            <h3 class="font-semibold text-gray-800">{{ $item->tujuanSurat->nama_tujuan ?? '-' }}</h3>
                            <p class="text-sm text-gray-600">Tanggal Pengajuan: {{ $item->created_at->translatedFormat('d F Y') }}</p>
                            <p class="text-sm text-gray-600">Ditolak pada {{ $item->updated_at->translatedFormat('d F Y') }}</p>
                            <p class="text-sm text-red-600">Alasan Penolakan: {{ $item->alasan_penolakan_pengajuan }}</p>
                        </li>
                        @endforeach
                        @foreach ($ditolakLain as $item)
                        <li class="border p-4 rounded-md">
                            <h3 class="font-semibold text-gray-800">{{ $item->tujuan_manual }}</h3>
                            <p class="text-sm text-gray-600">Tanggal Pengajuan: {{ $item->created_at->translatedFormat('d F Y') }}</p>
                            <p class="text-sm text-gray-600">Ditolak pada {{ $item->updated_at->translatedFormat('d F Y') }}</p>
                            <p class="text-sm text-red-600">Alasan Penolakan: {{ $item->alasan_penolakan_pengajuan_lain }}</p>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>

        <div id="tab-2" class="hidden">
            {{-- Surat Selesai dan Download --}}
            <div class="mt-8 bg-white p-4 rounded shadow">
                <h2 class="text-lg sm:text-xl font-semibold text-blue-600 mb-4">📥 Surat Selesai & Siap Download</h2>
                @if($hasilSurat->isEmpty())
                    <p class="text-gray-500 italic">Belum ada surat yang selesai dan dapat diunduh.</p>
                @else
                <div class="{{ count($hasilSurat) > 5 ? 'max-h-[30rem] overflow-y-auto pr-2' : '' }}">
                    <ul class="space-y-4">
                        @foreach($hasilSurat as $surat)
                        <li class="border p-4 rounded-md flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
                            <div>
                                @php
                                    // Coba cari nama tujuan surat dari relasi pengajuan
                                    $namaTujuan = '-';
                                    $tglPengajuan = '-';
                                    $tglSelesai = $surat->updated_at->translatedFormat('d F Y');

                                    if($surat->jenis == 'biasa' && $surat->pengajuanSurat) {
                                        $namaTujuan = $surat->pengajuanSurat->tujuanSurat->nama_tujuan ?? 'Surat Biasa';
                                        $tglPengajuan = $surat->pengajuanSurat->created_at->translatedFormat('d F Y');
                                    } elseif($surat->jenis == 'lain' && $surat->pengajuanSuratLain) {
                                        $namaTujuan = $surat->pengajuanSuratLain->tujuan_manual ?? 'Surat Lain';
                                        $tglPengajuan = $surat->pengajuanSuratLain->created_at->translatedFormat('d F Y');
                                    }
                                @endphp

                                <h3 class="font-semibold text-gray-800">{{ $namaTujuan }}</h3>
                                <p class="text-sm text-gray-600">Tanggal Pengajuan: {{ $tglPengajuan }}</p>
                                <p class="text-sm text-gray-600">Selesai pada {{ $tglSelesai }}</p>
                            </div>
                            <a href="{{ route('surat.pdf', $surat->id_hasil_surat_ttd_rw) }}"
                                class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                    Download Surat
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>

    </div>

    <script>
        const tab1Btn = document.getElementById('tab-1-btn');
        const tab2Btn = document.getElementById('tab-2-btn');
        const tab1Content = document.getElementById('tab-1');
        const tab2Content = document.getElementById('tab-2');

        function activateTab1() {
        tab1Btn.classList.add('border-blue-600');
        tab1Btn.classList.remove('border-transparent');
        tab2Btn.classList.add('border-transparent');
        tab2Btn.classList.remove('border-blue-600');
        tab1Content.classList.remove('hidden');
        tab2Content.classList.add('hidden');
        }

        function activateTab2() {
        tab2Btn.classList.add('border-blue-600');
        tab2Btn.classList.remove('border-transparent');
        tab1Btn.classList.add('border-transparent');
        tab1Btn.classList.remove('border-blue-600');
        tab2Content.classList.remove('hidden');
        tab1Content.classList.add('hidden');
        }

        tab1Btn.addEventListener('click', activateTab1);
        tab2Btn.addEventListener('click', activateTab2);
        activateTab1();
    </script>

</body>
</html>

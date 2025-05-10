<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Pengajuan Surat</title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <!-- Font Inter -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

  <style>
    body { font-family: 'Inter', sans-serif; }
    [x-cloak] { display: none !important; }
  </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-blue-100 via-white to-pink-100">

    @if(session('success_form'))
    <div x-data="{ open: true }" x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
      <div class="bg-white rounded-lg shadow-lg p-6 sm:p-8 w-[90%] max-w-md text-center">
        <div class="flex justify-center mb-4">
          <img src="https://img.icons8.com/color/96/000000/ok--v1.png" alt="Success" class="w-20 h-20">
        </div>

        <h2 class="text-xl font-bold mb-4 text-gray-800">
          {{ session('success_form') }}
        </h2>

        <p class="text-sm text-gray-600 mb-6">
            Pengajuan surat Anda telah berhasil dikirim. Silakan tunggu maksimal 3 hari kerja.
            Jika dalam waktu tersebut Anda belum menerima notifikasi melalui email bahwa surat telah selesai diproses,
            segera hubungi ketua RT setempat untuk konfirmasi lebih lanjut.
        </p>

        <button @click="open = false" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded">
          Tutup
        </button>
      </div>
    </div>
    @endif


  <!-- Navbar -->
  @include('komponen.nav')

  <div x-data="{ showModal: false, tujuan: '', untuk: '', keluarga: '' }"
       class="pt-8 px-6">

    <!-- Breadcrumb (Tengah) -->
    <nav class="pt-6 px-6 text-sm text-gray-600 text-center">
      <ol class="inline-flex items-center space-x-2 justify-center">
        <li><a href="{{ route('dashboardWarga') }}" class="text-blue-600 no-underline">Home</a></li>
        <li>/</li>
        <li class="text-gray-800 font-medium">Pengajuan Surat</li>
      </ol>
    </nav>

    <!-- Heading -->
    <div class="text-center mb-4">
      <h1 class="text-3xl font-bold text-gray-800 mb-2">Pengajuan Surat Pengantar</h1>
      <p class="text-gray-600 text-lg">Pilih jenis surat yang ingin diajukan</p>
    </div>

    <!-- Search and Button -->
    <div class="flex justify-between mb-8">
      <!-- Form Search -->
      <form method="GET" action="{{ route('pengajuanSuratWarga') }}" class="relative w-full sm:w-80">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
          <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
              viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8" />
            <line x1="21" y1="21" x2="16.65" y2="16.65" />
          </svg>
        </span>
        <input type="text" name="search" id="searchInput"
               value="{{ request('search') }}"
               placeholder="Cari nama tujuan atau nomor surat..."
               class="pl-10 pr-4 py-2 border rounded w-full focus:outline-none focus:ring-2 focus:ring-yellow-400" />
      </form>

      <!-- Button Tidak Ada Jenis Pengajuan -->
      <div class="flex justify-end">
        <a href="{{ route('formPengajuanSuratLain') }}"
            class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
            Tidak Ada Jenis Pengajuan yang Cocok
         </a>
      </div>
    </div>

    <!-- Dua Kolom: Populer dan Lainnya -->
    <div class="flex flex-wrap lg:flex-nowrap gap-6 mb-8">
      <!-- Tujuan Populer -->
      <div class="w-full lg:w-1/2 space-y-4">
        <h2 class="text-2xl font-semibold text-gray-800">Tujuan Populer</h2>
        <div class="space-y-4 max-h-[550px] overflow-y-auto pr-2">
          @foreach($tujuanSurat->where('status_populer', true)->take(20) as $item)
            <div class="bg-white border border-yellow-400 p-4 rounded-xl shadow-lg flex justify-between items-center">
              <div class="flex flex-col">
                <h3 class="text-lg font-semibold text-gray-800">{{ $item->nama_tujuan }}</h3>
                <p class="text-gray-600 text-sm">{{ Str::limit($item->deskripsi ?? 'Deskripsi tidak tersedia', 100) }}</p>
              </div>
              <a href="{{ route('formPengajuanSurat', ['tujuan' => $item->nama_tujuan, 'id' => $item->id_tujuan_surat]) }}"
                class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                Ajukan
             </a>
            </div>
          @endforeach
        </div>
      </div>

      <!-- Tujuan Lainnya -->
      <div class="w-full lg:w-1/2 space-y-4">
        <h2 class="text-2xl font-semibold text-gray-800">Tujuan Lainnya</h2>
        <div class="space-y-4 max-h-[550px] overflow-y-auto pr-2">
          @foreach($tujuanSurat->where('status_populer', false)->take(20) as $item)
            <div class="bg-white border border-gray-300 p-4 rounded-xl shadow-md flex justify-between items-center">
              <div class="flex flex-col">
                <h3 class="text-lg font-semibold text-gray-800">{{ $item->nama_tujuan }}</h3>
                <p class="text-gray-600 text-sm">{{ Str::limit($item->deskripsi ?? 'Deskripsi tidak tersedia', 100) }}</p>
              </div>
              <a href="{{ route('formPengajuanSurat', ['tujuan' => $item->nama_tujuan, 'id' => $item->id_tujuan_surat]) }}"
                class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                Ajukan
             </a>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</body>
</html>

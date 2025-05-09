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
        <button
          class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition"
          @click="tujuan = 'Lainnya'; showModal = true; untuk = ''; keluarga = ''">
          Tidak Ada Jenis Pengajuan yang Cocok
        </button>
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
              <button
                @click="tujuan = '{{ $item->nama_tujuan }}'; showModal = true; untuk = ''; keluarga = ''"
                class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                Ajukan
              </button>
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
              <button
                @click="tujuan = '{{ $item->nama_tujuan }}'; showModal = true; untuk = ''; keluarga = ''"
                class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                Ajukan
              </button>
            </div>
          @endforeach
        </div>
      </div>
    </div>

    <!-- Modal untuk Surat Pengantar -->
    <div x-show="showModal" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-90"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-90"
         class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center px-4">

      <div @click.away="showModal = false"
           class="bg-white/80 backdrop-blur-xl border border-gray-300 shadow-2xl rounded-xl p-6 w-full max-w-lg">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Template Surat Pengantar</h2>

        <!-- Konten Surat -->
        <div class="text-center border-b border-black pb-2 mb-2">
            <div class="flex items-start justify-between">
                <div class="w-24">
                    <img src="{{ asset('images/Logo_Indramayu.png') }}" alt="Logo" class="w-full">
                </div>
                <div class="flex-1 text-center">
                    <h1 class="font-bold text-lg uppercase">Pemerintah Kabupaten Indramayu</h1>
                    <h2 class="font-bold text-md uppercase">Kecamatan Indramayu</h2>
                    <h3 class="font-bold uppercase">Kelurahan Margadadi</h3>
                    <p class="text-sm">Jl. May Sastra Atmaja Nomor : 47 Tlp. (0234) 273 301 Kode Pos 45211</p>
                    <p class="text-sm">e-mail : kelurahanmargadadi.indramayu@gmail.com</p>
                    <h4 class="font-bold uppercase tracking-widest mt-1">INDRAMAYU</h4>
                </div>
            </div>
        </div>

        <div class="text-right mb-4">
            <p>Kepada</p>
            <p>Yth. Lurah Margadadi</p>
            <p>di_</p>
            <p class="underline">TEMPAT</p>
        </div>

        <div class="text-center mb-2">
            <h2 class="font-bold tracking-widest underline">SURAT PENGANTAR</h2>
            <p>Nomor : ......................................</p>
        </div>

        <p class="mb-4">Yang bertanda tangan di bawah ini, Ketua RT .......... RW .......... Kelurahan Margadadi Kecamatan Indramayu Kabupaten Indramayu, Memberikan Pengantar Kepada :</p>

        <!-- Data Pengaju -->
        <div class="pl-6">
            <div class="flex">
                <p class="w-52">Nama</p>
                <p>: ...............................................................</p>
            </div>
            <div class="flex">
                <p class="w-52">Tempat/ Tanggal Lahir</p>
                <p>: ...............................................................</p>
            </div>
            <div class="flex">
                <p class="w-52">Nomor KTP</p>
                <p>: ...............................................................</p>
            </div>
            <div class="flex">
                <p class="w-52">Status Perkawinan</p>
                <p>: Kawin / Belum / Janda / Duda</p>
            </div>
            <div class="flex">
                <p class="w-52">Kebangsaan/ Agama</p>
                <p>: ...............................................................</p>
            </div>
            <div class="flex">
                <p class="w-52">Pekerjaan</p>
                <p>: ...............................................................</p>
            </div>
            <div class="flex">
                <p class="w-52">Alamat</p>
                <p>: ...............................................................</p>
            </div>
            <div class="flex">
                <p class="w-52">Untuk/ Maksud/ Tujuan</p>
                <p>: ...............................................................</p>
            </div>
        </div>

        <p class="mt-4">Demikian Surat Pengantar ini, untuk dipergunakan sebagaimana mestinya.</p>

        <!-- Tanda tangan -->
        <div class="flex justify-between mt-6">
            <div class="text-center">
                <p>Mengetahui,</p>
                <p class="font-bold">Ketua RW</p>
                <br><br><br>
                <p>( ............................................. )</p>
            </div>
            <div class="text-center">
                <p>Indramayu ........................................</p>
                <p class="font-bold">Ketua RT</p>
                <br><br><br>
                <p>( ............................................. )</p>
            </div>
        </div>

        <!-- QR Code -->
        <div class="absolute bottom-10 right-10 text-center">
            <img src="" alt="QR Code" class="w-24 h-24 mx-auto">
            <p class="text-[10px] mt-1">Scan untuk verifikasi surat</p>
        </div>

        <!-- Tombol Tutup Modal -->
        <button @click="showModal = false" class="mt-4 text-gray-600 hover:text-gray-800 text-sm w-full text-center">
          Batal / Kembali
        </button>
      </div>
    </div>
  </div>
</body>
</html>

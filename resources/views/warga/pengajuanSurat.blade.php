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
       class="max-w-5xl mx-auto space-y-6 pt-8 px-6">

       <!-- Breadcrumb (Tengah) -->
<nav class="max-w-5xl mx-auto pt-6 px-6 text-sm text-gray-600 text-center">
    <ol class="inline-flex items-center space-x-2 justify-center">
      <li><a href="{{ route('dashboardWarga') }}" class="text-blue-600 no-underline">Home</a></li>
      <li>/</li>
      <li class="text-gray-800 font-medium">Pengajuan Surat</li>
    </ol>
  </nav>

    <!-- Heading -->
    <div class="text-center">
      <h1 class="text-4xl font-bold text-gray-800 mb-2">Pengajuan Surat Pengantar</h1>
      <p class="text-gray-600 text-lg">Silakan pilih jenis surat yang ingin diajukan</p>
    </div>

    <!-- Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <template x-for="item in ['Surat Domisili', 'Surat Keterangan Usaha', 'Surat Tidak Mampu', 'Surat Izin Keramaian', 'Surat Keterangan Penghasilan', 'Lainnya', 'Surat Pengantar Kesehatan', 'Surat Rekomendasi Kerja']" :key="item">
        <div class="bg-white/70 backdrop-blur-md border border-gray-200 p-6 rounded-2xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-1">
          <h2 class="text-xl font-semibold text-gray-800 mb-2" x-text="item"></h2>
          <p class="text-gray-600 text-sm mb-4">Klik untuk mulai pengajuan surat ini.</p>
          <div class="flex justify-end">
            <button
              @click="tujuan = item; showModal = true; untuk = ''; keluarga = ''"
              class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition"
            >
              Ajukan
            </button>
          </div>
        </div>
      </template>
    </div>

    <!-- Modal -->
    <div x-show="showModal" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-90"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-90"
         class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center px-4">

      <div @click.away="showModal = false"
           class="bg-white/80 backdrop-blur-xl border border-gray-300 shadow-2xl rounded-2xl p-6 w-full max-w-lg">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Ajukan: <span x-text="tujuan"></span></h2>

        <!-- Pilih Untuk -->
        <div class="mb-4">
          <label class="block text-gray-700 mb-1 font-medium">Pengajuan Untuk</label>
          <select x-model="untuk" class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300">
            <option value="">-- Pilih --</option>
            <option value="sendiri">Diri Sendiri</option>
            <option value="keluarga">Anggota Keluarga</option>
          </select>
        </div>

        <!-- Pilih Anggota Keluarga -->
        <div x-show="untuk === 'keluarga'" class="mb-4" x-transition>
          <label class="block text-gray-700 mb-1 font-medium">Anggota Keluarga</label>
          <select x-model="keluarga" class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300">
            <option value="">-- Pilih --</option>
            <option value="Ayah">Ayah</option>
            <option value="Ibu">Ibu</option>
            <option value="Adik">Adik</option>
          </select>
        </div>

        <!-- Form -->
        <div x-show="untuk && (untuk === 'sendiri' || keluarga)" x-transition>
          <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-1">Alamat</label>
            <textarea class="w-full px-4 py-2 border rounded-lg" rows="3" placeholder="Tulis alamat lengkap..."></textarea>
          </div>

          <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-1">Tanggal Permohonan</label>
            <input type="date" class="w-full px-4 py-2 border rounded-lg"/>
          </div>

          <button class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
            Kirim Permohonan
          </button>
        </div>

        <button @click="showModal = false" class="mt-4 text-gray-600 hover:text-gray-800 text-sm w-full text-center">
          Batal / Kembali
        </button>
      </div>
    </div>
  </div>
</body>
</html>

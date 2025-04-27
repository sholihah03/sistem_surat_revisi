<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Riwayat Surat</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    /* Ukuran gambar carousel responsif */
    .custom-carousel-img {
      height: 260px; /* mobile size */
      object-fit: cover;
    }

    @media (min-width: 768px) {
      .custom-carousel-img {
        height: 380px; /* desktop size */
      }
    }

    /* Ukuran teks caption responsif */
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

<body class="min-h-screen bg-gradient-to-br from-blue-100 via-white to-pink-100">

  @include('komponen.nav')

<!-- Breadcrumb -->
<nav class="max-w-7xl mx-auto px-4 pt-6 text-sm text-gray-600">
    <ol class="list-reset flex items-center space-x-2">
      <li><a href="{{ route('dashboardWarga') }}" class="text-blue-600 no-underline">Home</a></li>
      <li>/</li>
      <li class="text-gray-800 font-medium">Riwayat Surat</li>
    </ol>
  </nav>


  <!-- Riwayat Surat Section -->
  <div class="max-w-7xl mx-auto mt-6 px-4 py-6 bg-white bg-opacity-18 rounded-lg">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">📄 Riwayat Pengajuan Surat</h2>

    <!-- Tabel Surat yang Selesai dan Bisa Diunduh -->
    <div class="overflow-x-auto bg-white rounded-lg shadow p-4 mb-6">
      <h3 class="text-xl font-semibold text-gray-700 mb-4">Surat yang Selesai</h3>
      <table class="min-w-full text-sm text-gray-700">
        <thead class="bg-gray-100 border-b">
          <tr>
            <th class="px-4 py-2 text-left">Tanggal Pengajuan</th>
            <th class="px-4 py-2 text-left">Tanggal Selesai</th>
            <th class="px-4 py-2 text-left">Tujuan Surat</th>
            <th class="px-4 py-2 text-left">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <!-- Data Surat 1 -->
          <tr class="hover:bg-gray-50 transition-colors duration-200">
            <td class="px-4 py-2">2025-04-01</td>
            <td class="px-4 py-2">2025-04-05</td>
            <td class="px-4 py-2">Surat Domisili</td>
            <td class="px-4 py-2">
              <a href="#" class="text-blue-500 hover:underline">Unduh</a>
            </td>
          </tr>
          <!-- Data Surat 2 -->
          <tr class="hover:bg-gray-50 transition-colors duration-200">
            <td class="px-4 py-2">2025-03-20</td>
            <td class="px-4 py-2">2025-03-25</td>
            <td class="px-4 py-2">Keterangan Usaha</td>
            <td class="px-4 py-2">
              <a href="#" class="text-blue-500 hover:underline">Unduh</a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Proses Surat (Status Proses) -->
    <div class="bg-white rounded-lg shadow p-4">
      <h3 class="text-xl font-semibold text-gray-700 mb-4">Proses Surat</h3>
      <div class="space-y-4">
        <!-- Proses Surat 1 -->
        <div class="flex items-center space-x-4">
          <div class="w-1/12 bg-blue-500 rounded-full h-2"></div>
          <div class="w-full">
            <h4 class="text-lg font-semibold text-gray-800">Surat Domisili</h4>
            <ul class="space-y-2 text-gray-700">
              <li>✅ Diserahkan ke RT: 2025-03-28</li>
              <li>✅ RT Menyetujui: 2025-03-29</li>
              <li>✅ Diserahkan ke RW: 2025-03-30</li>
              <li>✅ RW Menyetujui: 2025-04-01</li>
            </ul>
          </div>
        </div>

        <!-- Proses Surat 2 -->
        <div class="flex items-center space-x-4">
          <div class="w-1/12 bg-yellow-500 rounded-full h-2"></div>
          <div class="w-full">
            <h4 class="text-lg font-semibold text-gray-800">Keterangan Usaha</h4>
            <ul class="space-y-2 text-gray-700">
              <li>⏳ Diserahkan ke RT: 2025-03-22</li>
              <li>⏳ RT Menyetujui: 2025-03-23</li>
              <li>⏳ Diserahkan ke RW: 2025-03-24</li>
            </ul>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

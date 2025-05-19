<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Warga</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            inter: ['Inter', 'sans-serif']
          }
        }
      }
    }
  </script>

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
<!-- Navbar -->
  @include('komponen.nav')


    <!-- Carousel + Teks Selamat Datang -->
    <div class="max-w-5xl mx-auto px-4 mt-6 rounded-xl overflow-hidden shadow">
        <div id="autoCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
            <div class="carousel-inner">

                <!-- Slide 1 -->
                <div class="carousel-item active position-relative">
                    <img src="{{ asset('images/layananMasyarakat5.png') }}" class="d-block w-100 custom-carousel-img" alt="Slide 1">
                    <div class="carousel-caption carousel-text bg-black bg-opacity-50 rounded px-3 py-2">
                        <h5 class="text-white font-bold text-xl md:text-2xl">Selamat Datang, {{ $warga->nama_lengkap }}!</h5>
                        <p class="text-white text-xs md:text-sm mt-1">
                        Gunakan sistem ini untuk mengelola surat pengantar secara digital, cepat, dan mudah.
                        </p>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="carousel-item">
                    <img src="{{ asset('images/layananMasyarakat3.png') }}" class="d-block w-100 custom-carousel-img" alt="Slide 2">
                    <div class="carousel-caption carousel-text bg-black bg-opacity-50 rounded px-3 py-2">
                        <h5 class="text-white font-bold text-xl md:text-2xl">Pelayanan RT/RW Kini Lebih Mudah</h5>
                        <p class="text-white text-xs md:text-sm mt-1">
                        Ajukan surat tanpa harus datang langsung ke pos RT. Semuanya bisa dari rumah.
                        </p>
                    </div>
                </div>

                <!-- Slide 3 -->
                <div class="carousel-item">
                    <img src="{{ asset('images/layananMasyarakat4.png') }}" class="d-block w-100 custom-carousel-img" alt="Slide 3">
                    <div class="carousel-caption carousel-text bg-black bg-opacity-50 rounded px-3 py-2">
                        <h5 class="text-white font-bold text-xl md:text-2xl">Transparansi & Efisiensi untuk Warga</h5>
                        <p class="text-white text-xs md:text-sm mt-1">
                        Lacak status pengajuan surat Anda dan nikmati proses yang lebih cepat dan jelas.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Fitur Card dan Histori Tabel dalam Kotak dengan Opacity -->
<div class="max-w-7xl mx-auto mt-6 px-4 py-6 bg-white bg-opacity-18 rounded-lg">
    <!-- Fitur Card -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Card Ajukan Surat -->
        <div class="feature-card bg-blue-100 p-6 rounded-xl shadow border-l-4 border-blue-500">
            <h2 class="text-lg font-semibold mb-2">📝 Ajukan Surat Pengantar</h2>
            <p class="text-sm text-gray-600 mb-3">Mulai proses pengajuan surat Anda untuk berbagai keperluan.</p>
            <a href="{{ route('pengajuanSuratWarga') }}" class="inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors duration-300 transform transition-transform duration-300 hover:scale-105">
                Ajukan Sekarang
            </a>
        </div>

        <!-- Card Riwayat Surat -->
        <div class="feature-card bg-teal-100 p-6 rounded-xl shadow border-l-4 border-teal-500">
            <h2 class="text-lg font-semibold mb-2">📄 Riwayat Surat</h2>
            <p class="text-sm text-gray-600 mb-3">Lihat status surat yang telah diajukan.</p>
            <a href="{{ route('riwayatSurat') }}" class="inline-block bg-teal-500 text-white px-4 py-2 rounded hover:bg-teal-600 transition-colors duration-300 transform transition-transform duration-300 hover:scale-105">
                Lihat Sekarang
            </a>
        </div>

        <!-- Card Histori Surat -->
        <div class="feature-card bg-pink-100 p-6 rounded-xl shadow border-l-4 border-pink-500">
            <h2 class="text-lg font-semibold mb-2">📜 Histori Surat</h2>
            <p class="text-sm text-gray-600 mb-3">Cek riwayat dan status pengajuan surat yang sudah disetujui atau ditolak.</p>
            <a href="{{ route('historiSuratWarga') }}" class="inline-block bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600 transition-colors duration-300 transform transition-transform duration-300 hover:scale-105">
                Lihat Histori
            </a>
        </div>
    </div>

    <!-- Status Tabel -->
    <div>
        <h3 class="text-xl font-bold text-gray-800 mb-4">
            📂 Status Pengajuan Surat - Bulan {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
        </h3>
        <div class="overflow-x-auto bg-white rounded-lg shadow p-4">
            @if($pengajuanSuratGabungan->isEmpty())
                <p class="text-center text-gray-600">Belum ada pengajuan surat.</p>
            @else
                <!-- Tambahkan max-h dan overflow-y-auto untuk scroll -->
                <div class="max-h-[400px] overflow-y-auto rounded-md">
                    <table class="min-w-full text-sm text-gray-700">
                        <thead class="bg-gray-100 border-b sticky top-0 z-10">
                            <tr>
                                <th class="px-4 py-2 text-left">Tanggal</th>
                                <th class="px-4 py-2 text-left">Tujuan Surat</th>
                                <th class="px-4 py-2 text-left">Status</th>
                                <th class="px-4 py-2 text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($pengajuanSuratGabungan as $surat)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-4 py-2">{{ $surat->created_at->format('Y-m-d') }}</td>
                                    <td class="px-4 py-2">{{ $surat->tujuan }}</td>
                                    <td class="px-4 py-2">
                                        @if($surat->status === 'disetujui')
                                            <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">Disetujui</span>
                                        @elseif($surat->status === 'menunggu')
                                            <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded">Menunggu</span>
                                        @elseif($surat->status === 'ditolak')
                                            <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded">Ditolak</span>
                                        @else
                                            <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2.5 py-0.5 rounded">Belum Diproses</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">
                                        <a href="{{ route('riwayatSurat') }}" class="text-blue-500 hover:underline">Lihat</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

</div>

  <div class="max-w-7xl mx-auto mt-10 bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">❓ Pertanyaan yang Sering Diajukan (FAQ)</h2>
    <div class="space-y-4">
      <div class="border rounded-lg p-4 shadow-sm">
        <h3 class="font-semibold text-gray-800">Bagaimana cara mengajukan surat?</h3>
        <p class="text-sm text-gray-600 mt-1">Klik tombol "Ajukan Surat" di dashboard dan isi formulir sesuai keperluan.</p>
      </div>
      <div class="border rounded-lg p-4 shadow-sm">
        <h3 class="font-semibold text-gray-800">Berapa lama proses pengajuan surat?</h3>
        <p class="text-sm text-gray-600 mt-1">Proses biasanya memakan waktu 1-2 hari kerja tergantung persetujuan ketua RT/RW.</p>
      </div>
      <div class="border rounded-lg p-4 shadow-sm">
        <h3 class="font-semibold text-gray-800">Bagaimana cara melihat status pengajuan surat saya?</h3>
        <p class="text-sm text-gray-600 mt-1">Setelah pengajuan, Anda bisa melacak status surat Anda melalui halaman riwayat pengajuan surat pada dashboard.</p>
      </div>
    </div>
  </div>


  {{-- Bantuan --}}
  <div class="max-w-7xl mx-auto mt-10 bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">🆘 Bantuan</h2>
    <p class="text-gray-700 mb-4">Jika Anda mengalami kendala, silakan hubungi kami melalui:</p>
    <ul class="list-disc list-inside text-gray-600 space-y-2">
      <li>Email: <a href="mailto:bantuan@rtrw.id" class="text-blue-600 underline">bantuan@rtrw.id</a></li>
      <li>WhatsApp Admin: <a href="https://wa.me/6281234567890" class="text-green-600 underline">+62 812-3456-7890</a></li>
    </ul>

    <div class="mt-6">
      <a href="#" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">📘 Panduan Penggunaan</a>
    </div>
  </div>



  <!-- Informasi -->
  <div class="max-w-7xl mx-auto mt-12 px-4">
    <div class="bg-yellow-100 p-4 rounded-md shadow text-yellow-700 text-sm flex items-center">
      <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <span><strong>Info:</strong> RT Anda aktif setiap hari kerja pukul 08.00 – 16.00 untuk memproses surat.</span>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

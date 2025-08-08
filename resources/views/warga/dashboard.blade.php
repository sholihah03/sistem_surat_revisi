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
<body class="min-h-screen bg-yellow-50">

    <!-- Navbar -->
    @include('komponen.nav')

    {{-- Notifikasi kondisi KK --}}
    @if ($dataBelumLengkap)
        {{-- KK belum diupload --}}
        <div class="fixed left-0 right-0 z-30 px-4">
            <div class="max-w-5xl mx-auto bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative shadow-md">
                <strong class="font-bold">Data Belum Lengkap!</strong>
                <span class="block sm:inline">
                    Silakan lengkapi data diri Anda terlebih dahulu dengan menginputkan no KK dan NIK Anda
                </span>
                <div class="mt-3">
                    <a href="{{ route('cekKKForm') }}"
                    class="inline-block bg-red-500 hover:bg-red-600 text-white font-semibold py-1.5 px-4 rounded transition duration-200">
                        Cek Sekarang
                    </a>
                </div>
            </div>
        </div>
    @elseif ($statusKK === 'pending')
        {{-- KK sudah diupload tapi belum diverifikasi --}}
        <div class="fixed left-0 right-0 z-30 px-4">
            <div class="max-w-5xl mx-auto bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded relative shadow-md">
                <strong class="font-bold">Tunggu Verifikasi!</strong>
                <span class="block sm:inline">
                    Data diri Anda sedang diverifikasi oleh RT. Proses ini memakan waktu hingga 24 jam.
                    Anda akan mendapatkan informasi melalui email setelah proses selesai.
                </span>
            </div>
        </div>
    @elseif ($statusKK === 'ditolak')
        {{-- KK ditolak --}}
        <div class="fixed left-0 right-0 z-30 px-4">
            <div class="max-w-5xl mx-auto bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative shadow-md">
                <strong class="font-bold">Data Ditolak!</strong>
                <span class="block sm:inline">
                    Data diri Anda ditolak oleh RT.
                    Alasan: <em>{{ $alasanPenolakan ?? 'Tidak ada alasan yang diberikan.' }}</em>
                </span>
                <div class="mt-3">
                    <a href="{{ route('cekKKForm') }}"
                    class="inline-block bg-red-500 hover:bg-red-600 text-white font-semibold py-1.5 px-4 rounded transition duration-200">
                        Upload Ulang
                    </a>
                </div>
            </div>
        </div>
    @endif


    <!-- Modal pesan data berhasil lengkap-->
      @if(session('statusLengkap'))
        <div id="successDataModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white rounded-lg shadow-lg p-8 relative w-[520px] text-center animate-scale">
                <!-- Tombol Close -->
                <button onclick="closeDataModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>

                <!-- Ikon Ceklis -->
                <div class="flex justify-center mb-6">
                    <img src="https://img.icons8.com/color/96/000000/ok--v1.png" alt="Success Icon" class="w-20 h-20">
                </div>

                <!-- Judul -->
                <h2 class="text-2xl font-bold mb-4 text-gray-800 whitespace-nowrap">
                    No KK Anda sudah terdaftar.
                </h2>

                <!-- Deskripsi -->
                <p class="text-gray-600 mb-8 text-base leading-relaxed">
                    Selamat sekarang data diri Anda sudah lengkap.
                </p>

                <!-- Tombol Tutup -->
                <button onclick="closeDataModal()" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 rounded-lg">
                    Tutup
                </button>
            </div>
        </div>

        <script>
        function closeDataModal() {
            document.getElementById('successDataModal').style.display = 'none';
        }
        </script>

        <style>
        @keyframes scaleUp {
            from {
                transform: scale(0.8);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }
        .animate-scale {
            animation: scaleUp 0.3s ease-out;
        }
        </style>
        @endif

    <!-- Modal Upload KK-->
        @if(session('success_upload_kk'))
        <div id="successModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white rounded-lg shadow-lg p-8 relative w-[520px] text-center animate-scale">
                <!-- Tombol Close -->
                <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>

                <!-- Ikon Ceklis -->
                <div class="flex justify-center mb-6">
                    <img src="https://img.icons8.com/color/96/000000/ok--v1.png" alt="Success Icon" class="w-20 h-20">
                </div>

                <!-- Judul -->
                <h2 class="text-2xl font-bold mb-4 text-gray-800 whitespace-nowrap">
                    Terima Kasih Sudah Melengkapi<br>Data Diri Anda.
                </h2>

                <!-- Deskripsi -->
                <p class="text-gray-600 mb-8 text-base leading-relaxed">
                    Data Anda saat ini sedang divalidasi oleh pihak RT.<br>
                    Mohon menunggu hingga 24 jam untuk informasi berikutnya lewat Email.
                </p>

                <!-- Tombol Tutup -->
                <button onclick="closeModal()" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 rounded-lg">
                    Tutup
                </button>
            </div>
        </div>

        <script>
        function closeModal() {
            document.getElementById('successModal').style.display = 'none';
        }
        </script>

        <style>
        @keyframes scaleUp {
            from {
                transform: scale(0.8);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }
        .animate-scale {
            animation: scaleUp 0.3s ease-out;
        }
        </style>
        @endif

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
        <!-- Status Tabel -->
        <div>
            <h3 class="text-xl font-bold text-gray-800 mb-4">
                📂 Status Pengajuan Surat - Bulan {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
            </h3>
            <div class="overflow-x-auto bg-white rounded-lg shadow p-4">
                @if($pengajuanSuratGabungan->isEmpty())
                    <p class="text-center text-gray-600">Belum ada pengajuan surat.</p>
                @else
                    <div class="max-h-[400px] overflow-y-auto rounded-md">
                        <table class="min-w-full text-sm text-gray-700 border border-gray-300">
                            <thead class="bg-gray-100 sticky top-0 z-10">
                                <tr>
                                    <th class="px-4 py-2 text-center border border-gray-300">Tanggal Diajukan</th>
                                    <th class="px-4 py-2 text-center border border-gray-300">Tujuan Surat</th>
                                    <th class="px-4 py-2 text-center border border-gray-300">Status Persetujuan RT</th>
                                    <th class="px-4 py-2 text-center border border-gray-300">Status Persetujuan RW</th>
                                    <th class="px-4 py-2 text-center border border-gray-300">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                @foreach($pengajuanSuratGabungan as $surat)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-4 py-2 border border-gray-300">{{ $surat->created_at->translatedFormat('d F Y') }}</td>
                                        <td class="px-4 py-2 border border-gray-300">{{ $surat->tujuan }}</td>
                                        <td class="px-4 py-2 border border-gray-300">
                                            @if($surat->status_rt_universal === 'disetujui')
                                                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">Disetujui</span>
                                            @elseif($surat->status_rt_universal === 'menunggu')
                                                <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded">Menunggu</span>
                                            @elseif($surat->status_rt_universal === 'ditolak')
                                                <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded">Ditolak</span>
                                            @else
                                                <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2.5 py-0.5 rounded">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 border border-gray-300">
                                            @if($surat->status_rt_universal === 'ditolak')
                                                <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                                    Ditolak oleh RT
                                                </span>
                                            @elseif($surat->status_rw_universal === 'disetujui')
                                                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                                    Disetujui
                                                </span>
                                            @elseif($surat->status_rw_universal === 'menunggu')
                                                <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                                    Menunggu
                                                </span>
                                            @elseif($surat->status_rw_universal === 'ditolak')
                                                <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                                    Ditolak
                                                </span>
                                            @else
                                                <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                                    -
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 border border-gray-300">
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
            <p class="text-sm text-gray-600 mt-1">
                Klik tombol <strong class="text-blue-700">"Ajukan Sekarang"</strong> di halaman dashboard, kemudian pilih jenis pengajuan yang Anda inginkan.<br>
                Jika tidak menemukan jenis pengajuan yang sesuai, pilih opsi <strong class="text-blue-700">"Tidak ada jenis pengajuan yang cocok"</strong>.
                Setelah itu, lengkapi formulir sesuai keperluan Anda.
            </p>
        </div>
        <div class="border rounded-lg p-4 shadow-sm">
            <h3 class="font-semibold text-gray-800">Berapa lama proses pengajuan surat?</h3>
            <p class="text-sm text-gray-600 mt-1">
                Proses pengajuan biasanya memakan waktu 1–3 hari kerja, tergantung pada persetujuan dari ketua RT/RW.
            </p>
        </div>
        <div class="border rounded-lg p-4 shadow-sm">
            <h3 class="font-semibold text-gray-800">Bagaimana cara melihat status pengajuan surat saya?</h3>
            <p class="text-sm text-gray-600 mt-1">
                Setelah mengajukan surat, Anda dapat memantau status pengajuan melalui halaman <strong class="text-blue-700">Riwayat Surat</strong> di dashboard.
            </p>
        </div>
    </div>
</div>


    {{-- Bantuan --}}
@php
if (! function_exists('formatNomorIndo')) {
    function formatNomorIndo($nomor)
    {
        // Bersihkan karakter selain angka
        $nomor = preg_replace('/[^0-9]/', '', $nomor);

        // Jika kosong, tampilkan placeholder
        if (!$nomor) return 'Belum tersedia';

        // Normalisasi: jika mulai dengan 0 ganti jadi 62
        if (substr($nomor, 0, 1) === '0') {
            $nomor = '62' . ltrim($nomor, '0');
        }

        // Tambahkan tanda +
        $nomor = '+' . $nomor;

        // Format: +62 877-7981-9104 (regex sederhana)
        return preg_replace('/(\+62)(\d{2,4})(\d{3,4})(\d{3,4})/', '$1 $2-$3-$4', $nomor);
    }
}
@endphp


<div class="max-w-7xl mx-auto mt-10 bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">🆘 Bantuan</h2>
    <p class="text-gray-700 mb-4">Jika Anda mengalami kendala, silakan hubungi kami melalui:</p>
    <ul class="list-disc list-inside text-gray-600 space-y-2">
        <li>Email:
            <a href="mailto:sistemsurat007010@gmail.com?subject=Permintaan%20Bantuan&body=Halo,%20saya%20membutuhkan%20bantuan%20terkait..." class="text-blue-600 underline">
                sistemsurat007010@gmail.com
            </a>
        </li>
        <li>WhatsApp Admin:
            <a href="https://wa.me/6287779819104" class="text-green-600 underline">
                {{ formatNomorIndo('6287779819104') }}
            </a>
        </li>
    </ul>

    <p class="text-gray-700 mt-4">Jika membutuhkan informasi lebih lanjut dengan RT/RW, silakan hubungi nomer telepon atau email dibawah ini:</p>

    <ul class="list-disc list-inside text-gray-600 space-y-2 mt-2">RT
        <li>Email:
            <a href="mailto:{{ $rt->email_rt ?? '#' }}" class="text-blue-600 underline">
                {{ $rt->email_rt ?? 'Belum tersedia' }}
            </a>
        </li>
        <li>WhatsApp:
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $rt->no_hp_rt ?? '') }}" class="text-green-600 underline">
                {{ formatNomorIndo($rt->no_hp_rt ?? '') }}
            </a>
        </li>
    </ul>

    <ul class="list-disc mt-2 list-inside text-gray-600 space-y-2">RW
        <li>Email:
            <a href="mailto:{{ $rw->email_rw ?? '#' }}" class="text-blue-600 underline">
                {{ $rw->email_rw ?? 'Belum tersedia' }}
            </a>
        </li>
        <li>WhatsApp:
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $rw->no_hp_rw ?? '') }}" class="text-green-600 underline">
                {{ formatNomorIndo($rw->no_hp_rw ?? '') }}
            </a>
        </li>
    </ul>

    <div class="mt-6">
        <a href="{{ route('panduan') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">📘 Panduan Penggunaan</a>
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

    @include('components.modal-timeout')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

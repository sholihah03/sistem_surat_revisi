<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Panduan Penggunaan Web</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Bootstrap CSS (opsional, tapi tidak dibutuhkan kalau pakai Tailwind) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="min-h-screen bg-yellow-50">
    @include('komponen.nav')

    <!-- Breadcrumb -->
    <nav class="max-w-7xl mx-auto px-4 pt-6 text-sm text-gray-600">
        <ol class="flex items-center space-x-2">
            <li><a href="{{ route('dashboardWarga') }}" class="text-blue-600 no-underline">Home</a></li>
            <li>/</li>
            <li class="text-gray-800 font-medium">Panduan Penggunaan Web</li>
        </ol>
    </nav>

    <!-- Panduan -->
    <section class="max-w-7xl mx-auto bg-white p-6 mt-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">📘 Panduan Penggunaan Web Pelayanan Warga</h1>

        <!-- Langkah 1 -->
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-700 mb-2">1. Masuk ke Dashboard</h2>
            <p class="text-gray-600">Setelah login, warga akan langsung diarahkan ke halaman <strong>Dashboard</strong> untuk melihat layanan yang tersedia.</p>
        </div>

        <!-- Langkah 2 -->
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-700 mb-2">2. Mengajukan Surat Pengantar</h2>
            <p class="text-gray-600 mb-2">Klik tombol <strong>Ajukan Sekarang</strong> di bagian <strong>Ajukan Surat Pengantar</strong>.</p>
            <ul class="list-disc list-inside text-gray-600 pl-4">
                <li>Pilih tujuan dari surat pengantar sesuai kebutuhan.</li>
                <li>Jika tujuan tidak tersedia, pilih <strong>"Tidak Ada Jenis Pengajuan yang Cocok"</strong>.</li>
                <li>Setelah itu, isi formulir yang muncul dengan lengkap dan benar.</li>
            </ul>
        </div>

        <!-- Langkah 3 -->
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-700 mb-2">3. Melihat Proses Surat</h2>
            <p class="text-gray-600">Klik menu <strong>Riwayat Surat</strong> untuk melihat proses surat yang sudah diajukan. Di sana akan terlihat apakah surat masih diproses atau sudah selesai.</p>
        </div>

        <!-- Langkah 4 -->
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-700 mb-2">4. Melihat dan Mengunduh Surat Lama</h2>
            <p class="text-gray-600">Untuk melihat surat yang sudah selesai beberapa waktu lalu atau ingin mengunduhnya kembali, silakan buka menu <strong>Histori Surat</strong>.</p>
        </div>

        <!-- Langkah 5 -->
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-700 mb-2">5. Mengedit Profil</h2>
            <p class="text-gray-600">Jika ingin mengganti email atau nomor HP, klik <strong>ikon profil</strong> di bagian atas sebelah kanan, tepat di samping ikon notifikasi. Pilih <strong>Ubah</strong> untuk melakukan perubahan.</p>
        </div>

        <!-- Langkah 6 -->
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-700 mb-2">6. Masih Bingung atau Ada Kendala?</h2>
            <p class="text-gray-600">Silakan hubungi kami melalui <strong>Email</strong> atau <strong>WhatsApp Admin</strong> yang ada di halaman sebelumnya.</p>
        </div>

        <!-- Penutup -->
        <div class="mt-8 p-4 bg-blue-100 rounded-md text-sm text-blue-800">
            Semoga panduan ini bisa membantu Anda menggunakan layanan surat dengan lebih mudah. Terima kasih atas partisipasinya dalam sistem pelayanan warga berbasis digital ini.
        </div>
    </section>

    @include('components.modal-timeout')
</body>
</html>

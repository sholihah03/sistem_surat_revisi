<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil Verifikasi Surat</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-lg w-full bg-white shadow-lg rounded-lg p-6 text-center">

        @if ($status === 'valid')
            <div class="text-green-600 text-4xl font-bold mb-4">✅ Valid</div>
            <p class="text-lg mb-2">Surat ini <strong>asli</strong> dan belum dimodifikasi.</p>
            <div class="bg-gray-50 border rounded-lg p-4 text-left mt-4">
                <p><strong>Nama Warga:</strong> {{ $pengajuan->warga->nama_lengkap }}</p>
                <p><strong>NIK:</strong> {{ $pengajuan->warga->nik }}</p>
                <p><strong>RW:</strong> {{ $pengajuan->warga->rt->rw->no_rw }} {{ $pengajuan->warga->rt->rw->nama_lengkap_rw }}</p>
                <p><strong>RT:</strong> {{ $pengajuan->warga->rt->no_rt }} {{ $pengajuan->warga->rt->nama_lengkap_rt }}</p>
                <p><strong>Tanggal TTD RT:</strong> {{ $hasilSuratRt ? $hasilSuratRt->created_at->format('d-m-Y H:i') : '-' }}</p>
                <p><strong>Tanggal TTD RW:</strong> {{ $hasilSuratRw->created_at->format('d-m-Y H:i') }}</p>
            </div>
        @else
            <div class="text-red-600 text-4xl font-bold mb-4">❌ Tidak Valid</div>
            <p class="text-lg">{{ $pesan }}</p>
        @endif

        <a href="/" class="mt-6 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Kembali ke Beranda</a>
    </div>
</body>
</html>


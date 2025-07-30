<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Dokumen Surat</title>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white shadow-lg rounded-lg p-6 w-full max-w-xl">
        @if ($status === 'valid')
            <h2 class="text-xl font-bold text-green-600 mb-4">✅ Dokumen Valid</h2>
            <p><strong>Nama:</strong> {{ $pengajuan->warga->nama_lengkap }}</p>
            <p><strong>Jenis Surat:</strong> {{ $hasilSurat->jenis }}</p>

            <p>
                <strong>Ditandatangani oleh RT:</strong>
                {{ $pengajuan->warga->rt->nama_lengkap_rt }}
                pada
                {{ optional($pengajuan->hasilSuratTtdRt)->created_at?->format('d-m-Y') ?? '-' }}
            </p>

            <p>
                <strong>Disetujui oleh RW:</strong>
                {{ $pengajuan->warga->rt->rw->nama_lengkap_rw }}
                pada
                {{ $hasilSurat->created_at->format('d-m-Y') }}
            </p>

            {{-- <a class="text-blue-600 underline mt-4 inline-block" href="{{ asset('storage/' . $hasilSurat->file_surat) }}" target="_blank">📄 Lihat Surat PDF</a> --}}
        @else
            <h2 class="text-xl font-bold text-red-600 mb-4">❌ Dokumen Tidak Valid</h2>
            <p>{{ $pesan }}</p>
        @endif
    </div>
</body>
</html>

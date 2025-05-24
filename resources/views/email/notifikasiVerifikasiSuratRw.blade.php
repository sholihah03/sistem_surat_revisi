<!DOCTYPE html>
<html>
<head>
    <title>Verifikasi Surat Dibutuhkan</title>
</head>
<body>
    <p>Yth. Bapak/Ibu RW {{ $namaRw }},</p>

    <p>Telah disetujui oleh RT surat pengajuan <strong>{{ $jenisSurat }}</strong> milik warga <strong>{{ $namaWarga }}</strong>.</p>

    <p>Silakan lakukan verifikasi surat tersebut di sistem RW.</p>
    <p>
        <a href="{{ route('manajemenSuratWarga')}}" style="background: #3490dc; padding: 10px 20px; color: white; text-decoration: none; border-radius: 5px;">
            Verifikasi Sekarang
        </a>
    </p>

    <p>Terima kasih,<br/>Sistem Informasi RT</p>
</body>
</html>

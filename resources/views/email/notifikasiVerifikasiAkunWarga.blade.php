<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Notifikasi Verifikasi</title>
</head>
<body>
    <p>Yth. Ketua RT {{ $pendaftaran->rt->no_rt ?? '-' }},</p>
    <p>Ada pendaftaran warga baru dengan Nama Warga: <strong>{{ $pendaftaran->nama_lengkap }}</strong> yang sudah mengunggah KK dan menunggu verifikasi.</p>

    <p>Silakan verifikasi dalam waktu 24 jam melalui link berikut:</p>

    <p>
        <a href="{{ route('verifikasiAkunWarga')}}" style="background: #3490dc; padding: 10px 20px; color: white; text-decoration: none; border-radius: 5px;">
            Verifikasi Sekarang
        </a>
    </p>

    <p>Terima kasih.</p>
</body>
</html>

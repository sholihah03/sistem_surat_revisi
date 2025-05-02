<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Notifikasi Verifikasi</title>
</head>
<body>
    <h3>Hai RT,</h3>
    <p>Ada pendaftaran warga baru atas nama <strong>{{ $nama }}</strong>.</p>
    <p>Silakan verifikasi akun tersebut sebelum <strong>{{ $batasWaktu }}</strong>.</p>
    <p>
        Klik tombol di bawah ini untuk langsung menuju halaman verifikasi:
    </p>
    <p>
        <a href="{{ $link }}" style="background: #4CAF50; color: white; padding: 10px 20px; text-decoration: none;">Verifikasi Sekarang</a>
    </p>
    <p>Terima kasih.</p>
</body>
</html>

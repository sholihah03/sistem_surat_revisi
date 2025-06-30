<!DOCTYPE html>
<html>
<head>
    <title>OTP Reset Password</title>
</head>
<body>
    <p>Halo, Bapak/Ibu {{ $nama }}!</p>

    <p>Berikut adalah kode OTP untuk mereset password akun Anda:</p>

    <h2 style="font-size: 24px; color: #111;">{{ $kodeOtp }}</h2>

    <p>Kode ini berlaku selama 120 detik. Silakan masukkan kode tersebut pada halaman verifikasi OTP:</p>
    <p>
        <a href="{{ $linkVerifikasi }}" style="background: #3490dc; padding: 10px 20px; color: white; text-decoration: none; border-radius: 5px;">Masukkan Sekarang</a>
    </p>

    <p>Jika Anda tidak meminta reset password, abaikan email ini.</p>

    <br>
    <p>Salam,</p>
    <p><strong>Sistem Administrasi Surat RT/RW</strong></p>
</body>
</html>

<h2>Halo, {{ $nama }}</h2>
<p>Berikut Kode OTP Baru Anda</p>

<p><strong>Kode OTP Anda:</strong></p>
<h3 style="font-size: 28px;">{{ $otp }}</h3>
<p>Kode ini akan kedaluwarsa dalam 60 detik.</p>

<p>Silakan klik tombol di bawah ini untuk memverifikasi OTP:</p>
<a href="{{ $link }}" style="background: #3490dc; padding: 10px 20px; color: white; text-decoration: none; border-radius: 5px;">Verifikasi Sekarang</a>

<p>Terima kasih!</p>

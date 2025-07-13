<h2>Halo, {{ $nama }}</h2>
<p>Akun Anda telah <strong>disetujui</strong>.</p>

<p><strong>Kode OTP Anda:</strong></p>
<h3 style="font-size: 28px;120">{{ $otp }}</h3>
<p>Kode ini akan kedaluwarsa dalam 120 detik.</p>

<p>Silakan klik tombol di bawah ini untuk memverifikasi OTP:</p>
<a href="{{ $link }}" style="background: #3490dc; padding: 10px 20px; color: white; text-decoration: none; border-radius: 5px;">Kirim Sekarang</a>

<p>Terima kasih!</p>

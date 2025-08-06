<!DOCTYPE html>
<html>
<head>
    <title>Verifikasi KK Baru</title>
</head>
<body>
    <h2>Permintaan Verifikasi KK Baru</h2>
    <p>Yth. Ketua RT,</p>

    <p>Telah diunggah data KK baru dengan informasi berikut:</p>

    <ul>
        <li><strong>Nama Kepala Keluarga:</strong> {{ $scan->nama_kepala_keluarga }}</li>
        <li><strong>No KK:</strong> {{ $scan->no_kk_scan }}</li>
        <li><strong>Alamat:</strong> {{ $alamat->nama_jalan }}, RT {{ $alamat->rt_alamat }}/RW {{ $alamat->rw_alamat }}</li>
    </ul>

    <p>Silakan klik link berikut untuk memverifikasi data:</p>
    <p><a href="{{ route('verifikasiAkunWarga') }}" style="background: #3490dc; padding: 10px 20px; color: white; text-decoration: none; border-radius: 5px;">Verifikasi Sekarang</a></p>

    <p>Terima kasih.</p>
</body>
</html>

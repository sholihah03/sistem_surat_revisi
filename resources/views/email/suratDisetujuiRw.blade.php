<!DOCTYPE html>
<html>
<head>
    <title>Surat Pengantar</title>
</head>
<body>
    <p>Yth. Bapak/Ibu {{ $pengajuan->warga->nama_lengkap }}</p>

    @php
        $tujuanSurat = $pengajuan instanceof \App\Models\PengajuanSurat
            ? ($pengajuan->tujuanSurat->nama_tujuan ?? '-')
            : ($pengajuan->tujuan_manual ?? '-');
    @endphp

    <p>Pengajuan surat <strong>{{ $tujuanSurat }}</strong> Anda telah <strong>disetujui</strong> oleh RW.</p>
    <p>Silakan cek surat pengajuan Anda di sistem kami.</p>
    <p>
        <a href="{{ route('historiSuratWarga') }}" style="background: #3490dc; padding: 10px 20px; color: white; text-decoration: none; border-radius: 5px;">
            Lihat Sekarang
        </a>
    </p>

    <p>Terima kasih,<br/>Sistem Administrasi RT/RW</p>
</body>
</html>

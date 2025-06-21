<!DOCTYPE html>
<html>
<head>
    <title>Surat Pengantar Ditolak</title>
</head>
<body>
    <p>Yth. Bapak/Ibu {{ $pengajuan->warga->nama_lengkap }}</p>

    @php
        $tujuanSurat = $pengajuan instanceof \App\Models\PengajuanSurat
            ? ($pengajuan->tujuanSurat->nama_tujuan ?? '-')
            : ($pengajuan->tujuan_manual ?? '-');
    @endphp

    <p>Pengajuan surat <strong>{{ $tujuanSurat }}</strong> Anda telah <strong>DITOLAK</strong> oleh RW.</p>

    <p><strong>Alasan Penolakan:</strong></p>
    <blockquote style="color: #c0392b; font-style: italic; border-left: 4px solid #e74c3c; padding-left: 10px;">
        {{ $alasanPenolakan }}
    </blockquote>

    <p>Silakan cek pengajuan Anda di sistem kami atau hubungi RT/RW untuk informasi lebih lanjut.</p>

    <p>
        <a href="{{ route('historiSuratWarga') }}"
           style="background: #3490dc; padding: 10px 20px; color: white; text-decoration: none; border-radius: 5px;">
            Lihat Sekarang
        </a>
    </p>

    <p>Terima kasih,<br/>Sistem Administrasi RT/RW</p>
</body>
</html>

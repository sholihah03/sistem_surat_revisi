<!DOCTYPE html>
<html>
<head>
    <title>Surat Pengantar Warga Ditolak</title>
</head>
<body>
    <p>Yth. Bapak/Ibu RT {{ $pengajuan->warga->rt->no_rt }}</p>

    @php
        $tujuanSurat = $pengajuan instanceof \App\Models\PengajuanSurat
            ? ($pengajuan->tujuanSurat->nama_tujuan ?? '-')
            : ($pengajuan->tujuan_manual ?? '-');
    @endphp

    <p>Pengajuan surat <strong>{{ $tujuanSurat }}</strong> oleh warga <strong>{{ $pengajuan->warga->nama_lengkap }}</strong> telah <strong>DITOLAK</strong> oleh RW.</p>

    <p><strong>Alasan Penolakan:</strong></p>
    <blockquote style="color: #c0392b; font-style: italic; border-left: 4px solid #e74c3c; padding-left: 10px;">
        {{ $alasanPenolakan }}
    </blockquote>

    <p>Silakan tindak lanjuti atau arahkan warga untuk melakukan perbaikan pengajuan.</p>

    <p>Terima kasih,<br/>Sistem Administrasi RT/RW</p>
</body>
</html>

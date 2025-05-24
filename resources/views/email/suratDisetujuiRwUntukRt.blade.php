<!DOCTYPE html>
<html>
<head>
    <title>Surat Pengantar</title>
</head>
<body>
    <p>Yth. Bapak/Ibu RT {{ $pengajuan->warga->rt->no_rt }}</p>

    @php
        $tujuanSurat = $pengajuan instanceof \App\Models\PengajuanSurat
            ? ($pengajuan->tujuanSurat->nama_tujuan ?? '-')
            : ($pengajuan->tujuan_manual ?? '-');
    @endphp

    <p>Pengajuan surat <strong>{{ $tujuanSurat }}</strong> Warga <strong>{{ $pengajuan->warga->nama_lengkap }}</strong> telah <strong>disetujui</strong> oleh RW.</p>

    <p>Terima kasih,<br/>Sistem Administrasi RT/RW</p>
</body>
</html>

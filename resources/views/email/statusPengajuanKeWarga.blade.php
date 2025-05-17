<!DOCTYPE html>
<html>
<head>
    <title>Notifikasi Pengajuan Surat</title>
</head>
<body>
    <p>Yth. Bapak/Ibu {{ $namaWarga }},</p>

    @if($status == 'disetujui')
        <p>Pengajuan surat <strong>{{ $jenisSurat }}</strong> Anda telah <strong>disetujui</strong>.</p>
        <p>Silakan cek status dan detail pengajuan Anda di sistem kami.</p>
        <p>
            <a href="{{ $linkDetail }}" style="background: #28a745; padding: 10px 20px; color: white; text-decoration: none; border-radius: 5px;">
                Lihat Detail Pengajuan
            </a>
        </p>
    @elseif($status == 'ditolak')
        <p>Pengajuan surat <strong>{{ $jenisSurat }}</strong> Anda telah <strong>ditolak</strong>.</p>
        <p><strong>Alasan penolakan:</strong><br/>
           {{ $alasanPenolakan }}
        </p>
        <p>Silakan ajukan kembali pengajuan Anda di sistem kami.</p>
        <p>
            <a href="{{ $linkDetail }}" style="background: #dc3545; padding: 10px 20px; color: white; text-decoration: none; border-radius: 5px;">
                Lihat Detail Pengajuan
            </a>
        </p>
    @else
        <p>Status pengajuan tidak diketahui.</p>
    @endif

    <p>Terima kasih,<br/>Sistem Administrasi RT/RW</p>
</body>
</html>

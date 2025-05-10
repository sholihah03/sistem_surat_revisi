<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Notifikasi Pengajuan Surat</title>
</head>
<body>
    <h2>Pengajuan Surat Baru</h2>
    <p>Warga <strong>{{ $namaWarga }}</strong> telah mengajukan permohonan surat.</p>

    @if ($jenisPengajuan)
        <p>Jenis pengajuan: <strong>{{ $jenisPengajuan }}</strong>.</p>
    @else
        <p>Silakan periksa pengajuan tersebut secara langsung di sistem karena jenis surat ditulis manual oleh warga.</p>
    @endif

    <p>Login ke sistem untuk melakukan pengecekan dan verifikasi.</p>
    {{-- <p>
        <a href="{{ route('verifikasiAkunWarga')}}" style="background: #3490dc; padding: 10px 20px; color: white; text-decoration: none; border-radius: 5px;">
            Verifikasi Sekarang
        </a>
    </p> --}}
</body>
</html>

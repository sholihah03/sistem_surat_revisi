<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Pengantar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }

        .kop-surat {
            display: flex;
            align-items: center;
            padding-bottom: 0;
        }

        .content {
            position: relative;
        }

        .header-table {
            width: 100%;
            border-bottom: 2px solid black;
            margin-bottom: 5px; /* Mengurangi jarak bawah kop surat */
        }

        .header-table td {
            vertical-align: top;
        }

        .header-logo {
            width: 80px;
        }

        .header-text {
            text-align: center;
        }

        .header-text h1, .header-text h2, .header-text h3 {
            margin: 0;
            font-weight: bold;
        }

        .header-text p {
            margin: 2px 0;
            font-size: 10pt;
        }

        .kepada {
            text-align: right;
            margin-bottom: 15px;
        }

        .judul {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 5px;
        }

        .nomor-surat {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-section {
            margin-bottom: 10px;
        }

        .form-section label {
            display: inline-block;
            width: 180px;
            vertical-align: top;
        }

        .ttd {
        margin-top: 10px;
        flex-grow: 1;
        }

        .ttd-table {
        width: 100%;
        text-align: center;
        }

        .ttd-table td {
        vertical-align: bottom;
        padding-top: 20px;
        }
    </style>
</head>
<body>

    <!-- KOP SURAT -->
    <div class="kop-surat">
        <table class="header-table">
            <tr>
                <td>
                    <img src="{{ public_path('images/Logo_Indramayu.png') }}" alt="Logo" class="header-logo">
                </td>
                <td class="header-text">
                    <h3>PEMERINTAH KABUPATEN INDRAMAYU</h3>
                    <h2>KECAMATAN INDRAMAYU</h2>
                    <h2>KELURAHAN MARGADADI</h2>
                    <p>Jl. May Sastra Atmaja Nomor : 47 Tlp. (0234) 273 301 Kode Pos 45211</p>
                    <p>e-mail : kelurahanmargadadi.indramayu@gmail.com</p>
                    <h3>INDRAMAYU</h3>
                </td>
            </tr>
        </table>
    </div>

    <!-- ISI SURAT -->
    <div class="content">
        <div class="kepada">
            <p style="text-align: lift;">Kepada
            <br>Yth. Lurah Margadadi
            <br>di_
            <br><strong style="text-align: center;">TEMPAT</strong></p>
        </div>

        <div class="judul">SURAT PENGANTAR</div>
        <p class="nomor-surat">Nomor: {{ $pengajuan->tujuanSurat->nomor_surat ?? '.........................' }}</p>

        <p>Yang bertanda tangan di bawah ini, Ketua RT {{ $rt->no_rt }} RW {{ $rt->rw->no_rw }} Kelurahan Margadadi Kecamatan Indramayu Kabupaten Indramayu, memberikan pengantar kepada:</p>

        <div class="form-section">
            <label>Nama</label>: {{ $pengajuan->warga->nama_lengkap }}
        </div>
        <div class="form-section">
            <label>Tempat/ Tanggal Lahir</label>:
            @if ($jenis === 'biasa')
                {{ $pengajuan->tempat_lahir ?? '-' }},
                {{ \Carbon\Carbon::parse($pengajuan->tanggal_lahir)->translatedFormat('d F Y') }}
            @else
                {{ $pengajuan->tempat_lahir_pengaju_lain ?? '-' }},
                {{ \Carbon\Carbon::parse($pengajuan->tanggal_lahir_pengaju_lain)->translatedFormat('d F Y') }}
            @endif
        </div>
        <div class="form-section">
            <label>Nomor KTP</label>: {{ $pengajuan->warga->nik }}
        </div>
        <div class="form-section"><label>Status Perkawinan</label>:
            @if ($jenis === 'biasa')
                {{ $pengajuan->status_perkawinan ?? '-' }}
            @else
                {{ $pengajuan->status_perkawinan_pengaju_lain ?? '-' }}
            @endif
        </div>
        <div class="form-section">
            <label>Kebangsaan/ Agama</label>:
            @if ($jenis === 'biasa')
                {{ $pengajuan->agama ?? '-' }}
            @else
                {{ $pengajuan->agama_pengaju_lain ?? '-' }}
            @endif
        </div>
        <div class="form-section">
            <label>Pekerjaan</label>:
            @if ($jenis === 'biasa')
                {{ $pengajuan->pekerjaan ?? '-' }}
            @else
                {{ $pengajuan->pekerjaan_pengaju_lain ?? '-' }}
            @endif
        </div>
        <div class="form-section">
            <label>Alamat</label>:
            @php
                $alamat = $pengajuan->scanKk->alamat ?? null;
            @endphp

            @if ($alamat)
                {{ $alamat->nama_jalan }},
                RT {{ $alamat->rt_alamat ?? '-' }}/RW {{ $alamat->rw_alamat ?? '-' }},
                Kel. {{ $alamat->kelurahan ?? '-' }},
                Kec. {{ $alamat->kecamatan ?? '-' }},
                Kab. {{ $alamat->kabupaten_kota ?? '-' }},
                Prov. {{ $alamat->provinsi ?? '-' }},
                Kode Pos {{ $alamat->kode_pos ?? '-' }}
            @else
                -
            @endif
        </div>
        <div class="form-section"><label>Untuk/ Maksud/ Tujuan</label>: {{ $pengajuan->tujuanSurat->nama_tujuan ?? '-' }}</div>

        <p>Demikian Surat Pengantar ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>

        <!-- TANDA TANGAN -->
        <div class="ttd">
            <table class="ttd-table">
                <tr>
                    <td>Mengetahui,<br>Ketua RW</td>
                    <td>Indramayu, ................<br>Ketua RT</td>
                </tr>
                <tr>
                    <td>( .............................................. )</td>
                    <td>
                        <!-- Menampilkan Tanda Tangan Digital RT -->
                        <img src="data:image/png;base64,{{ $ttd }}" alt="Tanda Tangan RT" style="width: 100px; height: 100px;"/>
                        <br>({{ $rt->nama_lengkap_rt }})
                    </td>
                </tr>
            </table>
        </div>
    </div>

</body>
</html>

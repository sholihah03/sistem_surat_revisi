@extends('rt.dashboardRt')

@section('content')
    <h1 class="text-2xl pt-20 md:text-3xl font-bold text-gray-800 mb-2">Hasil Surat Pengantar</h1>

    <!-- Breadcrumb -->
    <nav class="px-6 text-sm text-gray-600">
        <ol class="inline-flex items-center space-x-2">
            <li><a href="{{ route('riwayatSuratWarga') }}" class="text-blue-600 no-underline hover:underline">Riwayat Surat</a></li>
            <li>/</li>
            <li class="text-gray-800 font-medium">Hasil Surat Pengantar</li>
        </ol>
    </nav>

    <div class="flex justify-center pt-5">
        <div class="bg-white p-10 w-full max-w-[794px] text-[14px] leading-relaxed font-serif relative shadow-md">
            <!-- Header -->
            <div class="text-center border-b border-black pb-2 mb-2">
                <div class="flex items-start justify-between">
                    <div class="w-24">
                        <img src="{{ asset('images/Logo_Indramayu.png') }}" alt="Logo" class="w-full">
                    </div>
                    <div class="flex-1 text-center">
                        <h1 class="font-bold text-lg uppercase">Pemerintah Kabupaten Indramayu</h1>
                        <h2 class="font-bold text-md uppercase">Kecamatan Indramayu</h2>
                        <h3 class="font-bold uppercase">Kelurahan Margadadi</h3>
                        <p class="text-sm">Jl. May Sastra Atmaja Nomor : 47 Tlp. (0234) 273 301 Kode Pos 45211</p>
                        <p class="text-sm">e-mail : kelurahanmargadadi.indramayu@gmail.com</p>
                        <h4 class="font-bold uppercase tracking-widest mt-1">INDRAMAYU</h4>
                    </div>
                </div>
            </div>

            <!-- Tujuan -->
            <div class="text-right mb-4">
                <div class="inline-block text-left">
                    <p>Kepada</p>
                    <p>Yth. Lurah Margadadi</p>
                    <p>di_</p>
                    <p class="text-center font-bold">TEMPAT</p>
                </div>
            </div>

            <!-- Judul -->
            <div class="text-center mb-2">
                <h2 class="font-bold tracking-widest underline">SURAT PENGANTAR</h2>
                <p>Nomor : {{ $pengajuan->tujuanSurat->nomor_surat ?? '.........................' }}</p>
            </div>

            <!-- Isi -->
            <p class="mb-4" style="text-indent: 2em;">Yang bertanda tangan di bawah ini, Ketua RT {{ $rt->no_rt }} RW {{ $rt->rw->no_rw }} Kelurahan Margadadi Kecamatan Indramayu Kabupaten Indramayu, Memberikan Pengantar Kepada :</p>

            <div class="pl-6">
                <div class="grid grid-cols-[13rem_auto]">
                    <p class="w-52">Nama</p>
                    <p>: {{ $pengajuan->warga->nama_lengkap }}</p>
                </div>
                <div class="grid grid-cols-[13rem_auto]">
                    <p class="w-52">Tempat/ Tanggal Lahir</p>
                    <p>:
                        @if ($jenis === 'biasa')
                            {{ $pengajuan->tempat_lahir ?? '-' }},
                            {{ \Carbon\Carbon::parse($pengajuan->tanggal_lahir)->translatedFormat('d F Y') }}
                        @else
                            {{ $pengajuan->tempat_lahir_pengaju_lain ?? '-' }},
                            {{ \Carbon\Carbon::parse($pengajuan->tanggal_lahir_pengaju_lain)->translatedFormat('d F Y') }}
                        @endif
                    </p>
                </div>
                <div class="grid grid-cols-[13rem_auto]">
                    <p class="w-52">Nomor KTP</p>
                    <p>: {{ $pengajuan->warga->nik }}</p>
                </div>
                <div class="grid grid-cols-[13rem_auto]">
                    <p class="w-52">Status Perkawinan</p>
                    <p>:
                        @if ($jenis === 'biasa')
                            {{ $pengajuan->status_perkawinan ?? '-' }}
                        @else
                            {{ $pengajuan->status_perkawinan_pengaju_lain ?? '-' }}
                        @endif
                    </p>
                </div>
                <div class="grid grid-cols-[13rem_auto]">
                    <p class="w-52">Kebangsaan/ Agama</p>
                    <p>:
                        @if ($jenis === 'biasa')
                            {{ $pengajuan->agama ?? '-' }}
                        @else
                            {{ $pengajuan->agama_pengaju_lain ?? '-' }}
                        @endif
                    <p>
                </div>
                <div class="grid grid-cols-[13rem_auto]">
                    <p class="w-52">Pekerjaan</p>
                    <p>:
                        @if ($jenis === 'biasa')
                            {{ $pengajuan->pekerjaan ?? '-' }}
                        @else
                            {{ $pengajuan->pekerjaan_pengaju_lain ?? '-' }}
                        @endif
                    </p>
                </div>
                <div class="grid grid-cols-[13rem_auto]">
                    <p class="w-52">Alamat</p>
                    <p>:
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
                    </p>
                </div>
                <div class="grid grid-cols-[13rem_auto]">
                    <p class="w-52">Untuk/ Maksud/ Tujuan</p>
                    <p>: {{ $pengajuan->tujuanSurat->nama_tujuan ?? '-' }}</p>
                </div>
            </div>

            <p class="mt-4" style="text-indent: 2em;">Demikian Surat Pengantar ini, untuk dipergunakan sebagaimana mestinya.</p>

            <!-- Tanda tangan -->
            <div class="flex justify-between mt-6">
                <!-- Bagian RW -->
                <div class="text-center">
                    <p>Mengetahui,</p>
                    <p class="font-bold">Ketua RW</p>
                    <br><br><br>
                    <p>( ............................................. )</p>
                </div>

                <!-- Bagian RT -->
                <div class="text-center">
                    <p>Indramayu ........................................</p>
                    <p class="font-bold">Ketua RT</p>
                    <div class="flex flex-col items-center mt-3">
                        <!-- Tanda tangan digital -->
                        <img src="data:image/png;base64,{{ $ttd }}" alt="Tanda Tangan RT" style="width: 100px; height: 100px;" />
                        <p class="mt-2">
                            (
                            <span style="display: inline-block; min-width: 200px; text-align: center;">
                                {{ $rt->nama_lengkap_rt }}
                            </span>
                            )
                        </p>
                    </div>
                </div>
            </div>

            <!-- QR Code -->
            {{-- <div class="absolute bottom-10 right-10 text-center">
                <img src="" alt="QR Code" class="w-24 h-24 mx-auto">
                <p class="text-[10px] mt-1">Scan untuk verifikasi surat</p>
            </div> --}}
        </div>
    </div>
@endsection

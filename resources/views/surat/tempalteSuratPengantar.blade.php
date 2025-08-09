@extends('rw.dashboardRw')

@section('content')
<h1 class="text-2xl pt-20 md:text-3xl font-bold text-gray-800 mb-6">
    Template Surat Pengantar
</h1>

<div class="flex flex-col md:flex-row gap-6 py-10 print:bg-white">

    <!-- Kiri: CRUD -->
    <div class="bg-white p-4 shadow md:w-1/3 self-start">
        <h2 class="font-bold mb-4">Edit Kop Surat</h2>
        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form
            action="{{ $kopSurat ? route('suratPengantar-update', $kopSurat->id_kop_surat) : route('suratPengantar-store') }}"
            method="POST"
        >
            @csrf
            @if($kopSurat)
                @method('PUT')
            @endif
            <button type="submit"
                class="{{ $kopSurat ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-blue-500 hover:bg-blue-600' }} text-white px-4 py-2 mb-3 rounded">
                {{ $kopSurat ? 'Edit Data' : 'Tambah Data' }}
            </button>

            <input type="text" name="nama_jalan" placeholder="Nama Jalan"
                value="{{ old('nama_jalan', $kopSurat->nama_jalan ?? '') }}" class="w-full border p-2 mb-2">
            <input type="text" name="no_kantor" placeholder="No Kantor"
                value="{{ old('no_kantor', $kopSurat->no_kantor ?? '') }}" class="w-full border p-2 mb-2">
            <input type="text" name="no_telepon" placeholder="No Telepon"
                value="{{ old('no_telepon', $kopSurat->no_telepon ?? '') }}" class="w-full border p-2 mb-2">
            <input type="text" name="kode_pos" placeholder="Kode Pos"
                value="{{ old('kode_pos', $kopSurat->kode_pos ?? '') }}" class="w-full border p-2 mb-2">
            <input type="email" name="email" placeholder="Email"
                value="{{ old('email', $kopSurat->email ?? '') }}" class="w-full border p-2 mb-2">
        </form>
    </div>

    <!-- Kanan: Hasil Surat -->
    <div class="bg-white p-4 md:p-10 w-full max-w-[794px] text-[14px] leading-relaxed font-serif relative shadow-md md:w-2/3">

        <!-- Header -->
        <div class="text-center border-b border-black pb-2 mb-2">
            <!-- Logo khusus mobile -->
            <div class="md:hidden mb-2">
                <img src="{{ asset('images/Logo_Indramayu.png') }}" alt="Logo" class="w-24 mx-auto">
            </div>

            <div class="hidden md:flex items-start justify-between">
                <div class="w-24">
                    <img src="{{ asset('images/Logo_Indramayu.png') }}" alt="Logo" class="w-full">
                </div>
                <div class="flex-1 text-center">
                    <h1 class="font-bold text-lg uppercase">Pemerintah Kabupaten Indramayu</h1>
                    <h2 class="font-bold text-md uppercase">Kecamatan Indramayu</h2>
                    <h3 class="font-bold uppercase">Kelurahan Margadadi</h3>
                    <p class="text-sm">
                        {{ optional($kopSurat)->nama_jalan
                            ? optional($kopSurat)->nama_jalan . ' Nomor : ' . optional($kopSurat)->no_kantor .
                            ' Tlp. (' . optional($kopSurat)->no_telepon . ') Kode Pos ' . optional($kopSurat)->kode_pos
                            : 'Jl. May Sastra Atmaja Nomor : 47 Tlp. (0234) 273 301 Kode Pos 45211' }}
                    </p>
                    <p class="text-sm">
                        e-mail : {{ optional($kopSurat)->email ?? 'kelurahanmargadadi.indramayu@gmail.com' }}
                    </p>
                    <h4 class="font-bold uppercase tracking-widest mt-1">INDRAMAYU</h4>
                </div>
            </div>

            <!-- Teks header untuk mobile -->
            <div class="md:hidden text-center">
                <h1 class="font-bold text-base uppercase">Pemerintah Kabupaten Indramayu</h1>
                <h2 class="font-bold text-sm uppercase">Kecamatan Indramayu</h2>
                <h3 class="font-bold uppercase text-sm">Kelurahan Margadadi</h3>
                <p class="text-xs">Jl. May Sastra Atmaja Nomor : 47 Tlp. (0234) 273 301</p>
                <p class="text-xs">e-mail: kelurahanmargadadi.indramayu@gmail.com</p>
                <h4 class="font-bold uppercase tracking-widest text-sm mt-1">INDRAMAYU</h4>
            </div>
        </div>

        <!-- Tujuan -->
        <div class="text-right mb-4">
            <p>Kepada</p>
            <p>Yth. Lurah Margadadi</p>
            <p>di_</p>
            <p class="underline">TEMPAT</p>
        </div>

        <!-- Judul -->
        <div class="text-center mb-2">
            <h2 class="font-bold tracking-widest underline">SURAT PENGANTAR</h2>
            <p>Nomor : ......................................</p>
        </div>

        <!-- Isi -->
        <p class="mb-4">
            Yang bertanda tangan di bawah ini, Ketua RT .......... RW .......... Kelurahan Margadadi Kecamatan Indramayu Kabupaten Indramayu, Memberikan Pengantar Kepada :
        </p>

        <div class="pl-6">
            @foreach ([
                'Nama',
                'Tempat/ Tanggal Lahir',
                'Nomor KTP',
                'Status Perkawinan',
                'Kebangsaan/ Agama',
                'Pekerjaan',
                'Alamat',
                'Untuk/ Maksud/ Tujuan'
            ] as $item)
                <div class="flex flex-wrap">
                    <p class="w-full md:w-52">{{ $item }}</p>
                    <p class="flex-1">: ...............................................................</p>
                </div>
            @endforeach
        </div>

        <p class="mt-4">Demikian Surat Pengantar ini, untuk dipergunakan sebagaimana mestinya.</p>

        <!-- Tanda tangan -->
        <div class="flex flex-col md:flex-row justify-between mt-6 gap-6 md:gap-0">
            <div class="text-center">
                <p>Mengetahui,</p>
                <p class="font-bold">Ketua RW</p>
                <br><br><br>
                <p>( ............................................. )</p>
            </div>
            <div class="text-center">
                <p>Indramayu ........................................</p>
                <p class="font-bold">Ketua RT</p>
                <br><br><br>
                <p>( ............................................. )</p>
            </div>
        </div>

        <!-- QR Code -->
        <div class="absolute bottom-10 right-10 text-center hidden md:block">
            <img src="" alt="QR Code" class="w-24 h-24 mx-auto">
            <p class="text-[10px] mt-1">Scan untuk verifikasi surat</p>
        </div>
        <div class="mt-6 text-center md:hidden">
            <img src="" alt="QR Code" class="w-24 h-24 mx-auto">
            <p class="text-[10px] mt-1">Scan untuk verifikasi surat</p>
        </div>
    </div>
</div>
@endsection

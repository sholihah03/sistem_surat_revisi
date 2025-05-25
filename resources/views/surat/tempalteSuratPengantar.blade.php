@extends('rw.dashboardRw')

@section('content')
<h1 class="text-2xl pt-20 md:text-3xl font-bold text-gray-800 mb-6">Template Surat Pengantar</h1>

<body class="flex justify-center items-start bg-gray-100 py-10 print:bg-white min-h-screen">
    <div class="absolute top-4 left-4">
        <a href="javascript:history.back()" class="text-blue-600 font-bold">Kembali</a>
    </div>

    <div class="bg-white p-4 md:p-10 w-full max-w-[794px] text-[14px] leading-relaxed font-serif relative shadow-md mx-auto">

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
                    <p class="text-sm">Jl. May Sastra Atmaja Nomor : 47 Tlp. (0234) 273 301 Kode Pos 45211</p>
                    <p class="text-sm">e-mail : kelurahanmargadadi.indramayu@gmail.com</p>
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
        <p class="mb-4">Yang bertanda tangan di bawah ini, Ketua RT .......... RW .......... Kelurahan Margadadi Kecamatan Indramayu Kabupaten Indramayu, Memberikan Pengantar Kepada :</p>

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
</body>
@endsection

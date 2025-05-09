<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Pengajuan Surat</title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <!-- Font Inter -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

  <style>
    body { font-family: 'Inter', sans-serif; }
    [x-cloak] { display: none !important; }
  </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-blue-100 via-white to-pink-100">
    <!-- Navbar -->
    @include('komponen.nav')

    <!-- Breadcrumb (Tengah) -->
    <nav class="pt-6 px-6 text-sm text-gray-600 text-center">
        <ol class="inline-flex items-center space-x-2 justify-center">
        <li><a href="{{ route('dashboardWarga') }}" class="text-blue-600 no-underline">Home</a></li>
        <li>/</li>
        <li class="text-gray-800 font-medium">Form Surat Pengantar</li>
        </ol>
    </nav>

    <!-- Konten Surat Pengantar (di tengah) -->
    <div class="flex justify-center items-center min-h-screen py-5">
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
                <div class="flex">
                    <p class="w-52">Nama</p>
                    <p>: ...............................................................</p>
                </div>
                <div class="flex">
                    <p class="w-52">Tempat/ Tanggal Lahir</p>
                    <p>: ...............................................................</p>
                </div>
                <div class="flex">
                    <p class="w-52">Nomor KTP</p>
                    <p>: ...............................................................</p>
                </div>
                <div class="flex">
                    <p class="w-52">Status Perkawinan</p>
                    <p>: Kawin / Belum / Janda / Duda</p>
                </div>
                <div class="flex">
                    <p class="w-52">Kebangsaan/ Agama</p>
                    <p>: ...............................................................</p>
                </div>
                <div class="flex">
                    <p class="w-52">Pekerjaan</p>
                    <p>: ...............................................................</p>
                </div>
                <div class="flex">
                    <p class="w-52">Alamat</p>
                    <p>: ...............................................................</p>
                </div>
                <div class="flex">
                    <p class="w-52">Untuk/ Maksud/ Tujuan</p>
                    <p>: ...............................................................</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

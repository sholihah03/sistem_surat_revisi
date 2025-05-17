<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Pengajuan Surat Lain</title>

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
     <nav class="pt-6 px-4 sm:px-6 md:px-8 text-sm mb-2 text-gray-600 text-center">
        <ol class="inline-flex items-center space-x-2 justify-center">
            <li><a href="{{ route('pengajuanSuratWarga') }}" class="text-blue-600 no-underline">Pengajuan Surat</a></li>
            <li>/</li>
            <li class="text-gray-800 font-medium">Form Surat Pengantar Lain</li>
        </ol>
    </nav>

    <!-- Heading -->
    <div class="text-center mb-4 px-4 sm:px-6 md:px-8">
        <p class="text-red-600 text-lg font-semibold max-w-xl mx-auto">
            Silakan lengkapi data berikut dengan benar dan teliti. Pastikan tidak ada kesalahan agar proses pengajuan surat berjalan lancar.
        </p>
    </div>

    <!-- Konten Surat Pengantar (di tengah) -->
    <div class="flex justify-center items-start py-6 px-2 sm:px-6 md:px-8">
        <div class="bg-white p-6 sm:p-8 md:p-10 w-full max-w-[794px] text-[14px] leading-relaxed font-serif relative shadow-md">
            <!-- Header -->
            <div class="text-center border-b border-black pb-2 mb-2">
                <!-- Mobile: column; Desktop: row -->
                <div class="flex flex-col sm:flex-row items-center sm:items-start justify-between">

                    <!-- Logo -->
                    <div class="w-24 shrink-0 mb-2 sm:mb-0 sm:mr-4">
                        <img src="{{ asset('images/Logo_Indramayu.png') }}" alt="Logo" class="w-full mx-auto sm:mx-0">
                    </div>

                    <!-- Kop Surat -->
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
                <p>Nomor : .....................................</p>
            </div>
            @if ($errors->any())
                <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form x-ref="form" method="POST" action="{{ route('formPengajuanSuratLainStore') }}">
                @csrf
                <input type="hidden" name="scan_kk_id" value="{{ $warga->scan_Kk?->id_scan }}">
                <!-- Isi -->
                {{-- <p class="mb-4" style="text-indent: 2em;">Yang bertanda tangan di bawah ini, Ketua RT {{ $warga->rt->no_rt ?? '-' }} RW {{ $warga->rw->no_rw ?? '-' }} Kelurahan Margadadi Kecamatan Indramayu Kabupaten Indramayu, Memberikan Pengantar Kepada:</p> --}}

                <div class="pl-0 sm:pl-6">
                    <div class="flex flex-col sm:flex-row sm:items-center mb-2">
                        <p class="w-full sm:w-52 font-semibold flex whitespace-nowrap mr-5">
                            Nama<span class="ml-1">:</span>
                        </p>
                        <p class="pl-2">{{ $warga->nama_lengkap }}</p>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center mb-2">
                        <p class="w-full sm:w-52 font-semibold flex whitespace-nowrap mr-5">
                            Tempat Lahir / Tanggal Lahir :
                        </p>
                        <div class="pl-2 flex flex-wrap gap-2 sm:gap-0 sm:flex-nowrap w-full">
                            <input type="text" name="tempat_lahir_pengaju_lain" required class="border border-gray-300 px-2 py-1 rounded w-full sm:w-36 mr-2" placeholder="Tempat Lahir"/>
                            <div class="relative w-full sm:w-40">
                                <input type="date"  name="tanggal_lahir_pengaju_lain" required class="border border-gray-300 px-2 py-1 rounded w-full peer"/>
                                <!-- Placeholder hanya muncul di mobile -->
                                <span class="absolute left-2 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none peer-placeholder-shown:block peer-focus:hidden block sm:hidden">
                                    Tanggal Lahir
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center mb-2">
                        <p class="w-full sm:w-52 font-semibold flex whitespace-nowrap mr-5">
                            Nomor KTP<span class="ml-1">:</span>
                        </p>
                        <p class="pl-2">{{ $warga->nik }}</p>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center mb-2">
                        <p class="w-full sm:w-52 font-semibold flex whitespace-nowrap mr-5">
                            Status Perkawinan<span class="ml-1">:</span>
                        </p>
                        <p class="pl-2">
                            <select name="status_perkawinan_pengaju_lain" required class="border border-gray-300 px-3 py-1 rounded w-full sm:w-[19rem] max-w-sm">
                                <option value="">-- Pilih Status --</option>
                                <option value="kawin">Kawin</option>
                                <option value="belum">Belum</option>
                                <option value="janda">Janda</option>
                                <option value="duda">Duda</option>
                            </select>
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center mb-2">
                        <p class="w-full sm:w-52 font-semibold flex whitespace-nowrap mr-5">
                            Agama<span class="ml-1">:</span>
                        </p>
                        <p class="pl-2"><input type="text" name="agama_pengaju_lain" required class="border border-gray-300 px-3 py-1 rounded w-full sm:w-[19rem] max-w-sm" placeholder="Agama"/></p>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center mb-2">
                        <p class="w-full sm:w-52 font-semibold flex whitespace-nowrap mr-5">
                            Pekerjaan<span class="ml-1">:</span>
                        </p>
                        <p class="pl-2"><input type="text" name="pekerjaan_pengaju_lain" required class="border border-gray-300 px-3 py-1 rounded w-full sm:w-[19rem] max-w-sm" placeholder="Pekerjaan"/></p>
                    </div>
                    <div class="flex flex-col sm:flex-row mb-4">
                        <p class="w-full sm:w-52 font-semibold flex whitespace-nowrap mr-5">
                            Alamat<span class="ml-1">:</span>
                        </p>
                        <p class="pl-2">
                            {{ $alamat->nama_jalan ?? '-' }}, RT {{ $alamat->rt_alamat ?? '-' }} RW {{ $alamat->rw_alamat ?? '-' }},<br>
                            Kel/Desa {{ $alamat->kelurahan ?? '-' }}, Kec {{ $alamat->kecamatan ?? '-' }}, Kab/Kota {{ $alamat->kabupaten_kota ?? '-' }},<br>
                            Provinsi {{ $alamat->provinsi ?? '-' }}, Kode Pos {{ $alamat->kode_pos ?? '-' }}
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center mb-2">
                        <p class="w-full sm:w-52 font-semibold flex whitespace-nowrap mr-5">
                            Untuk/ Maksud/ Tujuan<span class="ml-1">:</span>
                        </p>
                        <p class="pl-2"><input type="text" name="tujuan_manual" required class="border border-gray-300 px-3 py-1 rounded w-full sm:w-[19rem] max-w-sm" placeholder="Untuk/ Maksud/ Tujuan"/></p>
                    </div>
                </div>
                <!-- Modal Konfirmasi -->
                <div x-data="{ showModal: false, showSuccess: false }" x-cloak>
                    <!-- Trigger tombol -->
                    <div class="text-center mt-6">
                        <button type="button" @click="showModal = true" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                            Ajukan Surat
                        </button>
                    </div>

                    <!-- Modal -->
                    <div x-show="showModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                        <div class="bg-white p-6 rounded shadow-lg w-full max-w-md text-center">
                            <h2 class="text-lg font-semibold mb-4">Konfirmasi Pengajuan</h2>
                            <p class="mb-4">Apakah Anda yakin data yang Anda isi sudah benar?</p>
                            <div class="flex justify-center gap-4">
                                <button @click="showModal = false" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cek Lagi</button>
                                <button @click="showModal = false; showSuccess = true; $nextTick(() => $refs.form.submit())" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Iya</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

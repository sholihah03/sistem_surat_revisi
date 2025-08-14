<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
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

    <!-- Breadcrumb -->
    <nav class="pt-6 px-4 sm:px-6 md:px-8 text-sm mb-2 text-gray-600 text-center">
        <ol class="inline-flex items-center space-x-2 justify-center">
            <li><a href="{{ route('pengajuanSuratWarga') }}" class="text-blue-600 no-underline">Pengajuan Surat</a></li>
            <li>/</li>
            <li class="text-gray-800 font-medium">Form Surat Pengantar</li>
        </ol>
    </nav>

    <!-- Heading -->
    <div class="text-center mb-4 px-4 sm:px-6 md:px-8">
        <p class="text-red-600 text-lg font-semibold max-w-xl mx-auto">
            Silakan lengkapi data berikut dengan benar dan teliti. Pastikan tidak ada kesalahan agar proses pengajuan surat berjalan lancar.
        </p>
    </div>

    <!-- Konten Surat Pengantar -->
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
            <div class="text-center mb-6">
                <h2 class="font-bold tracking-widest underline">SURAT PENGANTAR</h2>
                <p>Nomor : {{ $nomor }}</p>
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
            @php
                $persyaratanByStatus = [];

                foreach ($persyaratanList as $item) {
                    $key = strtolower(trim($item->keterangan)); // cukup lowercase saja
                    $persyaratanByStatus[$key][] = [
                        'id' => $item->id,
                        'nama' => $item->nama_persyaratan,
                    ];
                }
            @endphp

            <form
                x-data="{
                    status: '',
                    allPersyaratan: {{ Js::from($persyaratanByStatus) }},
                    get isGeneral() {
                        // jika semua kunci adalah string kosong atau hanya satu entri
                        return Object.keys(this.allPersyaratan).length === 1 && Object.keys(this.allPersyaratan)[0] === '';
                    },
                    get filteredPersyaratan() {
                        return this.isGeneral ? this.allPersyaratan[''] : (this.allPersyaratan[this.status] || []);
                    }
                }"
                x-ref="form"
                method="POST"
                action="{{ route('formPengajuanSuratStore') }}"
                enctype="multipart/form-data"
            >
                @csrf
                <input type="hidden" name="tujuan_surat_id" value="{{ request()->query('id') }}">
                <input type="hidden" name="scan_kk_id" value="{{ $warga->scan_Kk?->id_scan }}">

                <!-- Isi Surat -->
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
                            <input type="text" name="tempat_lahir" required class="border border-gray-300 px-2 py-1 rounded w-full sm:w-36 mr-2" placeholder="Tempat Lahir"/>
                            <div class="relative w-full sm:w-40">
                                <input type="date"  name="tanggal_lahir" required class="border border-gray-300 px-2 py-1 rounded w-full peer"/>
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
                            <select
                                name="status_perkawinan"
                                required
                                class="border border-gray-300 px-3 py-1 rounded w-full sm:w-[19rem] max-w-sm"
                                x-model="status">
                                <option value="">-- Pilih Status --</option>
                                <option value="kawin">Kawin</option>
                                <option value="belum">Belum</option>
                                <option value="janda">Janda</option>
                                <option value="duda">Duda</option>
                            </select>
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center mb-2"
                        x-show="(isGeneral && filteredPersyaratan.length > 0) || filteredPersyaratan.length > 0">
                        <p class="text-sm text-gray-700 font-semibold">Dokumen yang harus dilengkapi:</p>
                        <ul class="list-disc pl-6 text-sm text-gray-800 space-y-4">
                            <template x-for="(item, index) in filteredPersyaratan" :key="item.id">
                                <li>
                                    <p class="font-medium" x-text="item.nama"></p>
                                    <input type="hidden" :name="`persyaratan_surat_id[]`" :value="item.id">
                                    <input :name="`dokumen[${item.id}]`" type="file" required
                                        class="border border-gray-300 px-3 py-1 rounded w-full sm:w-[19rem] max-w-sm" />
                                </li>
                            </template>
                        </ul>
                    </div>
                    <p class="text-sm text-red-600 italic mb-2" x-show="isGeneral">
                            Dokumen di atas ini wajib diisi tanpa memandang status perkawinan.
                        </p>
                    <div class="flex flex-col sm:flex-row sm:items-center mb-2">
                        <p class="w-full sm:w-52 font-semibold flex whitespace-nowrap mr-5">
                            Agama<span class="ml-1">:</span>
                        </p>
                        <p class="pl-2"><input type="text" name="agama" required class="border border-gray-300 px-3 py-1 rounded w-full sm:w-[19rem] max-w-sm" placeholder="Agama"/></p>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center mb-2">
                        <p class="w-full sm:w-52 font-semibold flex whitespace-nowrap mr-5">
                            Pekerjaan<span class="ml-1">:</span>
                        </p>
                        <p class="pl-2"><input type="text" name="pekerjaan" required class="border border-gray-300 px-3 py-1 rounded w-full sm:w-[19rem] max-w-sm" placeholder="Pekerjaan"/></p>
                    </div>
                    <div class="flex flex-col sm:flex-row mb-4">
                        <p class="w-full sm:w-52 font-semibold flex whitespace-nowrap mr-5">
                            Alamat<span class="ml-1">:</span>
                        </p>
                        <p class="pl-2">
                            {{ $alamat->nama_jalan ?? '-' }}, RT {{ $alamat->rt_alamat ?? '-' }} RW {{ $alamat->rw_alamat ?? '-' }},<br>
                            Kel/Desa {{ $alamat->kelurahan ?? '-' }}, Kec {{ $alamat->kecamatan ?? '-' }},
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row mb-6">
                        <p class="w-full sm:w-52 font-semibold flex whitespace-nowrap mr-5">
                            Untuk/ Maksud/ Tujuan<span class="ml-1">:</span>
                        </p>
                        <p class="pl-2">{{ $tujuan }}</p>
                    </div>
                </div>

                <!-- Modal Konfirmasi -->
                <div x-data="{ showModal: false }" x-cloak>
                    <!-- Trigger tombol -->
                    <div class="text-center mt-8">
                        <button type="button" @click="showModal = true"
                            class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                            Ajukan Surat
                        </button>
                    </div>

                        <!-- Modal -->
                    <div x-show="showModal"
                        class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50"
                        x-transition>
                        <div class="bg-white p-6 rounded shadow-md w-full max-w-md">
                            <h2 class="text-lg font-semibold mb-4 text-center">Konfirmasi Pengajuan</h2>
                            <p class="text-sm mb-6 text-center">
                                Pastikan semua data sudah benar sebelum dikirim. Apakah Anda yakin ingin mengajukan surat ini?
                            </p>
                            <div class="flex justify-end space-x-3">
                                <!-- Tombol Cek Lagi -->
                                <button type="button" @click="showModal = false"
                                    class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">
                                    Cek Lagi
                                </button>

                                <!-- Tombol Kirim -->
                                <button type="submit"
                                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                    Kirim
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('components.modal-timeout')
</body>
</html>

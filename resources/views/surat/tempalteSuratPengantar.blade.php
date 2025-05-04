<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Pengantar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            @page { size: A4; margin: 1in; }
            body { margin: 0; }
        }
    </style>
</head>
<body class="flex justify-center bg-gray-100 py-10 print:bg-white">
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
            <div class="pl-6">
                <p>...............................................................................................................................</p>
            </div>
        </div>

        <p class="mt-4">Demikian Surat Pengantar ini, untuk dipergunakan sebagaimana mestinya.</p>

        <!-- Tanda tangan -->
        <div class="flex justify-between mt-6">
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
        <div class="absolute bottom-10 right-10 text-center">
            <img src="" alt="QR Code" class="w-24 h-24 mx-auto">
            <p class="text-[10px] mt-1">Scan untuk verifikasi surat</p>
        </div>
    </div>
</body>
</html>

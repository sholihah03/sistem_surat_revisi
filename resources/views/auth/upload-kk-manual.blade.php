<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Upload KK</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center px-4" style="background-image: url('{{ asset('images/background login.png') }}')">

    <div class="w-full max-w-xl bg-white rounded-xl shadow-md p-6 space-y-6">
        <h2 class="text-2xl font-bold text-gray-800 text-center">Upload Kartu Keluarga</h2>
        <p class="text-sm text-center text-gray-600">Data scan/foto KK Anda tidak berhasil di deteksi oleh sistem. Silahkan isi manual beberapa data di bawah ini.</p>

        <hr class="my-4">
        <div class="bg-gray-50 p-4 rounded-md shadow-sm">
            <h3 class="text-lg font-semibold mb-2 text-gray-700">Hasil Upload KK:</h3>
            <p><strong>Foto KK:</strong></p>
            <div class="flex justify-center">
                <!-- Menampilkan gambar jika ada -->
                @if(isset($kkImageUrl))
                    <img src="{{ $kkImageUrl }}" alt="Gambar KK Gagal OCR" class="max-w-xs rounded-md shadow-lg">
                @endif
            </div>
        </div>

        <!-- Form untuk Edit Data -->
        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg shadow-sm">
            <h3 class="text-xl font-bold text-blue-700 mb-2">✏️ Edit Data Jika Ada yang Tidak Sesuai</h3>
            <p class="text-sm text-red-600 mb-4">Silakan perbaiki data Anda sebelum menyimpan. Pastikan data sesuai dengan dokumen KK asli dan tidak ada yang salah.</p>

            <form action="{{ route('uploadKKManualSimpan') }}" method="POST" class="space-y-5 max-h-[75vh] overflow-y-auto" enctype="multipart/form-data">
                @csrf

                <!-- Menampilkan pesan error jika ada -->
                @if ($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="nama_kepala_keluarga" class="block text-base font-semibold text-gray-800 mb-1">Nama Kepala Keluarga</label>
                        <input type="text" id="nama_kepala_keluarga" name="nama_kepala_keluarga" class="w-full px-4 py-2 border-2 border-blue-400 rounded-md focus:ring-blue-500 shadow-sm" placeholder="Masukkan Nama Kepala Keluarga">
                    </div>

                    <div>
                        <label for="no_kk" class="block text-base font-semibold text-gray-800 mb-1">No KK</label>
                        <input type="text" id="no_kk" name="no_kk" class="w-full px-4 py-2 border-2 border-blue-400 rounded-md focus:ring-blue-500 shadow-sm" placeholder="Masukkan No KK">
                    </div>

                    <div>
                        <label for="nama_jalan" class="block text-sm font-medium text-gray-700">Alamat</label>
                        <input type="text" name="nama_jalan" id="nama_jalan" class="w-full px-3 py-2 border rounded-md" placeholder="Alamat">
                    </div>

                    <div>
                        <label for="provinsi" class="block text-sm font-medium text-gray-700">Provinsi</label>
                        <input type="text" name="provinsi" id="provinsi" class="w-full px-3 py-2 border rounded-md" placeholder="Provinsi">
                    </div>

                    <div>
                        <label for="kabupaten" class="block text-sm font-medium text-gray-700">Kabupaten/Kota</label>
                        <input type="text" name="kabupaten_kota" id="kabupaten_kota" class="w-full px-3 py-2 border rounded-md" placeholder="Kabupaten">
                    </div>

                    <div>
                        <label for="kecamatan" class="block text-sm font-medium text-gray-700">Kecamatan</label>
                        <input type="text" name="kecamatan" id="kecamatan" class="w-full px-3 py-2 border rounded-md" placeholder="Kecamatan">
                    </div>

                    <div>
                        <label for="desa" class="block text-sm font-medium text-gray-700">Desa/Kelurahan</label>
                        <input type="text" name="desa" id="desa" class="w-full px-3 py-2 border rounded-md" placeholder="Desa">
                    </div>

                    <div>
                        <label for="rt" class="block text-sm font-medium text-gray-700">RT</label>
                        <input type="text" name="rt_alamat" id="rt_alamat" class="w-full px-3 py-2 border rounded-md" placeholder="RT">
                    </div>

                    <div>
                        <label for="rw" class="block text-sm font-medium text-gray-700">RW</label>
                        <input type="text" name="rw_alamat" id="rw_alamat" class="w-full px-3 py-2 border rounded-md" placeholder="RW">
                    </div>

                    <div>
                        <label for="kode_pos" class="block text-sm font-medium text-gray-700">Kode Pos</label>
                        <input type="text" name="kode_pos" id="kode_pos" class="w-full px-3 py-2 border rounded-md" placeholder="Kode Pos">
                    </div>
                </div>

                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-md transition shadow-md mt-4">
                    Simpan Data
                </button>
            </form>
        </div>
    </div>


</body>
</html>

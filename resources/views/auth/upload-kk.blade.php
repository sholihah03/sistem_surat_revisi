<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload KK</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center px-4" style="background-image: url('{{ asset('images/background login.png') }}')">

    <div class="w-full max-w-xl bg-white rounded-xl shadow-md p-6 space-y-6">
        <h2 class="text-2xl font-bold text-gray-800 text-center">Upload Kartu Keluarga</h2>
        <p class="text-sm text-center text-gray-600">Nomor KK Anda belum terdaftar. Silakan unggah scan/foto KK Anda untuk diverifikasi oleh RT.</p>

        <form method="POST" action="{{ route('uploadKK.proses') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Gambar KK</label>
                <input type="file" name="file_kk" accept="image/*" required
                       class="block w-full px-3 py-2 border border-blue-400 rounded-md file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-blue-100 file:text-sm file:text-blue-700 hover:file:bg-blue-200">
            </div>

            <button type="submit"
                    class="w-full bg-yellow-400 hover:bg-yellow-500 text-white font-semibold py-2 rounded-md transition">
                Konfirmasi
            </button>
        </form>

        <!-- Jika sudah diproses OCR -->
    @if (isset($no_kk) && isset($nama_kepala_keluarga))
    <hr>
        <p><strong>No KK:</strong> <span id="no_kk_text">{{ $no_kk }}</span></p>
        <p><strong>Nama Kepala Keluarga:</strong> <span id="nama_kepala_keluarga_text">{{ $nama_kepala_keluarga }}</span></p>
    </div>

    <!-- Form untuk Edit Data -->
    <form action="{{ route('uploadKK.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="no_kk">No KK</label>
            <input type="text" class="form-control" id="no_kk" name="no_kk" value="{{ old('no_kk', $no_kk) }}" required>
        </div>
        <div class="form-group">
            <label for="nama_kepala_keluarga">Nama Kepala Keluarga</label>
            <input type="text" class="form-control" id="nama_kepala_keluarga" name="nama_kepala_keluarga" value="{{ old('nama_kepala_keluarga', $nama_kepala_keluarga) }}" required>
        </div>

        <!-- Tambahkan field untuk alamat -->
        <div class="form-group">
            <label for="alamat">Alamat</label>
            <input type="text" class="form-control" id="alamat" name="alamat" value="{{ old('alamat', $alamat ?? '') }}">
        </div>
        <input type="hidden" name="path" value="{{ $path }}">
        <button type="submit" class="btn btn-success">Simpan Data</button>
    </form>
    @endif
</div>



    <!-- Modal Error -->
    @if (session('error'))
    <div id="errorModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full relative">
            <h2 class="text-xl font-bold text-red-600 mb-4">Gagal Membaca Data</h2>
            <p class="text-gray-700 mb-6">{{ session('error') }}</p>

            <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-800">
                ✖
            </button>

            <button onclick="closeModal()" class="w-full bg-red-500 hover:bg-red-600 text-white py-2 rounded-md mt-2">
                Oke, Saya Mengerti
            </button>
        </div>
    </div>
    @endif

    <script>
        function closeModal() {
            document.getElementById('errorModal').remove();
        }
    </script>

</body>
</html>

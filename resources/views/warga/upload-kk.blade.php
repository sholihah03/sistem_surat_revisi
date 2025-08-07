<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload KK</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center px-4 py-6 sm:py-10" style="background-image: url('{{ asset('images/background login.png') }}'); background-size: cover; background-position: center;">

    <div class="w-full max-w-xl bg-white rounded-xl shadow-md p-4 sm:p-6 space-y-6">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-800 text-center">Upload Kartu Keluarga</h2>
        <p class="text-sm text-center text-gray-600">Nomor KK Anda belum terdaftar. Silakan unggah scan/foto KK Anda untuk diverifikasi oleh RT.</p>

        @if (!isset($no_kk) || !isset($nama_kepala_keluarga))
        <form method="POST" action="{{ route('uploadKKproses') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <!-- Input NIK Pengupload -->
    <div class="mb-4">
        <label for="nik_pengupload" class="block text-sm font-medium text-gray-700 mb-1">Masukkan NIK Anda (yang mengupload KK)</label>
        <input type="text" name="nik_pengupload" id="nik_pengupload" required placeholder="Masukkan NIK Anda" minlength="16" maxlength="16"
            class="block w-full px-3 py-2 border border-blue-400 rounded-md">
    </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Gambar KK</label>
                <input type="file" name="path_file_kk" accept="image/*" required
                    class="block w-full px-3 py-2 border border-blue-400 rounded-md file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-blue-100 file:text-sm file:text-blue-700 hover:file:bg-blue-200">

                <p class="text-sm text-red-600 mt-2">
                    * Pastikan foto diambil dalam posisi <strong>horizontal</strong>, dengan kondisi <strong>jelas</strong> dan <strong>terang</strong> seperti contoh di bawah ini.
                </p>

                <p class="text-sm text-gray-600 mt-2">Contoh tampilan KK yang bisa diunggah:</p>
                <img src="{{ asset('images/contohKK.jpg') }}" alt="Contoh KK"
                    class="border rounded-md shadow object-cover w-full max-w-xs mx-auto cursor-pointer"
                    onclick="openImageModal('{{ asset('images/contohKK.jpg') }}')">
            </div>

            <button type="submit"
                class="w-full bg-yellow-400 hover:bg-yellow-500 text-white font-semibold py-2 rounded-md transition">
                Konfirmasi
            </button>
        </form>
        @endif
    </div>

    <!-- Modal Error -->
    @if (session('error'))
    <div id="errorModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full relative mx-4">
            <h2 class="text-xl font-bold text-red-600 mb-4">Gagal Membaca Data</h2>
            <p class="text-gray-700 mb-6">{{ session('error') }}</p>

            <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-800">✖</button>
            <button onclick="closeModal()" class="w-full bg-red-500 hover:bg-red-600 text-white py-2 rounded-md mt-2">Oke, Saya Mengerti</button>
        </div>
    </div>
    @endif

    <!-- Modal Error Gagal Unggah -->
    @if (session('error_gagal_unggah'))
    <div id="errorModalGagal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full relative mx-4">
            <h2 class="text-xl font-bold text-red-600 mb-4">Gagal Membaca Data</h2>
            <p class="text-gray-700 mb-6">{{ session('error_gagal_unggah') }}</p>

            <button onclick="closeModalGagal()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-800">✖</button>
            <button onclick="closeModalGagal()" class="w-full bg-red-500 hover:bg-red-600 text-white py-2 rounded-md mt-2">Oke, Saya Mengerti</button>
        </div>
    </div>
    @endif

    <!-- Modal Gambar -->
    <div id="imageModal" class="hidden fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50">
        <div class="relative w-full max-w-3xl px-4">
            <button onclick="closeImageModal()" class="absolute top-2 right-2 text-white text-3xl font-bold z-50">×</button>
            <img id="modalImage" src="" alt="Gambar KK" class="rounded-md max-h-[80vh] w-full object-contain">
        </div>
    </div>

    @include('components.modal-timeout')

    <script>
        function closeModal() {
            document.getElementById('errorModal')?.remove();
        }

        function closeModalGagal() {
            document.getElementById('errorModalGagal')?.remove();
        }

        function openImageModal(src) {
            document.getElementById('modalImage').src = src;
            document.getElementById('imageModal').classList.remove('hidden');
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
            document.getElementById('modalImage').src = '';
        }

        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });
    </script>

</body>
</html>

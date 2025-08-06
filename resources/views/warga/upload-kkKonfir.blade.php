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


        @if (isset($no_kk) && isset($nama_kepala_keluarga) && isset($alamatData))
        <hr class="my-4">
        <div class="bg-gray-50 p-4 rounded-md shadow-sm">
            <h3 class="text-lg font-semibold mb-2 text-gray-700">Data Hasil Upload KK:</h3>
            <p><strong>No KK:</strong> <span id="no_kk_text">{{ $no_kk }}</span></p>
            <p><strong>Nama Kepala Keluarga:</strong> <span id="nama_kepala_keluarga_text">{{ $nama_kepala_keluarga }}</span></p>
            {{-- <p><strong>Alamat:</strong> <span id="alamat_text">{{ $alamatData['provinsi'] ?? '' }}, {{ $alamatData['kabupaten_kota'] ?? '' }}, {{ $alamatData['kecamatan'] ?? '' }}, {{ $alamatData['kelurahan'] ?? '' }}, {{ $alamatData['rt'] ?? '' }}/{{ $alamatData['rw'] ?? '' }}, {{ $alamatData['kode_pos'] ?? '' }}</span></p> --}}
        </div>

        <!-- Form untuk Edit Data -->
        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg shadow-sm">
            <h3 class="text-xl font-bold text-blue-700 mb-2">✏️ Edit Data Jika Ada yang Tidak Sesuai</h3>
            <p class="text-sm text-red-600 mb-4">Silakan perbaiki data Anda sebelum menyimpan. Pastikan data sesuai dengan dokumen KK asli dan tidak ada yang salah serta gunakan <strong class="text-blue-500">huruf kapital</strong> jika ada perubahan</p>

            <form id="formData" action="{{ route('uploadKKsimpan') }}" method="POST" class="space-y-5 max-h-[75vh] overflow-y-auto">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="no_kk" class="block text-base font-semibold text-gray-800 mb-1">No KK</label>
                        <input type="text" id="no_kk_scan" name="no_kk_scan" value="{{ old('no_kk_scan', $no_kk) }}"
                            class="w-full px-4 py-2 border-2 border-blue-400 rounded-md focus:ring-blue-500 shadow-sm"
                            placeholder="Masukkan No KK">
                    </div>

                    <div>
                        <label for="nama_kepala_keluarga" class="block text-base font-semibold text-gray-800 mb-1">Nama Kepala Keluarga</label>
                        <input type="text" id="nama_kepala_keluarga" name="nama_kepala_keluarga"
                            value="{{ old('nama_kepala_keluarga', $nama_kepala_keluarga) }}"
                            class="w-full px-4 py-2 border-2 border-blue-400 rounded-md focus:ring-blue-500 shadow-sm"
                            placeholder="Masukkan Nama Kepala Keluarga">
                    </div>

                    <div>
                        <label for="nama_jalan" class="block text-sm font-medium text-gray-700">Alamat</label>
                        <input type="text" name="nama_jalan" id="nama_jalan" class="w-full px-3 py-2 border rounded-md" placeholder="Alamat" value="{{ old('nama_jalan', $alamatData['nama_jalan'] ?? '') }}">
                    </div>

                    <div>
                        <label for="provinsi" class="block text-sm font-medium text-gray-700">Provinsi</label>
                        <input type="text" name="provinsi" id="provinsi" class="w-full px-3 py-2 border rounded-md" placeholder="Provinsi" value="{{ old('provinsi', $alamatData['provinsi'] ?? '') }}">
                    </div>

                    <div>
                        <label for="kabupaten" class="block text-sm font-medium text-gray-700">Kabupaten/Kota</label>
                        <input type="text" name="kabupaten_kota" id="kabupaten_kota" class="w-full px-3 py-2 border rounded-md" placeholder="Kabupaten" value="{{ old('kabupaten_kota', $alamatData['kabupaten_kota'] ?? '') }}">
                    </div>

                    <div>
                        <label for="kecamatan" class="block text-sm font-medium text-gray-700">Kecamatan</label>
                        <input type="text" name="kecamatan" id="kecamatan" class="w-full px-3 py-2 border rounded-md" placeholder="Kecamatan" value="{{ old('kecamatan', $alamatData['kecamatan'] ?? '') }}">
                    </div>

                    <div>
                        <label for="desa" class="block text-sm font-medium text-gray-700">Desa/Kelurahan</label>
                        <input type="text" name="desa" id="desa" class="w-full px-3 py-2 border rounded-md" placeholder="Desa" value="{{ old('kelurahan', $alamatData['kelurahan'] ?? '') }}">
                    </div>

                    <div>
                        <label for="rt" class="block text-sm font-medium text-gray-700">RT</label>
                        <input type="text" name="rt_alamat" id="rt_alamat" class="w-full px-3 py-2 border rounded-md" placeholder="RT" value="{{ old('rt_alamat', $alamatData['rt'] ?? '') }}">
                    </div>

                    <div>
                        <label for="rw" class="block text-sm font-medium text-gray-700">RW</label>
                        <input type="text" name="rw_alamat" id="rw_alamat" class="w-full px-3 py-2 border rounded-md" placeholder="RW" value="{{ old('rw_alamat', $alamatData['rw'] ?? '') }}">
                    </div>

                    <div>
                        <label for="kode_pos" class="block text-sm font-medium text-gray-700">Kode Pos</label>
                        <input type="text" name="kode_pos" id="kode_pos" class="w-full px-3 py-2 border rounded-md" placeholder="Kode Pos" value="{{ old('kode_pos', $alamatData['kode_pos'] ?? '') }}">
                    </div>
                </div>

                <input type="hidden" name="path" value="{{ $path }}">

                <!-- Tombol untuk membuka modal -->
<button type="button"
    onclick="document.getElementById('modalKonfirmasi').classList.remove('hidden')"
    class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-md transition shadow-md mt-4">
    Simpan Data
</button>


            </form>
        </div>
        @endif
    </div>

    @include('components.modal-timeout')

    <!-- Modal Konfirmasi -->
<div id="modalKonfirmasi" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
        <h2 class="text-xl font-bold mb-4 text-gray-800">Konfirmasi Simpan</h2>
        <p class="text-gray-700 mb-6">
            Apakah Anda yakin ingin menyimpan data ini? Pastikan semua data sudah benar.
        </p>
        <div class="flex justify-end space-x-4">
            <!-- Tombol Batal -->
            <button type="button"
                onclick="document.getElementById('modalKonfirmasi').classList.add('hidden')"
                class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold rounded">
                Batal
            </button>

            <!-- Tombol Submit Form -->
            <button type="button"
                onclick="document.getElementById('formData').submit()"
                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded">
                Simpan
            </button>
        </div>
    </div>
</div>


</body>
</html>

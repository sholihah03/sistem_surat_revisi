@extends('rw.dashboardRw')

@section('content')
<h1 class="text-2xl pt-20 md:text-3xl font-bold text-gray-800 mb-6">Manajemen Surat Warga</h1>
<!-- Menampilkan pesan sukses jika ada -->
@if(session('success'))
    <div id="successModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 sm:p-8 relative w-[90%] max-w-md sm:max-w-lg text-center animate-scale">
            <!-- Tombol Close -->
            <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>

            <!-- Ikon Ceklis -->
            <div class="flex justify-center mb-6">
                <img src="https://img.icons8.com/color/96/000000/ok--v1.png" alt="Success Icon" class="w-20 h-20">
            </div>

            <!-- Judul -->
            <h2 class="text-2xl font-bold mb-4 text-gray-800 whitespace-nowrap">
                {{ session('success') }}
            </h2>

            <!-- Tombol Tutup -->
            <button onclick="closeModal()" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 rounded-lg">
                Tutup
            </button>
        </div>
    </div>
@endif

<!-- Modal Konfirmasi Hapus -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-30 hidden justify-center items-center z-50">
    <div class="bg-white rounded-lg p-6 sm:p-8 w-full max-w-sm sm:max-w-md md:max-w-lg relative text-center mx-4">
        <div class="flex justify-center mb-4">
            <!-- Ikon tanda seru -->
            <svg class="w-16 h-16 text-red-500" fill="none" stroke="currentColor" stroke-width="1.5"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v3m0 4h.01M21.6 18.3a10.5 10.5 0 11-19.2 0 10.5 10.5 0 0119.2 0z" />
            </svg>
        </div>
        <h2 class="text-lg font-semibold text-gray-800 mb-2">Yakin ingin menghapus akun <span id="rtName" class="font-bold"></span>?</h2>
        <form id="deleteForm" method="POST" class="mt-4">
            @csrf
            @method('DELETE')
            <div class="flex justify-center gap-4">
                <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded">Batal</button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Iya, Hapus</button>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Daftar Akun RT -->
<div class="overflow-x-auto max-h-[500px] overflow-y-auto border rounded-lg shadow">
    <table class="min-w-full bg-white border rounded shadow">
        <thead class="bg-green-100 sticky top-0 z-10">
            <tr class="text-left">
                <th class="px-4 py-2">No</th>
                <th class="px-4 py-2">Nama</th>
                <th class="px-4 py-2">Jenis</th>
                <th class="px-4 py-2">Nomor Surat</th>
                <th class="px-4 py-2">Nomor KTP</th>
                <th class="px-4 py-2">Pekerjaan</th>
                <th class="px-4 py-2">Alamat</th>
                <th class="px-4 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>

                <tr>
                    <td colspan="7" class="text-center text-gray-500 py-4"><strong>Belum ada data surat pengajuan yang masuk</strong></td>
                </tr>
        </tbody>
    </table>
</div>
@endsection

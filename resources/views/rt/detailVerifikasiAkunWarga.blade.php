@extends('rt.dashboardRt')

@section('content')

<div class="container mx-auto p-4 mb-6 pt-20">
    <h1 class="text-2xl font-bold mb-6">Detail Data Warga</h1>

    <div class="bg-white shadow-md rounded-lg p-4 mb-6 border border-gray-200">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="md:w-1/2">
                <p><strong>Nama Warga Pendaftar:</strong> {{ $item->nama_pendaftar ?? '-' }}</p>
                <p><strong>Nama Kepala Keluarga:</strong> {{ $item->nama_kepala_keluarga }}</p>
                <p><strong>No KK:</strong> {{ $item->no_kk_scan }}</p>
                <p><strong>Alamat:</strong></p>
                <ul class="ml-4 list-disc">
                    <li>{{ $item->alamat->nama_jalan ?? '-' }}</li>
                    <li>RT/RW: {{ $item->alamat->rt_alamat ?? '-' }}/{{ $item->alamat->rw_alamat ?? '-' }}</li>
                    <li>Kelurahan: {{ $item->alamat->kelurahan ?? '-' }}</li>
                    <li>Kecamatan: {{ $item->alamat->kecamatan ?? '-' }}</li>
                </ul>
            </div>
            <div class="md:w-1/2">
                {{-- Gambar kecil yang dapat diklik --}}
                <img src="{{ asset('storage/' . str_replace('public/', '', $item->path_file_kk)) }}" alt="Foto KK" class="w-full max-w-xs h-auto border border-red-500 cursor-pointer" onclick="showImage('{{ asset('storage/' . str_replace('public/', '', $item->path_file_kk)) }}')" />
            </div>
        </div>

        {{-- Gambar besar yang akan ditampilkan saat gambar kecil diklik --}}
        <div id="imageModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" onclick="closeImage(event)">
            <img id="largeImage" src="" alt="Gambar Besar" class="max-w-full max-h-full object-contain cursor-auto" />
        </div>
        <div id="imageModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-70">
            <div class="relative max-w-3xl w-full mx-4">
                <button class="absolute top-2 right-2 text-white text-2xl font-bold z-10" onclick="closeImage()">&times;</button>
                <img id="largeImage" src="" alt="Gambar Besar" class="w-full h-auto max-h-[80vh] object-contain rounded-lg shadow-lg border-4 border-white" />
            </div>
        </div>

        <div class="mt-4 flex gap-3">
            <form method="POST" action="{{ route('verifikasiAkunWarga.disetujui', $item->id_scan) }}">
                @csrf
                <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Setujui</button>
            </form>
            {{-- Tombol Tolak yang membuka modal --}}
            <button class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700" onclick="openModal({{ $item->id_scan }})">Tolak</button>

            <a href="{{ route('verifikasiAkunWarga') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 flex items-center justify-center">
                Kembali
            </a>
        </div>
    </div>
</div>

{{-- Modal untuk input alasan penolakan --}}
<div id="modalAlasan" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg w-full max-w-md mx-4 sm:mx-0">
        <h2 class="text-xl font-semibold mb-4">Masukkan Alasan Penolakan</h2>
        <form method="POST" id="formAlasanPenolakan" action="">
            @csrf
            <div class="mb-4">
                <label for="alasan_penolakan" class="block text-sm font-medium text-gray-700">Pilih Alasan Penolakan</label>
                <select id="alasan_penolakan" name="alasan_penolakan" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" onchange="toggleAlasanInput()">
                    <option value="">Pilih Alasan</option>
                    <option value="RT tidak sesuai">RT tidak sesuai</option>
                    <option value="Alamat tidak lengkap">Alamat tidak lengkap</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>

            {{-- Input alasan tambahan jika memilih 'Lainnya' --}}
            <div id="inputAlasanLainnya" class="hidden mb-4">
                <label for="alasan_lainnya" class="block text-sm font-medium text-gray-700">Alasan Lainnya</label>
                <textarea name="alasan_lainnya" id="alasan_lainnya" rows="4" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>

            <div class="flex justify-end gap-3">
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Kirim</button>
                <button type="button" onclick="closeModal()" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Tutup</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Menampilkan gambar besar di modal
    function showImage(src) {
        document.getElementById('largeImage').src = src;
        document.getElementById('imageModal').classList.remove('hidden');
    }

    // Menutup modal gambar besar ketika area di luar gambar diklik
    function closeImage(event) {
        if (event.target === event.currentTarget) {
            document.getElementById('imageModal').classList.add('hidden');
        }
    }

    function openModal(id) {
        const form = document.getElementById('formAlasanPenolakan');
        const route = "{{ route('verifikasiAkunWarga.ditolak', ['id' => '__ID__']) }}";
        form.action = route.replace('__ID__', id); // Ganti placeholder dengan ID yang sebenarnya
        document.getElementById('modalAlasan').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('modalAlasan').classList.add('hidden');

        // Reset isi form saat modal ditutup
        document.getElementById('formAlasanPenolakan').reset();

        // Sembunyikan textarea "Lainnya" jika sebelumnya terbuka
        document.getElementById('inputAlasanLainnya').classList.add('hidden');

        // Hapus atribut name dari textarea lainnya (jaga-jaga kalau sempat muncul)
        document.getElementById('alasan_lainnya').removeAttribute('name');
    }

    function toggleAlasanInput() {
        const select = document.getElementById('alasan_penolakan');
        const inputLainnya = document.getElementById('inputAlasanLainnya');
        if (select.value === 'Lainnya') {
            inputLainnya.classList.remove('hidden');
            document.getElementById('alasan_lainnya').setAttribute('name', 'alasan_penolakan');
        } else {
            inputLainnya.classList.add('hidden');
            document.getElementById('alasan_lainnya').removeAttribute('name');
        }
    }
</script>

@endsection

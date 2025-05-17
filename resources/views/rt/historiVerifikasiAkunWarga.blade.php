@extends('rt.dashboardRt')

@section('content')
<div class="container mx-auto p-4 mb-6 pt-20">
    <h1 class="text-2xl font-bold mb-6">Histori Verifikasi Akun Warga</h1>

    <p class="mb-6 text-sm md:text-base text-gray-700">
        Halaman ini menampilkan histori verifikasi akun warga, termasuk data yang telah disetujui atau ditolak beserta alasan dan waktu verifikasi.
    </p>

    <form method="GET" action="{{ route('historiVerifikasiAkunWarga') }}"
      class="mb-4 flex flex-col md:flex-row gap-2 items-start md:items-center">

    <div class="flex w-full md:w-1/3 gap-2">
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Cari nama warga atau no KK..."
            class="px-4 py-2 border rounded-md w-full text-sm" />

        <button
            type="submit"
            class="px-3 py-2 bg-blue-600 text-white rounded-md text-xs hover:bg-blue-700 transition flex-shrink-0">
            Cari
        </button>
    </div>

    <!-- Agar di desktop tombol tetap di bawah input, tapi di mobile tombol di samping -->
    <!-- Di desktop kita buat div input+tombol berdiri sendiri, tombol di bawah input secara flex-col -->
    <div class="hidden md:block w-auto">
        <!-- kosong, tombol sudah di div di atas untuk mobile -->
    </div>
</form>




    <div class="bg-white bg-opacity-80 p-4 md:p-6 rounded-xl shadow w-full">
        <div class="overflow-x-auto">
            <div class="min-w-full inline-block align-middle overflow-x-auto">
                <div class="overflow-x-auto border rounded-lg">
                    <table class="min-w-full text-xs md:text-sm text-gray-700">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-2 md:px-4 py-2 text-left">No</th>
                                <th class="px-2 md:px-4 py-2 text-left">Nama Warga</th>
                                <th class="px-2 md:px-4 py-2 text-left">Nama Kepala Keluarga</th>
                                <th class="px-2 md:px-4 py-2 text-left">No KK</th>
                                <th class="px-2 md:px-4 py-2 text-left">Alamat</th>
                                <th class="px-2 md:px-4 py-2 text-left">Waktu Verifikasi</th>
                                <th class="px-2 md:px-4 py-2 text-left">Status</th>
                                <th class="px-2 md:px-4 py-2 text-left">Alasan Penolakan</th>
                                <th class="px-2 md:px-4 py-2 text-left">Kartu Keluarga</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-gray-900">
                            @forelse ($historiData as $index => $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-2 md:px-4 py-2 whitespace-nowrap">{{ $index + 1 }}</td>
                                <td class="px-2 md:px-4 py-2 whitespace-nowrap">
                                    {{
                                        $item->status_verifikasi === 'disetujui'
                                            ? ($item->wargas->first()->nama_lengkap ?? '-')
                                            : ($item->pendaftaran->first()->nama_lengkap ?? '-')
                                    }}
                                </td>
                                <td class="px-2 md:px-4 py-2 whitespace-nowrap">{{ $item->nama_kepala_keluarga }}</td>
                                <td class="px-2 md:px-4 py-2 whitespace-nowrap">{{ $item->no_kk_scan }}</td>
                                <td class="px-2 md:px-4 py-2 whitespace-nowrap">
                                    {{ $item->alamat->nama_jalan ?? '-' }},<br>
                                    RT {{ $item->alamat->rt_alamat ?? '-' }}/RW {{ $item->alamat->rw_alamat ?? '-' }},<br>
                                    Kel. {{ $item->alamat->kelurahan ?? '-' }},
                                    Kec. {{ $item->alamat->kecamatan ?? '-' }}
                                </td>
                                <td class="px-2 md:px-4 py-2 whitespace-nowrap">
                                    {{ $item->updated_at->format('d-m-Y H:i') }}
                                </td>
                                <td class="px-2 md:px-4 py-2 whitespace-nowrap">
                                    <span class="font-semibold text-{{ $item->status_verifikasi === 'disetujui' ? 'green' : 'red' }}-600">
                                        {{ ucfirst($item->status_verifikasi) }}
                                    </span>
                                </td>
                                <td class="px-2 md:px-4 py-2 whitespace-nowrap">
                                    {{ $item->alasan_penolakan ?? '-' }}
                                </td>
                                <td class="px-2 md:px-4 py-2 whitespace-nowrap">
                                    <img src="{{ asset('storage/' . str_replace('public/', '', $item->path_file_kk)) }}"
                                         alt="Scan KK"
                                         class="w-20 h-auto rounded cursor-pointer"
                                         onclick="showModal('{{ asset('storage/' . str_replace('public/', '', $item->path_file_kk)) }}')" />
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="px-2 md:px-4 py-3 text-center text-gray-500">
                                    <p>✨ Tidak ada histori verifikasi saat ini. ✨</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="kkModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-60">
    <div class="bg-white p-4 md:p-6 rounded-lg shadow-lg max-w-3xl w-full relative">
        <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-600 hover:text-red-600 text-xl font-bold">&times;</button>
        <img id="kkImage" src="" alt="Kartu Keluarga" class="w-full h-auto rounded">
    </div>
</div>

<script>
    function showModal(imageUrl) {
        const modal = document.getElementById('kkModal');
        const img = document.getElementById('kkImage');
        img.src = imageUrl;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        const modal = document.getElementById('kkModal');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }

    // Optional: close modal when clicking outside the image box
    window.addEventListener('click', function (e) {
        const modal = document.getElementById('kkModal');
        if (e.target === modal) {
            closeModal();
        }
    });
</script>
@endsection

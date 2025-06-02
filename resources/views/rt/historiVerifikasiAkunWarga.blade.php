@extends('rt.dashboardRt')

@section('content')
<div class="container mx-auto p-4 mb-6 pt-20">
    <h1 class="text-2xl font-bold mb-2">Histori Verifikasi Akun Warga</h1>

    <p class="mb-6 text-lg text-gray-700">
        Halaman ini menampilkan histori verifikasi akun warga, termasuk data yang telah disetujui atau ditolak beserta alasan dan waktu verifikasi.
    </p>

    <!-- Form Search -->
    <form method="GET" action="{{ route('historiVerifikasiAkunWarga') }}" class="relative w-full max-w-sm mb-6">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8" />
                <line x1="21" y1="21" x2="16.65" y2="16.65" />
            </svg>
        </span>
        <input type="text" name="search" id="searchInput"
            value="{{ request('search') }}"
            placeholder="Cari nama warga atau no KK..."
            class="pl-10 pr-4 py-2 border rounded w-full focus:outline-none focus:ring-2 focus:ring-green-400 text-sm" />
    </form>

    <div class="bg-white bg-opacity-80 p-4 md:p-6 rounded-xl shadow w-full">
        <div class="max-h-[400px] overflow-y-auto rounded-lg shadow-md border">
            <table class="min-w-full table-fixed text-xs md:text-sm text-gray-700">
                <thead class="bg-gray-100 sticky top-0 z-10">
                    <tr>
                        <th class="px-2 md:px-4 py-2 text-left w-8">No</th>
                        <th class="px-2 md:px-4 py-2 text-left w-40">Nama Warga</th>
                        <th class="px-2 md:px-4 py-2 text-left w-40">Nama Kepala Keluarga</th>
                        <th class="px-2 md:px-4 py-2 text-left w-24">No KK</th>
                        <th class="px-2 md:px-4 py-2 text-left w-56">Alamat</th>
                        <th class="px-2 md:px-4 py-2 text-left w-32">Waktu Verifikasi</th>
                        <th class="px-2 md:px-4 py-2 text-left w-20">Status</th>
                        <th class="px-2 md:px-4 py-2 text-left w-40">Alasan Penolakan</th>
                        <th class="px-2 md:px-4 py-2 text-left w-28">Kartu Keluarga</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-gray-900">
                    @forelse ($historiData as $index => $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-2 md:px-4 py-2 whitespace-nowrap w-8">{{ $index + 1 }}</td>
                        <td class="px-2 md:px-4 py-2 whitespace-nowrap w-40">
                            {{
                                $item->status_verifikasi === 'disetujui'
                                    ? ($item->wargas->first()->nama_lengkap ?? '-')
                                    : ($item->pendaftaran->first()->nama_lengkap ?? '-')
                            }}
                        </td>
                        <td class="px-2 md:px-4 py-2 whitespace-nowrap w-40">{{ $item->nama_kepala_keluarga }}</td>
                        <td class="px-2 md:px-4 py-2 whitespace-nowrap w-24">{{ $item->no_kk_scan }}</td>
                        <td class="px-2 md:px-4 py-2 whitespace-nowrap w-56">
                            {{ $item->alamat->nama_jalan ?? '-' }},<br>
                            RT {{ $item->alamat->rt_alamat ?? '-' }}/RW {{ $item->alamat->rw_alamat ?? '-' }},<br>
                            Kel. {{ $item->alamat->kelurahan ?? '-' }},
                            Kec. {{ $item->alamat->kecamatan ?? '-' }}
                        </td>
                        <td class="px-2 md:px-4 py-2 whitespace-nowrap w-32">
                            {{ $item->updated_at->format('d-m-Y H:i') }}
                        </td>
                        <td class="px-2 md:px-4 py-2 whitespace-nowrap w-20">
                            <span class="font-semibold text-{{ $item->status_verifikasi === 'disetujui' ? 'green' : 'red' }}-600">
                                {{ ucfirst($item->status_verifikasi) }}
                            </span>
                        </td>
                        <td class="px-2 md:px-4 py-2 whitespace-nowrap w-40">{{ $item->alasan_penolakan ?? '-' }}</td>
                        <td class="px-2 md:px-4 py-2 whitespace-nowrap w-28">
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

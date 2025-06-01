@extends('rw.dashboardRw')

@section('content')
<h1 class="text-2xl pt-20 md:text-3xl font-bold text-gray-800 mb-2">Riwayat Surat Warga</h1>
<p class="text-gray-600 mb-6 text-lg">
    Berikut adalah daftar surat pengantar warga yang telah disetujui dan tersimpan.
    Anda dapat melihat detail dan mengunduh surat melalui tombol "<strong class="text-yellow-700">Lihat Surat</strong>" di setiap baris.
</p>

<!-- Form Search -->
<form method="GET" action="{{ route('riwayatSuratRw') }}" class="relative w-full sm:w-80 mb-6">
    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
            viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8" />
            <line x1="21" y1="21" x2="16.65" y2="16.65" />
        </svg>
    </span>
    <input type="text" name="search" id="searchInput"
        value="{{ request('search') }}"
        placeholder="Cari nama warga atau tujuan surat..."
        class="pl-10 pr-4 py-2 border rounded w-full focus:outline-none focus:ring-2 focus:ring-green-400" />
</form>


<div class="bg-white bg-opacity-80 p-4 md:p-6 rounded-xl shadow w-full">
    <div class="overflow-x-auto max-h-[500px] overflow-y-auto border rounded-lg shadow">
        <table class="min-w-full bg-white border rounded shadow">
            <thead class="bg-green-100 sticky top-0 z-10">
                <tr class="text-left">
                    <th class="px-4 py-2">No</th>
                    <th class="px-4 py-2">Nama Warga</th>
                    <th class="px-4 py-2">Jenis Surat</th>
                    <th class="px-4 py-2">Tujuan Surat</th>
                    <th class="px-4 py-2">Nomor Surat</th>
                    <th class="px-4 py-2">Tanggal Disetujui</th>
                    <th class="px-4 py-2">Hasil Surat</th>
                </tr>
            </thead>
            <tbody>
                @if($hasilSurat->isEmpty())
                    <tr>
                        <td colspan="7" class="text-center text-gray-500 py-4">
                            Belum ada surat yang disetujui dan tersimpan.
                        </td>
                    </tr>
                @else
                    @php $no = 1; @endphp
                    @foreach($hasilSurat as $item)
                    <tr>
                        <td class="px-4 py-2">{{ $no++ }}</td>
                        <td class="px-4 py-2">
                            @if($item->jenis === 'biasa')
                                {{ $item->pengajuanSurat->warga->nama_lengkap ?? '-' }}
                            @else
                                {{ $item->pengajuanSuratLain->warga->nama_lengkap ?? '-' }}
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            {{ ucfirst($item->jenis) }}
                        </td>
                        <td class="px-4 py-2">
                            @if($item->jenis === 'biasa')
                                {{ $item->pengajuanSurat->tujuanSurat->nama_tujuan ?? '-' }}
                            @else
                                {{ $item->pengajuanSuratLain->tujuan_manual ?? '-' }}
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            @if($item->jenis === 'biasa')
                                {{ $item->pengajuanSurat->tujuanSurat->nomor_surat ?? '-' }}
                            @else
                                {{ $item->pengajuanSuratLain->nomor_surat_pengajuan_lain ?? '-' }}
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            @if($item->jenis === 'biasa')
                                {{ $item->pengajuanSurat->updated_at->translatedFormat('d F Y') ?? '-' }}
                            @else
                                {{ $item->pengajuanSuratLain->updated_at->translatedFormat('d F Y') ?? '-' }}
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            @if($item->file_surat && \Illuminate\Support\Facades\Storage::exists($item->file_surat))
                                <button class="btn btn-info lihat-surat px-3 py-1 bg-yellow-500 text-white font-semibold rounded" data-id="{{ $item->id_hasil_surat_ttd_rw }}">
                                    Lihat Surat
                                </button>
                            @else
                                <p class="font-semibold">Tidak Ada</p>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>

<!-- Modal untuk lihat hasil surat -->
<div id="modalHasilSurat" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-[80%] h-[90vh] flex flex-col">
        <div class="flex justify-between items-center p-4 border-b">
            <h3 class="text-lg font-semibold">Hasil Surat</h3>
            <button id="closeModal" class="text-gray-600 hover:text-gray-900 text-2xl leading-none">&times;</button>
        </div>
        <div class="flex-1 overflow-auto p-4 flex justify-center">
            <iframe id="iframeHasilSurat" src="" frameborder="0" class="w-full h-full"></iframe>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.lihat-surat').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const idHasilSurat = this.getAttribute('data-id');
        const iframe = document.getElementById('iframeHasilSurat');
        const modal = document.getElementById('modalHasilSurat');

        iframe.src = `/rw/surat/${idHasilSurat}/lihatRw`; // Sesuaikan route RW untuk lihat surat

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    });
});

document.getElementById('closeModal').addEventListener('click', () => {
    const modal = document.getElementById('modalHasilSurat');
    const iframe = document.getElementById('iframeHasilSurat');

    modal.classList.add('hidden');
    modal.classList.remove('flex');

    iframe.src = '';
});
</script>
@endsection

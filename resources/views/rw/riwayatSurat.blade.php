@extends('rw.dashboardRw')

@section('content')
<h1 class="text-2xl pt-20 md:text-3xl font-bold text-gray-800 mb-6">Riwayat Surat Warga</h1>

{{-- @if($hasilSurat->isEmpty())
    <p class="text-center text-gray-500 py-4">Belum ada surat yang disetujui dan tersimpan.</p>
@else --}}
<div class="overflow-x-auto max-h-[500px] overflow-y-auto border rounded-lg shadow">
    <table class="min-w-full bg-white border rounded shadow">
        <thead class="bg-blue-100 sticky top-0 z-10">
            <tr class="text-left">
                <th class="px-4 py-2">No</th>
                <th class="px-4 py-2">Nama Warga</th>
                <th class="px-4 py-2">Jenis Surat</th>
                <th class="px-4 py-2">Nama Tujuan Surat</th>
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
                            {{ $item->pengajuanSurat->updated_at->format('d-m-Y') ?? '-' }}
                        @else
                            {{ $item->pengajuanSuratLain->updated_at->format('d-m-Y') ?? '-' }}
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

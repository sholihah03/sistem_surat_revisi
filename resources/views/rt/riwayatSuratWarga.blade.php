@extends('rt.dashboardRt')

@section('content')
<div class="container mx-auto p-4 mb-6 pt-20">
    <h1 class="text-2xl font-bold mb-6">Riwayat Surat Warga</h1>

    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full text-sm text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3">No</th>
                    <th class="p-3">Nama</th>
                    <th class="p-3">Tujuan</th>
                    <th class="p-3">Nomor Surat</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Tanggal Diproses</th>
                    <th class="p-3">Alasan Ditolak</th>
                    <th class="p-3">Hasil Surat</th>
                    {{-- <th class="p-3">Aksi</th> --}}
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @if($pengajuanBiasa->isEmpty() && $pengajuanLain->isEmpty())
                    <tr>
                        <td colspan="9" class="p-3 text-center text-gray-500">Data belum ada</td>
                    </tr>
                @else
                {{-- Pengajuan Surat Biasa --}}
                @foreach ($pengajuanBiasa as $item)
                    <tr class="border-t">
                        <td class="p-3">{{ $no++ }}</td>
                        <td class="p-3">{{ $item->warga->nama_lengkap }}</td>
                        <td class="p-3">{{ $item->tujuanSurat->nama_tujuan ?? '-' }}</td>
                        <td class="p-3">{{ $item->tujuanSurat->nomor_surat ?? '-' }}</td>
                        <td class="p-3">
                            @if ($item->status === 'disetujui')
                                <span class="text-green-600 font-semibold">{{ ucfirst($item->status) }}</span>
                            @elseif ($item->status === 'ditolak')
                                <span class="text-red-600 font-semibold">{{ ucfirst($item->status) }}</span>
                            @else
                                <span>{{ ucfirst($item->status) }}</span>
                            @endif
                        </td>
                        <td class="p-3">{{ $item->updated_at->format('d-m-Y') }}</td>
                        <td class="p-3">{{ $item->alasan_penolakan_pengajuan ?? 'Tidak Ada' }}</td>
                        <td class="p-3">
                            @php $key = 'biasa-'.$item->id_pengajuan_surat; @endphp
                            @if($hasilSurat->has($key))
                                <button class="btn btn-info lihat-surat px-3 py-1 bg-yellow-500 text-white font-semibold rounded" data-id="{{ $hasilSurat[$key]->id_hasil_surat_ttd_rt }}">
                                    Lihat Surat
                                </button>
                            @else
                                <p class="font-semibold">
                                    Tidak Ada
                                </p>
                            @endif
                        </td>
                        {{-- <td class="p-3">
                            @php $key = 'biasa-'.$item->id_pengajuan_surat; @endphp
                            @if($hasilSurat->has($key))
                                <a href="{{ route('rt.unduhHasilSurat', ['id' => $hasilSurat[$key]->id_hasil_surat_ttd_rt]) }}" class="px-3 py-1 bg-green-500 text-white rounded">
                                    Unduh
                                </a>
                            @else
                                Tidak Ada
                            @endif
                        </td> --}}
                    </tr>
                @endforeach

                {{-- Pengajuan Surat Lain --}}
                @foreach ($pengajuanLain as $item)
                    <tr class="border-t">
                        <td class="p-3">{{ $no++ }}</td>
                        <td class="p-3">{{ $item->warga->nama_lengkap }}</td>
                        <td class="p-3">{{ $item->tujuan_manual }}</td>
                        <td class="p-3">{{ $item->nomor_surat_pengajuan_lain ?? '-' }}</td>
                        <td class="p-3">
                            @if ($item->status_pengajuan_lain === 'disetujui')
                                <span class="text-green-600 font-semibold">{{ ucfirst($item->status_pengajuan_lain) }}</span>
                            @elseif ($item->status_pengajuan_lain === 'ditolak')
                                <span class="text-red-600 font-semibold">{{ ucfirst($item->status_pengajuan_lain) }}</span>
                            @else
                                <span>{{ ucfirst($item->status_pengajuan_lain) }}</span>
                            @endif
                        </td>
                        <td class="p-3">{{ $item->updated_at->format('d-m-Y') }}</td>
                        <td class="p-3">{{ $item->alasan_penolakan_pengajuan_lain ?? 'Tidak Ada' }}</td>
                        <td class="p-3">
                            @php $key = 'lain-'.$item->id_pengajuan_surat_lain; @endphp
                            @if($hasilSurat->has($key))
                                <button class="btn btn-info lihat-surat text-blue-600 underline" data-id="{{ $hasilSurat[$key]->id_hasil_surat_ttd_rt }}">
                                    Lihat Surat
                                </button>
                            @else
                                <p class="font-semibold">
                                    Tidak Ada
                                </p>
                            @endif
                        </td>
                        {{-- <td class="p-3">
                            @php $key = 'lain-'.$item->id_pengajuan_surat_lain; @endphp
                            @if($hasilSurat->has($key))
                                <a href="{{ route('rt.unduhHasilSurat', ['id' => $hasilSurat[$key]->id_hasil_surat_ttd_rt]) }}" class="px-3 py-1 bg-green-500 text-white rounded">
                                    Unduh
                                </a>
                            @else
                                Tidak Ada
                            @endif
                        </td> --}}
                    </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
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

        iframe.src = `/rt/surat/${idHasilSurat}/lihat`;

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

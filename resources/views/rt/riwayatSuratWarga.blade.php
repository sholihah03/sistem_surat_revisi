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
                    <th class="p-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                {{-- Pengajuan Surat Biasa --}}
                @foreach ($pengajuanBiasa as $item)
                    <tr class="border-t">
                        <td class="p-3">{{ $no++ }}</td>
                        <td class="p-3">{{ $item->warga->nama_lengkap }}</td>
                        <td class="p-3">{{ $item->tujuanSurat->nama_tujuan ?? '-' }}</td>
                        <td class="p-3">{{ $item->tujuanSurat->nomor_surat ?? '-' }}</td>
                        <td class="p-3">{{ ucfirst($item->status) }}</td>
                        <td class="p-3">{{ $item->updated_at->format('d-m-Y') }}</td>
                        <td class="p-3">{{ $item->alasan_penolakan_pengajuan ?? 'Tidak Ada' }}</td>
                        <td class="p-3">

                        </td>
                        <td class="p-3">
                            <a href="{{ route('rt.unduhSurat', ['jenis' => 'biasa', 'id' => $item->id_pengajuan_surat]) }}"
                                class="px-3 py-1 bg-green-500 text-white rounded">
                                Unduh
                            </a>
                        </td>
                    </tr>
                @endforeach
                {{-- Pengajuan Surat Lain --}}
                @foreach ($pengajuanLain as $item)
                    <tr class="border-t">
                        <td class="p-3">{{ $no++ }}</td>
                        <td class="p-3">{{ $item->warga->nama_lengkap }}</td>
                        <td class="p-3">{{ $item->tujuan_manual }}</td>
                        <td class="p-3">{{ $item->tujuan_manual->nomor_surat_pengajuan_lain ?? '-' }}</td>
                        <td class="p-3">{{ ucfirst($item->status_pengajuan_lain) }}</td>
                        <td class="p-3">{{ $item->updated_at->format('d-m-Y') }}</td>
                        <td class="p-3">{{ $item->alasan_penolakan_pengajuan_lain ?? 'Tidak Ada' }}</td>
                        <td class="p-3">

                        </td>
                        <td class="p-3">
                            <a href="{{ route('rt.unduhSurat', ['jenis' => 'lain', 'id' => $item->id_pengajuan_surat_lain]) }}"
                                class="px-3 py-1 bg-green-500 text-white rounded">
                                Unduh
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

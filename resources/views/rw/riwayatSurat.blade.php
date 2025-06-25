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
                    <th class="px-4 py-2">Tanggal Disetujui / Ditolak</th>
                    <th class="px-4 py-2">Status Surat</th>
                    <th class="px-4 py-2">Alasan Ditolak</th>
                    <th class="px-4 py-2">Dokumen Persyaratan</th>
                    <th class="px-4 py-2">Hasil Surat</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                {{-- Tampilkan surat yang disetujui --}}
                @foreach($hasilSuratDisetujui as $item)
                <tr>
                    <td class="px-4 py-2">{{ $no++ }}</td>
                    <td class="px-4 py-2">
                        @if($item->jenis === 'biasa')
                            {{ $item->pengajuanSurat->warga->nama_lengkap ?? '-' }}
                        @else
                            {{ $item->pengajuanSuratLain->warga->nama_lengkap ?? '-' }}
                        @endif
                    </td>
                    <td class="px-4 py-2">{{ ucfirst($item->jenis) }}</td>
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
                            {{ $item->pengajuanSurat && $item->pengajuanSurat->waktu_persetujuan_rw ? \Carbon\Carbon::parse($item->pengajuanSurat->waktu_persetujuan_rw)->translatedFormat('d F Y') : '-' }}
                        @else
                            {{ $item->pengajuanSuratLain && $item->pengajuanSuratLain->waktu_persetujuan_rw_pengajuan_lain ? \Carbon\Carbon::parse($item->pengajuanSuratLain->waktu_persetujuan_rw_pengajuan_lain)->translatedFormat('d F Y') : '-' }}
                        @endif
                    </td>
                    <td class="px-4 py-2">
                        @if($item->jenis === 'biasa')
                            {{ $item->pengajuanSurat->status_rw ?? '-' }}
                        @else
                            {{ $item->pengajuanSuratLain->status_rw_pengajuan_lain ?? '-' }}
                        @endif
                    </td>
                    <td class="px-4 py-2">
                        @if($item->jenis === 'biasa')
                            {{ $item->pengajuanSurat->alasan_penolakan_pengajuan ?? 'Tidak Ada' }}
                        @else
                            {{ $item->pengajuanSuratLain->alasan_penolakan_pengajuan_lain ?? 'Tidak Ada' }}
                        @endif
                    </td>
                    <td class="px-4 py-2">
                        @php
                            $isSuratBiasa = $item->jenis === 'biasa';
                            $pengajuan = $isSuratBiasa ? $item->pengajuanSurat : $item->pengajuanSuratLain;
                        @endphp
                        @if($isSuratBiasa && $pengajuan->pengajuan->isNotEmpty())
                            <ul class="list-disc pl-4">
                                @foreach ($pengajuan->pengajuan as $dokumen)
                                    <li class="mb-2">
                                        <p class="text-sm text-gray-700 mb-1">
                                            {{ $dokumen->persyaratan->nama_persyaratan ?? 'Dokumen' }}
                                        </p>
                                        <img
                                            src="{{ asset('storage/' . str_replace('public/', '', $dokumen->dokumen)) }}"
                                            alt="Dokumen Persyaratan"
                                            class="w-32 cursor-pointer"
                                            onclick="showImageModal('{{ asset('storage/' . str_replace('public/', '', $dokumen->dokumen)) }}', '{{ $dokumen->persyaratan->nama_persyaratan }}')"
                                        />
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-gray-400">Tidak ada dokumen</span>
                        @endif
                    </td>
                    <td class="px-4 py-2">
                        @if(!empty($item->id_hasil_surat_ttd_rw))
                            <button
                                onclick="showHasilSuratModal('{{ route('rw.lihatHasilSurat', ['id' => $item->id_hasil_surat_ttd_rw]) }}')"
                                class="btn btn-info lihat-surat px-3 py-1 bg-yellow-500 text-white font-semibold rounded"
                            >
                                Lihat Surat
                            </button>
                        @else
                            <span class="text-gray-400">Surat tidak tersedia</span>
                        @endif
                    </td>
                </tr>
                @endforeach

                {{-- Tampilkan surat yang ditolak (jenis biasa) --}}
                @foreach($pengajuanDitolakBiasa as $item)
                <tr>
                    <td class="px-4 py-2">{{ $no++ }}</td>
                    <td class="px-4 py-2">{{ $item->warga->nama_lengkap ?? '-' }}</td>
                    <td class="px-4 py-2">Biasa</td>
                    <td class="px-4 py-2">{{ $item->tujuanSurat->nama_tujuan ?? '-' }}</td>
                    <td class="px-4 py-2">{{ $item->tujuanSurat->nomor_surat ?? '-' }}</td>
                    <td class="px-4 py-2">
                        {{ $item->waktu_persetujuan_rw ? \Carbon\Carbon::parse($item->waktu_persetujuan_rw)->translatedFormat('d F Y') : '-' }}
                    </td>
                    <td class="px-4 py-2 text-red-600 font-semibold">Ditolak</td>
                    <td class="px-4 py-2">{{ $item->alasan_penolakan_pengajuan ?? '-' }}</td>
                    <td class="px-4 py-2">
                        @if($item->pengajuan && $item->pengajuan->isNotEmpty())
                            <ul class="list-disc pl-4">
                                @foreach ($item->pengajuan as $dokumen)
                                    <li class="mb-2">
                                        <p class="text-sm text-gray-700 mb-1">
                                            {{ $dokumen->persyaratan->nama_persyaratan ?? 'Dokumen' }}
                                        </p>
                                        <img
                                            src="{{ asset('storage/' . str_replace('public/', '', $dokumen->dokumen)) }}"
                                            alt="Dokumen Persyaratan"
                                            class="w-32 cursor-pointer"
                                            onclick="showImageModal('{{ asset('storage/' . str_replace('public/', '', $dokumen->dokumen)) }}', '{{ $dokumen->persyaratan->nama_persyaratan }}')"
                                        />
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-gray-500 font-semibold">Tidak Ada</span>
                        @endif
                    </td>
                    <td class="px-4 py-2"><span class="text-gray-500 font-semibold">Tidak Ada</span></td>
                </tr>
                @endforeach

                {{-- Tampilkan surat yang ditolak (jenis lain) --}}
                @foreach($pengajuanDitolakLain as $item)
                <tr>
                    <td class="px-4 py-2">{{ $no++ }}</td>
                    <td class="px-4 py-2">{{ $item->warga->nama_lengkap ?? '-' }}</td>
                    <td class="px-4 py-2">Lain</td>
                    <td class="px-4 py-2">{{ $item->tujuan_manual ?? '-' }}</td>
                    <td class="px-4 py-2">{{ $item->nomor_surat_pengajuan_lain ?? '-' }}</td>
                    <td class="px-4 py-2">
                        {{ $item->waktu_persetujuan_rw_pengajuan_lain ? \Carbon\Carbon::parse($item->waktu_persetujuan_rw_pengajuan_lain)->translatedFormat('d F Y') : '-' }}
                    </td>
                    <td class="px-4 py-2 text-red-600 font-semibold">Ditolak</td>
                    <td class="px-4 py-2">{{ $item->alasan_penolakan_pengajuan_lain ?? '-' }}</td>
                    <td class="px-4 py-2">
                        @if($item->pengajuan && $item->pengajuan->isNotEmpty())
                            <ul class="list-disc pl-4">
                                @foreach ($item->pengajuan as $dokumen)
                                    <li class="mb-2">
                                        <p class="text-sm text-gray-700 mb-1">
                                            {{ $dokumen->persyaratan->nama_persyaratan ?? 'Dokumen' }}
                                        </p>
                                        <img
                                            src="{{ asset('storage/' . str_replace('public/', '', $dokumen->dokumen)) }}"
                                            alt="Dokumen Persyaratan"
                                            class="w-32 cursor-pointer"
                                            onclick="showImageModal('{{ asset('storage/' . str_replace('public/', '', $dokumen->dokumen)) }}', '{{ $dokumen->persyaratan->nama_persyaratan }}')"
                                        />
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-gray-500 font-semibold">Tidak Ada</span>
                        @endif
                    </td>
                    <td class="px-4 py-2"><span class="text-gray-500 font-semibold">Tidak Ada</span></td>
                </tr>
                @endforeach

                {{-- Jika semua kosong --}}
                @if($hasilSuratDisetujui->isEmpty() && $pengajuanDitolakBiasa->isEmpty() && $pengajuanDitolakLain->isEmpty())
                <tr>
                    <td colspan="9" class="text-center text-gray-500 py-4">
                        Belum ada surat yang disetujui atau ditolak.
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Hasil Surat -->
<div id="modalHasilSurat" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2
                bg-white rounded-lg shadow-lg w-[80%] h-[90vh] flex flex-col">
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
    function showHasilSuratModal(url) {
        const iframe = document.getElementById('iframeHasilSurat');
        iframe.src = url;
        document.getElementById('modalHasilSurat').classList.remove('hidden');
    }

    document.getElementById('closeModal').addEventListener('click', function () {
        document.getElementById('modalHasilSurat').classList.add('hidden');
        document.getElementById('iframeHasilSurat').src = ''; // clear src saat modal ditutup
    });
</script>

@endsection

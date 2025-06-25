@extends('rw.dashboardRw')

@section('content')
<h1 class="text-2xl pt-20 md:text-3xl font-bold text-gray-800 mb-2">Manajemen Surat Warga</h1>
<p class="text-gray-700 mb-6 text-lg">
    Halaman ini menampilkan daftar pengajuan surat dari warga yang telah disetujui RT dan menunggu persetujuan RW.
    Periksa informasi surat secara menyeluruh sebelum menyetujui atau menolak.
</p>

@if(session('success'))
<div id="successModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 sm:p-8 relative w-[90%] max-w-md sm:max-w-lg text-center animate-scale">
        <button onclick="closeSuccessModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>
        <div class="flex justify-center mb-6">
            <img src="https://img.icons8.com/color/96/000000/ok--v1.png" alt="Success Icon" class="w-20 h-20">
        </div>
        <h2 class="text-2xl font-bold mb-4 text-gray-800 whitespace-nowrap">
            {{ session('success') }}
        </h2>
        <button onclick="closeSuccessModal()" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 rounded-lg">
            Tutup
        </button>
    </div>
</div>
@endif
<div class="bg-white bg-opacity-80 p-4 md:p-6 rounded-xl shadow w-full">
    <div class="overflow-x-auto max-h-[500px] overflow-y-auto border rounded-lg shadow">
        <table class="min-w-full bg-white border rounded shadow">
            <thead class="bg-green-100 sticky top-0 z-10">
                <tr class="text-left">
                    <th class="px-4 py-2">No</th>
                    <th class="px-4 py-2">Rt</th>
                    <th class="px-4 py-2">Nama</th>
                    <th class="px-4 py-2">Jenis</th>
                    <th class="px-4 py-2">Tujuan</th>
                    <th class="px-4 py-2">Nomor Surat</th>
                    <th class="px-4 py-2">Nomor KTP</th>
                    <th class="px-4 py-2">Pekerjaan</th>
                    <th class="px-4 py-2">Alamat</th>
                    <th class="px-4 py-2">Dokumen Persyaratan</th>
                    <th class="px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($surats as $index => $surat)
                    @php
                        $isSuratBiasa = $surat->jenis === 'biasa';
                        $pengajuan = $isSuratBiasa ? $surat->pengajuanSurat ?? null : $surat->pengajuanSuratLain ?? null;
                        $warga = $pengajuan->warga ?? null;
                    @endphp
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $index + 1 }}</td>
                        <td class="px-4 py-2">{{ $warga->rt->no_rt ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $warga->nama_lengkap ?? '-' }}</td>
                        <td class="px-4 py-2">{{ ucfirst($surat->jenis) }}</td>
                        <td class="px-4 py-2">{{ $isSuratBiasa ? ($pengajuan->tujuanSurat->nama_tujuan ?? '-') : ($pengajuan->tujuan_manual ?? '-') }}</td>
                        <td class="px-4 py-2">
                            {{ $isSuratBiasa ? ($pengajuan->tujuanSurat->nomor_surat ?? '-') : ($pengajuan->nomor_surat_pengajuan_lain ?? '-') }}
                        </td>
                        <td class="px-4 py-2">{{ $warga->nik ?? '-' }}</td>
                        <td class="px-4 py-2">
                            {{ $isSuratBiasa ? ($pengajuan->pekerjaan ?? '-') : ($pengajuan->pekerjaan_pengaju_lain ?? '-') }}
                        </td>
                        <td class="px-4 py-2">
                            @if($isSuratBiasa)
                                {{ $pengajuan->alamat ?? $warga->scan_KK->alamat->nama_jalan ?? '-' }}
                            @else
                                {{ $pengajuan->alamat_pengaju_lain ?? $warga->scan_KK->alamat->nama_jalan ?? '-' }}
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            @if($isSuratBiasa && $pengajuan?->pengajuan && $pengajuan->pengajuan->isNotEmpty())
                                <ul class="list-disc pl-4">
                                    @foreach ($pengajuan->pengajuan as $item)
                                        <li class="mb-2">
                                            <p class="text-sm text-gray-700 mb-1">
                                                {{ $item->persyaratan->nama_persyaratan ?? 'Dokumen' }}
                                            </p>
                                            <img
                                                src="{{ asset('storage/' . str_replace('public/', '', $item->dokumen)) }}"
                                                alt="Dokumen Persyaratan"
                                                class="w-32 cursor-pointer"
                                                onclick="showImageModal('{{ asset('storage/' . str_replace('public/', '', $item->dokumen)) }}', '{{ $item->persyaratan->nama_persyaratan }}')"
                                            />
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-gray-400">Tidak ada dokumen</span>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            <form class="mb-2" method="POST" action="{{ route('rw.setujuiSurat') }}">
                                @csrf
                                @if ($isSuratBiasa)
                                    <input type="hidden" name="pengajuan_surat_id" value="{{ $surat->pengajuan_surat_id }}">
                                @else
                                    <input type="hidden" name="pengajuan_surat_lain_id" value="{{ $surat->pengajuan_surat_lain_id }}">
                                @endif
                                <input type="hidden" name="jenis" value="{{ $surat->jenis }}">
                                {{-- <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                                    Setujui
                                </button> --}}
                                <button type="button"
                                    onclick="showConfirmModal('{{ $surat->pengajuan_surat_id ?? $surat->pengajuan_surat_lain_id }}', '{{ $surat->jenis }}')"
                                    class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                                    Setujui
                                </button>
                            </form>
                            @if ($isSuratBiasa)
                                <button type="button"
                                    onclick="showModal('{{ $surat->pengajuan_surat_id }}', '{{ $surat->jenis }}', 'biasa')"
                                    class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                    Tolak
                                </button>
                            @else
                                <button type="button"
                                    onclick="showModal('{{ $surat->pengajuan_surat_lain_id }}', '{{ $surat->jenis }}', 'lain')"
                                    class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                    Tolak
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center text-gray-500 py-4"><strong>Belum ada surat yang masuk</strong></td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal untuk melihat gambar dokumen -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white p-4 rounded-lg shadow-lg max-w-3xl w-full relative">
        <button onclick="closeImageModal()" class="absolute top-2 right-2 text-gray-600 hover:text-red-500 text-xl font-bold">&times;</button>
        <img id="modalImage" src="" alt="Preview Gambar" class="w-full max-h-[80vh] object-contain rounded">
    </div>
</div>

<!-- Modal konfirmasi setujui -->
<div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white p-6 rounded shadow w-full max-w-md text-center">
        <h2 class="text-xl font-bold mb-4 text-gray-800">Konfirmasi Persetujuan</h2>
        <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menyetujui pengajuan surat ini?</p>
        <form id="confirmForm" method="POST" action="{{ route('rw.setujuiSurat') }}">
            @csrf
            <input type="hidden" name="jenis" id="confirmJenis">
            <input type="hidden" id="confirmId">
            <div class="flex justify-center gap-4">
                <button type="button" onclick="closeConfirmModal()" class="px-4 py-2 bg-gray-300 rounded">Batal</button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Ya, Setujui</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal alasan ditolak -->
<div id="modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
    <div class="bg-white p-6 rounded shadow w-full max-w-md">
        <form id="modalForm" method="POST" action="{{ route('rw.tolakSurat') }}">
            @csrf
            <input type="hidden" id="formId">
            <input type="hidden" name="jenis" id="formJenis">
            <input type="hidden" name="aksi" id="formAksi">

            <div id="inputNomorSurat" class="mb-4 hidden">
                <label class="block mb-1">Nomor Surat</label>
                <input type="text" name="nomor_surat" class="w-full border rounded px-3 py-2">
            </div>

            <div id="inputAlasanTolak" class="mb-4">
                <label class="block mb-1">Alasan Penolakan</label>
                <textarea name="alasan_penolakan" required class="w-full border rounded px-3 py-2"></textarea>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 rounded">Batal</button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded">Tolak Surat</button>
            </div>
        </form>
    </div>
</div>

<script>
    let currentImageUrl = '';
    function showImageModal(imageUrl) {
        currentImageUrl = imageUrl;
        document.getElementById('modalImage').src = imageUrl;
        document.getElementById('imageModal').classList.remove('hidden');
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
        document.getElementById('modalImage').src = '';
        currentImageUrl = '';
    }

    function showConfirmModal(id, jenis) {
        document.getElementById('confirmForm').reset();

        if (jenis === 'biasa') {
            document.getElementById('confirmId').setAttribute('name', 'pengajuan_surat_id');
        } else {
            document.getElementById('confirmId').setAttribute('name', 'pengajuan_surat_lain_id');
        }

        document.getElementById('confirmId').value = id;
        document.getElementById('confirmJenis').value = jenis;
        document.getElementById('confirmModal').classList.remove('hidden');
    }

    function closeConfirmModal() {
        document.getElementById('confirmModal').classList.add('hidden');
        document.getElementById('confirmId').value = '';
        document.getElementById('confirmJenis').value = '';
    }

    function showModal(id, jenis, tipe) {
        if (tipe === 'biasa') {
            document.getElementById('formId').setAttribute('name', 'pengajuan_surat_id');
        } else {
            document.getElementById('formId').setAttribute('name', 'pengajuan_surat_lain_id');
        }
        document.getElementById('formId').value = id;
        document.getElementById('formJenis').value = jenis;
        document.getElementById('formAksi').value = 'tolak';

        document.getElementById('inputNomorSurat').classList.add('hidden');
        document.getElementById('inputAlasanTolak').classList.remove('hidden');

        document.getElementById('modalForm').action = "{{ route('rw.tolakSurat') }}";
        document.getElementById('modal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('modal').classList.add('hidden');
        document.getElementById('formId').value = '';
        document.getElementById('formJenis').value = '';
        document.getElementById('formAksi').value = '';
        document.querySelector('[name=alasan_penolakan]').value = '';
    }

    function closeSuccessModal() {
        const modal = document.getElementById('successModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

</script>
@endsection

@extends('rt.dashboardRt')

@section('content')
<div class="container mx-auto p-4 mb-6 pt-20">
    <h1 class="text-2xl font-bold mb-2">Verifikasi Surat Warga</h1>
    <p class="text-gray-600 text-lg mb-6">
        Halaman ini menampilkan daftar pengajuan surat dari warga yang membutuhkan verifikasi oleh Ketua RT.
        <br>Silakan teliti setiap data pengajuan sebelum mengambil keputusan untuk menyetujui atau menolak. Pastikan nomor surat diisi untuk pengajuan jenis "<strong class="text-red-500">Lain</strong>" yang disetujui, dan sertakan alasan penolakan jika menolak pengajuan.
    </p>


    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full text-sm text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3">Nama</th>
                    <th class="p-3">Tujuan</th>
                    <th class="p-3">Jenis</th>
                    <th class="p-3">Nomor Surat</th>
                    <th class="p-3">Tempat/ Tanggal Lahir</th>
                    <th class="p-3">Nomor KTP</th>
                    <th class="p-3">Status Perkawinan</th>
                    <th class="p-3">Kebangsaan/ Agama</th>
                    <th class="p-3">Pekerjaan</th>
                    <th class="p-3">Alamat</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Dokumen Persyaratan</th>
                    <th class="p-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $jumlahData = $pengajuanSurat->count() + $pengajuanSuratLain->count();
                @endphp

                @if ($jumlahData === 0)
                    <tr>
                        <td colspan="11" class="p-4 text-center text-gray-500">Data pengajuan belum ada.</td>
                    </tr>
                @else
                    @foreach($pengajuanSurat as $surat)
                    <tr class="border-t">
                        <td class="p-3">{{ $surat->warga->nama_lengkap }}</td>
                        <td class="p-3">{{ $surat->tujuanSurat->nama_tujuan ?? '-' }}</td>
                        <td class="p-3">Biasa</td>
                        <td class="p-3">{{ $surat->tujuanSurat->nomor_surat }}</td>
                        <td class="p-3">{{ $surat->tempat_lahir }}, {{ $surat->tanggal_lahir}}</td>
                        <td class="p-3">{{ $surat->warga->nik }}</td>
                        <td class="p-3">{{ $surat->status_perkawinan }}</td>
                        <td class="p-3">{{ $surat->agama }}</td>
                        <td class="p-3">{{ $surat->pekerjaan }}</td>
                        <td class="p-3">{{ $surat->warga->scan_kk->alamat->nama_jalan }}</td>
                        <td class="p-3">{{ ucfirst($surat->status_rt) }}</td>
                        <td class="p-3">
                            @if(!empty($surat->pengajuan) && $surat->pengajuan->count() > 0)
                                <ul class="list-disc pl-4">
                                    @foreach($surat->pengajuan as $dok)
                                        <li class="mb-2">
                                            <p class="text-sm text-gray-700 mb-1">
                                                {{ $dok->persyaratan->nama_persyaratan ?? 'Dokumen' }}
                                            </p>
                                            <img
                                                src="{{ asset('storage/' . str_replace('public/', '', $dok->dokumen)) }}"
                                                alt="Dokumen Persyaratan"
                                                class="w-32 cursor-pointer"
                                                onclick="showImageModal('{{ asset('storage/' . str_replace('public/', '', $dok->dokumen)) }}', '{{ $dok->persyaratan->nama_persyaratan }}')"
                                            />
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-gray-400">Tidak ada dokumen</span>
                            @endif
                        </td>
                        <td class="p-3 flex gap-2">
                            <button onclick="showModal('setuju', {{ $surat->id_pengajuan_surat }}, 'biasa')" class="px-3 py-1 bg-green-500 text-white rounded">Setujui</button>
                            <button onclick="showModal('tolak', {{ $surat->id_pengajuan_surat }}, 'biasa')" class="px-3 py-1 bg-red-500 text-white rounded">Tolak</button>
                        </td>
                    </tr>
                    @endforeach

                    @foreach($pengajuanSuratLain as $surat)
                    <tr class="border-t">
                        <td class="p-3">{{ $surat->warga->nama_lengkap }}</td>
                        <td class="p-3">{{ $surat->tujuan_manual }}</td>
                        <td class="p-3">Lain</td>
                        <td class="p-3">{{ $surat->nomor_surat_pengajuan_lain ?? 'Belum Memiliki Nomor Surat' }}</td>
                        <td class="p-3">{{ $surat->tempat_lahir_pengaju_lain }}, {{ $surat->tanggal_lahir_pengaju_lain }}</td>
                        <td class="p-3">{{ $surat->warga->nik }}</td>
                        <td class="p-3">{{ $surat->status_perkawinan_pengaju_lain }}</td>
                        <td class="p-3">{{ $surat->agama_pengaju_lain }}</td>
                        <td class="p-3">{{ $surat->pekerjaan_pengaju_lain }}</td>
                        <td class="p-3">{{ $surat->warga->scan_kk->alamat->nama_jalan }}</td>
                        <td class="p-3">{{ ucfirst($surat->status_rt_pengajuan_lain) }}</td>
                        <td class="p-3 flex gap-2">
                            <button onclick="showModal('setuju', {{ $surat->id_pengajuan_surat_lain }}, 'lain')" class="px-3 py-1 bg-green-500 text-white rounded">Setujui</button>
                            <button onclick="showModal('tolak', {{ $surat->id_pengajuan_surat_lain }}, 'lain')" class="px-3 py-1 bg-red-500 text-white rounded">Tolak</button>
                        </td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
    <div class="bg-white p-6 rounded shadow w-full max-w-md">
        <form id="modalForm" method="POST" action="{{ route('verifikasiSuratProses') }}">
            @csrf
            <input type="hidden" name="id" id="formId">
            <input type="hidden" name="jenis" id="formJenis">
            <input type="hidden" name="aksi" id="formAksi">

            <div id="inputNomorSurat" class="mb-4 hidden">
                <label class="block mb-1">Nomor Surat</label>
                <input type="text" name="nomor_surat" class="w-full border rounded px-3 py-2">
            </div>

            <div id="inputAlasanTolak" class="mb-4 hidden">
                <label class="block mb-1">Alasan Penolakan</label>
                <textarea name="alasan_penolakan" class="w-full border rounded px-3 py-2"></textarea>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 rounded">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal untuk melihat gambar dokumen -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white p-4 rounded-lg shadow-lg max-w-3xl w-full relative">
        <button onclick="closeImageModal()" class="absolute top-2 right-2 text-gray-600 hover:text-red-500 text-xl font-bold">&times;</button>
        <img id="modalImage" src="" alt="Preview Gambar" class="w-full max-h-[80vh] object-contain rounded">
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

function showModal(aksi, id, jenis) {
    if (aksi === 'setuju' && jenis === 'biasa') {
        // Buat form dinamis dan langsung submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('verifikasiSuratProses') }}";

        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        form.appendChild(csrf);

        const inputId = document.createElement('input');
        inputId.type = 'hidden';
        inputId.name = 'id';
        inputId.value = id;
        form.appendChild(inputId);

        const inputJenis = document.createElement('input');
        inputJenis.type = 'hidden';
        inputJenis.name = 'jenis';
        inputJenis.value = jenis;
        form.appendChild(inputJenis);

        const inputAksi = document.createElement('input');
        inputAksi.type = 'hidden';
        inputAksi.name = 'aksi';
        inputAksi.value = aksi;
        form.appendChild(inputAksi);

        document.body.appendChild(form);
        form.submit();
        return;
    }

    // Reset input dan tampilkan modal untuk selain "biasa disetujui"
    document.getElementById('formId').value = id;
    document.getElementById('formAksi').value = aksi;
    document.getElementById('formJenis').value = jenis;

    document.getElementById('inputNomorSurat').classList.add('hidden');
    document.getElementById('inputAlasanTolak').classList.add('hidden');

    if (aksi === 'setuju' && jenis === 'lain') {
        document.getElementById('inputNomorSurat').classList.remove('hidden');
    } else if (aksi === 'tolak') {
        document.getElementById('inputAlasanTolak').classList.remove('hidden');
    }

    document.getElementById('modal').classList.remove('hidden');
}


function closeModal() {
    document.getElementById('modal').classList.add('hidden');
}
</script>
@endsection

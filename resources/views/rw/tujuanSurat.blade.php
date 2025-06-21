@extends('rw.dashboardRw')

@section('content')
<h1 class="text-2xl pt-20 md:text-3xl font-bold text-gray-800 mb-2">Kelola Tujuan Surat</h1>
<p class="text-gray-600 mb-6 text-lg">
    Halaman ini digunakan untuk menambahkan, mengedit, atau menghapus daftar tujuan surat yang tersedia untuk pengajuan warga.
</p>

@if(session('success'))
    <div id="successModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 sm:p-8 relative w-[90%] max-w-md sm:max-w-lg text-center animate-scale">
            <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>
            <div class="flex justify-center mb-6">
                <img src="https://img.icons8.com/color/96/000000/ok--v1.png" alt="Success Icon" class="w-20 h-20">
            </div>
            <h2 class="text-2xl font-bold mb-4 text-gray-800 whitespace-nowrap">
                {{ session('success') }}
            </h2>
            <button onclick="closeModal()" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 rounded-lg">
                Tutup
            </button>
        </div>
    </div>
@endif

<!-- Tombol Tambah + Form Search dalam satu baris -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
    <!-- Tombol Tambah -->
    <button onclick="openAddModal()" class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg shadow">
        ➕ Tambah Tujuan Surat
    </button>

    <!-- Form Search -->
    <form method="GET" action="{{ route('tujuanSurat') }}" class="relative w-full sm:w-80">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8" />
                <line x1="21" y1="21" x2="16.65" y2="16.65" />
            </svg>
        </span>
        <input type="text" name="search" id="searchInput"
            value="{{ request('search') }}"
            placeholder="Cari nama tujuan atau nomor surat..."
            class="pl-10 pr-4 py-2 border rounded w-full focus:outline-none focus:ring-2 focus:ring-green-400" />
    </form>
</div>

<!-- Tabel Tujuan Surat -->
<div class="overflow-x-auto max-h-[500px] overflow-y-auto border rounded-lg shadow">
    <table class="min-w-full bg-white border rounded shadow">
        <thead class="bg-green-100 sticky top-0 z-10">
            <tr class="text-left">
                <th class="px-4 py-2">No</th>
                <th class="px-4 py-2">Nama Tujuan</th>
                <th class="px-4 py-2">Deskripsi</th>
                <th class="px-4 py-2">Nomor Surat</th>
                <th class="px-4 py-2">Persyaratan Surat</th>
                <th class="px-4 py-2">Keterangan Status Perkawinan</th>
                <th class="px-4 py-2">Populer</th>
                <th class="px-4 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tujuanSurat as $index => $tujuan)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2">{{ $index + 1 }}</td>
                    <td class="px-4 py-2">{{ $tujuan->nama_tujuan }}</td>
                    <td class="px-4 py-2">{{ $tujuan->deskripsi }}</td>
                    <td class="px-4 py-2">{{ $tujuan->nomor_surat }}</td>
                    <td class="px-4 py-2">
                        @if ($tujuan->persyaratan->isNotEmpty())
                            <ul class="list-disc list-inside">
                                @foreach ($tujuan->persyaratan as $item)
                                    <li>{{ $item->nama_persyaratan }}</li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-gray-500 italic">Tidak ada</span>
                        @endif
                    </td>
                    <td class="px-4 py-2">
                        @if ($tujuan->persyaratan->isNotEmpty())
                            <ul class="list-disc list-inside">
                                @foreach ($tujuan->persyaratan as $item)
                                    <li>{{ $item->keterangan ?? '-' }}</li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-gray-500 italic">Tidak ada</span>
                        @endif
                    </td>
                    <td class="px-4 py-2">
                        {{ $tujuan->status_populer ? 'Populer' : 'Biasa' }}
                    </td>
                    <td class="px-4 py-2">
                        <div class="flex flex-wrap gap-2">
                            <button
                            onclick='openEditModal(
                                {{ $tujuan->id_tujuan_surat }},
                                @json($tujuan->nama_tujuan),
                                @json($tujuan->deskripsi),
                                @json($tujuan->nomor_surat),
                                {{ $tujuan->status_populer }},
                                @json($tujuan->persyaratan->map(function ($p) {
                                    return ['id' => $p->id_persyaratan_surat, 'nama' => $p->nama_persyaratan, 'keterangan' => $p->keterangan];
                                }))
                            )'
                            class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600">Edit</button>
                            <button onclick="openDeleteModal('{{ $tujuan->id_tujuan_surat }}', '{{ $tujuan->nama_tujuan }}')" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Hapus</button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-gray-500 py-4">
                        <strong>
                            @if(request('search'))
                                Tidak ada data yang Anda maksud.
                            @else
                                Belum ada tujuan surat.
                            @endif
                        </strong>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal Tambah -->
<div id="addModal" class="fixed inset-0 bg-black bg-opacity-30 hidden justify-center items-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md max-h-[90vh] overflow-y-auto">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Tambah Tujuan Surat</h2>
        <form action="{{ route('tujuanSurat.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block mb-1 text-sm font-medium">Nama Tujuan</label>
                <input type="text" name="nama_tujuan" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1 text-sm font-medium">Deskripsi</label>
                <textarea name="deskripsi" class="w-full border rounded px-3 py-2" required></textarea>
            </div>
            <div class="mb-4">
                <label class="block mb-1 text-sm font-medium">Nomor Surat</label>
                <input type="text" name="nomor_surat" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1 text-sm font-medium">Status Populer</label>
                <select name="status_populer" class="w-full border rounded px-3 py-2" required>
                    <option value="0">Biasa</option>
                    <option value="1">Populer</option>
                </select>
            </div>

            <!-- Persyaratan Dinamis -->
            <div class="mb-4">
                <label class="block mb-2 text-sm font-medium">Persyaratan Surat (Opsional)</label>
                <div id="persyaratanWrapper" class="space-y-2 max-h-48 overflow-y-auto pr-1">
                    <div class="flex gap-2 mb-2">
                        <input type="text" name="persyaratan[]" class="w-full border rounded px-3 py-2" placeholder="Masukkan persyaratan...">
                        <select name="keterangan[]" class="w-full border rounded px-3 py-2">
                            <option value="">Pilih status perkawinan</option>
                            <option value="belum">Belum</option>
                            <option value="kawin">Kawin</option>
                            <option value="janda">Janda</option>
                            <option value="duda">Duda</option>
                        </select>
                        <button type="button" onclick="addPersyaratan()" class="bg-blue-500 text-white px-3 rounded">+</button>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeAddModal()" class="text-gray-600 hover:text-gray-800">Batal</button>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Simpan</button>
            </div>
        </form>
    </div>
</div>


<!-- Modal Edit -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-30 hidden justify-center items-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Edit Tujuan Surat</h2>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block mb-1 text-sm font-medium">Nama Tujuan</label>
                <input id="editNama" name="nama_tujuan" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1 text-sm font-medium">Deskripsi</label>
                <textarea id="editDeskripsi" name="deskripsi" class="w-full border rounded px-3 py-2" required></textarea>
            </div>
            <div class="mb-4">
                <label class="block mb-1 text-sm font-medium">Nomor Surat</label>
                <input id="editNomorSurat" name="nomor_surat" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1 text-sm font-medium">Status Populer</label>
                <select id="editStatusPopuler" name="status_populer" class="w-full border rounded px-3 py-2" required>
                    <option value="0">Biasa</option>
                    <option value="1">Populer</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block mb-1 text-sm font-medium">Persyaratan Surat</label>
                <div id="editPersyaratanWrapper" class="space-y-2 max-h-48 overflow-y-auto pr-1"></div>
                <button type="button" onclick="addEditPersyaratan()" class="mt-2 text-sm text-blue-600 hover:underline">
                    + Tambah Persyaratan
                </button>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeEditModal()" class="text-gray-600 hover:text-gray-800">Batal</button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Update</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Hapus -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-30 hidden justify-center items-center z-50">
    <div class="bg-white rounded-lg p-6 sm:p-8 w-full max-w-sm sm:max-w-md md:max-w-lg relative text-center mx-4">
        <h2 class="text-lg font-semibold text-gray-800 mb-2">Yakin ingin menghapus tujuan <span id="tujuanName" class="font-bold"></span>?</h2>
        <form id="deleteForm" method="POST" class="mt-4">
            @csrf
            @method('DELETE')
            <div class="flex justify-center gap-4">
                <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded">Batal</button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Iya, Hapus</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddModal() {
        document.getElementById('addModal').classList.remove('hidden');
        document.getElementById('addModal').classList.add('flex');
    }
    function closeAddModal() {
        document.getElementById('addModal').classList.add('hidden');
    }

    function openEditModal(id, nama, deskripsi, nomor, status_populer, persyaratan = []) {
        const form = document.getElementById('editForm');
        form.action = `/rw/tujuanSurat/update/${id}`;
        document.getElementById('editNama').value = nama;
        document.getElementById('editDeskripsi').value = deskripsi;
        document.getElementById('editNomorSurat').value = nomor;
        document.getElementById('editStatusPopuler').value = status_populer;
        document.getElementById('editModal').classList.remove('hidden');
        document.getElementById('editModal').classList.add('flex');
        document.getElementById('editPersyaratanWrapper').innerHTML = '';
        persyaratan.forEach(item => {
            const div = document.createElement('div');
            div.className = 'flex gap-2 mb-2';
            div.innerHTML = `
                <input type="hidden" name="persyaratan_id[]" value="${item.id}">
                <input type="text" name="persyaratan[]" value="${item.nama}" class="w-full border rounded px-3 py-2">
                <select name="keterangan[]" class="w-full border rounded px-3 py-2">
                    <option value="">Pilih status perkawinan</option>
                    <option value="belum" ${item.keterangan === 'belum' ? 'selected' : ''}>Belum</option>
                    <option value="kawin" ${item.keterangan === 'kawin' ? 'selected' : ''}>Kawin</option>
                    <option value="janda" ${item.keterangan === 'janda' ? 'selected' : ''}>Janda</option>
                    <option value="duda" ${item.keterangan === 'duda' ? 'selected' : ''}>Duda</option>
                </select>
                <button type="button" onclick="removePersyaratan(this)" class="bg-red-500 text-white px-3 rounded">-</button>
            `;
            document.getElementById('editPersyaratanWrapper').appendChild(div);
        });
    }

    function addEditPersyaratan() {
        const wrapper = document.getElementById('editPersyaratanWrapper');
        const div = document.createElement('div');
        div.className = 'flex gap-2 mb-2';
        div.innerHTML = `
            <input type="text" name="persyaratan[]" class="w-full border rounded px-3 py-2" placeholder="Masukkan persyaratan...">
            <select name="keterangan[]" class="w-full border rounded px-3 py-2">
                <option value="">Pilih status perkawinan</option>
                <option value="belum">Belum</option>
                <option value="kawin">Kawin</option>
                <option value="janda">Janda</option>
                <option value="duda">Duda</option>
            </select>
            <button type="button" onclick="removePersyaratan(this)" class="bg-red-500 text-white px-3 rounded">-</button>
        `;
        wrapper.appendChild(div);
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    function closeModal() {
        const modal = document.getElementById('successModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function openDeleteModal(id, nama) {
        document.getElementById('tujuanName').innerText = nama;
        const form = document.getElementById('deleteForm');
        form.action = `/rw/tujuanSurat/delete/${id}`;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteModal').classList.add('flex');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.remove('flex');
        document.getElementById('deleteModal').classList.add('hidden');
    }

    function addPersyaratan() {
        const wrapper = document.getElementById('persyaratanWrapper');
        const div = document.createElement('div');
        div.className = 'flex gap-2 mb-2';

        div.innerHTML = `
            <input type="text" name="persyaratan[]" class="w-full border rounded px-3 py-2" placeholder="Masukkan persyaratan...">
            <select name="keterangan[]" class="w-full border rounded px-3 py-2">
                <option value="">Pilih status perkawinan</option>
                <option value="belum">Belum</option>
                <option value="kawin">Kawin</option>
                <option value="janda">Janda</option>
                <option value="duda">Duda</option>
            </select>
            <button type="button" onclick="removePersyaratan(this)" class="bg-red-500 text-white px-3 rounded">-</button>
        `;

        wrapper.appendChild(div);
    }

    function removePersyaratan(button) {
        const wrapper = button.parentElement;
        const hiddenInput = wrapper.querySelector('input[name="persyaratan_id[]"]');
        if (hiddenInput) {
            const deletedId = hiddenInput.value;
            const deleteInput = document.createElement('input');
            deleteInput.type = 'hidden';
            deleteInput.name = 'persyaratan_deleted[]';
            deleteInput.value = deletedId;
            document.getElementById('editForm').appendChild(deleteInput);
        }
        wrapper.remove();
    }

</script>
@endsection

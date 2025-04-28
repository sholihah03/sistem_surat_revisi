@extends('rw.dashboardRw')

@section('content')
<h1 class="text-2xl pt-20 md:text-3xl font-bold text-gray-800 mb-6">Manajemen Akun RT</h1>
<!-- Tombol Tambah Akun -->
<div class="flex justify-end md:grid-cols-3 gap-6 mb-8">
    <button onclick="openAddModal()" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg shadow">
        ➕ Tambah Akun RT
    </button>
</div>

<!-- Tabel Daftar Akun RT -->
<div class="bg-white rounded-xl shadow p-4 overflow-x-auto">
    {{-- @if($dataRt->isEmpty())
        <div class="text-center text-gray-500 py-10">
            Data RT belum tersedia.
        </div>
    @else --}}
    <table class="min-w-full text-sm text-gray-700">
        <thead class="bg-green-100">
            <tr>
                <th class="px-4 py-2 text-left">No</th>
                <th class="px-4 py-2 text-left">Rt</th>
                <th class="px-4 py-2 text-left">Nama RT</th>
                <th class="px-4 py-2 text-left">Nomer WhatsApp</th>
                <th class="px-4 py-2 text-left">Email</th>
                <th class="px-4 py-2 text-left">Aksi</th>
            </tr>
        </thead>
        {{-- <tbody class="divide-y divide-gray-200">
            <!-- Contoh Data -->
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-2">1</td>
                <td class="px-4 py-2">RT 01</td>
                <td class="px-4 py-2">rt01</td>
                <td class="px-4 py-2">rt01@example.com</td>
                <td class="px-4 py-2">
                    <div class="flex flex-wrap gap-2">
                        <button onclick="openEditModal('RT 01', 'rt01', 'rt01@example.com')" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs">Edit</button>
                        <button onclick="confirmDelete('RT 01')" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">Hapus</button>
                    </div>
                </td>
            </tr>
        </tbody> --}}
        <tbody class="divide-y divide-gray-200">
            @forelse ($rts as $index => $rt)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2">{{ $index + 1 }}</td>
                    <td class="px-4 py-2">{{ $rt->no_rt }}</td>
                    <td class="px-4 py-2">{{ $rt->nama_lengkap_rt }}</td>
                    <td class="px-4 py-2">{{ $rt->no_hp_rt }}</td>
                    <td class="px-4 py-2">{{ $rt->email_rt }}</td>
                    <td class="px-4 py-2">
                        <div class="flex flex-wrap gap-2">
                            <button onclick="openEditModal('{{ $rt->id_rt }}', '{{ $rt->no_rt }}', '{{ $rt->nama_lengkap_rt }}', '{{ $rt->no_hp_rt }}', '{{ $rt->email_rt }}')" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs">Edit</button>

                            <form action="{{ route('manajemenAkunRt.destroy', $rt->id_rt) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $rt->nama_lengkap_rt }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-gray-500 py-4">Data RT belum ada.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal Tambah Akun -->
<div id="addModal" class="fixed inset-0 bg-black bg-opacity-30 hidden justify-center items-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Tambah Akun RT</h2>
        <form action="{{ route('manajemenAkunRt.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block mb-1 text-sm font-medium">Rt Berapa</label>
                <input type="text" name="no_rt" class="w-full border rounded px-3 py-2" placeholder="Masukkan No Rt" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1 text-sm font-medium">Nama RT</label>
                <input type="text" name="nama_lengkap_rt" class="w-full border rounded px-3 py-2" placeholder="Masukkan Nama RT" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1 text-sm font-medium">Nomer WhatsApp</label>
                <input type="text" name="no_hp_rt" class="w-full border rounded px-3 py-2" placeholder="Masukkan Nomer WhatsApp" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1 text-sm font-medium">Email</label>
                <input type="email" name="email_rt" class="w-full border rounded px-3 py-2" placeholder="Masukkan Email" required>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeAddModal()" class="text-gray-600 hover:text-gray-800">Batal</button>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Simpan</button>
            </div>
        </form>

    </div>
</div>

<!-- Modal Edit Akun -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-30 hidden justify-center items-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Edit Akun RT</h2>
        <form id="editForm" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block mb-1 text-sm font-medium">Rt Berapa</label>
                <input type="editNoRt" name="no_rt" class="w-full border rounded px-3 py-2" placeholder="Masukkan No Rt" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1 text-sm font-medium">Nama RT</label>
                <input id="editNamaRt" type="text" class="w-full border rounded px-3 py-2">
            </div>
            <div class="mb-4">
                <label class="block mb-1 text-sm font-medium">Nomer WhatsApp</label>
                <input type="editNoHpRt" name="no_hp_rt" class="w-full border rounded px-3 py-2" placeholder="Masukkan Nomer WhatsApp" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1 text-sm font-medium">Email</label>
                <input id="editEmail" type="email" class="w-full border rounded px-3 py-2">
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeEditModal()" class="text-gray-600 hover:text-gray-800">Batal</button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Update</button>
            </div>
        </form>
    </div>
</div>

<!-- Script untuk Modal -->
<script>
    function openAddModal() {
        document.getElementById('addModal').classList.remove('hidden');
        document.getElementById('addModal').classList.add('flex');
    }
    function closeAddModal() {
        document.getElementById('addModal').classList.add('hidden');
    }

    function openEditModal(id, noRt, namaRt, whatsapp, email) {
    document.getElementById('editNoRt').value = noRt;
    document.getElementById('editNamaRt').value = namaRt;
    document.getElementById('editNoHpRt').value = whatsapp;
    document.getElementById('editEmail').value = email;

    const form = document.getElementById('editForm');
    form.action = `manajemenAkunRt/update/${id}`;
    form.innerHTML = `
        @csrf
        <div class="mb-4">
            <label class="block mb-1 text-sm font-medium">Rt Berapa</label>
            <input name="no_rt" type="text" value="${noRt}" class="w-full border rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label class="block mb-1 text-sm font-medium">Nama RT</label>
            <input name="nama_lengkap_rt" type="text" value="${namaRt}" class="w-full border rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label class="block mb-1 text-sm font-medium">Username</label>
            <input name="no_hp_rt" type="text" value="${whatsapp}" class="w-full border rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label class="block mb-1 text-sm font-medium">Email</label>
            <input name="email_rt" type="email" value="${email}" class="w-full border rounded px-3 py-2" required>
        </div>
        <div class="flex justify-end space-x-2">
            <button type="button" onclick="closeEditModal()" class="text-gray-600 hover:text-gray-800">Batal</button>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Update</button>
        </div>
    `;

    document.getElementById('editModal').classList.remove('hidden');
    document.getElementById('editModal').classList.add('flex');
}
    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    function confirmDelete(namaRt) {
        if (confirm('Apakah Anda yakin ingin menghapus akun ' + namaRt + '?')) {
            // Aksi penghapusan data
            alert('Akun ' + namaRt + ' berhasil dihapus!');
        }
    }
</script>
@endsection

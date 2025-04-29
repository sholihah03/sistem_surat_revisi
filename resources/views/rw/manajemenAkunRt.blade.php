@extends('rw.dashboardRw')

@section('content')
<h1 class="text-2xl pt-20 md:text-3xl font-bold text-gray-800 mb-6">Manajemen Akun RT</h1>
<!-- Tombol Tambah Akun -->

<!-- Menampilkan pesan sukses jika ada -->
@if(session('success'))
    <div id="successModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white rounded-lg shadow-lg p-8 relative w-[520px] text-center animate-scale">
            <!-- Tombol Close -->
            <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>

            <!-- Ikon Ceklis -->
            <div class="flex justify-center mb-6">
                <img src="https://img.icons8.com/color/96/000000/ok--v1.png" alt="Success Icon" class="w-20 h-20">
            </div>

            <!-- Judul -->
            <h2 class="text-2xl font-bold mb-4 text-gray-800 whitespace-nowrap">
                {{ session('success') }}
            </h2>

            <!-- Tombol Tutup -->
            <button onclick="closeModal()" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 rounded-lg">
                Tutup
            </button>
        </div>
    </div>
@endif

<!-- Modal Konfirmasi Hapus -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-30 hidden justify-center items-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md relative text-center">
        <div class="flex justify-center mb-4">
            <!-- Ikon tanda seru -->
            <svg class="w-16 h-16 text-red-500" fill="none" stroke="currentColor" stroke-width="1.5"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v3m0 4h.01M21.6 18.3a10.5 10.5 0 11-19.2 0 10.5 10.5 0 0119.2 0z" />
            </svg>
        </div>
        <h2 class="text-lg font-semibold text-gray-800 mb-2">Yakin ingin menghapus akun <span id="rtName" class="font-bold"></span>?</h2>
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


<div class="flex justify-end md:grid-cols-3 gap-6 mb-8">
    <button onclick="openAddModal()" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg shadow">
        ➕ Tambah Akun RT
    </button>
</div>

<!-- Tabel Daftar Akun RT -->
<div class="bg-white rounded-xl shadow p-4 overflow-x-auto">
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

                            <div class="flex flex-wrap gap-2">
                                <button onclick="openDeleteModal('{{ $rt->id_rt }}', 'RT {{ $rt->no_rt }}')" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">
                                    Hapus
                                </button>
                            </div>

                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-gray-500 py-4"><strong>Belum ada akun RT</strong></td>
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
            <div class="mb-4">
                <label class="block mb-1 text-sm font-medium">Password</label>
                <input type="password" name="password" class="w-full border rounded px-3 py-2" placeholder="Masukkan Password" required>
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
                <input id="editNoRt" name="no_rt" class="w-full border rounded px-3 py-2" placeholder="Masukkan No Rt" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1 text-sm font-medium">Nama RT</label>
                <input id="editNamaRt" type="text" class="w-full border rounded px-3 py-2">
            </div>
            <div class="mb-4">
                <label class="block mb-1 text-sm font-medium">Nomer WhatsApp</label>
                <input id="editNoHpRt" name="no_hp_rt" class="w-full border rounded px-3 py-2" placeholder="Masukkan Nomer WhatsApp" required>
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
    document.getElementById('editModal').classList.remove('hidden');
    document.getElementById('editModal').classList.add('flex');
    document.getElementById('editNoRt').value = noRt;
    document.getElementById('editNamaRt').value = namaRt;
    document.getElementById('editNoHpRt').value = whatsapp;
    document.getElementById('editEmail').value = email;

    const form = document.getElementById('editForm');
    form.action = `/rw/manajemenAkunRt/update/${id}`;
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

    function closeModal() {
    const modal = document.getElementById('successModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    }

    function openDeleteModal(id, noRt) {
    document.getElementById('rtName').innerText = noRt;
    const form = document.getElementById('deleteForm');
    form.action = `manajemenAkunRt/delete/${id}`;
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.remove('flex');
        document.getElementById('deleteModal').classList.add('hidden');
    }

</script>
@endsection

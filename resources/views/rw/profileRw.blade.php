@extends('rw.dashboardRw')

@section('content')
<div class="container mx-auto p-4 mb-6 pt-20">
    <h1 class="text-2xl font-bold mb-6">Profil RW</h1>
    <p class="text-gray-600 text-lg mb-6">Halaman ini menampilkan data profil RW dan memungkinkan Anda untuk memperbarui informasi serta mengunggah tanda tangan digital.
        <br>Anda <strong class="text-red-500">wajib</strong> mengunggah scan tanda tangan digital jika belum melakukannya agar proses administrasi dapat berjalan lancar.
    </p>

    <div class="md:flex md:space-x-6 space-y-6 md:space-y-0 items-stretch">
        <!-- Card Profil -->
        <div class="bg-white shadow-lg rounded-2xl p-6 md:w-1/2 h-full">
            <h1 class="text-xl font-bold mb-4 text-gray-800">Edit Profile RW</h1>
            @if (session('dataSuccess'))
                <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
                    {{ session('dataSuccess') }}
                </div>
            @endif

            @if (session('passwordUbahSuccess'))
                <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
                    {{ session('passwordUbahSuccess') }}
                </div>
            @endif

            @if (session('uploadSuccess'))
                <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
                    {{ session('uploadSuccess') }}
                </div>
            @endif

            @if (
                $errors->any() &&
                !($errors->has('current_password') ||
                $errors->has('new_password') ||
                $errors->has('new_password_confirmation'))
            )
                <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- @if ($errors->any())
                <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif --}}
            <div class="md:flex items-center space-y-6 md:space-y-0 md:space-x-10">
                <!-- Foto Profil + Upload -->
                <div class="flex-shrink-0 mx-auto md:mx-0 text-center">
                    <img id="previewImage" src="{{ $rw->profile_rw ? asset('storage/profile_rw/' . $rw->profile_rw) : asset('images/profile.png') }}"
                        alt="Foto Profil"
                        class="w-32 h-32 rounded-full object-cover border-4 border-yellow-500 mx-auto mb-3">

                    <!-- Tombol Edit Profil -->
                    <button type="button"
                            id="editButton"
                            class="mt-2 px-4 py-2 bg-yellow-600 text-white font-semibold rounded hover:bg-yellow-700 transition">
                        Edit Profil
                    </button>

                    <!-- Input file disembunyikan -->
                    <input type="file" id="imageInput" name="profile_rw" accept="image/*" class="hidden">

                    <!-- Form simpan profil -->
                    <form action="{{ route('uploadProfileRw') }}" method="POST" enctype="multipart/form-data" class="mt-4 hidden" id="uploadForm">
                        @csrf
                        <div class="flex justify-center space-x-4">
                            <button type="button" id="cancelButton"
                                    class="px-4 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition">
                                Batal
                            </button>
                            <button type="submit"
                                    class="px-4 py-1 bg-green-600 text-white rounded hover:bg-green-700 transition">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Informasi Rw -->
                <div class="flex-1">
                    <div class="flex flex-col items-center">
                        <!-- Kontainer data -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 w-full max-w-4xl">
                            <!-- Kolom kiri: span 2 kolom -->
                            <div class="sm:col-span-2 space-y-4">
                                <!-- Nama Lengkap -->
                                <div>
                                    <h2 class="text-sm text-gray-500">Nama Lengkap</h2>
                                    <p class="text-lg font-semibold text-gray-800">{{ $rw->nama_lengkap_rw }}</p>
                                </div>

                                <!-- No HP -->
                                <div>
                                    <h2 class="text-sm text-gray-500">No. HP</h2>
                                    <p class="text-lg font-semibold text-gray-800">{{ $rw->no_hp_rw }}</p>
                                </div>

                                <!-- Email -->
                                <div class="overflow-hidden">
                                    <h2 class="text-sm text-gray-500">Email</h2>
                                    <p class="text-100 font-semibold text-gray-800 " title="{{ $rw->email_rw }}">
                                        {{ $rw->email_rw }}
                                    </p>
                                </div>
                            </div>

                            <!-- Kolom kanan: Nomor RW -->
                            <div class="space-y-4">
                                <div>
                                    <h2 class="text-sm text-gray-500">Nomor RW</h2>
                                    <p class="text-lg font-semibold text-gray-800">RW {{ $rw->no_rw }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Edit Profil dan Ubah Password -->
                        <div class="mt-3 flex justify-center gap-4">
                            <button onclick="openModal()"
                                class="inline-block bg-yellow-600 hover:bg-yellow-700 text-white font-semibold py-2 px-6 rounded-lg transition">
                                Edit Data
                            </button>
                            <button onclick="openPasswordModal()" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-6 rounded-lg">
                                Ubah Password
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Upload TTD -->
        <div class="bg-white shadow-lg rounded-2xl p-6 md:w-1/2">
            <h1 class="text-2xl font-bold mb-4 text-center text-gray-800">Upload Scan Tanda Tangan</h1>
            <p class="text-lg text-gray-700">
                Harap unggah scan tanda tangan digital dengan ketentuan berikut:
                <ul class="list-disc list-inside mt-2">
                    <li>Latar belakang (background) gambar sebaiknya berwarna putih atau terang agar proses transparansi berjalan optimal.</li>
                    <li>Kejernihan tanda tangan harus jelas dan tidak blur agar hasil digitalisasi tampak rapi.</li>
                    <li>Format gambar yang diterima adalah JPG, JPEG, atau PNG dengan ukuran maksimal sesuai batas server.</li>
                    <li>Pastikan tanda tangan tidak terpotong dan memenuhi area gambar agar hasil transparan sempurna.</li>
                    <li>Gambar akan diproses untuk menghilangkan latar belakang putih menjadi transparan agar bisa digunakan di dokumen digital.</li>
                </ul>
            </p>

            @if (session('ttdSuccess'))
                <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
                    {{ session('ttdSuccess') }}
                </div>
            @endif

            @if (
                $errors->any() &&
                !($errors->has('current_password') ||
                $errors->has('new_password') ||
                $errors->has('new_password_confirmation'))
            )
                <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            {{-- @if ($errors->any())
                <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif --}}
            <!-- Jika tanda tangan belum ada -->
            @if (empty($rw->ttd_digital) && empty($rw->ttd_digital_bersih))
                <form action="{{ route('scanTtdRwUpload') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    <div>
                        <label for="scan_ttd" class="block text-sm font-medium text-gray-700 mb-1">Pilih Gambar Scan Tanda Tangan</label>
                        <input type="file" name="ttd_digital" accept="image/*" required
                            class="block w-full text-sm text-gray-700 bg-gray-100 border border-gray-300 rounded-lg cursor-pointer p-2">
                    </div>

                    <div class="flex justify-center">
                        <button type="submit" class="px-6 py-2 bg-yellow-600 text-white rounded-xl hover:bg-yellow-700 transition-all">
                            Upload
                        </button>
                    </div>
                </form>
            @else
            <!-- Jika tanda tangan sudah ada, tampilkan gambar dan tombol Edit -->
            <div class="mt-4">
                <table class="min-w-full table-auto border border-gray-300 border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 border border-gray-300">Tanda Tangan Scan</th>
                            <th class="px-4 py-2 border border-gray-300">Tanda Tangan Bersih</th>
                            <th class="px-4 py-2 border border-gray-300">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="px-4 py-2 border border-gray-300 text-center">
                                <img src="{{ Storage::url($rw->ttd_digital) }}"
                                    alt="Tanda Tangan Scan"
                                    class="w-20 h-20 object-cover mx-auto cursor-pointer transition-transform duration-200 hover:scale-105"
                                    onclick="showImageModal('{{ Storage::url($rw->ttd_digital) }}')">
                            </td>
                            <td class="px-4 py-2 border border-gray-300 text-center">
                                <img src="{{ Storage::url($rw->ttd_digital_bersih) }}"
                                    alt="Tanda Tangan Bersih"
                                    class="w-20 h-20 object-cover mx-auto cursor-pointer transition-transform duration-200 hover:scale-105"
                                    onclick="showImageModal('{{ Storage::url($rw->ttd_digital_bersih) }}')">
                            </td>
                            <td class="px-4 py-2 border border-gray-300 text-center">
                                <button type="button" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600" onclick="openModalTtd()">
                                    Edit
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

    <!-- Modal Edit -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden h-full w-full overflow-hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 relative box-border">
            <!-- Tombol Close -->
            <button onclick="closeModal()" class="absolute top-2 right-3 text-gray-500 hover:text-gray-700 text-xl font-bold">&times;</button>

            <h2 class="text-xl font-bold mb-4">Edit Data</h2>

            <form action="{{ route('updateDataRw') }}" method="POST">
                @csrf
                @method('PUT')

                <!-- No HP -->
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-medium">No. HP</label>
                    <div class="flex">
                        <!-- Prefix "62" tidak bisa diedit -->
                        <span class="inline-flex items-center px-3 rounded-l border border-r-0 border-gray-300 bg-gray-100 text-gray-600 select-none">62</span>
                        <!-- Input nomor hp tanpa "62" -->
                        <input
                            type="text"
                            name="no_hp_rw"
                            id="no_hp_rw"
                            class="flex-1 border border-gray-300 rounded-r px-3 py-2"
                            placeholder="Masukkan nomor setelah 62"
                            required
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                            maxlength="11"
                            value="{{ Str::startsWith($rw->no_hp_rw, '62') ? substr($rw->no_hp_rw, 2) : $rw->no_hp_rw }}"
                        >
                    </div>
                    <small class="text-gray-500">Nomor harus diawali dengan 62 (otomatis), hanya masukkan nomor setelah kode negara.</small>
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-gray-600 mb-1" for="email_rw">Email</label>
                    <input type="email" name="email_rw" id="email_rw"
                        class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-indigo-500"
                        value="{{ $rw->email_rw }}">
                </div>

                <!-- Submit -->
                <div class="text-center mt-6">
                    <button type="submit"
                        class="bg-yellow-600 hover:bg-yellow-700 text-white font-semibold py-2 px-6 rounded-lg">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Ubah Password -->
    <div id="passwordModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 relative">
            <!-- Tombol Close Modal -->
            <button onclick="closePasswordModal()" class="absolute top-2 right-3 text-gray-500 hover:text-gray-700 text-xl font-bold">&times;</button>

            <h2 class="text-xl font-bold text-center mb-1">Ubah Password</h2>
            <small class="block text-red-500 mb-4 text-center">Password minimal 6 karakter dan harus cocok saat dikonfirmasi.</small>
            @if (
                $errors->has('current_password') ||
                $errors->has('new_password') ||
                $errors->has('new_password_confirmation')
            )
                <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->get('current_password') as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                        @foreach ($errors->get('new_password') as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                        @foreach ($errors->get('new_password_confirmation') as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('updatePassword') }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Password Lama -->
                <div class="mb-4">
                    <label class="text-sm font-medium text-gray-700">Password Lama</label>
                    <div class="flex items-center border border-gray-300 rounded px-3 py-2 relative mt-1">
                        <input type="password" name="current_password" id="current_password"
                            required minlength="6" maxlength="6"
                            class="w-full bg-transparent focus:outline-none text-gray-700 pr-10">
                        <button type="button" onclick="togglePassword('current_password', this)" class="absolute right-3 text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Password Baru -->
                <div class="mb-4">
                    <label class="text-sm font-medium text-gray-700">Password Baru</label>
                    <div class="flex items-center border border-gray-300 rounded px-3 py-2 relative mt-1">
                        <input type="password" name="new_password" id="new_password"
                            required minlength="6" maxlength="6"
                            class="w-full bg-transparent focus:outline-none text-gray-700 pr-10">
                        <button type="button" onclick="togglePassword('new_password', this)" class="absolute right-3 text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Konfirmasi Password Baru -->
                <div class="mb-4">
                    <label class="text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                    <div class="flex items-center border border-gray-300 rounded px-3 py-2 relative mt-1">
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                            required minlength="6" maxlength="6"
                            class="w-full bg-transparent focus:outline-none text-gray-700 pr-10">
                        <button type="button" onclick="togglePassword('new_password_confirmation', this)" class="absolute right-3 text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="text-center mt-6">
                    <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white py-2 px-6 rounded-lg">
                        Simpan Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal tampilan gambar -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center hidden z-50">
        <span class="absolute top-4 right-6 text-white text-3xl cursor-pointer" onclick="closeImageModal()">&times;</span>
        <img id="modalImage" src="" alt="Preview" class="max-w-sm max-h-50 border-4 border-white rounded-lg shadow-lg">
    </div>

    <!-- Modal untuk Upload Tanda Tangan -->
    <div id="editTtdModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 relative">
            <!-- Tombol Close Modal -->
            <button onclick="closeModalTtd()" class="absolute top-2 right-3 text-gray-500 hover:text-gray-700 text-xl font-bold">&times;</button>

            <h2 class="text-xl font-bold mb-4">Edit Tanda Tangan</h2>

            <form action="{{ route('scanTtdRwUpload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- Pilih Gambar Tanda Tangan -->
                <div class="mb-4">
                    <label for="scan_ttd" class="block text-sm font-medium text-gray-700 mb-1">Pilih Gambar Scan Tanda Tangan</label>
                    <input type="file" name="ttd_digital" accept="image/*" required
                        class="block w-full text-sm text-gray-700 bg-gray-100 border border-gray-300 rounded-lg cursor-pointer p-2">
                </div>

                <div class="text-center mt-6">
                    <button type="submit" class="bg-blue-600 text-white py-2 px-6 rounded-lg hover:bg-blue-700">
                        Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const editBtn = document.getElementById('editButton');
    const imageInput = document.getElementById('imageInput');
    const previewImage = document.getElementById('previewImage');
    const uploadForm = document.getElementById('uploadForm');
    const cancelBtn = document.getElementById('cancelButton');

    // Saat tombol "Edit Profil" diklik, buka input file
    editBtn.addEventListener('click', function () {
        imageInput.click();
    });

    // Saat file dipilih
    imageInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            // Tampilkan preview gambar
            previewImage.src = URL.createObjectURL(file);

            // Sembunyikan tombol edit, tampilkan form
            editBtn.classList.add('hidden');
            uploadForm.classList.remove('hidden');

            // Tambahkan input file ke form (agar bisa dikirim)
            if (uploadForm.querySelector('input[type="file"]')) {
                uploadForm.removeChild(uploadForm.querySelector('input[type="file"]'));
            }
            const newInput = document.createElement('input');
            newInput.type = 'file';
            newInput.name = 'profile_rw';
            newInput.files = event.target.files;
            newInput.classList.add('hidden');
            uploadForm.appendChild(newInput);
        }
    });

    // Saat klik batal
    cancelBtn.addEventListener('click', function () {
        // Reset preview gambar
        previewImage.src = "{{ $rw->profile_rw ? asset('storage/profile_rw/' . $rw->profile_rw) : asset('images/profile.png') }}";

        // Reset input file
        imageInput.value = '';

        // Tampilkan kembali tombol edit, sembunyikan form
        editBtn.classList.remove('hidden');
        uploadForm.classList.add('hidden');
    });

    // RESET SEMUA INPUT & PASSWORD VISIBILITY SAAT MODAL DITUTUP
    function resetModal(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;

        // Reset semua form di modal
        const forms = modal.querySelectorAll('form');
        forms.forEach(form => form.reset());

        // Set kembali semua input type ke "password"
        const passwordInputs = modal.querySelectorAll('input[type="text"]');
        passwordInputs.forEach(input => {
            if (input.name.includes('password')) {
                input.type = 'password';
            }
        });

        // Hapus class warna dari icon svg
        modal.querySelectorAll('button svg').forEach(svg => {
            svg.classList.remove('text-blue-600');
        });
    }

    function togglePassword(fieldId, btn) {
        const input = document.getElementById(fieldId);
        const icon = btn.querySelector('svg');
        if (input.type === "password") {
            input.type = "text";
            icon.classList.add('text-blue-600');
        } else {
            input.type = "password";
            icon.classList.remove('text-blue-600');
        }
    }

    function openModal() {
        document.getElementById('editModal').classList.remove('hidden');
    }

    // Tutup Modal Edit
    function closeModal() {
        document.getElementById('editModal').classList.add('hidden');
        resetModal('editModal');
    }

    function openModalTtd() {
        document.getElementById('editTtdModal').classList.remove('hidden');
    }

    // Tutup Modal TTD
    function closeModalTtd() {
        document.getElementById('editTtdModal').classList.add('hidden');
        resetModal('editTtdModal');
    }

    function showImageModal(imageSrc) {
        document.getElementById('modalImage').src = imageSrc;
        document.getElementById('imageModal').classList.remove('hidden');
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
    }

    function openPasswordModal() {
        document.getElementById('passwordModal').classList.remove('hidden');
    }

    // Tutup Modal Password
    function closePasswordModal() {
        //     document.getElementById('passwordModal').classList.add('hidden');
        //     resetModal('passwordModal');
        const modal = document.getElementById('passwordModal');
        modal.classList.add('hidden');
        resetModal('passwordModal');

        // Hapus pesan error dari modal secara manual
        const errorBox = modal.querySelector('.bg-red-100');
        if (errorBox) {
            errorBox.remove();
        }
    }

</script>

{{-- Jika ada error validasi pada ubah password, otomatis buka modal saat halaman dimuat / modal tetap terbuka --}}
@if ($errors->has('current_password') || $errors->has('new_password') || $errors->has('new_password_confirmation'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('passwordModal');
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
        }
    });
</script>
@endif

@endsection

@extends('rt.dashboardRt')

@section('content')
<div class="container mx-auto p-4 mb-6 pt-20">
    <h1 class="text-2xl font-bold mb-2">Profil RT</h1>
    <p class="text-gray-600 text-lg mb-6">Halaman ini menampilkan data profil RT dan memungkinkan Anda untuk memperbarui informasi serta mengunggah tanda tangan digital.
        <br>Anda <strong class="text-red-500">wajib</strong> mengunggah scan tanda tangan digital jika belum melakukannya agar proses administrasi dapat berjalan lancar.
    </p>

    <div class="md:flex md:space-x-6 space-y-6 md:space-y-0 items-stretch">
        <!-- Card Profil -->
        <div class="bg-white shadow-lg rounded-2xl p-6 md:w-1/2 h-full">
            <h1 class="text-2xl font-bold mb-4 text-gray-800">Edit Profile RT</h1>
            @if (session('dataSuccess'))
                <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
                    {{ session('dataSuccess') }}
                </div>
            @endif

            @if (session('uploadSuccess'))
                <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
                    {{ session('uploadSuccess') }}
                </div>
            @endif

            @if (session('passwordSuccess'))
                <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
                    {{ session('passwordSuccess') }}
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
                    <img id="previewImage" src="{{ $rt->profile_rt ? asset('storage/profile_rt/' . $rt->profile_rt) : asset('images/profile.png') }}"
                        alt="Foto Profil"
                        class="w-32 h-32 rounded-full object-cover border-4 border-indigo-500 mx-auto mb-3">

                    <!-- Tombol Edit Profil -->
                    <button type="button"
                            id="editButton"
                            class="mt-2 px-4 py-2 bg-indigo-600 text-white font-semibold rounded hover:bg-indigo-700 transition">
                        Edit Profil
                    </button>

                    <!-- Input file disembunyikan -->
                    <input type="file" id="imageInput" name="profile_rt" accept="image/*" class="hidden">

                    <!-- Form simpan profil -->
                    <form action="{{ route('uploadProfileRt') }}" method="POST" enctype="multipart/form-data" class="mt-4 hidden" id="uploadForm">
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

                <!-- Informasi RT -->
                <div class="flex-1">
                    <div class="flex flex-col items-center">
                        <!-- Kontainer data -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 w-full max-w-4xl">
                            <!-- Kolom kiri: span 2 kolom -->
                            <div class="sm:col-span-2 space-y-4">
                                <!-- Nama Lengkap -->
                                <div>
                                    <h2 class="text-sm text-gray-500">Nama Lengkap</h2>
                                    <p class="text-lg font-semibold text-gray-800">{{ $rt->nama_lengkap_rt }}</p>
                                </div>

                                <!-- No HP -->
                                <div>
                                    <h2 class="text-sm text-gray-500">No. HP</h2>
                                    <p class="text-lg font-semibold text-gray-800">{{ $rt->no_hp_rt }}</p>
                                </div>

                                <!-- Email -->
                                <div class="overflow-hidden">
                                    <h2 class="text-sm text-gray-500">Email</h2>
                                    <p class="text-100 font-semibold text-gray-800 " title="{{ $rt->email_rt }}">
                                        {{ $rt->email_rt }}
                                    </p>
                                </div>
                            </div>

                            <!-- Kolom kanan: Nomor RT -->
                            <div class="space-y-4">
                                <div>
                                    <h2 class="text-sm text-gray-500">Nomor RT</h2>
                                    <p class="text-lg font-semibold text-gray-800">RT {{ $rt->no_rt }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Edit Profil dan Ubah Password -->
                        <div class="mt-3 flex justify-center gap-4">
                            <button onclick="openModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-6 rounded-lg">
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
        <div class="bg-white shadow-lg rounded-2xl p-6 md:w-1/2 h-full">
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

            @if ($errors->has('ttd_digital'))
                <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
                    {{ $errors->first('ttd_digital') }}
                </div>
            @endif
            
            <!-- Jika tanda tangan belum ada -->
            @if (empty($rt->ttd_digital) && empty($rt->ttd_digital_bersih))
                <form action="{{ route('scanTtdRtUpload') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    <div>
                        <label for="scan_ttd" class="block text-sm font-semibold text-gray-800 mb-1">Pilih Gambar Scan Tanda Tangan</label>
                        <input type="file" name="ttd_digital" accept="image/*" required
                            class="block w-full text-sm text-gray-700 bg-gray-100 border border-gray-300 rounded-lg cursor-pointer p-2">
                        <p id="file-error" class="text-red-600 text-sm mt-1 hidden">Ukuran file melebihi 2 MB.</p>
                    </div>

                    <div class="flex justify-center">
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all">
                            Upload
                        </button>
                    </div>
                </form>
            @else
            <!-- Jika tanda tangan sudah ada, tampilkan gambar dan tombol Edit -->
            <div class="mt-4 w-full">
                <table class="w-full table-fixed border border-gray-300 border-collapse rounded-md shadow-md">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-2 py-2 border border-gray-300 text-sm w-1/3">Tanda Tangan Scan</th>
                            <th class="px-2 py-2 border border-gray-300 text-sm w-1/3">Tanda Tangan Bersih</th>
                            <th class="px-2 py-2 border border-gray-300 text-sm w-1/3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="px-2 py-2 border border-gray-300 text-center">
                                <img src="{{ Storage::url($rt->ttd_digital) }}"
                                    alt="Tanda Tangan Scan" width="768"
                                    height="951"
                                    class="object-contain mx-auto cursor-pointer hover:scale-105 transition-transform duration-200"
                                    onclick="showImageModal('{{ Storage::url($rt->ttd_digital) }}')">
                            </td>
                            <td class="px-2 py-2 border border-gray-300 text-center">
                                <img src="{{ Storage::url($rt->ttd_digital_bersih) }}"
                                    alt="Tanda Tangan Bersih"
                                    width="768"
                                    height="951"
                                    class="mx-auto object-contain cursor-pointer hover:scale-105 transition-transform duration-200"
                                    onclick="showImageModal('{{ Storage::url($rt->ttd_digital_bersih) }}')">

                            </td>
                            <td class="px-2 py-2 border border-gray-300 text-center">
                                <button type="button"
                                    class="px-3 py-1 bg-yellow-500 text-white text-sm rounded hover:bg-yellow-600"
                                    onclick="openModalTtd()">
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

            <form action="{{ route('updateDataRt') }}" method="POST">
                @csrf
                @method('PUT')

                <!-- No HP -->
                <div class="mb-4">
                    <label class="block mb-1 text-gray-600 font-medium">Nomer WhatsApp</label>
                    <div class="flex">
                        <!-- Prefix "62" tidak bisa diedit -->
                        <span class="inline-flex items-center px-3 rounded-l border border-r-0 border-gray-300 bg-gray-100 text-gray-600 select-none">62</span>
                        <!-- Input nomor hp tanpa "62" -->
                        <input
                            type="text"
                            name="no_hp_rt"
                            id="no_hp_rt"
                            class="flex-1 border border-gray-300 rounded-r px-3 py-2"
                            placeholder="Masukkan nomor setelah 62"
                            required
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                            maxlength="11"
                            value="{{ Str::startsWith($rt->no_hp_rt, '62') ? substr($rt->no_hp_rt, 2) : $rt->no_hp_rt }}"
                        >
                    </div>
                    <small class="text-gray-500">Nomor harus diawali dengan 62 (otomatis), hanya masukkan nomor setelah kode negara.</small>
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-gray-600 mb-1" for="email_rt">Email</label>
                    <input type="email" name="email_rt" id="email_rt"
                        class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-indigo-500"
                        value="{{ $rt->email_rt }}">
                </div>

                <!-- Submit -->
                <div class="text-center mt-6">
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-6 rounded-lg">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Ubah Password -->
    <div id="passwordModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 relative">
            <!-- Tombol Close -->
            <button onclick="closePasswordModal()" class="absolute top-2 right-3 text-gray-500 hover:text-gray-700 text-xl font-bold">&times;</button>

            <h2 class="text-xl font-bold mb-1">Ubah Password</h2>
            <small class="block text-red-500 mb-4">Password minimal 6 karakter dan harus cocok saat dikonfirmasi.</small>
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

            @if(session('passwordSuccess'))
                <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
                    {{ session('passwordSuccess') }}
                </div>
            @endif

            @if(session('passwordError'))
                <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
                    {{ session('passwordError') }}
                </div>
            @endif

            <form method="POST" action="{{ route('updatePasswordRt') }}">
                @csrf

                <!-- Password Saat Ini -->
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-semibold">Password Saat Ini</label>
                    <div class="flex items-center border border-gray-300 rounded px-3 py-2 relative mt-1">
                        <input type="password" name="current_password" id="current_password_rt"
                            required minlength="6" maxlength="6"
                            class="w-full bg-transparent focus:outline-none text-gray-700 pr-10">
                        <button type="button" onclick="togglePassword('current_password_rt', this)" class="absolute right-3 text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7
                                    -1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Password Baru -->
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-semibold">Password Baru</label>
                    <div class="flex items-center border border-gray-300 rounded px-3 py-2 relative mt-1">
                        <input type="password" name="new_password" id="new_password_rt"
                            required minlength="6" maxlength="6"
                            class="w-full bg-transparent focus:outline-none text-gray-700 pr-10">
                        <button type="button" onclick="togglePassword('new_password_rt', this)" class="absolute right-3 text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7
                                    -1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Konfirmasi Password Baru -->
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-semibold">Konfirmasi Password Baru</label>
                    <div class="flex items-center border border-gray-300 rounded px-3 py-2 relative mt-1">
                        <input type="password" name="new_password_confirmation" id="confirm_password_rt"
                            required minlength="6" maxlength="6"
                            class="w-full bg-transparent focus:outline-none text-gray-700 pr-10">
                        <button type="button" onclick="togglePassword('confirm_password_rt', this)" class="absolute right-3 text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7
                                    -1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="text-center mt-6">
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-6 rounded-lg">
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

            <form action="{{ route('scanTtdRtUpload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- Pilih Gambar Tanda Tangan -->
                <div class="mb-4">
                    <label for="scan_ttd" class="block text-sm font-medium text-gray-700 mb-1">Pilih Gambar Scan Tanda Tangan</label>
                    <input type="file" name="ttd_digital" accept="image/*" required
                        class="block w-full text-sm text-gray-700 bg-gray-100 border border-gray-300 rounded-lg cursor-pointer p-2">
                </div>

                <div class="text-center mt-6">
                    <button type="submit" class="bg-indigo-600 text-white py-2 px-6 rounded-lg hover:bg-indigo-700">
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
            newInput.name = 'profile_rt';
            newInput.files = event.target.files;
            newInput.classList.add('hidden');
            uploadForm.appendChild(newInput);
        }
    });

    // Saat klik batal
    cancelBtn.addEventListener('click', function () {
        // Reset preview gambar
        previewImage.src = "{{ $rt->profile_rt ? asset('storage/profile_rt/' . $rt->profile_rt) : asset('images/profile.png') }}";

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
        // document.getElementById('passwordModal').classList.add('hidden');
        // resetModal('passwordModal');
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

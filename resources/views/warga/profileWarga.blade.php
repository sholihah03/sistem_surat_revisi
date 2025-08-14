<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Profil Warga</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="min-h-screen bg-[#CFFFE2]">
    @include('komponen.nav')

    <!-- Breadcrumb -->
    <nav class="max-w-7xl mx-auto px-4 pt-6 text-sm text-gray-600">
    <ol class="flex items-center space-x-2">
        <li><a href="{{ route('dashboardWarga') }}" class="text-blue-600 no-underline">Home</a></li>
        <li>/</li>
        <li class="text-gray-800 font-medium">Profile Warga</li>
    </ol>
    </nav>

    <!-- Menampilkan pesan sukses jika ada -->
    @if(session('success'))
        <div id="successModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 sm:p-8 relative w-[90%] max-w-md sm:max-w-lg text-center animate-scale">
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

        <script>
            function closeModal() {
            const modal = document.getElementById('successModal');
            if (modal) {
                modal.style.display = 'none';
            }
            }
        </script>
    @endif

    <div class="container py-4">
        <div class="max-w-7xl mx-auto bg-white rounded-xl shadow-md overflow-hidden">
            <div class="flex flex-col md:flex-row">
                <!-- Avatar Section -->
                <div class="bg-gradient-to-r from-[#9FC87E] to-[#80D8C3] text-white flex flex-col items-center justify-center p-6 md:w-1/3 rounded-r-xl">
    <div class="relative">
        <img src="{{ $warga->profile_warga ? asset('storage/profile_warga/' . $warga->profile_warga) : asset('images/profile.png') }}"
        alt="Avatar" class="w-32 h-32 rounded-full border-4 border-white shadow">
        <button class="absolute bottom-0 right-0 bg-white text-[#9FC87E] p-2 rounded-full shadow" data-bs-toggle="modal" data-bs-target="#modalUploadFoto">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6-6m2 2l-6 6m-6 2v4h4l10-10a2.828 2.828 0 00-4-4L5 15z" />
            </svg>
        </button>
    </div>
    <h3 class="mt-4 text-xl font-semibold text-white">{{ $warga->nama_lengkap }}</h3>
    <p class="text-base text-white">RW {{ $warga->rw->no_rw ?? '-' }} - RT {{ $warga->rt->no_rt ?? '-' }}</p>
</div>


                <!-- Form Section -->
                <div class="p-6 md:w-2/3">
                    <h2 class="text-2xl font-bold mb-4">Edit Profil</h2>
                    <form action="#" method="POST">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Nama Depan</label>
                            <input type="text" class="form-control" name="first_name" value="{{ explode(' ', $warga->nama_lengkap)[0] }}" readonly>
                        </div>
                        <div>
                            <label class="form-label">Nama Belakang</label>
                            <input type="text" class="form-control" name="last_name" value="{{ explode(' ', $warga->nama_lengkap)[1] ?? '' }}" readonly>
                        </div>
                        <div>
                            <label class="form-label">NIK</label>
                            <input type="text" class="form-control" name="nik" value="{{ $warga->nik }}" readonly>
                        </div>
                        <div>
                            <label class="form-label">No KK</label>
                            <input type="text" class="form-control" name="no_kk" value="{{ $warga->no_kk }}" readonly>
                        </div>
                        <div>
                            <label class="form-label">No HP</label>
                            <input type="text" class="form-control" name="no_hp" value="{{ $warga->no_hp }}" readonly>
                        </div>
                        <div>
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" value="{{ $warga->email }}" readonly>
                        </div>
                        </div>
                        @if ($alamat)
                            <div class="mt-4">
                                <label class="form-label">Alamat Lengkap</label>
                                <textarea class="form-control" rows="2" readonly>{{ $alamat->nama_jalan }}, RT {{ $alamat->rt_alamat }}/RW {{ $alamat->rw_alamat }}, Kel. {{ $alamat->kelurahan }}, Kec. {{ $alamat->kecamatan }}, Kab. {{ $alamat->kabupaten_kota }},
{{ $alamat->provinsi }} - {{ $alamat->kode_pos }}
                                </textarea>
                            </div>
                        @else
                            <div class="alert alert-warning mt-4">
                                Alamat belum tersedia.
                            </div>
                        @endif
                        <div class="text-end mt-4">
                            <button type="button" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg" data-bs-toggle="modal" data-bs-target="#modalEditProfil">
                                Ubah
                            </button>

                            <!-- Tombol Ubah Password -->
                            <button type="button" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg" data-bs-toggle="modal" data-bs-target="#modalUbahPassword">
                                Ubah Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Upload Foto Profil -->
    <div class="modal fade" id="modalUploadFoto" tabindex="-1" aria-labelledby="modalUploadFotoLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('profileWarga.uploadFoto') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title font-semibold" id="modalUploadFotoLabel">Upload Foto Profil</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2">
                            <div class="d-flex justify-content-center">
                                <img id="profilePreview" src="{{ $warga->profile_warga ? asset('storage/profile_warga/' . $warga->profile_warga) : asset('images/profile.png') }}"
                                alt="Avatar" class="w-32 h-32 rounded-full border-4 border-white shadow mb-3">
                            </div>
                            <label for="profile_foto" class="form-label">Pilih Foto (JPG/PNG, maks 2MB)</label>
                            <input id="profileInput" class="form-control" type="file" name="profile_foto" accept="image/*" required onchange="previewImage(event)">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Modal Edit Profil -->
    <div class="modal fade" id="modalEditProfil" tabindex="-1" aria-labelledby="modalEditProfilLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
            <form action="{{ route('profileWarga.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                <h5 class="modal-title font-semibold" id="modalEditProfilLabel">Edit Profil Warga</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                <div class="row g-3">
                    <!-- No HP -->
                    <div class="col-md-6 mb-4">
                        <label class="block mb-1 text-sm font-medium">No HP</label>
                        <div class="flex">
                            <!-- Prefix "62" tidak bisa diedit -->
                            <span class="inline-flex items-center px-3 rounded-l border border-r-0 border-gray-300 bg-gray-100 text-gray-600 select-none">62</span>
                            <!-- Input nomor hp tanpa "62" -->
                            <input
                                type="text"
                                name="no_hp"
                                id="no_hp"
                                class="flex-1 border border-gray-300 rounded-r px-3 py-2"
                                placeholder="Masukkan nomor setelah 62"
                                required
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                maxlength="11"
                                value="{{ Str::startsWith($warga->no_hp, '62') ? substr($warga->no_hp, 2) : $warga->no_hp }}"
                            >
                        </div>
                        <small class="text-gray-500">Nomor harus diawali dengan 62 (otomatis), hanya masukkan nomor setelah kode negara.</small>
                    </div>
                    <div class="col-md-6">
                    <label class="form-label font-medium">Email</label>
                    <input type="email" class="form-control" name="email" value="{{ $warga->email }}">
                    </div>
                </div>
                </div>

                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
            </div>
        </div>
    </div>

    <!-- Modal Ubah Password -->
    <div class="modal fade" id="modalUbahPassword" tabindex="-1" aria-labelledby="modalUbahPasswordLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('profileWarga.updatePassword') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title font-semibold" id="modalUbahPasswordLabel">Ubah Password</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    @if (
                            $errors->has('current_password') ||
                            $errors->has('new_password') ||
                            $errors->has('new_password_confirmation')
                        )
                            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-2">
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
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="block mb-1 text-sm font-semibold">Password Saat Ini</label>
                        <div class="flex items-center border border-gray-300 rounded px-3 py-2 relative mt-1">
                            <input type="password" name="current_password" id="current_password_rt"
                                required minlength="6" maxlength="6"
                                class="w-full bg-transparent focus:outline-none text-gray-700 pr-10">
                            <button type="button" data-eye onclick="togglePassword('current_password_rt', this)" class="absolute right-3 text-gray-500">
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
                        <div class="mb-3">
                            <label class="block mb-1 text-sm font-semibold">Password Baru</label>
                        <div class="flex items-center border border-gray-300 rounded px-3 py-2 relative mt-1">
                            <input type="password" name="new_password" id="new_password_rt"
                                required minlength="6" maxlength="6"
                                class="w-full bg-transparent focus:outline-none text-gray-700 pr-10">
                            <button type="button" data-eye onclick="togglePassword('new_password_rt', this)" class="absolute right-3 text-gray-500">
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
                        <div class="mb-3">
                            <label class="block mb-1 text-sm font-semibold">Konfirmasi Password Baru</label>
                        <div class="flex items-center border border-gray-300 rounded px-3 py-2 relative mt-1">
                            <input type="password" name="new_password_confirmation" id="confirm_password_rt"
                                required minlength="6" maxlength="6"
                                class="w-full bg-transparent focus:outline-none text-gray-700 pr-10">
                            <button type="button" data-eye onclick="togglePassword('confirm_password_rt', this)" class="absolute right-3 text-gray-500">
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
                        <small class="block text-red-500 font-semibold">Password minimal 6 karakter dan harus cocok saat dikonfirmasi.</small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('components.modal-timeout')

    <script>
        let originalSrc = document.getElementById('profilePreview').src;

        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('profilePreview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Reset preview if modal ditutup (klik batal atau close modal)
    const uploadModal = document.getElementById('modalUploadFoto');
        uploadModal.addEventListener('hidden.bs.modal', function () {
            const preview = document.getElementById('profilePreview');
            const input = document.getElementById('profileInput');
            preview.src = originalSrc;
            input.value = ""; // reset file input
        });

    const modals = ['modalUploadFoto', 'modalEditProfil', 'modalUbahPassword'];

    modals.forEach(modalId => {
        const modalEl = document.getElementById(modalId);

        if (modalEl) {
            modalEl.addEventListener('hidden.bs.modal', function () {
                // Reset semua form di dalam modal
                const forms = modalEl.querySelectorAll('form');
                forms.forEach(form => form.reset());

                // Reset preview foto (khusus upload foto)
                if (modalId === 'modalUploadFoto') {
                    const preview = document.getElementById('profilePreview');
                    const input = document.getElementById('profileInput');
                    if (preview && originalSrc) preview.src = originalSrc;
                    if (input) input.value = "";
                }

                // Reset input password ke type="password"
                const inputs = modalEl.querySelectorAll('input[type="text"], input[type="password"]');
                inputs.forEach(input => {
                    if (input.type === "text" && input.getAttribute("data-password") === "true") {
                        input.type = "password";
                    }
                });

                // Reset icon mata
                const eyeButtons = modalEl.querySelectorAll('button[data-eye]');
                eyeButtons.forEach(btn => {
                    const icon = btn.querySelector('svg');
                    icon.classList.remove('text-blue-600');
                });
            });
        }

        // Reset khusus modal ubah password, termasuk hapus error dan reset input/icon
        const modalUbahPassword = document.getElementById('modalUbahPassword');
        if (modalUbahPassword) {
            modalUbahPassword.addEventListener('hidden.bs.modal', function () {
                // Reset form
                const form = modalUbahPassword.querySelector('form');
                if (form) form.reset();

                // Hilangkan pesan error (hapus elemen)
                const errorAlert = modalUbahPassword.querySelector('.bg-red-100');
                if (errorAlert) errorAlert.remove();

                // Reset input type ke password
                const inputs = modalUbahPassword.querySelectorAll('input[type="text"], input[type="password"]');
                inputs.forEach(input => {
                    if (input.type === "text" && input.getAttribute("data-password") === "true") {
                        input.type = "password";
                    }
                });

                // Reset icon mata
                const eyeButtons = modalUbahPassword.querySelectorAll('button[data-eye]');
                eyeButtons.forEach(btn => {
                    const icon = btn.querySelector('svg');
                    icon.classList.remove('text-blue-600');
                });
            });
        }

    });

    function togglePassword(fieldId, btn) {
        const input = document.getElementById(fieldId);
        const icon = btn.querySelector('svg');
        input.setAttribute("data-password", "true");

        if (input.type === "password") {
            input.type = "text";
            icon.classList.add("text-blue-600");
        } else {
            input.type = "password";
            icon.classList.remove("text-blue-600");
        }
    }
    </script>

{{-- Jika ada error validasi pada ubah password, otomatis buka modal saat halaman dimuat / modal tetap terbuka --}}
    @if ($errors->has('current_password') || $errors->has('new_password') || $errors->has('new_password_confirmation'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = new bootstrap.Modal(document.getElementById('modalUbahPassword'));
            modal.show();
        });
    </script>
    @endif

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

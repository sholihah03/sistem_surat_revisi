<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daftar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
</head>
<body class="min-h-screen bg-cover bg-center" style="background-image: url('{{ asset('images/background login.png') }}')">

    <div class="min-h-screen flex items-center justify-center px-5">
        <div class="flex flex-col md:flex-row items-center bg-transparent w-full max-w-6xl">

            <!-- Form Daftar -->
            <div class="bg-white rounded-2xl shadow-xl p-10 w-full md:w-1/2">
                <h2 class="text-xl font-bold text-center text-black mb-6 leading-relaxed">
                    SILAHKAN DAFTAR DI <br>
                    SISTEM ADMINISTRASI SURAT <br>
                    RT/RW DIGITAL
                </h2>

                <!-- Pesan Error -->
                @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Pesan Error dari Session (contoh: email atau password salah) -->
                @if (session('daftar_error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded">
                        <p>{{ session('daftar_error') }}</p>
                    </div>
                @endif

                <form action="{{ route('daftar') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Nama Lengkap -->
                    <div>
                        <label for="nama_lengkap" class="block text-sm font-semibold text-gray-700">Nama Lengkap</label>
                        <p class="text-xs text-red-500">Harap isi sesuai dengan nama lengkap yang tertera di KTP.</p>
                        <div class="flex items-center border border-blue-400 rounded-md px-3 py-2 mt-1">
                            <input type="text" name="nama_lengkap" id="nama_lengkap" required placeholder="Nama Lengkap"
                                class="w-full focus:outline-none bg-transparent text-gray-700" />
                        </div>
                    </div>

                    <!-- No WhatsApp -->
                    <div>
                        <label for="no_hp" class="block text-sm font-semibold text-gray-700">Nomor WhatsApp</label>
                        <div class="flex items-center border border-blue-400 rounded-md px-3 py-2 mt-1">
                            <input type="number" name="no_hp" id="no_hp" required placeholder="Nomor WhatsApp"
                                class="w-full focus:outline-none bg-transparent text-gray-700" maxlength="13" oninput="validateLengthWa(this)"/>
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700">Email</label>
                        <div class="flex items-center border border-blue-400 rounded-md px-3 py-2 mt-1">
                            <input type="email" name="email" id="email" required placeholder="Email"
                                class="w-full focus:outline-none bg-transparent text-gray-700" />
                        </div>
                    </div>

                    <!-- Button -->
                    <button type="submit"
                        class="w-full bg-yellow-400 hover:bg-yellow-500 text-white font-semibold py-2 rounded-md transition">
                        Daftar
                    </button>

                    <!-- Link kembali ke login -->
                    <p class="text-center text-sm text-gray-600">
                        Kembali ke <a href="{{ route('login') }}" class="text-yellow-600 font-medium">Login</a>
                    </p>
                </form>
            </div>

            <!-- Gambar Ilustrasi (disembunyikan di layar kecil) -->
            <div class="hidden md:block md:w-1/2 ml-0 md:ml-10 mt-10 md:mt-0">
                <img src="{{ asset('images/gambar-register.png') }}" alt="Ilustrasi Login"
                    class="object-contain w-full h-auto drop-shadow-lg rounded-xl">
            </div>

        </div>
    </div>

    @include('components.modal-timeout')

    <script>
        function validateLength(input) {
            // Batasi input hanya 16 digit
            if (input.value.length > 16) {
                input.value = input.value.slice(0, 16);
            }
        }

        function validateLengthWa(input) {
            // Batasi input hanya 16 digit
            if (input.value.length > 13) {
                input.value = input.value.slice(0, 13);
            }
        }
    </script>
</body>
</html>

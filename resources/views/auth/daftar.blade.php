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

        {{-- buat hilangin panah di type number --}}
    {{-- <style>
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style> --}}
</head>
<body class="min-h-screen bg-cover bg-center" style="background-image: url('{{ asset('images/background login.png') }}')">

    <div class="min-h-screen flex items-center justify-center px-5">
        <div class="flex flex-col md:flex-row items-center bg-transparent w-full max-w-6xl">

            <!-- Form Login -->
            <div class="bg-white rounded-2xl shadow-xl p-10 w-full md:w-1/2">
                <h2 class="text-xl font-bold text-center text-black mb-6 leading-relaxed">
                    SILAHKAN DAFTAR DI <br>
                    SISTEM ADMINISTRASI SURAT <br>
                    RT/RW DIGITAL
                </h2>

                <form action="{{ route('daftar') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Username -->
                    <div class="flex items-center border border-blue-400 rounded-md px-3 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zM4.8 20.4c0-3.96 6.4-6 7.2-6s7.2 2.04 7.2 6v1.2H4.8v-1.2z"/>
                        </svg>
                        <input type="text" name="nama_lengkap" required placeholder="Nama Lengkap"
                            class="w-full focus:outline-none bg-transparent text-gray-700" />
                    </div>

                    <!-- No KK -->
                    <div class="flex items-center border border-blue-400 rounded-md px-3 py-2">
                        <svg class="h-5 w-5 text-gray-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M9 2.221V7H4.221a2 2 0 0 1 .365-.5L8.5 2.586A2 2 0 0 1 9 2.22ZM11 2v5a2 2 0 0 1-2 2H4v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2h-7Z" clip-rule="evenodd"/>
                        </svg>
                        <input type="number" name="no_kk" required placeholder="Nomer Kartu Keluarga"
                            class="w-full focus:outline-none bg-transparent text-gray-700" />
                    </div>

                    <!-- No WhatsApp -->
                    <div class="flex items-center border border-blue-400 rounded-md px-3 py-2">
                        <svg class="h-5 w-5 text-gray-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M7.978 4a2.553 2.553 0 0 0-1.926.877C4.233 6.7 3.699 8.751 4.153 10.814c.44 1.995 1.778 3.893 3.456 5.572 1.68 1.679 3.577 3.018 5.57 3.459 2.062.456 4.115-.073 5.94-1.885a2.556 2.556 0 0 0 .001-3.861l-1.21-1.21a2.689 2.689 0 0 0-3.802 0l-.617.618a.806.806 0 0 1-1.14 0l-1.854-1.855a.807.807 0 0 1 0-1.14l.618-.62a2.692 2.692 0 0 0 0-3.803l-1.21-1.211A2.555 2.555 0 0 0 7.978 4Z"/>
                        </svg>
                        <input type="number" name="no_hp" required placeholder="Nomer WhatsApp"
                            class="w-full focus:outline-none bg-transparent text-gray-700" />
                    </div>

                    <!-- Email -->
                    <div class="flex items-center border border-blue-400 rounded-md px-3 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M2 6c0-1.1.9-2 2-2h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6zm2 0l8 5 8-5H4zm0 2.3v9.7h16V8.3l-8 5-8-5z"/>
                        </svg>
                        <input type="email" name="email" required placeholder="Email"
                            class="w-full focus:outline-none bg-transparent text-gray-700" />
                    </div>

                    <!-- RW -->
                    <div class="flex items-center border border-blue-400 rounded-md px-3 py-2">
                        <svg class="h-5 w-5 text-gray-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M12 20a7.966 7.966 0 0 1-5.002-1.756l.002.001v-.683c0-1.794 1.492-3.25 3.333-3.25h3.334c1.84 0 3.333 1.456 3.333 3.25v.683A7.966 7.966 0 0 1 12 20ZM2 12C2 6.477 6.477 2 12 2s10 4.477 10 10c0 5.5-4.44 9.963-9.932 10h-.138C6.438 21.962 2 17.5 2 12Zm10-5c-1.84 0-3.333 1.455-3.333 3.25S10.159 13.5 12 13.5c1.84 0 3.333-1.455 3.333-3.25S13.841 7 12 7Z" clip-rule="evenodd"/>
                        </svg>
                        {{-- <input type="number" name="rw" required placeholder="Rw"
                            class="w-full focus:outline-none bg-transparent text-gray-700" /> --}}
                        <input type="text" name="rw" value="007" readonly placeholder="RW"
                            class="w-full focus:outline-none bg-transparent text-gray-700" />
                    </div>

                    <!-- RW -->
                    <div class="flex items-center border border-blue-400 rounded-md px-3 py-2">
                        <svg class="h-5 w-5 text-gray-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M12 20a7.966 7.966 0 0 1-5.002-1.756l.002.001v-.683c0-1.794 1.492-3.25 3.333-3.25h3.334c1.84 0 3.333 1.456 3.333 3.25v.683A7.966 7.966 0 0 1 12 20ZM2 12C2 6.477 6.477 2 12 2s10 4.477 10 10c0 5.5-4.44 9.963-9.932 10h-.138C6.438 21.962 2 17.5 2 12Zm10-5c-1.84 0-3.333 1.455-3.333 3.25S10.159 13.5 12 13.5c1.84 0 3.333-1.455 3.333-3.25S13.841 7 12 7Z" clip-rule="evenodd"/>
                        </svg>
                        <input type="number" name="rt" required placeholder="Rt"
                            class="w-full focus:outline-none bg-transparent text-gray-700" />
                    </div>

                    <!-- Button -->
                    <button type="submit"
                        class="w-full bg-yellow-400 hover:bg-yellow-500 text-white font-semibold py-2 rounded-md transition">
                        Daftar
                    </button>

                    <!-- Link daftar -->
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

</body>
</html>

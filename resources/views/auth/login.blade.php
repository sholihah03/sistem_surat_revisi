<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="min-h-screen bg-cover bg-center" style="background-image: url('{{ asset('images/background login.png') }}')">

    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="flex flex-col md:flex-row items-center bg-transparent w-full max-w-6xl">

            <!-- Form Login -->
            <div class="bg-white rounded-2xl shadow-xl p-10 w-full md:w-1/2">
                <h2 class="text-xl font-bold text-center text-black mb-6 leading-relaxed">
                    SELAMAT DATANG DI <br>
                    SISTEM ADMINISTRASI SURAT <br>
                    RT/RW DIGITAL
                </h2>

                <form action="{{ route('login-post') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Username -->
                    <div class="flex items-center border border-blue-400 rounded-md px-3 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zM4.8 20.4c0-3.96 6.4-6 7.2-6s7.2 2.04 7.2 6v1.2H4.8v-1.2z"/>
                        </svg>
                        <input type="text" name="username" required placeholder="Nama Lengkap"
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

                    {{-- <!-- Password -->
                    <div class="flex items-center border border-blue-400 rounded-md px-3 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 1a5 5 0 00-5 5v3H5a1 1 0 00-1 1v12a1 1 0 001 1h14a1 1 0 001-1V10a1 1 0 00-1-1h-2V6a5 5 0 00-5-5zm-3 5a3 3 0 016 0v3H9V6zm3 6a1.5 1.5 0 110 3 1.5 1.5 0 010-3z"/>
                        </svg>
                        <input type="password" name="password" required placeholder="Password"
                                class="w-full focus:outline-none bg-transparent text-gray-700" />
                    </div> --}}

                    <!-- Password -->
                    <div class="flex items-center border border-blue-400 rounded-md px-3 py-2 relative">
                        <!-- Ikon Lock -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 1a5 5 0 00-5 5v3H5a1 1 0 00-1 1v12a1 1 0 001 1h14a1 1 0 001-1V10a1 1 0 00-1-1h-2V6a5 5 0 00-5-5zm-3 5a3 3 0 016 0v3H9V6zm3 6a1.5 1.5 0 110 3 1.5 1.5 0 010-3z"/>
                        </svg>

                        <!-- Input -->
                        <input id="password" type="password" name="password" required placeholder="Password"
                            class="w-full focus:outline-none bg-transparent text-gray-700 pr-8" />

                        <!-- Toggle Visibility -->
                        <button type="button" onclick="togglePassword()" class="absolute right-3 text-gray-500 focus:outline-none">
                            <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>


                    <!-- Button -->
                    <button type="submit"
                        class="w-full bg-yellow-400 hover:bg-yellow-500 text-white font-semibold py-2 rounded-md transition">
                        Masuk
                    </button>

                    <!-- Link daftar -->
                    <p class="text-center text-sm text-gray-600">
                        Belum punya akun? <a href="{{ route('daftar') }}" class="text-yellow-600 font-medium">Daftar</a>
                    </p>
                </form>
            </div>

            <!-- Gambar Ilustrasi (disembunyikan di layar kecil) -->
            <div class="hidden md:block md:w-1/2 ml-0 md:ml-10 mt-10 md:mt-0">
                <img src="{{ asset('images/gambaran login2-fotor.png') }}" alt="Ilustrasi Login"
                    class="object-contain w-full h-auto drop-shadow-lg rounded-xl">
            </div>

        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eye-icon');

            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a10.056 10.056 0 012.408-4.041m2.03-1.707A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.956 9.956 0 01-4.138 5.157M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3l18 18" />
                `;
            } else {
                input.type = 'password';
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        }
    </script>

</body>
</html>

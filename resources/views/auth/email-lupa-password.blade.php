<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Email Lupa Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center px-4" style="background-image: url('{{ asset('images/background login.png') }}')">
    <div class="bg-white w-full max-w-md rounded-xl shadow-lg p-6 space-y-5">
        <h2 class="text-2xl font-bold text-center text-gray-800">Lupa Password</h2>
        <p class="text-center text-sm text-gray-600">Masukkan data yang sesuai dengan akun Anda.</p>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-2 rounded mb-3 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 text-red-700 p-2 rounded mb-3 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.kirimOtp') }}" class="space-y-4">
            @csrf

            <label class="block text-sm font-medium">Pilih Peran:</label>
            <select name="role" id="role" class="w-full border rounded p-2" onchange="toggleFields()" required>
                <option value="">-- Pilih --</option>
                <option value="warga">Warga</option>
                <option value="rt">RT</option>
                <option value="rw">RW</option>
            </select>

            <div>
                <label>Email:</label>
                <input type="email" name="email" class="w-full border rounded p-2" required placeholder="Email yang terdaftar">
            </div>

            <div id="nik-field" style="display:none;">
                <label>NIK:</label>
                <input type="text" name="nik" class="w-full border rounded p-2" maxlength="16" placeholder="NIK yang terdaftar">
            </div>

            <div id="rt-field" style="display:none;">
                <label>No RT:</label>
                <input type="text" name="no_rt" class="w-full border rounded p-2" placeholder="NO RT Anda">
            </div>

            <div id="rw-field" style="display:none;">
                <label>No RW:</label>
                <input type="text" name="no_rw" class="w-full border rounded p-2" placeholder="NO RW Anda">
            </div>

            <button type="submit" class="w-full bg-yellow-500 text-white py-2 font-semibold rounded">
                Kirim OTP
            </button>

            <!-- Link daftar -->
            <p class="text-center text-sm text-gray-600">
                Kembali ke <a href="{{ route('login') }}" class="text-yellow-600 font-medium">Login</a>
            </p>
        </form>
    </div>

    @include('components.modal-timeout')

    <script>
        function toggleFields() {
            const role = document.getElementById('role').value;
            document.getElementById('nik-field').style.display = role === 'warga' ? 'block' : 'none';
            document.getElementById('rt-field').style.display = role === 'rt' ? 'block' : 'none';
            document.getElementById('rw-field').style.display = role === 'rw' ? 'block' : 'none';
        }
    </script>
</body>
</html>

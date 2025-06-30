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
        <h2 class="text-2xl font-bold text-center text-gray-800">Masukkan Email</h2>
        <p class="text-center text-sm text-gray-600">Masukkan Email Akun Anda.</p>
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

        <form method="POST" action="" class="space-y-4">
            @csrf
            <label>Email:</label>
            <input type="email" name="email" class="w-full border rounded p-2" required>
            <button type="submit" class="w-full bg-yellow-500 text-white py-2 font-semibold rounded">Cek Email</button>
        </form>
    </div>
    @include('components.modal-timeout')
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pengecekan No KK dan NIK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center px-4" style="background-image: url('{{ asset('images/background login.png') }}')">
    <div class="bg-white w-full max-w-md rounded-xl shadow-lg p-6 space-y-5">
        <h2 class="text-2xl font-bold text-center text-gray-800">Pengecekan No KK dan NIK</h2>
        <p class="text-center text-sm text-gray-600">Masukkan No KK dan NIK Anda untuk melakukan pengecekan data diri Anda.</p>

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

        @if (session('error_pending_kk'))
            <div class="bg-red-100 text-red-800 p-2 rounded mb-3 text-sm">
                {{ session('error_pending_kk') }}
            </div>
        @endif

        <form method="POST" action="{{ route('cekKKProcess') }}" class="space-y-4">
            @csrf

            <div id="kk-field">
                <label>No KK:</label>
                <input type="text" name="no_kk" value="{{ old('no_kk') }}"
                    class="w-full border rounded p-2 @error('no_kk') border-red-500 @enderror"
                    maxlength="16" placeholder="Masukkan No KK Anda">
                @error('no_kk')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div id="nik-field">
                <label>NIK:</label>
                <input type="text" name="nik" value="{{ old('nik') }}"
                    class="w-full border rounded p-2 @error('nik') border-red-500 @enderror"
                    maxlength="16" placeholder="Masukkan No NIK Anda">
                @error('nik')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full bg-[#8AC47F] hover:bg-[#76A95B] text-white py-2 font-semibold rounded">
                Pengecekan
            </button>

            <!-- Link daftar -->
            <p class="text-center text-sm text-gray-600">
                Kembali ke <a href="{{ route('dashboardWarga') }}" class="text-[#76A95B] font-medium">Dashboard</a>
            </p>
        </form>
    </div>

    @include('components.modal-timeout')

    {{-- <script>
        function toggleFields() {
            const role = document.getElementById('role').value;
            document.getElementById('nik-field').style.display = role === 'warga' ? 'block' : 'none';
            document.getElementById('rt-field').style.display = role === 'rt' ? 'block' : 'none';
            document.getElementById('rw-field').style.display = role === 'rw' ? 'block' : 'none';
        }
    </script> --}}
</body>
</html>

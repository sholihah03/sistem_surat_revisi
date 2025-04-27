<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Form Surat</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen py-10 px-4">

  <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow space-y-6">
    <h2 class="text-xl font-bold text-center text-gray-800">Form Pengajuan Surat Pengantar</h2>

    <form action="#" method="POST" class="space-y-4">
      <!-- Nama -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Nama</label>
        <input type="text" name="nama" required class="w-full px-4 py-2 border rounded-md border-gray-300 focus:ring focus:ring-blue-300" />
      </div>

      <!-- Tempat / Tanggal Lahir -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Tempat / Tanggal Lahir</label>
        <input type="text" name="ttl" required class="w-full px-4 py-2 border rounded-md border-gray-300 focus:ring focus:ring-blue-300" />
      </div>

      <!-- KTP -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Nomor KTP</label>
        <input type="text" name="ktp" required class="w-full px-4 py-2 border rounded-md border-gray-300 focus:ring focus:ring-blue-300" />
      </div>

      <!-- Status -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Status Perkawinan</label>
        <select name="status" class="w-full px-4 py-2 border rounded-md border-gray-300 focus:ring focus:ring-blue-300">
          <option value="Kawin">Kawin</option>
          <option value="Belum">Belum</option>
          <option value="Janda">Janda</option>
          <option value="Duda">Duda</option>
        </select>
      </div>

      <!-- Kebangsaan / Agama -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Kebangsaan / Agama</label>
        <input type="text" name="kebangsaan" required class="w-full px-4 py-2 border rounded-md border-gray-300 focus:ring focus:ring-blue-300" />
      </div>

      <!-- Pekerjaan -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Pekerjaan</label>
        <input type="text" name="pekerjaan" required class="w-full px-4 py-2 border rounded-md border-gray-300 focus:ring focus:ring-blue-300" />
      </div>

      <!-- Alamat -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Alamat</label>
        <textarea name="alamat" rows="2" required class="w-full px-4 py-2 border rounded-md border-gray-300 focus:ring focus:ring-blue-300"></textarea>
      </div>

      <!-- Maksud / Tujuan -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Untuk / Maksud / Tujuan</label>
        <textarea name="maksud" rows="2" required class="w-full px-4 py-2 border rounded-md border-gray-300 focus:ring focus:ring-blue-300"></textarea>
      </div>

      <!-- Tombol Submit -->
      <button type="submit" class="w-full bg-yellow-400 hover:bg-yellow-500 text-white font-semibold py-2 rounded">
        Kirim Pengajuan
      </button>
    </form>
  </div>

</body>
</html>


{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Form Surat</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen py-10 px-4">
    <div class="max-w-3xl mx-auto bg-white rounded-xl shadow p-6">
        <h2 class="text-xl font-bold mb-4 text-gray-800 text-center">Form Pengajuan Surat Pengantar</h2>

        <form method="POST" action="{{ route('kirim-surat') }}">
            @csrf
            <input type="hidden" name="tujuan_surat" value="{{ request('tujuan') }}">

            <div class="grid grid-cols-1 gap-4">
                <x-input label="Nama" name="nama" required />
                <x-input label="Tempat / Tanggal Lahir" name="ttl" required />
                <x-input label="Nomor KTP" name="ktp" required />
                <x-select label="Status Perkawinan" name="status" :options="['Kawin', 'Belum', 'Janda', 'Duda']" required />
                <x-input label="Kebangsaan / Agama" name="kebangsaan" required />
                <x-input label="Pekerjaan" name="pekerjaan" required />
                <x-textarea label="Alamat" name="alamat" required />
                <x-textarea label="Untuk / Maksud / Tujuan" name="maksud" required />
            </div>

            <button type="submit" class="mt-6 w-full bg-yellow-400 hover:bg-yellow-500 text-white py-2 rounded font-semibold">
                Kirim Pengajuan
            </button>
        </form>
    </div>
</body>
</html> --}}

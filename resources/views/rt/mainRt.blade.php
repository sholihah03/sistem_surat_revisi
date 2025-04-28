<main class="flex-1 p-4 md:p-8 overflow-x-auto">
    <h1 class="text-2xl pt-20 md:text-3xl font-bold text-gray-800 mb-6">Selamat Datang, Ketua RT!</h1>

    <!-- Kartu Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="p-4 md:p-6 bg-green-100 rounded-xl shadow border-l-4 border-green-500">
            <h2 class="text-base md:text-lg font-semibold mb-2">📑 Surat Masuk</h2>
            <p class="text-xl md:text-2xl font-bold text-green-700">12</p>
        </div>
        <div class="p-4 md:p-6 bg-blue-100 rounded-xl shadow border-l-4 border-blue-500">
            <h2 class="text-base md:text-lg font-semibold mb-2">✅ Surat Disetujui</h2>
            <p class="text-xl md:text-2xl font-bold text-blue-700">8</p>
        </div>
        <div class="p-4 md:p-6 bg-yellow-100 rounded-xl shadow border-l-4 border-yellow-500">
            <h2 class="text-base md:text-lg font-semibold mb-2">👥 Warga Belum Diverifikasi</h2>
            <p class="text-xl md:text-2xl font-bold text-yellow-700">4</p>
        </div>
    </div>

    <!-- Daftar Pengajuan Terbaru -->
    <div class="bg-white bg-opacity-80 p-4 md:p-6 rounded-xl shadow w-full">
        <h2 class="text-lg md:text-xl font-bold text-gray-800 mb-4">📋 Pengajuan Surat Terbaru</h2>
        <div class="overflow-x-auto">
            <div class="min-w-full inline-block align-middle overflow-x-auto">
                <div class="overflow-x-auto border rounded-lg">
                <table class="min-w-full text-xs md:text-sm text-gray-700">
                    <thead class="bg-gray-100">
                    <tr>
                        <th class="px-2 md:px-4 py-2 text-left">Tanggal</th>
                        <th class="px-2 md:px-4 py-2 text-left">Nama Warga</th>
                        <th class="px-2 md:px-4 py-2 text-left">Tujuan Surat</th>
                        <th class="px-2 md:px-4 py-2 text-left">Status</th>
                        <th class="px-2 md:px-4 py-2 text-left">Aksi</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <td class="px-2 md:px-4 py-2 whitespace-nowrap">2025-04-26</td>
                        <td class="px-2 md:px-4 py-2 whitespace-nowrap">Budi Santoso</td>
                        <td class="px-2 md:px-4 py-2 whitespace-nowrap">Surat Domisili</td>
                        <td class="px-2 md:px-4 py-2">
                        <span class="bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded text-xs font-semibold">Menunggu</span>
                        </td>
                        <td class="px-2 md:px-4 py-2">
                        <a href="#" class="text-blue-500 hover:underline">Verifikasi</a>
                        </td>
                    </tr>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</main>

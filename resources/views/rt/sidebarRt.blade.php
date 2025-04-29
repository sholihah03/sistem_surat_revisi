<aside id="sidebar" class="w-64 bg-white bg-opacity-90 shadow-md flex-col fixed inset-y-0 left-0 top-0 transform -translate-x-full transition-transform duration-200 ease-in-out md:relative md:translate-x-0 md:flex z-40">
    <nav class="flex flex-col gap-4 text-gray-700 pt-20 p-6">
    <a href="#" class="px-4 py-2 rounded-lg font-medium whitespace-nowrap transition-all duration-200 hover:bg-blue-100 hover:text-blue-700 hover:shadow-md active:bg-blue-200">🏠 Dashboard</a>
    <a href="{{ route('verifikasiAkunWarga') }}" class="px-4 py-2 rounded-lg font-medium whitespace-nowrap transition-all duration-200 hover:bg-blue-100 hover:text-blue-700 hover:shadow-md active:bg-blue-200">👤 Verifikasi Akun Warga</a>
    <a href="#" class="px-4 py-2 rounded-lg font-medium whitespace-nowrap transition-all duration-200 hover:bg-blue-100 hover:text-blue-700 hover:shadow-md active:bg-blue-200">📝 Verifikasi Surat</a>
    <a href="#" class="px-4 py-2 rounded-lg font-medium whitespace-nowrap transition-all duration-200 hover:bg-blue-100 hover:text-blue-700 hover:shadow-md active:bg-blue-200">📂 Bank Data KK</a>
    <a href="#" class="px-4 py-2 rounded-lg font-medium whitespace-nowrap transition-all duration-200 hover:bg-blue-100 hover:text-blue-700 hover:shadow-md active:bg-blue-200">📜 Riwayat Surat</a>
    <a href="{{ route('logout') }}" class="mt-8 px-4 py-2 rounded-lg font-medium whitespace-nowrap transition-all duration-200 text-red-600 hover:bg-red-100 hover:text-red-700 hover:shadow-md active:bg-red-200">🚪 Logout</a>
    </nav>
</aside>

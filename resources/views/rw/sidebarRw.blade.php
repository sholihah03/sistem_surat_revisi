<div id="sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen bg-white bg-opacity-90 border-r transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
    <nav class="flex flex-col gap-4 text-gray-700 pt-20 p-6">
        <a href="{{ route('dashboardRw') }}" class="px-4 py-2 rounded-lg font-medium whitespace-nowrap transition-all duration-200 hover:bg-green-100 hover:text-green-700 hover:shadow-md active:bg-green-200">🏠 Dashboard</a>
        <a href="{{ route('manajemenAkunRt') }}" class="px-4 py-2 rounded-lg font-medium whitespace-nowrap transition-all duration-200 hover:bg-green-100 hover:text-green-700 hover:shadow-md active:bg-green-200">👥 Manajemen Akun RT</a>
        <a href="{{ route('manajemenSuratWarga') }}" class="px-4 py-2 rounded-lg font-medium whitespace-nowrap transition-all duration-200 hover:bg-green-100 hover:text-green-700 hover:shadow-md active:bg-green-200">📄 Manajemen Surat</a>
        <a href="{{ route('riwayatSuratRw') }}" class="px-4 py-2 rounded-lg font-medium whitespace-nowrap transition-all duration-200 hover:bg-green-100 hover:text-green-700 hover:shadow-md active:bg-green-200">📜 Riwayat Surat</a>
        <a href="{{ route('suratPengantar') }}" class="px-4 py-2 rounded-lg font-medium whitespace-nowrap transition-all duration-200 hover:bg-green-100 hover:text-green-700 hover:shadow-md active:bg-green-200">🖋️ Template Surat</a>
        <a href="{{ route('tujuanSurat') }}" class="px-4 py-2 rounded-lg font-medium whitespace-nowrap transition-all duration-200 hover:bg-green-100 hover:text-green-700 hover:shadow-md active:bg-green-200">📬 Kelola Tujuan Surat</a>
        <a href="{{ route('profileRw') }}" class="px-4 py-2 rounded-lg font-medium whitespace-nowrap transition-all duration-200 hover:bg-green-100 hover:text-green-700 hover:shadow-md active:bg-green-200">✒️ TTD Digital</a>
        <a href="{{ route('logout') }}" class="mt-8 px-4 py-2 rounded-lg font-medium whitespace-nowrap transition-all duration-200 text-red-600 hover:bg-red-100 hover:text-red-700 hover:shadow-md active:bg-red-200">🚪 Logout</a>
    </nav>
</div>

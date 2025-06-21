<div id="sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen bg-white bg-opacity-90 border-r transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
    <nav class="flex flex-col gap-4 text-gray-700 pt-20 p-6">
        @php
            $disabled = $showModalUploadTtdRw; // true kalau belum upload ttd
        @endphp
        <a href="{{ route('dashboardRw') }}"
            class="px-4 py-2 rounded-lg font-medium transition-all duration-200
            hover:bg-green-100 hover:text-green-700 hover:shadow-md
            {{ request()->routeIs('dashboardRw') ? 'bg-green-200 text-green-700' : 'text-gray-700' }}">
            🏠 Dashboard
        </a>

        <div>
            <button
            @if($disabled)
                class="px-4 py-2 rounded-lg font-medium transition-all duration-200 opacity-50 cursor-not-allowed"
                tabindex="-1"
                onclick="return false;"
            @else
                class="w-full text-left px-4 py-2 rounded-lg font-medium transition-all duration-200
                        hover:bg-green-100 hover:text-green-700 hover:shadow-md text-gray-700"
                onclick="document.getElementById('submenu-verifikasi').classList.toggle('hidden')"
            @endif>
                👤 Verifikasi Akun RT
            </button>
            <div id="submenu-verifikasi" class="mt-1 space-y-1 {{ request()->routeIs('manajemenAkunRt') || request()->routeIs('akunRT') ? '' : 'hidden' }}">
                <a href="{{ route('manajemenAkunRt') }}"
                    class="block pl-8 py-1.5 rounded-lg font-medium text-sm transition-all duration-200
                            hover:bg-blue-100 hover:text-blue-700
                            {{ request()->routeIs('manajemenAkunRt') ? 'bg-blue-100 text-blue-700' : 'text-gray-700' }}">
                    Manajemen Akun RT
                </a>
                <a href="{{ route('akunRT') }}"
                    class="block pl-8 py-1.5 rounded-lg font-medium text-sm transition-all duration-200
                            hover:bg-blue-100 hover:text-blue-700
                            {{ request()->routeIs('akunRT') ? 'bg-blue-100 text-blue-700' : 'text-gray-700' }}">
                    Akun RT
                </a>
            </div>
        </div>

        <a href="{{ route('manajemenSuratWarga') }}"
        @if($disabled)
            class="px-4 py-2 rounded-lg font-medium transition-all duration-200 opacity-50 cursor-not-allowed"
            tabindex="-1"
            onclick="return false;"
        @else
            class="px-4 py-2 rounded-lg font-medium transition-all duration-200
            hover:bg-green-100 hover:text-green-700 hover:shadow-md
            {{ request()->routeIs('manajemenSuratWarga') ? 'bg-green-200 text-green-700' : 'text-gray-700' }}"
        @endif>
            📄 Manajemen Surat
        </a>

        <a href="{{ route('riwayatSuratRw') }}"
        @if($disabled)
            class="px-4 py-2 rounded-lg font-medium transition-all duration-200 opacity-50 cursor-not-allowed"
            tabindex="-1"
            onclick="return false;"
        @else
            class="px-4 py-2 rounded-lg font-medium transition-all duration-200
            hover:bg-green-100 hover:text-green-700 hover:shadow-md
            {{ request()->routeIs('riwayatSuratRw') ? 'bg-green-200 text-green-700' : 'text-gray-700' }}"
        @endif>
            📜 Riwayat Surat
        </a>

        <a href="{{ route('suratPengantar') }}"
        @if($disabled)
            class="px-4 py-2 rounded-lg font-medium transition-all duration-200 opacity-50 cursor-not-allowed"
            tabindex="-1"
            onclick="return false;"
        @else
            class="px-4 py-2 rounded-lg font-medium transition-all duration-200
            hover:bg-green-100 hover:text-green-700 hover:shadow-md
            {{ request()->routeIs('suratPengantar') ? 'bg-green-200 text-green-700' : 'text-gray-700' }}"
        @endif>
            🖋️ Template Surat
        </a>

        <a href="{{ route('tujuanSurat') }}"
        @if($disabled)
            class="px-4 py-2 rounded-lg font-medium transition-all duration-200 opacity-50 cursor-not-allowed"
            tabindex="-1"
            onclick="return false;"
        @else
            class="px-4 py-2 rounded-lg font-medium transition-all duration-200
            hover:bg-green-100 hover:text-green-700 hover:shadow-md
            {{ request()->routeIs('tujuanSurat') ? 'bg-green-200 text-green-700' : 'text-gray-700' }}"
        @endif>
            📬 Kelola Tujuan Surat
        </a>

        <a href="{{ route('profileRw') }}"
            class="px-4 py-2 rounded-lg font-medium transition-all duration-200
            hover:bg-green-100 hover:text-green-700 hover:shadow-md
            {{ request()->routeIs('profileRw') ? 'bg-green-200 text-green-700' : 'text-gray-700' }}">
            ✒️ TTD Digital
        </a>

        <a href="{{ route('logout') }}"
            class="mt-8 px-4 py-2 rounded-lg font-medium text-red-600 transition-all duration-200
                    hover:bg-red-100 hover:text-red-700 hover:shadow-md active:bg-red-200">
            🚪 Logout
        </a>
    </nav>
</div>

<aside id="sidebar" class="w-64 bg-white bg-opacity-90 shadow-md flex-col fixed inset-y-0 left-0 top-0 transform -translate-x-full transition-transform duration-200 ease-in-out md:relative md:translate-x-0 md:flex z-40">
    <nav class="flex flex-col gap-4 text-gray-700 pt-20 p-6">
@php
    $disabled = $showModalUploadTtd; // true kalau belum upload ttd
@endphp

        {{-- Dashboard --}}
        <a href="{{ route('dashboardRt') }}"
           class="px-4 py-2 rounded-lg font-medium transition-all duration-200
                  hover:bg-blue-100 hover:text-blue-700 hover:shadow-md
                  {{ request()->routeIs('dashboardRt') ? 'bg-blue-200 text-blue-700' : 'text-gray-700' }}">
            🏠 Dashboard
        </a>

        {{-- Verifikasi Akun (dengan anak submenu) --}}
        <div>
            <button
            @if($disabled)
                class="px-4 py-2 rounded-lg font-medium transition-all duration-200 opacity-50 cursor-not-allowed"
                tabindex="-1"
                onclick="return false;"
            @else
                class="w-full text-left px-4 py-2 rounded-lg font-medium transition-all duration-200
                       hover:bg-blue-100 hover:text-blue-700 hover:shadow-md text-gray-700"
                onclick="document.getElementById('submenu-verifikasi').classList.toggle('hidden')"
            @endif>
                👤 Verifikasi Akun
            </button>
            <div id="submenu-verifikasi" class="mt-1 space-y-1 {{ request()->routeIs('verifikasiAkunWarga') || request()->routeIs('historiVerifikasiAkunWarga') || request()->routeIs('historiAkunKadaluwarsa') ? '' : 'hidden' }}">
                <a href="{{ route('verifikasiAkunWarga') }}"
                   class="block pl-8 py-1.5 rounded-lg font-medium text-sm transition-all duration-200
                          hover:bg-yellow-100 hover:text-yellow-700
                          {{ request()->routeIs('verifikasiAkunWarga') ? 'bg-yellow-100 text-yellow-700' : 'text-gray-700' }}">
                    Verifikasi Akun Warga
                </a>
                <a href="{{ route('historiVerifikasiAkunWarga') }}"
                   class="block pl-8 py-1.5 rounded-lg font-medium text-sm transition-all duration-200
                          hover:bg-yellow-100 hover:text-yellow-700
                          {{ request()->routeIs('historiVerifikasiAkunWarga') ? 'bg-yellow-100 text-yellow-700' : 'text-gray-700' }}">
                    Histori Verifikasi
                </a>
                <a href="{{ route('historiAkunKadaluwarsa') }}"
                   class="block pl-8 py-1.5 rounded-lg font-medium text-sm transition-all duration-200
                          hover:bg-yellow-100 hover:text-yellow-700
                          {{ request()->routeIs('historiAkunKadaluwarsa') ? 'bg-yellow-100 text-yellow-700' : 'text-gray-700' }}">
                    Histori Akun Expired
                </a>
            </div>
        </div>

        {{-- Verifikasi Surat --}}
        <a href="{{ route('verifikasiSurat') }}"
        @if($disabled)
            class="px-4 py-2 rounded-lg font-medium transition-all duration-200 opacity-50 cursor-not-allowed"
            tabindex="-1"
            onclick="return false;"
        @else
            class="px-4 py-2 rounded-lg font-medium transition-all duration-200
                    hover:bg-blue-100 hover:text-blue-700 hover:shadow-md
                    {{ request()->routeIs('verifikasiSurat') ? 'bg-blue-200 text-blue-700' : 'text-gray-700' }}"
        @endif>
            📝 Verifikasi Surat
        </a>

        {{-- Riwayat Surat --}}
        <a href="{{ route('riwayatSuratWarga') }}"
        @if($disabled)
            class="px-4 py-2 rounded-lg font-medium transition-all duration-200 opacity-50 cursor-not-allowed"
            tabindex="-1"
            onclick="return false;"
        @else
            class="px-4 py-2 rounded-lg font-medium transition-all duration-200
                hover:bg-blue-100 hover:text-blue-700 hover:shadow-md
                {{ request()->routeIs('riwayatSuratWarga') ? 'bg-blue-200 text-blue-700' : 'text-gray-700' }}"
        @endif>
            📜 Riwayat Surat
        </a>

        {{-- Bank Data KK --}}
        <a href="{{ route('bankDataKk') }}"
        @if($disabled)
            class="px-4 py-2 rounded-lg font-medium transition-all duration-200 opacity-50 cursor-not-allowed"
            tabindex="-1"
            onclick="return false;"
        @else
        class="px-4 py-2 rounded-lg font-medium transition-all duration-200
                hover:bg-blue-100 hover:text-blue-700 hover:shadow-md
                {{ request()->routeIs('bankDataKk') ? 'bg-blue-200 text-blue-700' : 'text-gray-700' }}"
        @endif>
            📂 Bank Data KK
        </a>

        {{-- Scan TTD --}}
        <a href="{{ route('profileRt') }}"
            class="px-4 py-2 rounded-lg font-medium transition-all duration-200
                    hover:bg-blue-100 hover:text-blue-700 hover:shadow-md
                    {{ request()->routeIs('profileRt') ? 'bg-blue-200 text-blue-700' : 'text-gray-700' }}">
            ✒️ TTD Digital
        </a>

        {{-- Logout --}}
        <a href="{{ route('logout') }}"
            class="mt-8 px-4 py-2 rounded-lg font-medium text-red-600 transition-all duration-200
                    hover:bg-red-100 hover:text-red-700 hover:shadow-md active:bg-red-200">
            🚪 Logout
        </a>

    </nav>
</aside>

<nav class="bg-yellow-400 p-6 shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto flex justify-between items-center text-white">
        <a href="{{ route('dashboardWarga') }}" class="font-bold text-base md:text-lg whitespace-nowrap">
            🧾 Surat Digital RT/RW
        </a>

        <div class="hidden md:flex items-center gap-4 relative">
            {{-- <a href="{{ route('pengajuanSuratWarga') }}" class="text-white hover:underline font-semibold">Ajukan Surat</a>
            <a href="{{ route('riwayatSurat') }}" class="text-white hover:underline font-semibold">Riwayat Surat</a>
            <a href="{{ route('historiSuratWarga') }}" class="text-white hover:underline font-semibold">Histori Surat</a> --}}

            @if ($dataBelumLengkap)
                <span class="text-gray-400 cursor-not-allowed font-semibold" title="Lengkapi data diri terlebih dahulu">Ajukan Surat</span>
                <span class="text-gray-400 cursor-not-allowed font-semibold" title="Lengkapi data diri terlebih dahulu">Riwayat Surat</span>
                <span class="text-gray-400 cursor-not-allowed font-semibold" title="Lengkapi data diri terlebih dahulu">Histori Surat</span>
            @else
                <a href="{{ route('pengajuanSuratWarga') }}" class="text-white hover:underline font-semibold">Ajukan Surat</a>
                <a href="{{ route('riwayatSurat') }}" class="text-white hover:underline font-semibold">Riwayat Surat</a>
                <a href="{{ route('historiSuratWarga') }}" class="text-white hover:underline font-semibold">Histori Surat</a>
            @endif

            <div class="relative">
                <button id="notifButton" onclick="toggleNotif()" class="relative hover:opacity-80 hover:scale-110 transition-all duration-200">
                    <img src="{{ asset('images/notification2.png') }}" class="w-6 h-6" alt="Notif" />
                    <!-- Badge Desktop -->
                    @if($totalNotifBaru > 0)
                        <span id="notifBadge" class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">
                            {{ $totalNotifBaru }}
                        </span>
                    @endif
                </button>
                <div id="notifDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white text-black rounded shadow-lg z-50 max-h-96 overflow-auto">
                    @if($notifikasi->count() > 0)
                        @foreach($notifikasi as $notif)
                            @php
                                $isHasil = $notif instanceof \App\Models\HasilSuratTtdRw;
                                $isUnread = !$notif->is_read;
                            @endphp
                            <div class="border-b border-gray-300 p-2 hover:bg-yellow-100 cursor-pointer {{ $isUnread ? 'bg-yellow-200 font-semibold' : '' }}"
                                data-id="{{ $isHasil ? $notif->id_hasil_surat_ttd_rw : ($notif->id_pengajuan_surat ?? $notif->id_pengajuan_surat_lain) }}"
                                data-type="{{ $isHasil ? 'hasil' : (isset($notif->id_pengajuan_surat) ? 'biasa' : 'lain') }}">
                                @if($isHasil)
                                    @php
                                        $tujuan = $notif->pengajuanSurat->tujuanSurat->nama_tujuan ?? ($notif->pengajuanSuratLain->tujuan_manual ?? 'Surat');
                                    @endphp
                                    <p class="text-sm">
                                        Surat <strong>{{ $tujuan }}</strong> Anda telah <span class="text-green-600 font-bold">selesai diproses</span>
                                    </p>
                                    <p class="text-xs text-gray-500 mb-1">
                                        {{ $notif->created_at->diffForHumans() }}
                                    </p>
                                    <a href="{{ route('historiSuratWarga') }}" class="text-blue-600 text-sm hover:underline">Lihat surat</a>
                                @else
                                    @php
                                        $status_rt = $notif->status_rt ?? $notif->status_rt_pengajuan_lain ?? null;
                                        $status_rw = $notif->status_rw ?? $notif->status_rw_pengajuan_lain ?? null;

                                        $tujuan = $notif->tujuanSurat->nama_tujuan ?? ($notif->tujuan_manual ?? 'Surat');
                                        $isDisetujuiRt = $status_rt === 'disetujui';
                                        $isDitolakRt = $status_rt === 'ditolak';
                                        $isDisetujuiRw = $status_rw === 'disetujui';
                                        $isDitolakRw = $status_rw === 'ditolak';
                                    @endphp
                                    <p class="text-sm">
                                        @if($isDisetujuiRw)
                                            Surat pengajuan dengan tujuan <strong>{{ $tujuan }}</strong> telah
                                            <span class="text-green-600 font-bold">selesai diproses</span> dan sudah dapat <strong>dilihat atau diunduh</strong>.
                                        @elseif($isDitolakRw)
                                            Surat pengajuan dengan tujuan <strong>{{ $tujuan }}</strong> telah
                                            <span class="text-red-600 font-bold">ditolak oleh RW</span>
                                        @elseif($isDisetujuiRt)
                                            Surat pengajuan dengan tujuan <strong>{{ $tujuan }}</strong> telah
                                            <span class="text-green-600 font-bold">disetujui oleh RT</span>
                                        @elseif($isDitolakRt)
                                            Surat pengajuan dengan tujuan <strong>{{ $tujuan }}</strong> telah
                                            <span class="text-red-600 font-bold">ditolak oleh RT</span>
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-500 mb-1">
                                        {{ $notif->created_at->diffForHumans() }}
                                    </p>
                                    <a href="{{ route('riwayatSurat') }}" class="text-blue-600 text-sm hover:underline">Lihat selengkapnya</a>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="p-2 text-center text-gray-600">
                            Tidak ada notifikasi
                        </div>
                    @endif
                </div>
            </div>

            <!-- Profil Dropdown -->
            <div class="relative">
                <button onclick="toggleProfile()" class="flex items-center gap-2 hover:opacity-80 transition-all duration-200">
                    <img src="{{ $warga->profile_warga ? asset('storage/profile_warga/' . $warga->profile_warga) : asset('images/profile2.png') }}" class="w-8 h-8 rounded-full object-cover" alt="Profile" />
                    <svg id="arrowIcon" class="w-4 h-4 transition-transform duration-300 transform rotate-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div id="profileDropdown" class="hidden absolute right-0 mt-2 bg-white text-gray-800 rounded shadow-md w-40 z-50">
                    <a href="{{ route('profileWarga') }}" class="block px-4 py-2 hover:bg-yellow-100">Profil</a>
                    <a href="{{ route('logout') }}" class="block px-4 py-2 hover:bg-yellow-100">Logout</a>
                </div>
            </div>

        </div>

        <!-- Mobile Hamburger -->
        <div class="md:hidden relative">
            <button onclick="toggleMenu()" class="focus:outline-none">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <div id="mobileMenu" class="hidden absolute right-0 mt-2 bg-white text-gray-800 rounded shadow-md w-80 max-h-[400px] overflow-auto z-50">
                <div class="border-b border-gray-300 p-2">
                    <button id="notifButtonMobile" onclick="toggleNotifMobile()" class="relative w-full text-left hover:bg-yellow-100 p-1 rounded flex items-center gap-2">
                        <span>🔔 Notifikasi</span>
                        <!-- Badge Mobile -->
                        @if($totalNotifBaru > 0)
                            <span id="notifBadgeMobile" class="ml-auto inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">
                                {{ $totalNotifBaru }}
                            </span>
                        @endif
                    </button>
                    <div id="notifDropdownMobile" class="hidden mt-2 max-h-60 overflow-auto">
                        @if($notifikasi->count() > 0)
                        @foreach($notifikasi as $notif)
                            @php
                                $isHasil = $notif instanceof \App\Models\HasilSuratTtdRw;
                                $isUnread = !$notif->is_read;
                            @endphp
                                <div class="border-b border-gray-300 p-2 hover:bg-yellow-100 cursor-pointer"
                                    data-id="{{ $isHasil ? $notif->id_hasil_surat_ttd_rw : ($notif->id_pengajuan_surat ?? $notif->id_pengajuan_surat_lain) }}"
                                    data-type="{{ $isHasil ? 'hasil' : (isset($notif->id_pengajuan_surat) ? 'biasa' : 'lain') }}">
                                    @if($isHasil)
                                        @php
                                            $tujuan = $notif->pengajuanSurat->tujuanSurat->nama_tujuan ?? ($notif->pengajuanSuratLain->tujuan_manual ?? 'Surat');
                                        @endphp
                                        <p class="text-sm">
                                            Surat <strong>{{ $tujuan }}</strong> Anda telah <span class="text-green-600 font-bold">selesai diproses</span>
                                        </p>
                                        <p class="text-xs text-gray-500 mb-1">
                                            {{ $notif->created_at->diffForHumans() }}
                                        </p>
                                        <a href="{{ route('historiSuratWarga') }}" class="text-blue-600 text-sm hover:underline">Lihat surat</a>
                                    @else
                                        @php
                                            $status = $notif->status_rt ?? $notif->status_rt_pengajuan_lain ?? null;
                                            $tujuan = $notif->tujuanSurat->nama_tujuan ?? ($notif->tujuan_manual ?? 'Surat Lain');
                                            $statusText = $status === 'disetujui' ? 'disetujui' : 'ditolak';
                                        @endphp
                                        <p class="text-sm">
                                            Surat pengajuan dengan tujuan <strong>{{ $tujuan }}</strong> telah
                                            <span class="{{ $status === 'disetujui' ? 'text-green-600 font-bold' : 'text-red-600 font-bold' }}">{{ $statusText }}</span> oleh RT
                                        </p>
                                        <p class="text-xs text-gray-500 mb-1">
                                            {{ $notif->created_at->diffForHumans() }}
                                        </p>
                                        <a href="{{ route('riwayatSurat') }}" class="text-blue-600 text-sm hover:underline">Lihat selengkapnya</a>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <div class="p-2 text-center text-gray-600">
                                Tidak ada notifikasi
                            </div>
                        @endif
                    </div>
                </div>

                <a href="{{ route('profileWarga') }}" class="block px-4 py-2 hover:bg-gray-100 flex items-center gap-2">
                    @if($warga->profile_warga)
                        <img src="{{ asset('storage/profile_warga/' . $warga->profile_warga) }}" class="w-8 h-8 rounded-full object-cover" alt="Profile" />
                    @else
                        <span class="text-xl">👤</span>
                    @endif
                    Profil
                </a>
                <a href="{{ route('logout') }}" class="block px-4 py-2 hover:bg-gray-100 flex items-center gap-2">
                    🚪 Logout
                </a>
            </div>
        </div>
    </div>
</nav>

<script>
function toggleNotif() {
    const notif = document.getElementById('notifDropdown');
    notif.classList.toggle('hidden');

    if (!notif.classList.contains('hidden')) {
        const notifIds = [];
        const notifTypes = [];

        document.querySelectorAll('#notifDropdown > div[data-id][data-type]').forEach(el => {
            const id = el.getAttribute('data-id');
            const type = el.getAttribute('data-type');
            if (id && type) {
                notifIds.push(id);
                notifTypes.push(type);
            }
        });

        notifIds.forEach((id, idx) => {
            fetch('{{ route("notifikasi.markAsRead") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    id: id,
                    type: notifTypes[idx],
                }),
            });
        });

        const badge = document.getElementById('notifBadge');
        if (badge) badge.style.display = 'none';
    }
}

function toggleNotifMobile() {
    const notif = document.getElementById('notifDropdownMobile');
    notif.classList.toggle('hidden');

    if (!notif.classList.contains('hidden')) {
        const notifIds = [];
        const notifTypes = [];

        document.querySelectorAll('#notifDropdownMobile > div[data-id][data-type]').forEach(el => {
            const id = el.getAttribute('data-id');
            const type = el.getAttribute('data-type');
            if (id && type) {
                notifIds.push(id);
                notifTypes.push(type);
            }
        });

        notifIds.forEach((id, idx) => {
            fetch('{{ route("notifikasi.markAsRead") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    id: id,
                    type: notifTypes[idx],
                }),
            });
        });

        const badge = document.getElementById('notifBadgeMobile');
        if (badge) badge.style.display = 'none';
    }
}

function toggleMenu() {
    const mobileMenu = document.getElementById('mobileMenu');
    mobileMenu.classList.toggle('hidden');
}

document.addEventListener('click', function(event) {
        const notifButton = document.getElementById('notifButton');
        const notifDropdown = document.getElementById('notifDropdown');

        const notifButtonMobile = document.getElementById('notifButtonMobile');
        const notifDropdownMobile = document.getElementById('notifDropdownMobile');

        // Untuk desktop
        if (!notifDropdown.classList.contains('hidden') && !notifDropdown.contains(event.target) && !notifButton.contains(event.target)) {
            notifDropdown.classList.add('hidden');
        }

        // Untuk mobile
        if (!notifDropdownMobile.classList.contains('hidden') && !notifDropdownMobile.contains(event.target) && !notifButtonMobile.contains(event.target)) {
            notifDropdownMobile.classList.add('hidden');
        }
    });

    function toggleProfile() {
    const dropdown = document.getElementById('profileDropdown');
    const arrowIcon = document.getElementById('arrowIcon');

    dropdown.classList.toggle('hidden');

    if (dropdown.classList.contains('hidden')) {
        arrowIcon.classList.remove('rotate-180');
    } else {
        arrowIcon.classList.add('rotate-180');
    }
}

// Tutup dropdown saat klik di luar
document.addEventListener('click', function (event) {
    const profileDropdown = document.getElementById('profileDropdown');
    const profileButton = document.querySelector('button[onclick="toggleProfile()"]');

    if (profileDropdown && !profileDropdown.contains(event.target) && !profileButton.contains(event.target)) {
        profileDropdown.classList.add('hidden');
        document.getElementById('arrowIcon').classList.remove('rotate-180');
    }
});

</script>


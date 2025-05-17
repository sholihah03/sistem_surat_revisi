<nav class="bg-yellow-400 p-6 shadow-md sticky top-0 z-50">
  <div class="max-w-7xl mx-auto flex justify-between items-center text-white">
    <a href="{{ route('dashboardWarga') }}" class="font-bold text-base md:text-lg whitespace-nowrap">
      🧾 Surat Digital RT/RW
    </a>

    <div class="hidden md:flex items-center gap-4 relative">
      <div class="relative">
        <button id="notifButton" onclick="toggleNotif()" class="relative hover:opacity-80 hover:scale-110 transition-all duration-200">
          <img src="{{ asset('images/notification2.png') }}" class="w-6 h-6" alt="Notif" />
          <!-- Badge Desktop -->
            @if(isset($notifikasi) && $notifikasi->count() > 0)
            <span id="notifBadge" class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full" style="display: none;">
            {{ $notifikasi->count() }}
            </span>
            @endif
        </button>

        <div id="notifDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white text-black rounded shadow-lg z-50 max-h-96 overflow-auto">
          @if(isset($notifikasi) && $notifikasi->count() > 0)
            @foreach($notifikasi as $notif)
              <div class="border-b border-gray-300 p-2 hover:bg-yellow-100 cursor-pointer">
                @php
                  $status = $notif->status ?? $notif->status_pengajuan_lain ?? null;
                  $tujuan = $notif->tujuanSurat->nama_tujuan ?? ($notif->tujuan_manual ?? 'Surat Lain');
                  $statusText = $status === 'disetujui' ? 'disetujui' : 'ditolak';
                @endphp
                <p class="text-sm">
                  Surat pengajuan dengan tujuan <strong>{{ $tujuan }}</strong> telah
                  <span class="{{ $status === 'disetujui' ? 'text-green-600 font-bold' : 'text-red-600 font-bold' }}">{{ $statusText }}</span>
                </p>
                <p class="text-xs text-gray-500 mb-1">
                  {{ $notif->updated_at->diffForHumans() }}
                </p>
                <a href="{{ route('historiSuratWarga') }}" class="text-blue-600 text-sm hover:underline">Lihat selengkapnya</a>
              </div>
            @endforeach
          @else
            <div class="p-2 text-center text-gray-600">
              Tidak ada notifikasi
            </div>
          @endif
        </div>
      </div>

      <button class="hover:opacity-80 hover:scale-110 transition-all duration-200">
        <img src="{{ asset('images/profile2.png') }}" class="w-6 h-6" alt="Profile" />
      </button>
      <a href="{{ route('logout') }}" class="text-base no-underline font-semibold hover:scale-105 hover:text-gray-200 transition-all duration-200">
        Logout
      </a>
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
            @if(isset($notifikasi) && $notifikasi->count() > 0)
            <span id="notifBadgeMobile" class="ml-auto inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full" style="display: none;">
            {{ $notifikasi->count() }}
            </span>
            @endif
          </button>
          <div id="notifDropdownMobile" class="hidden mt-2 max-h-60 overflow-auto">
            @if(isset($notifikasi) && $notifikasi->count() > 0)
              @foreach($notifikasi as $notif)
                <div class="border-b border-gray-300 p-2 hover:bg-yellow-100 cursor-pointer">
                  @php
                    $status = $notif->status ?? $notif->status_pengajuan_lain ?? null;
                    $tujuan = $notif->tujuanSurat->nama_tujuan ?? ($notif->tujuan_manual ?? 'Surat Lain');
                    $statusText = $status === 'disetujui' ? 'disetujui' : 'ditolak';
                  @endphp
                  <p class="text-sm">
                    Surat pengajuan dengan tujuan <strong>{{ $tujuan }}</strong> telah
                    <span class="{{ $status === 'disetujui' ? 'text-green-600 font-bold' : 'text-red-600 font-bold' }}">{{ $statusText }}</span>
                  </p>
                  <p class="text-xs text-gray-500 mb-1">
                    {{ $notif->updated_at->diffForHumans() }}
                  </p>
                  <a href="{{ route('historiSuratWarga') }}" class="text-blue-600 text-sm hover:underline">Lihat selengkapnya</a>
                </div>
              @endforeach
            @else
              <div class="p-2 text-center text-gray-600">
                Tidak ada notifikasi
              </div>
            @endif
          </div>
        </div>

        <a href="#" class="block px-4 py-2 hover:bg-gray-100 flex items-center gap-2">👤 Profil
          <img src="{{ asset('images/profile2.png') }}" class="w-6 h-6" alt="Profile" />
        </a>
        <a href="{{ route('logout') }}" class="block px-4 py-2 hover:bg-gray-100 flex items-center gap-2">
          🚪 Logout
        </a>
      </div>
    </div>
  </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const notifCount = {{ $notifikasi->count() ?? 0 }};
  const lastSeenKey = 'lastSeenNotifCount';
  const badge = document.getElementById('notifBadge');
  const badgeMobile = document.getElementById('notifBadgeMobile');

  const lastSeen = parseInt(localStorage.getItem(lastSeenKey) || '0');
  const newNotif = notifCount - lastSeen;

  // Tampilkan badge hanya jika ada notifikasi baru
  if (newNotif > 0) {
    if (badge) {
      badge.style.display = 'inline-flex';
      badge.textContent = newNotif;
    }
    if (badgeMobile) {
      badgeMobile.style.display = 'inline-flex';
      badgeMobile.textContent = newNotif;
    }
  }
});

function toggleMenu() {
  const menu = document.getElementById('mobileMenu');
  menu.classList.toggle('hidden');
}

function toggleNotif() {
  const notif = document.getElementById('notifDropdown');
  const badge = document.getElementById('notifBadge');
  const notifCount = {{ $notifikasi->count() ?? 0 }};
  const lastSeenKey = 'lastSeenNotifCount';

  const isOpen = !notif.classList.contains('hidden');
  notif.classList.toggle('hidden');

  if (!isOpen) {
    if (badge) badge.style.display = 'none';
    localStorage.setItem(lastSeenKey, notifCount);
  }
}

function toggleNotifMobile() {
  const notif = document.getElementById('notifDropdownMobile');
  const badge = document.getElementById('notifBadgeMobile');
  const notifCount = {{ $notifikasi->count() ?? 0 }};
  const lastSeenKey = 'lastSeenNotifCount';

  const isOpen = !notif.classList.contains('hidden');
  notif.classList.toggle('hidden');

  if (!isOpen) {
    if (badge) badge.style.display = 'none';
    localStorage.setItem(lastSeenKey, notifCount);
  }
}

document.addEventListener('click', function(event) {
  const notifDropdown = document.getElementById('notifDropdown');
  const notifButton = document.getElementById('notifButton');
  const notifDropdownMobile = document.getElementById('notifDropdownMobile');
  const notifButtonMobile = document.getElementById('notifButtonMobile');

  // Klik di luar dropdown desktop
  if (notifDropdown && notifButton) {
    if (!notifDropdown.contains(event.target) && !notifButton.contains(event.target)) {
      notifDropdown.classList.add('hidden');
    }
  }

  // Klik di luar dropdown mobile
  if (notifDropdownMobile && notifButtonMobile) {
    if (!notifDropdownMobile.contains(event.target) && !notifButtonMobile.contains(event.target)) {
      notifDropdownMobile.classList.add('hidden');
    }
  }
});
</script>

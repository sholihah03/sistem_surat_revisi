<nav class="bg-yellow-400 p-6 shadow-md sticky top-0 z-50">

    <div class="max-w-7xl mx-auto flex justify-between items-center text-white">

      <a href="{{ route('dashboardWarga') }}" class="font-bold text-base md:text-lg whitespace-nowrap">
        🧾 Surat Digital RT/RW
      </a>

      <!-- Desktop Icon -->
      <div class="hidden md:flex items-center gap-4">
        <button class="hover:opacity-80 hover:scale-110 transition-all duration-200">
          <img src="{{ asset('images/notification2.png') }}" class="w-6 h-6" alt="Notif" />
        </button>
        <button class="hover:opacity-80 hover:scale-110 transition-all duration-200">
          <img src="{{ asset('images/profile2.png') }}" class="w-6 h-6" alt="Profile" />
        </button>
        <a href="#" class="text-base no-underline font-semibold hover:scale-105 hover:text-gray-200 transition-all duration-200">
            Logout</a>
      </div>

      <!-- Mobile Hamburger -->
      <div class="md:hidden relative">
        <button onclick="toggleMenu()" class="focus:outline-none">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>

        <!-- Dropdown Menu -->
        <div id="mobileMenu" class="hidden absolute right-0 mt-2 bg-white text-gray-800 rounded shadow-md w-40 z-50">
          <a href="#" class="block px-4 py-2 hover:bg-gray-100">🔔 Notifikasi</a>
          <a href="#" class="block px-4 py-2 hover:bg-gray-100">👤 Profil</a>
          <a href="#" class="block px-4 py-2 hover:bg-gray-100">🚪 Logout</a>
        </div>
      </div>
    </div>
  </nav>

  <script>
    function toggleMenu() {
      const menu = document.getElementById('mobileMenu');
      menu.classList.toggle('hidden');
    }
  </script>

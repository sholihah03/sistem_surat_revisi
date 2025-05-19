<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard RW</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>


    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
        theme: {
            extend: {
            fontFamily: {
                inter: ['Inter', 'sans-serif'],
            },
            }
        }
        }
    </script>

    <style>
        @media print {
            @page { size: A4; margin: 1in; }
            body { margin: 0; }
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-green-100 via-white to-yellow-100 font-inter">
    <!-- Navbar -->
    <header class="fixed top-0 left-0 right-0 bg-white shadow-md p-4 flex justify-between items-center z-50">
        <h2 class="text-xl font-bold text-green-700">Dashboard RW</h2>

        <!-- Right section -->
        <div class="flex items-center gap-4">
            <!-- Notification Icon -->
            <div x-data="{ open: false }" class="relative">
                <!-- Tombol Notifikasi -->
                <button @click="open = !open" class="relative focus:outline-none">
                    <!-- Icon Lonceng -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700 hover:text-green-700" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C8.67 6.165 8 7.388 8 9v5.159c0 .538-.214 1.055-.595 1.436L6 17h5m4 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>

                    <!-- Badge Notifikasi -->
                    @if($totalNotifRw > 0)
                    <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold rounded-full px-1">
                        {{ $totalNotifRw }}
                    </span>
                    @endif
                </button>

                <!-- Isi Dropdown Notifikasi -->
                <div x-show="open" @click.outside="open = false" x-transition
                    class="absolute right-0 mt-2 w-72 bg-white rounded-lg shadow-lg z-50">
                    <div class="p-4 text-sm text-gray-800">
                        <p class="font-semibold">🔔 Notifikasi</p>
                        <hr class="my-2">

                        @foreach($pengajuanSuratBaru as $surat)
                            <p>
                                Surat: <strong>{{ $surat->tujuanSurat->nama_tujuan ?? 'Nama Surat Tidak Ada' }}</strong><br>
                                Warga: <strong>{{ $surat->warga->nama_lengkap ?? '-' }}</strong><br>
                                RT: <strong>{{ $surat->warga->rt->no_rt ?? '-' }}</strong><br>
                                (Sudah disetujui RT)<br>
                                <a href="{{ route('manajemenSuratWarga') }}" class="text-blue-600 hover:underline text-sm">Verifikasi Sekarang</a><br>
                            </p>
                            <hr>
                        @endforeach

                        @foreach($pengajuanSuratLainBaru as $surat)
                            <p>
                                Surat Lain: <strong>{{ $surat->tujuan_manual ?? 'Nama Surat Tidak Ada' }}</strong><br>
                                Warga: <strong>{{ $surat->warga->nama_lengkap ?? '-' }}</strong><br>
                                RT: <strong>{{ $surat->warga->rt->no_rt ?? '-' }}</strong><br>
                                (Sudah disetujui RT)<br>
                                <a href="{{ route('manajemenSuratWarga') }}" class="text-blue-600 hover:underline text-sm">Verifikasi Sekarang</a><br>
                            </p>
                            <hr>
                        @endforeach

                        @if($totalNotifRw == 0)
                            <p>Tidak ada notifikasi.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Profile Icon -->
            <a href="{{ route('profileRw') }}" class="rounded-full overflow-hidden w-8 h-8 bg-green-100 hover:ring-2 hover:ring-green-400 block">
                @if ($profile_rw)
                    <img src="{{ asset('storage/profile_rw/' . $profile_rw) }}" alt="Profile" class="w-full h-full object-cover">
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full text-green-700 p-1" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z"/>
                    </svg>
                @endif
            </a>

            <!-- Hamburger Mobile -->
            <button id="menu-button" class="text-2xl focus:outline-none md:hidden">
            ☰
            </button>
        </div>
    </header>

    <div class="flex md:pl-64 min-h-screen overflow-hidden">
        @include('rw.sidebarRw')
        <!-- Overlay for closing sidebar when clicking outside (mobile only) -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-30 z-30 hidden md:hidden"></div>
        <main class="flex-1 p-4 md:p-8 overflow-x-auto">
            @yield('content')
        </main>
    </div>

    <!-- Toggle Sidebar Script -->
    <script>
        const btn = document.getElementById('menu-button');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        btn.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        });
    </script>

</body>
</html>

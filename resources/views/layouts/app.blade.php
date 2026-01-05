<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard | Dapur Negriku')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif
        }

        .transition-width {
            transition: width .25s ease;
        }

        /* STYLE ITEM SIDEBAR */
        .sidebar-item {
            color: #6C7A40;
            transition: all .2s ease;
            position: relative;
            overflow: hidden;
        }

        .sidebar-item:hover {
            background-color: #E6E4D9;
        }

        .sidebar-item.active {
            font-weight: 600;
            background-color: #5B6541 !important;
        }

        .sidebar-item.active:hover {
            color: white !important;
            box-shadow: none !important;
        }

        /* STYLE UNTUK MOBILE */
        @media (max-width: 768px) {
            /* Sembunyikan sidebar desktop di mobile */
            .desktop-sidebar {
                display: none !important;
            }

            /* Sembunyikan logo di navbar mobile */
            .mobile-hide-logo {
                display: none !important;
            }

            /* Navbar mobile yang lebih compact */
            header {
                height: 70px !important;
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }

            /* Main content dengan padding untuk mobile */
            main {
                padding: 1rem !important;
            }

            /* Toggle button disembunyikan di mobile */
            button[aria-label="Toggle sidebar"] {
                display: none !important;
            }

            /* Mobile hamburger menu */
            .mobile-hamburger {
                display: flex !important;
                flex-direction: column;
                justify-content: space-between;
                width: 30px;
                height: 24px;
                cursor: pointer;
            }

            .mobile-hamburger span {
                display: block;
                height: 3px;
                width: 100%;
                background-color: #5B6541;
                border-radius: 3px;
                transition: all 0.3s ease;
            }

            /* Mobile sidebar */
            .mobile-sidebar {
                position: fixed;
                top: 0;
                left: -280px;
                width: 280px;
                height: 100vh;
                background: linear-gradient(to bottom, #F8F6F0, #ECE8DD);
                z-index: 50;
                overflow-y: auto;
                transition: left 0.3s ease;
                box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            }

            .mobile-sidebar.active {
                left: 0;
            }

            /* Overlay untuk mobile sidebar */
            .mobile-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 45;
                opacity: 0;
                visibility: hidden;
                transition: opacity 0.3s ease, visibility 0.3s ease;
            }

            .mobile-overlay.active {
                opacity: 1;
                visibility: visible;
            }

            /* Logo di mobile sidebar - SEJAJAR HORIZONTAL */
            .mobile-sidebar-logo {
                padding: 1.5rem;
                border-bottom: 1px solid rgba(91, 101, 65, 0.1);
                display: flex !important;
                align-items: center;
                gap: 1rem;
            }

            .mobile-sidebar-logo img {
                height: 50px;
                width: auto;
                display: block;
            }

            .mobile-sidebar-logo span {
                font-weight: 600;
                color: #5B6541;

            }

            /* Menu item di mobile sidebar - JARAK LEBIH RAPAT */
            .mobile-sidebar .sidebar-item {
                padding: 0.75rem 1.5rem !important;
                margin: 0.15rem 0.5rem !important;
                border-radius: 0.75rem !important;
            }

            .mobile-sidebar .sidebar-item.active {
                background-color: #A7B48A !important;
                color: white !important;
                border-radius: 0.75rem !important;
            }

            /* Gap antar menu lebih kecil */
            .mobile-sidebar nav {
                gap: 0.5rem !important;
            }
        }

        /* TABLET STYLES (768px - 1024px) */
        @media (min-width: 769px) and (max-width: 1024px) {
            /* Navbar tablet */
            header {
                padding-left: 1.5rem !important;
                padding-right: 1.5rem !important;
            }

            /* Logo di navbar tablet lebih kecil */
            header img {
                height: 60px !important;
            }

            /* Sembunyikan mobile sidebar logo di tablet */
            .mobile-sidebar-logo {
                display: none !important;
            }

            /* Sidebar tablet - lebih compact */
            .desktop-sidebar {
                width: 80px !important;
            }

            /* Hide text labels di sidebar tablet */
            .desktop-sidebar .sidebar-item span:not(.material-icons) {
                display: none !important;
            }

            /* Menu items di sidebar tablet - padding lebih kecil */
            .desktop-sidebar .sidebar-item {
                padding: 0.75rem !important;
                margin: 0.15rem 0.5rem !important;
                justify-content: center;
            }

            .desktop-sidebar .sidebar-item.active {
                padding-left: 0.75rem !important;
                padding-right: 0.75rem !important;
                margin-right: 0.5rem !important;
                border-radius: 0.75rem !important;
            }

            .desktop-sidebar .sidebar-item .material-icons {
                margin: 0 auto;
            }

            /* Main content lebih besar di tablet */
            main {
                padding: 1.5rem !important;
            }

            /* Sembunyikan hamburger di tablet */
            .mobile-hamburger {
                display: none !important;
            }

            /* Sembunyikan mobile sidebar di tablet */
            .mobile-sidebar,
            .mobile-overlay {
                display: none !important;
            }
        }

        /* DESKTOP STYLES (>1024px) */
        @media (min-width: 1025px) {
            /* Sembunyikan mobile sidebar logo di desktop */
            .mobile-sidebar-logo {
                display: none !important;
            }

            /* Sembunyikan semua mobile elements di desktop */
            .mobile-hamburger,
            .mobile-sidebar,
            .mobile-overlay {
                display: none !important;
            }
        }
    </style>
</head>

<body class="bg-[#F5F5F0]" x-data="{ sidebarOpen: false, desktopSidebarOpen: true }">

    <!-- OVERLAY UNTUK MOBILE SIDEBAR -->
    <div class="mobile-overlay"
         :class="{ 'active': sidebarOpen }"
         @click="sidebarOpen = false"></div>

    <div class="flex flex-col min-h-screen">

        <!-- NAVBAR -->
        <header class="h-20 bg-white shadow-md flex items-center justify-between px-6">
            <div class="flex items-center gap-3">
                <!-- HAMBURGER MENU UNTUK MOBILE -->
                <div class="mobile-hamburger" @click="sidebarOpen = !sidebarOpen">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>

                <!-- LOGO (DISEMBUNYIKAN DI MOBILE) -->
                <img src="/images/LogoDapurNegeriku.png" class="h-20 object-contain mobile-hide-logo" alt="Logo">
            </div>

            <div class="flex items-center gap-4">
                <div class="text-right">
                    <div class="text-sm font-semibold text-[#333]">
                        {{ Auth::user()->nama_pengguna ?? 'Admin' }}
                    </div>
                    <div class="text-xs text-gray-500">Admin</div>
                </div>

                @if(Auth::user() && Auth::user()->foto_pengguna)
                <img src="{{ asset('storage/' . Auth::user()->foto_pengguna) }}"
                    class="w-10 h-10 rounded-full object-cover">
                @else
                <img src="/images/user.png" class="w-10 h-10 rounded-full object-cover">
                @endif
            </div>
        </header>

        <!-- MAIN WRAPPER -->
        <div class="flex flex-1">

            <!-- SIDEBAR DESKTOP (KEMBALI SEPERTI SEMULA DENGAN LOGO) -->
            <aside x-data="{ open: desktopSidebarOpen }" :class="open ? 'w-64' : 'w-20'"
                class="desktop-sidebar relative bg-gradient-to-b from-[#F8F6F0] to-[#ECE8DD] transition-width flex flex-col border-r border-[#5B6541]/20">

                <!-- GARIS SEJAJAR DENGAN TOGGLE -->
                <div class="absolute top-14 left-0 right-0 h-[4px] bg-[#5B6541]/100"></div>

                <!-- TOGGLE BUTTON -->
                <button @click="open = !open; desktopSidebarOpen = !desktopSidebarOpen"
                    class="absolute -right-5 top-6 bg-white border border-[#5B6541]/20 rounded-full z-10 w-14 h-14 flex items-center justify-center transition-all duration-200">
                    <span x-show="open" class="material-icons text-[#5B6541] text-2xl transition-transform font-bold">
                        arrow_back
                    </span>
                    <span x-show="!open" class="material-icons text-[#5B6541] text-2xl transition-transform font-bold">
                        arrow_forward
                    </span>
                </button>

                <!-- LOGO AREA - KEMBALI SEPERTI SEMULA -->
                <div class="px-6 py-8 border-b border-[#5B6541]/10">
                    <div class="flex items-center gap-3">
                        <!-- Logo kembali ditampilkan -->
                    </div>
                </div>

                <!-- MENU - LAYOUT TETAP SAMA -->
                <nav class="mt-6 flex flex-col gap-1.5 flex-1">
                    @php
                    $menu = [
                    ['icon' => 'dashboard', 'label' => 'Dasbor', 'route' => 'dashboard'],
                    ['icon' => 'folder', 'label' => 'Proyek', 'route' => 'proyek.index'],
                    ['icon' => 'inventory_2', 'label' => 'Produk', 'route' => 'produk'],
                    ['icon' => 'miscellaneous_services', 'label' => 'Layanan', 'route' => 'layanan.index'],
                    ['icon' => 'campaign', 'label' => 'Banner', 'route' => 'banner.index'],
                    ['icon' => 'person', 'label' => 'Profil Klien', 'route' => 'klien.index'],
                    ['icon' => 'people', 'label' => 'Pengguna', 'route' => 'pengguna.index'],
                    ['icon' => 'contact_mail', 'label' => 'Info Kontak', 'route' => 'info-kontak'],
                    ['icon' => 'business', 'label' => 'Profil Perusahaan', 'route' => 'profil-perusahaan'],
                    ];
                    @endphp

                    @foreach ($menu as $item)
                    @php $isActive = request()->routeIs($item['route']); @endphp

                  <a href="{{ route($item['route']) }}" class="flex items-center gap-4 py-3 rounded-xl sidebar-item transition-all duration-200
          {{ $isActive
                ? 'active bg-[#A7B48A] text-white rounded-l-none rounded-r-3xl pl-0 pr-4 mr-4 shadow-md'
                : 'text-[#5B6541] px-4' }}">

                        <!-- ICON -->
                        <span class="material-icons text-xl {{ $isActive ? 'text-white pl-4' : '' }}">
                            {{ $item['icon'] }}
                        </span>

                        <!-- LABEL -->
                        <span x-show="open" class="transition-all duration-200
                 {{ $isActive ? 'text-white font-semibold text-base' : 'text-[#5B6541] font-medium text-sm' }}">
                            {{ $item['label'] }}
                        </span>

                    </a>

                    @endforeach

                    <!-- LOGOUT -->
                    <div class="mt-auto mb-6 px-4 pt-6 border-t border-[#5B6541]/10">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center gap-3 px-4 py-3
                               bg-gradient-to-r from-[#DC2626] to-[#EF4444] text-white
                               rounded-xl
                               transition-all duration-200 group">
                                <span class="material-icons text-lg ">
                                    logout
                                </span>
                                <span x-show="open" class="font-medium">Logout</span>
                            </button>
                        </form>
                    </div>

                </nav>

            </aside>

            <!-- MOBILE SIDEBAR -->
            <div class="mobile-sidebar" :class="{ 'active': sidebarOpen }">
                <!-- LOGO AREA - SEJAJAR HORIZONTAL -->
               <div class="mobile-sidebar-logo">
    <img src="/images/LogoDapurNegeriku.png" alt="Logo Dapur Negriku">
    <span class="text-[15px] font-semibold">Dapur Negriku</span>
</div

                <!-- MENU MOBILE - JARAK LEBIH RAPAT -->
                <nav class="flex flex-col gap-1 px-3 py-3">
                    @foreach ($menu as $item)
                    @php $isActive = request()->routeIs($item['route']); @endphp

                    <a href="{{ route($item['route']) }}"
                       @click="sidebarOpen = false"
                       class="flex items-center gap-3 py-2.5 sidebar-item transition-all duration-200
                       {{ $isActive ? 'active' : '' }}">
                        <span class="material-icons text-xl">
                            {{ $item['icon'] }}
                        </span>
                        <span class="font-medium text-sm">{{ $item['label'] }}</span>
                    </a>
                    @endforeach

                    <!-- LOGOUT MOBILE -->
                    <div class="mt-6 pt-5 border-t border-[#5B6541]/10">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                    @click="sidebarOpen = false"
                                    class="w-full flex items-center justify-center gap-3 px-4 py-3
                                           bg-gradient-to-r from-[#DC2626] to-[#EF4444] text-white
                                           rounded-xl transition-all duration-200">
                                <span class="material-icons text-lg">logout</span>
                                <span class="font-medium">Logout</span>
                            </button>
                        </form>
                    </div>
                </nav>
            </div>

            <!-- MAIN CONTENT -->
            <main class="flex-1 p-6 overflow-auto">
                @if(session('success'))
                {{--  <div class="mb-4 p-3 bg-green-100 border border-green-200 text-green-800 rounded">
                    {{ session('success') }}
                </div>  --}}
                @endif

                @yield('content')
            </main>

        </div>
    </div>

</body>
</html>

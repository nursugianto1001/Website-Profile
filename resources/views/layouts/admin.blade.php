<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <style>
        .sidebar {
            width: 240px;
            min-width: 240px;
            max-width: 240px;
        }

        .main-content {
            margin-left: 240px;
        }

        @media (max-width: 1024px) {
            .sidebar {
                width: 280px;
                min-width: 280px;
                max-width: 280px;
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-sidebar-hidden {
                transform: translateX(-100%);
            }

            .mobile-sidebar-visible {
                transform: translateX(0);
            }
        }

        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }

        /* Header fixed untuk mobile */
        .mobile-header {
            height: 64px;
        }

        .main-content-mobile {
            padding-top: 64px;
            padding-bottom: 60px;
        }

        /* Footer fixed untuk mobile */
        .mobile-footer {
            height: 60px;
        }

        /* Desktop header dan footer */
        .desktop-header {
            height: 70px;
        }

        .desktop-footer {
            height: 50px;
        }

        .main-content-desktop {
            padding-top: 70px;
            padding-bottom: 50px;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-50">
    <!-- Mobile Header -->
    <header class="lg:hidden fixed top-0 left-0 right-0 z-40 bg-white shadow-lg border-b border-gray-200 mobile-header">
        <div class="flex items-center justify-between h-full px-4">
            <!-- Menu Button -->
            <button onclick="toggleMobileSidebar()"
                class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition-colors">
                <i class="bi bi-list text-xl text-gray-700"></i>
            </button>

            <!-- Logo & Title -->
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                    <i class="bi bi-grid-fill text-white text-sm"></i>
                </div>
                <div class="text-center">
                    <h1 class="text-sm font-bold text-gray-800">Admin Panel</h1>
                    <p class="text-xs text-gray-500">Karvin Badminton</p>
                </div>
            </div>

            <!-- User Avatar -->
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-bold">
                    {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                </div>
            </div>
        </div>
    </header>

    <!-- Desktop Header -->
    <header class="hidden lg:block fixed top-0 right-0 z-30 bg-white shadow-lg border-b border-gray-200 desktop-header" style="left: 240px;">
        <div class="flex items-center justify-between h-full px-6">
            <!-- Page Title & Breadcrumb -->
            <div class="flex items-center space-x-4">
                <div>
                    @isset($header)
                    <h1 class="text-lg font-semibold text-gray-900">{{ $header }}</h1>
                    @else
                    <h1 class="text-lg font-semibold text-gray-900">Dashboard</h1>
                    @endisset
                    <p class="text-xs text-gray-500 mt-1">{{ now()->format('l, d F Y - H:i') }} WIB</p>
                </div>
            </div>

            <!-- Desktop Header Actions -->
            <div class="flex items-center space-x-4">
                <!-- User Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-bold">
                            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                        </div>
                        <div class="text-left">
                            <p class="text-sm font-medium text-gray-700">{{ Auth::user()->name ?? 'Admin' }}</p>
                            <p class="text-xs text-gray-500">Administrator</p>
                        </div>
                        <i class="bi bi-chevron-down text-gray-400 text-sm transition-transform duration-200" :class="{'rotate-180': open}"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 transform scale-100"
                        x-transition:leave-end="opacity-0 transform scale-95"
                        @click.away="open = false"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">

                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="bi bi-person mr-3"></i>Profile
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="bi bi-gear mr-3"></i>Settings
                        </a>
                        <hr class="my-1">
                        <form method="POST" action="{{ route('logout') }}" class="block">
                            @csrf
                            <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                <i class="bi bi-box-arrow-right mr-3"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Overlay -->
    <div id="mobileOverlay"
        class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden"
        onclick="closeMobileSidebar()">
    </div>

    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div id="sidebar" class="sidebar fixed inset-y-0 left-0 z-50 bg-gray-900 text-white shadow-xl flex flex-col sidebar-transition lg:translate-x-0 mobile-sidebar-hidden lg:mobile-sidebar-visible">

            <!-- Header -->
            <div class="flex items-center justify-between h-16 px-4 bg-gray-800 border-b border-gray-700">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                        <i class="bi bi-grid-fill text-white text-sm"></i>
                    </div>
                    <div>
                        <h1 class="text-sm font-bold text-white">Admin Panel</h1>
                        <p class="text-xs text-gray-400">Karvin Badminton</p>
                    </div>
                </div>

                <!-- Close Button for Mobile -->
                <button onclick="closeMobileSidebar()"
                    class="lg:hidden text-gray-400 hover:text-white p-1 rounded">
                    <i class="bi bi-x-lg text-lg"></i>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
                <x-sidebar-link route="admin.dashboard" icon="bi-house-door-fill" label="Dashboard" />

                <!-- Manajemen Konten -->
                <div x-data="{ open: {{ request()->routeIs('admin.facilities.*') || request()->routeIs('admin.background-videos.*') || request()->routeIs('admin.gallery.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="flex items-center w-full px-3 py-2 text-sm font-medium text-left rounded-lg text-gray-300 hover:bg-gray-700 focus:outline-none group {{ request()->routeIs(['admin.facilities.*', 'admin.background-videos.*', 'admin.gallery.*']) ? 'bg-gray-700' : '' }}">
                        <i class="bi bi-layout-text-window-reverse text-gray-400 mr-3 text-lg"></i>
                        <span class="flex-1 truncate">Manajemen Konten</span>
                        <!-- PERBAIKAN: Menggunakan chevron-right yang berubah menjadi chevron-down -->
                        <i class="bi transition-all duration-200 text-sm text-gray-400"
                            :class="open ? 'bi-chevron-down' : 'bi-chevron-right'"></i>
                    </button>

                    <div x-show="open"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 transform -translate-y-1"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 transform translate-y-0"
                        x-transition:leave-end="opacity-0 transform -translate-y-1"
                        class="ml-6 mt-1 space-y-1 border-l-2 border-gray-700 pl-3">
                        <x-sidebar-link route="admin.facilities.index" icon="bi-building" label="Fasilitas" match="admin.facilities.*" />
                        <x-sidebar-link route="admin.background-videos.index" icon="bi-camera-video" label="Video Latar" match="admin.background-videos.*" />
                        <x-sidebar-link route="admin.gallery.index" icon="bi-image" label="Galeri" match="admin.gallery.*" />
                    </div>
                </div>

                <!-- Sistem Pemesanan -->
                <div x-data="{ open: {{ request()->routeIs('admin.fields.*') || request()->routeIs('admin.bookings.*') || request()->routeIs('admin.transactions.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="flex items-center w-full px-3 py-2 text-sm font-medium text-left rounded-lg text-gray-300 hover:bg-gray-700 focus:outline-none group {{ request()->routeIs(['admin.fields.*', 'admin.bookings.*', 'admin.transactions.*']) ? 'bg-gray-700' : '' }}">
                        <i class="bi bi-calendar-week text-gray-400 mr-3 text-lg"></i>
                        <span class="flex-1 truncate">Sistem Pemesanan</span>
                        <!-- PERBAIKAN: Menggunakan chevron-right yang berubah menjadi chevron-down -->
                        <i class="bi transition-all duration-200 text-sm text-gray-400"
                            :class="open ? 'bi-chevron-down' : 'bi-chevron-right'"></i>
                    </button>

                    <div x-show="open"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 transform -translate-y-1"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 transform translate-y-0"
                        x-transition:leave-end="opacity-0 transform -translate-y-1"
                        class="ml-6 mt-1 space-y-1 border-l-2 border-gray-700 pl-3">
                        <x-sidebar-link route="admin.fields.index" icon="bi-grid-3x3" label="Lapangan" match="admin.fields.*" />
                        <x-sidebar-link route="admin.bookings.index" icon="bi-calendar-check" label="Pemesanan" match="admin.bookings.*" />
                        <x-sidebar-link route="admin.transactions.index" icon="bi-credit-card" label="Transaksi" match="admin.transactions.*" />
                    </div>
                </div>

                <!-- Lihat Situs -->
                <hr class="my-3 border-gray-700">
                <a href="{{ route('home') }}" target="_blank"
                    class="flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-300 hover:bg-gray-700 transition-colors"
                    onclick="closeMobileSidebar()">
                    <i class="bi bi-globe text-gray-400 mr-3 text-lg"></i>
                    <span class="truncate">Lihat Situs</span>
                    <i class="bi bi-box-arrow-up-right text-xs ml-auto"></i>
                </a>
            </nav>

            <!-- Footer -->
            <div class="p-4 bg-gray-800 border-t border-gray-700">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                        {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name ?? 'Admin' }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email ?? 'admin@email.com' }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center w-full px-3 py-2 text-sm font-medium rounded-lg text-gray-300 hover:bg-red-600 hover:text-white transition-colors"
                        onclick="closeMobileSidebar()">
                        <i class="bi bi-box-arrow-right mr-3 text-lg"></i>
                        <span class="truncate">Keluar</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content flex-1 flex flex-col lg:main-content-desktop main-content-mobile">
            <!-- Content -->
            <main class="flex-1 py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <!-- Flash Messages -->
                    @if (session('success'))
                    <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-4 rounded-r-lg">
                        <div class="flex">
                            <i class="bi bi-check-circle-fill text-green-400 mr-2"></i>
                            <p class="text-sm text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                    @endif

                    @if (session('error'))
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-4 rounded-r-lg">
                        <div class="flex">
                            <i class="bi bi-exclamation-circle-fill text-red-400 mr-2"></i>
                            <p class="text-sm text-red-700">{{ session('error') }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Page Content -->
                    @yield('content')
                </div>
            </main>

            <!-- Desktop Footer -->
            <footer class="hidden lg:block fixed bottom-0 right-0 bg-white border-t border-gray-200 desktop-footer" style="left: 240px;">
                <div class="flex items-center justify-between h-full px-6">
                    <!-- Left Side -->
                    <div class="flex items-center space-x-4">
                        <p class="text-sm text-gray-600">© 2025 Karvin Badminton Admin Panel</p>
                        <span class="text-gray-300">|</span>
                        <p class="text-sm text-gray-500">Version 1.0.0</p>
                    </div>

                    <!-- Right Side -->
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                            <span class="text-sm text-gray-500">System Online</span>
                        </div>
                    </div>
                </div>
            </footer>

            <!-- Mobile Footer -->
            <footer class="lg:hidden fixed bottom-0 left-0 right-0 z-30 bg-white border-t border-gray-200 mobile-footer">
                <div class="flex items-center justify-around h-full px-4">
                    <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center py-2 {{ request()->routeIs('admin.dashboard') ? 'text-blue-600' : 'text-gray-500' }}">
                        <i class="bi bi-house-door text-lg"></i>
                        <span class="text-xs mt-1">Dashboard</span>
                    </a>
                    <a href="{{ route('admin.bookings.index') }}" class="flex flex-col items-center py-2 {{ request()->routeIs('admin.bookings.*') ? 'text-blue-600' : 'text-gray-500' }}">
                        <i class="bi bi-calendar-check text-lg"></i>
                        <span class="text-xs mt-1">Booking</span>
                    </a>
                    <a href="{{ route('admin.fields.index') }}" class="flex flex-col items-center py-2 {{ request()->routeIs('admin.fields.*') ? 'text-blue-600' : 'text-gray-500' }}">
                        <i class="bi bi-grid-3x3 text-lg"></i>
                        <span class="text-xs mt-1">Lapangan</span>
                    </a>
                    <a href="{{ route('admin.transactions.index') }}" class="flex flex-col items-center py-2 {{ request()->routeIs('admin.transactions.*') ? 'text-blue-600' : 'text-gray-500' }}">
                        <i class="bi bi-credit-card text-lg"></i>
                        <span class="text-xs mt-1">Transaksi</span>
                    </a>
                    <button onclick="toggleMobileSidebar()" class="flex flex-col items-center py-2 text-gray-500">
                        <i class="bi bi-list text-lg"></i>
                        <span class="text-xs mt-1">Menu</span>
                    </button>
                </div>
            </footer>
        </div>
    </div>

    <script>
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobileOverlay');

            if (sidebar.classList.contains('mobile-sidebar-hidden')) {
                // Show sidebar
                sidebar.classList.remove('mobile-sidebar-hidden');
                sidebar.classList.add('mobile-sidebar-visible');
                overlay.classList.remove('hidden');
            } else {
                // Hide sidebar
                sidebar.classList.remove('mobile-sidebar-visible');
                sidebar.classList.add('mobile-sidebar-hidden');
                overlay.classList.add('hidden');
            }
        }

        function closeMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobileOverlay');

            // Only close on mobile
            if (window.innerWidth < 1024) {
                sidebar.classList.remove('mobile-sidebar-visible');
                sidebar.classList.add('mobile-sidebar-hidden');
                overlay.classList.add('hidden');
            }
        }

        // Close sidebar when window is resized to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('mobileOverlay');

                sidebar.classList.remove('mobile-sidebar-hidden');
                sidebar.classList.add('mobile-sidebar-visible');
                overlay.classList.add('hidden');
            }
        });

        // Close sidebar on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && window.innerWidth < 1024) {
                closeMobileSidebar();
            }
        });
    </script>
</body>

</html>
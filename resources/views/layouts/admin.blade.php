<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- Custom CSS untuk Fixed Width Sidebar -->
    <style>
        .sidebar-fixed {
            width: 280px;
            min-width: 280px;
            max-width: 280px;
            flex-shrink: 0;
        }
        
        .main-content {
            margin-left: 280px;
        }
        
        @media (max-width: 1024px) {
            .sidebar-fixed {
                width: 320px;
                min-width: 320px;
                max-width: 320px;
            }
            .main-content {
                margin-left: 0;
            }
        }
        
        .sidebar-text {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 200px;
        }
    </style>

    <!-- App Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>

<body class="font-sans antialiased bg-gray-50">

    <!-- Toggle Button (Mobile Only) -->
    <button onclick="toggleSidebar()" class="fixed top-5 left-4 z-50 text-gray-700 text-2xl lg:hidden bg-white p-2 rounded-lg shadow-lg hover:bg-gray-100 transition-all duration-200">
        <i class="bi bi-filter-left"></i>
    </button>

    <!-- Overlay (Mobile Only) -->
    <div id="overlay" class="hidden fixed inset-0 z-40 bg-black bg-opacity-50 backdrop-blur-sm lg:hidden" onclick="toggleSidebar()">
    </div>

    <div class="min-h-screen flex">

        <!-- Navigation Sidebar - Fixed Width -->
        <div id="mobile-sidebar" class="sidebar-fixed fixed inset-y-0 left-0 z-50 bg-gradient-to-b from-gray-900 via-gray-800 to-gray-900 text-white shadow-2xl border-r border-gray-700/50 transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static flex flex-col h-screen overflow-hidden">
            
            <!-- Header dengan Fixed Height -->
            <div class="flex items-center justify-center h-16 bg-gradient-to-r from-blue-900/30 to-purple-900/20 border-b border-gray-700/50 flex-shrink-0 px-5">
                <div class="flex items-center justify-between w-full">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-700 rounded-lg flex items-center justify-center shadow-lg">
                            <i class="bi bi-grid-fill text-white text-xl"></i>
                        </div>
                        <div class="text-left">
                            <h1 class="text-lg font-bold bg-gradient-to-r from-blue-300 to-purple-300 bg-clip-text text-transparent truncate max-w-32">
                                Admin Panel
                            </h1>
                            <p class="text-xs text-gray-400 truncate max-w-32">Karvin Badminton</p>
                        </div>
                    </div>
                    <i class="bi bi-x-lg cursor-pointer text-gray-400 hover:text-white hover:scale-110 transition-transform duration-200 lg:hidden"
                        onclick="toggleSidebar()"></i>
                </div>
            </div>

            <!-- Navigation dengan Scroll -->
            <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
                <x-sidebar-link route="admin.dashboard" icon="bi-house-door-fill" label="Dashboard"
                    class="group transition-all duration-200" />

                <!-- Content Management Dropdown dengan Fixed Width -->
                <div x-data="{ open: {{ request()->routeIs('admin.facilities.*') || request()->routeIs('admin.background-videos.*') || request()->routeIs('admin.gallery.*') ? 'true' : 'false' }} }"
                    class="space-y-1">
                    <button @click="open = !open"
                        class="flex items-center w-full px-3 py-3 text-sm font-medium text-left rounded-lg transition-all duration-200 hover:bg-gradient-to-r hover:from-gray-700/50 hover:to-gray-600/30 focus:outline-none group {{ request()->routeIs(['admin.facilities.*', 'admin.background-videos.*', 'admin.gallery.*']) ? 'bg-gradient-to-r from-blue-600/20 to-blue-500/10 text-blue-300 border-l-4 border-blue-500 shadow-lg shadow-blue-500/10' : 'text-gray-300 hover:translate-x-1' }}">
                        <div class="flex items-center justify-center w-6 h-6 mr-3 flex-shrink-0">
                            <i class="bi bi-layout-text-window-reverse text-lg {{ request()->routeIs(['admin.facilities.*', 'admin.background-videos.*', 'admin.gallery.*']) ? 'text-blue-400' : 'text-gray-400 group-hover:text-blue-400' }} transition-colors duration-200"></i>
                        </div>
                        <span class="flex-1 truncate sidebar-text {{ request()->routeIs(['admin.facilities.*', 'admin.background-videos.*', 'admin.gallery.*']) ? 'text-blue-200 font-semibold' : 'group-hover:text-blue-300' }} transition-colors duration-200">Manajemen Konten</span>
                        <i class="bi bi-chevron-right ml-2 transition-transform duration-300 transform text-gray-400 group-hover:text-blue-400 flex-shrink-0" :class="{'rotate-90': open}"></i>
                    </button>
                    
                    <div x-show="open"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform -translate-y-2"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 transform translate-y-0"
                        x-transition:leave-end="opacity-0 transform -translate-y-2"
                        class="ml-6 mt-2 space-y-1 border-l-2 border-gray-700/50 pl-4">
                        <x-sidebar-link route="admin.facilities.index" icon="bi-building" label="Fasilitas" match="admin.facilities.*" />
                        <x-sidebar-link route="admin.background-videos.index" icon="bi-camera-video" label="Video Latar" match="admin.background-videos.*" />
                        <x-sidebar-link route="admin.gallery.index" icon="bi-image" label="Galeri" match="admin.gallery.*" />
                    </div>
                </div>

                <!-- Booking System Dropdown -->
                <div x-data="{ open: {{ request()->routeIs('admin.fields.*') || request()->routeIs('admin.bookings.*') || request()->routeIs('admin.transactions.*') ? 'true' : 'false' }} }"
                    class="space-y-1">
                    <button @click="open = !open"
                        class="flex items-center w-full px-3 py-3 text-sm font-medium text-left rounded-lg transition-all duration-200 hover:bg-gradient-to-r hover:from-gray-700/50 hover:to-gray-600/30 focus:outline-none group {{ request()->routeIs(['admin.fields.*', 'admin.bookings.*', 'admin.transactions.*']) ? 'bg-gradient-to-r from-blue-600/20 to-blue-500/10 text-blue-300 border-l-4 border-blue-500 shadow-lg shadow-blue-500/10' : 'text-gray-300 hover:translate-x-1' }}">
                        <div class="flex items-center justify-center w-6 h-6 mr-3 flex-shrink-0">
                            <i class="bi bi-calendar-week text-lg {{ request()->routeIs(['admin.fields.*', 'admin.bookings.*', 'admin.transactions.*']) ? 'text-blue-400' : 'text-gray-400 group-hover:text-blue-400' }} transition-colors duration-200"></i>
                        </div>
                        <span class="flex-1 truncate sidebar-text {{ request()->routeIs(['admin.fields.*', 'admin.bookings.*', 'admin.transactions.*']) ? 'text-blue-200 font-semibold' : 'group-hover:text-blue-300' }} transition-colors duration-200">Sistem Pemesanan</span>
                        <i class="bi bi-chevron-right ml-2 transition-transform duration-300 transform text-gray-400 group-hover:text-blue-400 flex-shrink-0" :class="{'rotate-90': open}"></i>
                    </button>

                    <div x-show="open"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform -translate-y-2"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 transform translate-y-0"
                        x-transition:leave-end="opacity-0 transform -translate-y-2"
                        class="ml-6 mt-2 space-y-1 border-l-2 border-gray-700/50 pl-4">
                        <x-sidebar-link route="admin.fields.index" icon="bi-grid-3x3" label="Lapangan" match="admin.fields.*" />
                        <x-sidebar-link route="admin.bookings.index" icon="bi-calendar-check" label="Pemesanan" match="admin.bookings.*" />
                        <x-sidebar-link route="admin.transactions.index" icon="bi-credit-card" label="Transaksi" match="admin.transactions.*" />
                    </div>
                </div>

                <!-- External Link -->
                <hr class="my-4 border-gray-700/50 mx-2">
                <a href="{{ route('home') }}" target="_blank"
                    class="group flex items-center px-3 py-3 rounded-lg text-sm font-medium text-gray-300 hover:bg-gradient-to-r hover:from-gray-700/50 hover:to-gray-600/30 hover:text-blue-300 transition-all duration-200 hover:translate-x-1">
                    <div class="flex items-center justify-center w-6 h-6 mr-3 flex-shrink-0">
                        <i class="bi bi-globe text-lg text-gray-400 group-hover:text-blue-400 transition-colors duration-200"></i>
                    </div>
                    <span class="flex-1 truncate sidebar-text group-hover:text-blue-300 transition-colors duration-200">Lihat Situs</span>
                    <i class="bi bi-box-arrow-up-right text-xs text-gray-500 group-hover:text-blue-400 transition-colors duration-200 flex-shrink-0"></i>
                </a>
            </nav>

            <!-- Footer dengan Fixed Height -->
            <div class="p-4 bg-gradient-to-r from-gray-800/80 to-gray-900/60 border-t border-gray-700/50 backdrop-blur-sm flex-shrink-0">
                <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-800/40 hover:bg-gray-700/40 transition-colors duration-200">
                    <div class="relative flex-shrink-0">
                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center font-bold text-white shadow-lg hover:shadow-blue-500/20 hover:scale-105 transition-all duration-200">
                            {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-400 rounded-full border-2 border-gray-800"></div>
                    </div>
                    <div class="text-sm leading-tight flex-1 min-w-0">
                        <p class="font-semibold text-white truncate">{{ Auth::user()->name ?? 'User' }}</p>
                        <p class="text-gray-400 text-xs truncate">{{ Auth::user()->email ?? 'email@example.com' }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit"
                        class="flex items-center w-full p-3 rounded-lg hover:bg-gradient-to-r hover:from-red-600/20 hover:to-red-500/10 hover:text-red-300 text-left transition-all duration-200 group border border-transparent hover:border-red-500/20">
                        <div class="flex items-center justify-center w-6 h-6 mr-3 flex-shrink-0">
                            <i class="bi bi-box-arrow-right text-lg text-gray-400 group-hover:text-red-400 transition-colors duration-200"></i>
                        </div>
                        <span class="font-medium text-gray-300 group-hover:text-red-300 transition-colors duration-200 truncate">Keluar</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content Area dengan Margin Left Fixed -->
        <div class="flex-1 flex flex-col main-content">

            <!-- Page Heading -->
            @isset($header)
            <header class="bg-white shadow-sm sticky top-0 z-10">
                <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                    <h1 class="text-lg font-semibold text-gray-800">
                        {{ $header }}
                    </h1>
                    <div class="hidden lg:block text-sm text-gray-500">
                        {{ config('app.name', 'Laravel') }} Admin
                    </div>
                </div>
            </header>
            @endisset

            <!-- Page Content -->
            <main class="flex-1 py-8">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                    <!-- Flash Messages -->
                    <div class="px-4 sm:px-0">
                        @if (session('success'))
                        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow-sm flex items-center"
                            role="alert">
                            <i class="bi bi-check-circle-fill text-green-500 mr-3 text-lg"></i>
                            <div>
                                <p class="text-sm font-medium">{{ session('success') }}</p>
                            </div>
                        </div>
                        @endif

                        @if (session('error'))
                        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md shadow-sm flex items-center"
                            role="alert">
                            <i class="bi bi-exclamation-circle-fill text-red-500 mr-3 text-lg"></i>
                            <div>
                                <p class="text-sm font-medium">{{ session('error') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Sidebar Toggle Script -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('mobile-sidebar');
            const overlay = document.getElementById('overlay');

            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        }
    </script>

</body>

</html>

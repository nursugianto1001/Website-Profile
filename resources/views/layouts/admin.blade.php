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

    <div class="min-h-screen flex flex-col lg:flex-row">

        <!-- Navigation Sidebar -->
        <div id="mobile-sidebar" class="fixed inset-y-0 left-0 z-50 w-[320px] bg-gradient-to-b from-gray-900 to-gray-800 text-white shadow-xl transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static flex flex-col justify-between backdrop-blur-md">
            <!-- Header -->
            <div>
                <div class="mb-6 p-5 border-b border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center shadow">
                                <i class="bi bi-grid-fill text-white text-xl"></i>
                            </div>
                            <h1 class="text-xl font-bold text-white tracking-wide">Admin Panel</h1>
                        </div>
                        <i class="bi bi-x-lg cursor-pointer text-gray-400 hover:text-white hover:scale-110 transition-transform duration-200 lg:hidden"
                           onclick="toggleSidebar()"></i>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="space-y-2 px-4">
                    <x-sidebar-link route="admin.dashboard" icon="bi-house-door-fill" label="Dashboard"
                                    class="group transition hover:translate-x-1 duration-200"/>

                    <!-- Content Management Dropdown -->
                    <div x-data="{ open: {{ request()->routeIs('admin.facilities.*') || request()->routeIs('admin.background-videos.*') || request()->routeIs('admin.gallery.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                                class="flex items-center w-full px-3 py-3 text-sm font-medium text-left rounded-md transition-all duration-200 hover:bg-gray-700/50 focus:outline-none group {{ request()->routeIs(['admin.facilities.*', 'admin.background-videos.*', 'admin.gallery.*']) ? 'bg-blue-600/20 text-blue-300 border-l-4 border-blue-500 pl-2' : 'text-gray-300 hover:translate-x-1' }}">
                            <i class="bi bi-layout-text-window-reverse text-lg mr-3 group-hover:text-blue-400"></i>
                            <span class="group-hover:text-blue-300">Manajemen Konten</span>
                            <i class="bi bi-chevron-right ml-auto transition-transform duration-300 transform" :class="{'rotate-90': open}"></i>
                        </button>
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform -translate-y-4"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform -translate-y-4"
                             class="mt-1 space-y-1 pl-6">
                            <x-sidebar-link route="admin.facilities.index" icon="bi-building" label="Fasilitas" match="admin.facilities.*"/>
                            <x-sidebar-link route="admin.background-videos.index" icon="bi-camera-video" label="Video Latar Belakang" match="admin.background-videos.*"/>
                            <x-sidebar-link route="admin.gallery.index" icon="bi-image" label="Galeri" match="admin.gallery.*"/>
                        </div>
                    </div>

                    <!-- Booking System Dropdown -->
                    <div x-data="{ open: {{ request()->routeIs('admin.fields.*') || request()->routeIs('admin.bookings.*') || request()->routeIs('admin.transactions.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                                class="flex items-center w-full px-3 py-3 text-sm font-medium text-left rounded-md transition-all duration-200 hover:bg-gray-700/50 focus:outline-none group {{ request()->routeIs(['admin.fields.*', 'admin.bookings.*', 'admin.transactions.*']) ? 'bg-blue-600/20 text-blue-300 border-l-4 border-blue-500 pl-2' : 'text-gray-300 hover:translate-x-1' }}">
                            <i class="bi bi-calendar-week text-lg mr-3 group-hover:text-blue-400"></i>
                            <span class="group-hover:text-blue-300">Sistem Pemesanan</span>
                            <i class="bi bi-chevron-right ml-auto transition-transform duration-300 transform" :class="{'rotate-90': open}"></i>
                        </button>
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform -translate-y-4"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform -translate-y-4"
                             class="mt-1 space-y-1 pl-6">
                            <x-sidebar-link route="admin.fields.index" icon="bi-grid-3x3" label="Lapangan" match="admin.fields.*"/>
                            <x-sidebar-link route="admin.bookings.index" icon="bi-calendar-check" label="Pemesanan" match="admin.bookings.*"/>
                            <x-sidebar-link route="admin.transactions.index" icon="bi-credit-card" label="Transaksi" match="admin.transactions.*"/>
                        </div>
                    </div>

                    <!-- External Link -->
                    <hr class="my-6 border-gray-700 mx-4 opacity-50">
                    <a href="{{ route('home') }}" target="_blank"
                       class="flex items-center px-4 py-3 rounded-md text-sm font-medium text-gray-300 hover:bg-blue-600/20 hover:text-blue-300 group transition-all duration-200">
                        <i class="bi bi-globe text-lg mr-3 group-hover:text-blue-400"></i>
                        <span>Lihat Situs</span>
                    </a>
                </nav>
            </div>

            <!-- Footer -->
            <div class="p-5 bg-gray-800/60 border-t border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center font-bold text-white shadow hover:shadow-blue-500/20 hover:scale-105 transition">
                        {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                    </div>
                    <div class="text-sm leading-tight">
                        <p class="font-semibold text-white">{{ Auth::user()->name ?? 'User' }}</p>
                        <p class="text-gray-400 text-xs">{{ Auth::user()->email ?? 'email@example.com' }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button type="submit"
                            class="flex items-center w-full p-3 rounded-md hover:bg-red-600/20 hover:text-red-300 text-left transition-all duration-200 group">
                        <i class="bi bi-box-arrow-right text-lg mr-3 group-hover:text-red-400"></i>
                        <span class="font-medium">Keluar</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col lg:ml-0">

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

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 py-4 mt-auto">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center">
                        <p class="text-sm text-gray-500">
                            © {{ date('Y') }} Karvin Badminton. All rights reserved.
                        </p>
                        <a href="#" class="text-blue-500 hover:text-blue-600 text-sm">Kembali Ke atas</a>
                    </div>
                </div>
            </footer>
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

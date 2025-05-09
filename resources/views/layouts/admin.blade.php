<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

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
    <button onclick="toggleSidebar()"
        class="fixed top-5 left-4 z-50 text-gray-700 text-3xl lg:hidden bg-gray-200 px-2 py-1 rounded-md shadow">
        <i class="bi bi-filter-left"></i>
    </button>


    <!-- Overlay (Mobile Only) -->
    <div id="overlay" class="hidden fixed inset-0 z-40 bg-black bg-opacity-40 lg:hidden" onclick="openSidebar()">
    </div>

    <div class="min-h-screen flex flex-col md:flex-row">

        <!-- Navigation Sidebar -->
        <div
            class="sidebar fixed top-0 bottom-0 left-0 z-50 w-[320px] bg-gray-900 text-white shadow-xl transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out lg:static">
            @include('layouts.navigation')
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col lg:ml-[320px]">

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow-sm z-10">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                        <h1 class="text-lg font-medium text-gray-900">
                            {{ $header }}
                        </h1>
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="flex-1 py-8">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                    <!-- Flash Messages -->
                    <div class="px-4 sm:px-0">
                        @if (session('success'))
                            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow-sm"
                                role="alert">
                                <div class="flex">
                                    <div class="ml-3">
                                        <p class="text-sm">{{ session('success') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md shadow-sm"
                                role="alert">
                                <div class="flex">
                                    <div class="ml-3">
                                        <p class="text-sm">{{ session('error') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    @yield('content')
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 py-4">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <p class="text-center text-sm text-gray-500">
                        © {{ date('Y') }} Karvin Badminton. All rights reserved.
                    </p>
                </div>
            </footer>
        </div>
    </div>

    <!-- Sidebar Toggle Script -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('mobile-sidebar');
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
            } else {
                sidebar.classList.add('-translate-x-full');
            }
        }
    </script>

</body>

</html>

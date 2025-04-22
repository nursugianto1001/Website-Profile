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
    
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>

<body class="font-sans antialiased bg-gray-50">
    <span class="absolute text-gray-700 text-4xl top-5 left-4 cursor-pointer lg:hidden" onclick="openSidebar()">
        <i class="bi bi-filter-left px-2 bg-gray-200 rounded-md"></i>
    </span>

    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Navigation Sidebar -->
        @include('layouts.navigation')
        
        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col lg:ml-[300px]">
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
                            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow-sm" role="alert">
                                <div class="flex">
                                    <div class="ml-3">
                                        <p class="text-sm">{{ session('success') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        @if (session('error'))
                            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md shadow-sm" role="alert">
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
                        © {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.
                    </p>
                </div>
            </footer>
        </div>
    </div>

    <script type="text/javascript">
        function openSidebar() {
            document.querySelector(".sidebar").classList.toggle("hidden");
        }
    </script>
</body>
</html>
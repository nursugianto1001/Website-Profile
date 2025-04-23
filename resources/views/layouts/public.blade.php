<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Cafe') }} - @yield('title')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased overflow-x-hidden min-h-screen flex flex-col">
    <!-- Transparent Header with Right-Aligned Menu -->
    <header class="fixed w-full z-50 transition-all duration-300" id="main-header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-white drop-shadow-md">Your Company</a>
                </div>
                <nav class="flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="text-white hover:text-gray-200 px-3 py-2 rounded-md text-sm font-medium drop-shadow-md">Home</a>
                    <a href="{{ route('about') }}" class="text-white hover:text-gray-200 px-3 py-2 rounded-md text-sm font-medium drop-shadow-md">About</a>
                    <a href="{{ route('menu') }}" class="text-white hover:text-gray-200 px-3 py-2 rounded-md text-sm font-medium drop-shadow-md">Menu</a>
                    <a href="{{ route('outlets') }}" class="text-white hover:text-gray-200 px-3 py-2 rounded-md text-sm font-medium drop-shadow-md">Outlets</a>
                    <a href="{{ route('facilities') }}" class="text-white hover:text-gray-200 px-3 py-2 rounded-md text-sm font-medium drop-shadow-md">Facilities</a>
                    <a href="{{ route('careers') }}" class="text-white hover:text-gray-200 px-3 py-2 rounded-md text-sm font-medium drop-shadow-md">Careers</a>
                    <a href="{{ route('contact') }}" class="text-white hover:text-gray-200 px-3 py-2 rounded-md text-sm font-medium drop-shadow-md">Contact</a>
                </nav>
            </div>
        </div>
    </header>

    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer Fixed at Bottom -->
    <footer class="relative z-10 bg-transparent text-white mt-auto">
        <div class="bg-black bg-opacity-70 backdrop-blur-md py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div>
                        <h3 class="text-xl font-bold mb-4">About Us</h3>
                        <p class="text-gray-300">
                            Experience the finest coffee and food in a comfortable atmosphere.
                            We're committed to quality and service.
                        </p>
                    </div>
                    <div class="flex justify-end"> <!-- This aligns content to the right -->
                        <div>
                            <h3 class="text-xl font-bold mb-4">Contact Us</h3>
                            <ul class="space-y-2 text-gray-300">
                                <li>Email: info@example.com</li>
                                <li>Phone: +1234567890</li>
                                <li>Address: 123 Coffee Street, City, Country</li>
                            </ul>
                            <div class="mt-4 flex space-x-4">
                                <a href="#" class="text-gray-300 hover:text-white">
                                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M22 12c0-5.523..." clip-rule="evenodd" />
                                    </svg>
                                </a>
                                <a href="#" class="text-gray-300 hover:text-white">
                                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M12.315 2c2.43..." clip-rule="evenodd" />
                                    </svg>
                                </a>
                                <a href="#" class="text-gray-300 hover:text-white">
                                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M8.29 20.251c7.547..." />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 border-t border-gray-700 pt-8 text-center">
                    <p class="text-gray-300">
                        &copy; {{ date('Y') }} Cafe. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>


    <!-- Script for header transparency/background -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const header = document.getElementById('main-header');

            window.addEventListener('scroll', function() {
                if (window.scrollY > 50) {
                    header.classList.add('bg-black', 'bg-opacity-70', 'backdrop-blur-md', 'shadow-md');
                } else {
                    header.classList.remove('bg-black', 'bg-opacity-70', 'backdrop-blur-md', 'shadow-md');
                }
            });
        });
    </script>
</body>

</html>
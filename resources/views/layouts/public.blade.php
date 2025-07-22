<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<!-- Di layouts/public.blade.php - HAPUS baris Font Awesome yang duplikat -->

<head>

    <!-- Resource hints untuk CDN -->
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sport') }} - @yield('title')</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- HANYA SATU Font Awesome loading -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-Avb2QiuDEEvB4bZJYdft2mNjVShBftLdPG8FJ0V7irTLQ8Uo0qcPxh4Plq7G5tGm0rU+1SPhVotteLpBERwTkw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Tambahkan Tailwind CSS dengan konfigurasi yang lebih lengkap -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'gray-800': '#1f2937',
                        'gray-900': '#111827',
                        'emerald-400': '#34d399',
                        'emerald-500': '#10b981',
                        'emerald-600': '#059669',
                        'gray-200': '#e5e7eb',
                        'gray-700': '#374151'
                    }
                }
            }
        }
    </script>
</head>


<body class="font-sans overflow-x-hidden w-full relative m-0 p-0 min-h-screen flex flex-col bg-white">
    <header id="main-header" class="fixed top-0 left-0 w-full z-50 bg-transparent transition duration-300 ease-in-out">
        <nav class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8" aria-label="Top">
            <div class="w-full py-4 flex items-center justify-between">
                <div class="flex-shrink-0 flex items-center relative">
                    <img src="{{ Vite::asset(asset: 'resources/images/Karvin.png') }}" alt="Karvin Logo"
                        class="h-12 w-auto">
                    <span
                        class="italic font-black text-white">Karvin Badminton</span>
                </div>

                <div id="desktopMenu" class="hidden md:flex flex-grow justify-center space-x-6">
                    <a href="{{ route('home') }}"
                        class="text-white px-3 py-2 rounded-md text-sm font-bold hover:text-emerald-400">Beranda</a>
                    <a href="{{ route('about') }}"
                        class="text-white px-3 py-2 rounded-md text-sm font-bold hover:text-emerald-400">Tentang</a>
                    <a href="{{ route('facilities') }}"
                        class="text-white px-3 py-2 rounded-md text-sm font-bold hover:text-emerald-400">Fasilitas</a>
                    <a href="{{ route('contact') }}"
                        class="text-white px-3 py-2 rounded-md text-sm font-bold hover:text-emerald-400">Kontak</a>
                </div>

                <div class="hidden md:block flex-shrink-0">
                    <a href="{{ route('booking.form') }}"
                        class="bg-emerald-500 bg-opacity-90 text-white px-5 py-2 rounded-md text-sm font-bold border-2 border-emerald-500 border-opacity-90 transition duration-300 hover:bg-transparent hover:text-white hover:border-white text-center">
                        Pesan Sekarang
                    </a>
                </div>

                <div id="mobileMenuButton" class="flex md:hidden">
                    <button type="button"
                        class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-emerald-400"
                        onclick="toggleMobileMenu()">
                        <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>

            <div id="mobileMenu"
                class="hidden absolute top-full left-0 right-0 w-full z-50 bg-gray-800 bg-opacity-90 rounded-b-md p-2 shadow-lg">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <a href="{{ route('home') }}"
                        class="text-white block w-full px-3 py-3 rounded-md text-base font-bold hover:text-emerald-400 hover:bg-gray-700 hover:bg-opacity-70">Beranda</a>
                    <a href="{{ route('about') }}"
                        class="text-white block w-full px-3 py-3 rounded-md text-base font-bold hover:text-emerald-400 hover:bg-gray-700 hover:bg-opacity-70">Tentang</a>
                    <a href="{{ route('facilities') }}"
                        class="text-white block w-full px-3 py-3 rounded-md text-base font-bold hover:text-emerald-400 hover:bg-gray-700 hover:bg-opacity-70">Fasilitas</a>
                    <a href="{{ route('contact') }}"
                        class="text-white block w-full px-3 py-3 rounded-md text-base font-bold hover:text-emerald-400 hover:bg-gray-700 hover:bg-opacity-70">Kontak</a>
                </div>
            </div>
        </nav>
    </header>

    <main class="mt-20 md:mt-20 flex-grow w-full">
        @yield('content')
    </main>

    <footer class="w-full mt-auto">
        <div class="bg-gray-800 backdrop-blur p-8">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-8">
                    <div>
                        <h3 class="text-lg sm:text-xl font-bold mb-4 text-emerald-400">Kontak Kami</h3>
                        <ul class="text-gray-200 text-sm space-y-2">
                            <li>Email: saranasehatborneo2@gmail.com</li>
                            <li>No. HP: +62 822-1000-2256</li>
                            <li>Alamat: Jalan Veteran, Jalan Karvin</li>
                        </ul>
                        <div class="mt-4 flex gap-4">
                            <a href="https://www.instagram.com/karvin_badminton/"
                                class="text-gray-200 hover:text-emerald-400 w-10 h-10 flex items-center justify-center">
                                <i class="fab fa-instagram text-xl"></i>
                            </a>
                            <a href="https://maps.app.goo.gl/XryjjSfbFV3FjJJAA" target="_blank"
                                class="text-gray-200 hover:text-emerald-400 w-10 h-10 flex items-center justify-center">
                                <i class="fas fa-map-marker-alt text-xl"></i>
                            </a>
                            <a href="https://www.tiktok.com/@karvinbadminton?_t=ZS-8xlSGLPLg4V&_r=1"
                                class="text-gray-200 hover:text-emerald-400 w-10 h-10 flex items-center justify-center">
                                <i class="fab fa-tiktok text-xl"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="mt-6 border-t border-gray-200 pt-6 text-center">
                    <p class="text-gray-200 text-sm">&copy; {{ date('Y') }} Karvin Badminton. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        function updateHeaderBackground() {
            const header = document.getElementById('main-header');
            if (window.scrollY > 50) {
                header.classList.remove('bg-transparent');
                header.classList.add('bg-gray-800');
            } else {
                header.classList.remove('bg-gray-800');
                header.classList.add('bg-transparent');
            }
        }

        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobileMenu');
            mobileMenu.classList.toggle('hidden');
        }

        document.addEventListener('DOMContentLoaded', function() {
            updateHeaderBackground();
            window.addEventListener('scroll', updateHeaderBackground);
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768) {
                    document.getElementById('mobileMenu').classList.add('hidden');
                }
            });

            document.body.addEventListener('touchmove', function(e) {
                if (e.target === document.body) {
                    e.preventDefault();
                }
            }, {
                passive: false
            });
        });
    </script>

</body>

</html>

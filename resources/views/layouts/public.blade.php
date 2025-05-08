<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sport') }} - @yield('title')</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="..." crossorigin="anonymous" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans overflow-x-hidden w-full relative m-0 p-0 min-h-screen flex flex-col bg-white">
    <header id="main-header" class="fixed top-0 left-0 w-full z-50 bg-transparent transition duration-300 ease-in-out">
        <nav class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8" aria-label="Top">
            <div class="w-full py-4 flex items-center justify-between">
                <div class="flex-shrink-0 flex items-center relative">
                    <img src="{{ Vite::asset(asset: 'resources/images/Karvin.png') }}" alt="Karvin Logo"
                        class="h-10 w-auto">
                    <span
                        class="italic font-black text-white text-xl ml-2 absolute right-[-60px] whitespace-nowrap">Karvin</span>
                </div>

                <div id="desktopMenu" class="hidden md:flex flex-grow justify-center space-x-6">
                    <a href="{{ route('home') }}"
                        class="text-white px-3 py-2 rounded-md text-sm font-bold hover:text-[#A66E38]">Beranda</a>
                    <a href="{{ route('about') }}"
                        class="text-white px-3 py-2 rounded-md text-sm font-bold hover:text-[#A66E38]">Tentang</a>
                    <a href="{{ route('facilities') }}"
                        class="text-white px-3 py-2 rounded-md text-sm font-bold hover:text-[#A66E38]">Fasilitas</a>
                    <a href="{{ route('contact') }}"
                        class="text-white px-3 py-2 rounded-md text-sm font-bold hover:text-[#A66E38]">Kontak</a>
                </div>

                <div class="hidden md:block flex-shrink-0">
                    <a href="#"
                        class="bg-[#A66E38] text-white px-5 py-2 rounded-md text-sm font-bold border-2 border-[#A66E38] transition duration-300 hover:bg-transparent hover:text-[#ffff] hover:border-white text-center">
                        Pesan Sekarang
                    </a>
                </div>

                <div id="mobileMenuButton" class="flex md:hidden">
                    <button type="button"
                        class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-[#A66E38]"
                        onclick="toggleMobileMenu()">
                        <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>

            <div id="mobileMenu"
                class="hidden absolute top-full left-0 right-0 w-full z-50 bg-black/90 rounded-b-md p-2 shadow-lg">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <a href="{{ route('home') }}"
                        class="text-white block w-full px-3 py-3 rounded-md text-base font-bold hover:text-[#A66E38] hover:bg-gray-700/70">Beranda</a>
                    <a href="{{ route('about') }}"
                        class="text-white block w-full px-3 py-3 rounded-md text-base font-bold hover:text-[#A66E38] hover:bg-gray-700/70">Tentang</a>
                    <a href="{{ route('facilities') }}"
                        class="text-white block w-full px-3 py-3 rounded-md text-base font-bold hover:text-[#A66E38] hover:bg-gray-700/70">Fasilitas</a>
                    <a href="{{ route('contact') }}"
                        class="text-white block w-full px-3 py-3 rounded-md text-base font-bold hover:text-[#A66E38] hover:bg-gray-700/70">Kontak</a>
                </div>
            </div>
        </nav>
    </header>

    <main class="mt-[70px] md:mt-[80px] flex-grow w-full">
        @yield('content')
    </main>

    <footer class="w-full mt-auto">
        <div class="bg-[#1A1A19] backdrop-blur p-8">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-8">
                    <div>
                        <h3 class="text-lg sm:text-xl font-bold mb-4 text-white">Kontak Kami</h3>
                        <ul class="text-white text-sm space-y-2">
                            <li>Email: saranaseharborneo2@gmail.com</li>
                            <li>No.HP: +62 822-1000-2256</li>
                            <li>Alamat: Jalan Veteran, Jalan Karvin</li>
                        </ul>
                        <div class="mt-4 flex gap-4">
                            <a href="#"
                                class="text-gray-300 hover:text-[#A66E38] w-10 h-10 flex items-center justify-center">
                                <i class="fab fa-facebook text-xl"></i>
                            </a>
                            <a href="#"
                                class="text-gray-300 hover:text-[#A66E38] w-10 h-10 flex items-center justify-center">
                                <i class="fab fa-twitter text-xl"></i>
                            </a>
                            <a href="#"
                                class="text-gray-300 hover:text-[#A66E38] w-10 h-10 flex items-center justify-center">
                                <i class="fab fa-instagram text-xl"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="mt-6 border-t border-[#ffff] pt-6 text-center">
                    <p class="text-white text-sm">&copy; {{ date('Y') }} Karvin. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        function updateHeaderBackground() {
            const header = document.getElementById('main-header');
            if (window.scrollY > 50) {
                header.classList.remove('bg-transparent');
                header.classList.add('bg-[#1A1A19]');
            } else {
                header.classList.remove('bg-[#1A1A19]');
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

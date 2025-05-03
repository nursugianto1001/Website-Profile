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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Custom Styles -->
    <style>
      /* Tailwind classes */
      .menu_links {
        color: white;
        padding: 0.5rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        font-weight: 500;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
      }
      .menu_links:hover {
        color: #e5e7eb;
      }
      .mobile_links {
        color: white;
        display: block;
        padding: 0.5rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 1rem;
        font-weight: 500;
      }
      .mobile_links:hover {
        background-color: #374151;
      }
      .social_icon {
        color: #d1d5db;
      }
      .social_icon:hover {
        color: white;
      }

      /* Custom styles for mobile menu */
      #mobileMenu.show {
        display: block;
      }

      /* Media query untuk menyembunyikan/menampilkan menu */
      @media (min-width: 768px) {
        #desktopMenu {
          display: block;
        }
        #mobileMenuButton {
          display: none;
        }
      }

      @media (max-width: 767px) {
        #desktopMenu {
          display: none;
        }
        #mobileMenuButton {
          display: flex;
        }
      }
    </style>
</head>

<body class="font-sans antialiased overflow-x-hidden min-h-screen flex flex-col">
    <!-- Responsive Header with Hamburger Menu -->
    <header class="fixed w-full z-50 transition-all duration-300" id="main-header">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" aria-label="Top">
            <div class="w-full py-4 flex items-center justify-between">
                <!-- Logo (left side) -->
                <a href="{{ route('home') }}" class="text-xl md:text-2xl font-bold text-white" style="text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);">copicop</a>

                <!-- Desktop Navigation Links (center) -->
                <div id="desktopMenu" class="space-x-8">
                    <a href="{{ route('home') }}" class="menu_links">Home</a>
                    <a href="{{ route('about') }}" class="menu_links">About</a>
                    <a href="{{ route('facilities') }}" class="menu_links">Facilities</a>
                    <a href="{{ route('contact') }}" class="menu_links">Contact</a>
                </div>

                <!-- Mobile Menu Button (right side) -->
                <div id="mobileMenuButton">
                    <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-white" onclick="toggleMobileMenu()">
                        <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation Menu (hidden by default) -->
            <div id="mobileMenu" style="display: none; background-color: rgba(0, 0, 0, 0.9); border-radius: 0.375rem; padding: 0.5rem;">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <a href="{{ route('home') }}" class="mobile_links">Home</a>
                    <a href="{{ route('about') }}" class="mobile_links">About</a>
                    <a href="{{ route('facilities') }}" class="mobile_links">Facilities</a>
                    <a href="{{ route('contact') }}" class="mobile_links">Contact</a>
                </div>
            </div>
        </nav>
    </header>

    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Responsive Footer -->
    <footer class="relative z-10 bg-transparent text-white mt-auto">
        <div style="background-color: rgba(0, 0, 0, 0.7); backdrop-filter: blur(4px); padding: 2rem 0;">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div style="display: grid; grid-template-columns: 1fr; gap: 2rem;">
                    <div>
                        <h3 class="text-lg sm:text-xl font-bold mb-4">About Us</h3>
                        <p style="color: #d1d5db; font-size: 0.875rem;">
                            Experience the finest coffee and food in a comfortable atmosphere.
                            We're committed to quality and service.
                        </p>
                    </div>
                    <div style="display: flex; justify-content: flex-start;">
                        <div>
                            <h3 class="text-lg sm:text-xl font-bold mb-4">Contact Us</h3>
                            <ul style="color: #d1d5db; font-size: 0.875rem; margin-bottom: 0.5rem;">
                                <li style="margin-bottom: 0.5rem;">Email: info@example.com</li>
                                <li style="margin-bottom: 0.5rem;">Phone: +1234567890</li>
                                <li style="margin-bottom: 0.5rem;">Address: 123 Coffee Street, City, Country</li>
                            </ul>
                            <div style="margin-top: 1rem; display: flex; gap: 1rem;">
                                <a href="#" class="social_icon">
                                    <i class="fa fa-facebook" style="font-size: 1.25rem;"></i>
                                </a>
                                <a href="#" class="social_icon">
                                    <i class="fa fa-twitter" style="font-size: 1.25rem;"></i>
                                </a>
                                <a href="#" class="social_icon">
                                    <i class="fa fa-instagram" style="font-size: 1.25rem;"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="margin-top: 1.5rem; border-top: 1px solid #4b5563; padding-top: 1.5rem; text-align: center;">
                    <p style="color: #d1d5db; font-size: 0.875rem;">
                        &copy; {{ date('Y') }} Cafe. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Inline JavaScript for header and menu toggling -->
    <script>
        // Header background control
        function updateHeaderBackground() {
            const header = document.getElementById('main-header');
            if (window.scrollY > 50) {
                header.style.backgroundColor = 'rgba(0, 0, 0, 0.7)';
                header.style.backdropFilter = 'blur(4px)';
                header.style.boxShadow = '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)';
            } else {
                header.style.backgroundColor = 'transparent';
                header.style.backdropFilter = 'none';
                header.style.boxShadow = 'none';
            }
        }

        // Mobile menu toggle
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobileMenu');
            if (mobileMenu.style.display === 'none') {
                mobileMenu.style.display = 'block';
            } else {
                mobileMenu.style.display = 'none';
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Check initial scroll position
            updateHeaderBackground();

            // Add scroll event listener
            window.addEventListener('scroll', updateHeaderBackground);
        });
    </script>
</body>

</html>

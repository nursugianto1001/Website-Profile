<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <!-- Updated viewport setting with user-scalable parameter -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Cafe') }} - @yield('title')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Custom Styles with Mobile Fixes -->
    <style>
      /* Color variables */
      :root {
        --semi-gold: #d4af37;
        --semi-gold-lighter: #e5c76b;
        --semi-gold-darker: #b8941e;
        --white: #ffffff;
        --off-white: #f8f8f8;
      }

      /* Added box-sizing reset to prevent overflow issues */
      *, *::before, *::after {
        box-sizing: border-box;
      }

      /* Fixed body styling to prevent overflow issues */
      body {
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        overflow-x: hidden;
        width: 100%;
        position: relative;
        margin: 0;
        padding: 0;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        background-color: #ffffff;
      }

      /* Tailwind classes */
      .menu_links {
        color: var(--white);
        padding: 0.5rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        font-weight: 700;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        transition: color 0.3s ease;
        display: inline-block; /* Added to fix IE compatibility */
      }
      .menu_links:hover {
        color: var(--semi-gold);
      }
      .mobile_links {
        color: var(--white);
        display: block;
        width: 100%; /* Added to ensure links take full width */
        padding: 0.75rem; /* Increased padding for better touch targets */
        border-radius: 0.375rem;
        font-size: 1rem;
        font-weight: 700;
        transition: color 0.3s ease, background-color 0.3s ease;
      }
      .mobile_links:hover {
        color: var(--semi-gold);
        background-color: rgba(55, 65, 81, 0.7);
      }
      .social_icon {
        color: #d1d5db;
        transition: color 0.3s ease;
        display: inline-flex; /* Better for touch targets */
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
      }
      .social_icon:hover {
        color: var(--semi-gold);
      }

      /* Book Now Button with improved mobile styling */
      .book-now-btn {
        background-color: var(--semi-gold);
        color: #1a1a1a;
        padding: 0.5rem 1.25rem;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        font-weight: 700;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        border: 2px solid var(--semi-gold);
        text-align: center;
      }
      .book-now-btn:hover {
        background-color: transparent;
        color: var(--semi-gold);
      }

      /* Improved mobile menu styling */
      #mobileMenu {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        width: 100%;
        z-index: 50;
        background-color: rgba(0, 0, 0, 0.9);
        border-radius: 0 0 0.375rem 0.375rem;
        padding: 0.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
      }

      #mobileMenu.show {
        display: block;
      }

      /* Site logo */
      .site-logo {
        font-weight: 700;
        color: var(--white);
        transition: color 0.3s ease;
        font-size: 1.25rem; /* Base size */
      }
      .site-logo:hover {
        color: var(--semi-gold);
      }

      /* Fixed header styling */
      #main-header {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 50;
        background-color: #1a1a1a;
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
      }

      /* Added explicit margin-top to main content to account for fixed header */
      main {
        margin-top: 70px; /* Adjust based on your header height */
        flex-grow: 1;
        width: 100%;
      }

      /* Footer styles */
      footer {
        width: 100%;
        margin-top: auto;
      }

      /* Improved media queries with more precise breakpoints */
      /* Mobile first approach */
      #desktopMenu {
        display: none;
      }

      #mobileMenuButton {
        display: flex;
      }

      .book-now-mobile {
        display: block;
        margin-top: 0.5rem;
        text-align: center;
        width: 100%;
      }

      /* Tablet and above */
      @media (min-width: 768px) {
        #desktopMenu {
          display: flex;
          justify-content: center;
        }

        #mobileMenuButton {
          display: none;
        }

        .book-now-mobile {
          display: none;
        }

        .site-logo {
          font-size: 1.5rem;
        }

        main {
          margin-top: 80px; /* Slightly larger margin for desktop */
        }
      }

      /* Added explicit styles for different screen sizes */
      @media (max-width: 320px) {
        /* Extra small devices */
        .site-logo {
          font-size: 1.1rem;
        }

        .mobile_links {
          padding: 0.5rem;
        }
      }

      @media (min-width: 321px) and (max-width: 480px) {
        /* Small devices */
        .site-logo {
          font-size: 1.2rem;
        }
      }
    </style>
</head>

<body>
    <!-- Fixed Header with improved mobile layout -->
    <header id="main-header">
        <nav class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8" aria-label="Top">
            <div class="w-full py-4 flex items-center justify-between">
                <!-- Logo (left side) -->
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}" class="site-logo">copicop</a>
                </div>

                <!-- Desktop Navigation Links (centered) -->
                <div id="desktopMenu" class="flex-grow flex justify-center space-x-6">
                    <a href="{{ route('home') }}" class="menu_links">Home</a>
                    <a href="{{ route('about') }}" class="menu_links">About</a>
                    <a href="{{ route('facilities') }}" class="menu_links">Facilities</a>
                    <a href="{{ route('contact') }}" class="menu_links">Contact</a>
                </div>

                <!-- Book Now Button (right side) -->
                <div class="hidden md:block flex-shrink-0">
                    <a href="#" class="book-now-btn">Book Now</a>
                </div>

                <!-- Mobile Menu Button (right side on mobile) -->
                <div id="mobileMenuButton">
                    <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-[#d4af37]" onclick="toggleMobileMenu()">
                        <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Improved Mobile Navigation Menu -->
            <div id="mobileMenu">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <a href="{{ route('home') }}" class="mobile_links">Home</a>
                    <a href="{{ route('about') }}" class="mobile_links">About</a>
                    <a href="{{ route('facilities') }}" class="mobile_links">Facilities</a>
                    <a href="{{ route('contact') }}" class="mobile_links">Contact</a>
                    <a href="#" class="mobile_links book-now-mobile"><span class="book-now-btn w-full inline-block">Book Now</span></a>
                </div>
            </div>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <!-- Responsive Footer with improved layout -->
    <footer>
        <div style="background-color: rgba(0, 0, 0, 0.7); backdrop-filter: blur(4px); padding: 2rem 0;">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-8">
                    <div>
                        <h3 class="text-lg sm:text-xl font-bold mb-4 text-white">Contact Us</h3>
                        <ul class="text-white text-sm space-y-2">
                            <li>Email: info@example.com</li>
                            <li>Phone: +1234567890</li>
                            <li>Address: 123 Coffee Street, City, Country</li>
                        </ul>
                        <div class="mt-4 flex gap-4">
                            <a href="#" class="social_icon">
                                <i class="fa fa-facebook text-xl"></i>
                            </a>
                            <a href="#" class="social_icon">
                                <i class="fa fa-twitter text-xl"></i>
                            </a>
                            <a href="#" class="social_icon">
                                <i class="fa fa-instagram text-xl"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="mt-6 border-t border-[#d4af37] pt-6 text-center">
                    <p class="text-off-white text-sm">
                        &copy; {{ date('Y') }} Cafe. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Improved JavaScript for header and menu toggling -->
    <script>
        // Header background control
        function updateHeaderBackground() {
            const header = document.getElementById('main-header');
            if (window.scrollY > 50) {
                header.style.backgroundColor = 'rgba(0, 0, 0, 0.7)';
                header.style.backdropFilter = 'blur(4px)';
                header.style.boxShadow = '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)';
            } else {
                header.style.backgroundColor = '#1a1a1a';
                header.style.backdropFilter = 'none';
                header.style.boxShadow = 'none';
            }
        }

        // Mobile menu toggle with improved behavior
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobileMenu');
            mobileMenu.style.display = mobileMenu.style.display === 'block' ? 'none' : 'block';
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Check initial scroll position
            updateHeaderBackground();

            // Add scroll event listener
            window.addEventListener('scroll', updateHeaderBackground);

            // Add resize listener to handle orientation changes
            window.addEventListener('resize', function() {
                // Close mobile menu on resize (typical when orientation changes)
                const mobileMenu = document.getElementById('mobileMenu');
                if (window.innerWidth >= 768) {
                    mobileMenu.style.display = 'none';
                }
            });

            // Prevent iOS Safari overscroll/bounce effect which can cause layout issues
            document.body.addEventListener('touchmove', function(e) {
                if (e.target === document.body) {
                    e.preventDefault();
                }
            }, { passive: false });
        });
    </script>
</body>

</html>

@extends('layouts.public')

@section('title', 'Home')

@section('content')
    <!-- Full-screen Parallax Background -->
    <div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('{{ Vite::asset('resources/images/copicop.jpg') }}');">
        <!-- Semi-transparent overlay for better text readability -->
        <div class="absolute inset-0 bg-black opacity-40"></div>
    </div>

    <!-- Hero Section with transparent overlay -->
    <div class="relative h-screen flex items-center z-10 pt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-lg" data-aos="fade-right" data-aos-duration="1000">
                <h1 class="text-5xl font-bold text-white drop-shadow-lg mb-6">
                    Welcome to Profile copicop
                </h1>
                <p class="text-xl text-white drop-shadow-lg mb-8 max-w-md">
                    Tersedia untuk memanjakan selera dan menemani waktu santai Anda, selamat datang di copicop! Di sini, kami menyajikan suasana hangat, cita rasa yang autentik, dan pelayanan yang bersahabat. Jelajahi menu kami dan temukan momen tak terlupakan bersama kami. Nikmati, dan rasakan pengalaman berbeda!
                </p>
                <a href="{{ route('menu') }}" class="bg-white text-gray-900 hover:bg-gray-100 px-8 py-4 rounded-md font-medium transition duration-300 transform hover:scale-105 inline-block shadow-lg">
                    View Our Menu
                </a>
            </div>
        </div>
    </div>

    <!-- About Section - Semi-transparent Layer -->
    <div class="relative z-10">
        <div class="bg-white bg-opacity-80 backdrop-blur-md py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                    <div data-aos="fade-right" data-aos-duration="1000">
                        <h2 class="text-3xl font-bold mb-6">About Us</h2>
                        <div class="w-20 h-1 bg-gray-800 mb-6"></div>
                        <p class="text-gray-700 mb-4">
                            Founded in 2010, our restaurant has been dedicated to providing exceptional dining experiences with quality ingredients and warm service.
                        </p>
                        <p class="text-gray-700 mb-6">
                            Our team of passionate chefs crafts each dish with care, blending traditional techniques with innovative approaches to create memorable flavors that keep our customers coming back.
                        </p>
                        <a href="{{ route('about') }}" class="text-gray-800 font-medium border-b-2 border-gray-800 hover:text-gray-600 hover:border-gray-600 transition duration-300">
                            Learn more about our story
                        </a>
                    </div>
                    <div class="rounded-lg overflow-hidden shadow-xl" data-aos="fade-left" data-aos-duration="1000">
                        <img src="{{ Vite::asset(asset: 'resources/images/copicop.jpg') }}" alt="About Us" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Menu Section - Semi-transparent Layer -->
    <div class="relative z-10">
        <div class="bg-black bg-opacity-70 backdrop-blur-md py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12" data-aos="fade-up" data-aos-duration="800">
                    <h2 class="text-3xl font-bold text-white">Featured Menu Items</h2>
                    <div class="w-20 h-1 bg-white mx-auto mt-4 mb-2"></div>
                    <p class="text-gray-300">Discover our chef's special selections</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($featuredMenus as $index => $menu)
                        <div class="bg-white bg-opacity-90 shadow-lg rounded-lg overflow-hidden transition transform hover:scale-105"
                             data-aos="fade-up"
                             data-aos-duration="800"
                             data-aos-delay="{{ $index * 100 }}">
                            <img src="{{ asset('storage/' . $menu->image_path) }}" alt="{{ $menu->name }}" class="w-full h-64 object-cover">
                            <div class="p-6">
                                <h3 class="text-xl font-bold mb-2">{{ $menu->name }}</h3>
                                <p class="text-gray-600 mb-4">{{ Str::limit($menu->description, 100) }}</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-bold text-gray-900">Rp{{ number_format($menu->price, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="text-center mt-10" data-aos="fade-up" data-aos-duration="800" data-aos-delay="300">
                    <a href="{{ route('menu') }}" class="bg-white text-gray-900 hover:bg-gray-100 px-6 py-3 rounded-md font-medium transition duration-300 inline-block shadow-lg">
                        View Full Menu
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Outlets Section - Semi-transparent Layer -->
    <div class="relative z-10">
        <div class="bg-white bg-opacity-80 backdrop-blur-md py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12" data-aos="fade-up" data-aos-duration="800">
                    <h2 class="text-3xl font-bold">Our Locations</h2>
                    <div class="w-20 h-1 bg-gray-800 mx-auto mt-4 mb-2"></div>
                    <p class="text-gray-600">Visit us at one of our convenient locations</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($outlets as $index => $outlet)
                        <div class="bg-white shadow-lg rounded-lg overflow-hidden transition transform hover:scale-105"
                             data-aos="fade-up"
                             data-aos-duration="800"
                             data-aos-delay="{{ $index * 100 }}">
                            <img src="{{ asset('storage/' . $outlet->image_path) }}" alt="{{ $outlet->name }}" class="w-full h-48 object-cover">
                            <div class="p-6">
                                <h3 class="text-xl font-bold mb-2">{{ $outlet->name }}</h3>
                                <p class="text-gray-600 mb-2">{{ $outlet->address }}</p>
                                <p class="text-gray-600 mb-2"><strong>Hours:</strong> {{ $outlet->opening_hours }}</p>
                                <p class="text-gray-600"><strong>Contact:</strong> {{ $outlet->contact }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="text-center mt-10" data-aos="fade-up" data-aos-duration="800" data-aos-delay="300">
                    <a href="{{ route('outlets') }}" class="bg-gray-800 text-white hover:bg-gray-700 px-6 py-3 rounded-md font-medium transition duration-300 inline-block shadow-lg">
                        View All Locations
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- AOS Library Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                once: true,
                offset: 120,
                duration: 800
            });
        });
    </script>
@endsection

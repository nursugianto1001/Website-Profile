@extends('layouts.public')

@section('title', 'Home')

@section('content')
    <!-- Full-screen Video Background -->
    <div class="fixed inset-0 z-0 overflow-hidden">
        @php
            $backgroundVideo = \App\Models\BackgroundVideo::getActive();
        @endphp
        
        @if($backgroundVideo)
            <video autoplay muted loop class="absolute min-w-full min-h-full object-cover">
                <source src="{{ Storage::url($backgroundVideo->path) }}" type="{{ $backgroundVideo->mime_type }}">
                Your browser does not support the video tag.
            </video>
        @else
            <!-- Fallback to image if no video is active -->
            <div class="bg-cover bg-center absolute inset-0" style="background-image: url('{{ Vite::asset('resources/images/copicop.jpg') }}');"></div>
        @endif
        
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
                </div>
        </div>
    </div>

    <!-- Rest of the content remains the same -->
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
                        <img src="{{ Vite::asset('resources/images/copicop.jpg') }}" alt="About Us" class="w-full h-full object-cover">
                    </div>
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
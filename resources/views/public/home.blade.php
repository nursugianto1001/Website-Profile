@extends('layouts.public')

@section('title', 'Home')

@section('content')
    <!-- Hero Section with Video Background (fixed position) -->
    <div class="relative h-screen">
        <!-- Fixed Video Background that doesn't scroll -->
        <div class="fixed inset-0 z-0">
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

        <!-- Hero content -->
        <div class="relative z-10 h-full flex">
            <div class="container mx-0 px-0">
                <div class="max-w-lg pl-4 md:pl-8 lg:pl-16 mt-16" data-aos="fade-right" data-aos-duration="1000">
                    <h1 class="text-5xl font-bold text-white drop-shadow-lg mb-6">
                        Welcome to Profile copicop
                    </h1>
                    <p class="text-xl text-white drop-shadow-lg mb-8 max-w-md">
                        Tersedia untuk memanjakan selera dan menemani waktu santai Anda, selamat datang di copicop! Di sini, kami menyajikan suasana hangat, cita rasa yang autentik, dan pelayanan yang bersahabat. Jelajahi menu kami dan temukan momen tak terlupakan bersama kami. Nikmati, dan rasakan pengalaman berbeda!
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content that will scroll over the fixed background -->
    <div class="relative z-20">
        <!-- About Section - Full White Background -->
        <div class="bg-white-900 py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                    <div data-aos="fade-right" data-aos-duration="1000">
                        <h2 class="text-3xl font-bold text-white mb-6">About Us</h2>
                        
                        <p class="text-white 700 mb-4">
                            Founded in 2010, our restaurant has been dedicated to providing exceptional dining experiences with quality ingredients and warm service.
                        </p>
                        <p class="text-white 700 mb-6">
                            Our team of passionate chefs crafts each dish with care, blending traditional techniques with innovative approaches to create memorable flavors that keep our customers coming back.
                        </p>
                        <a href="{{ route('about') }}" class="text-white 800 font-medium border-b-2 border-white-800 hover:text-white-600 hover:border-gray-600 transition duration-300">
                            Learn more about our story
                        </a>
                    </div>
                    <div class="rounded-lg overflow-hidden shadow-xl" data-aos="fade-left" data-aos-duration="1000">
                        <img src="{{ Vite::asset('resources/images/copicop.jpg') }}" alt="About Us" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </div>

        <!-- Facilities Overview Section - Dark Background -->
        <div class="bg-white-900 py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12" data-aos="fade-up" data-aos-duration="800">
                    <h2 class="text-3xl font-bold text-white">Our Facilities</h2>
                    <br>
                    <p class="text-white 700 mb-4">
                        We offer a variety of amenities to enhance your experience at our cafes.
                        Whether you're looking for a quiet space to work, a cozy corner to read, or a venue for your next event,
                        we have something for everyone.
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    @foreach($facilities as $index => $facility)
                        <div class="bg-white shadow-lg rounded-lg overflow-hidden flex flex-col md:flex-row transition transform hover:scale-105"
                             data-aos="{{ $index % 2 == 0 ? 'fade-right' : 'fade-left' }}"
                             data-aos-duration="800"
                             data-aos-delay="{{ $index * 100 }}">
                            <div class="md:w-2/5">
                                <img src="{{ asset('storage/' . $facility->image_path) }}" alt="{{ $facility->name }}" class="h-full w-full object-cover">
                            </div>
                            <div class="p-6 md:w-3/5">
                                <h3 class="text-2xl font-bold mb-4">{{ $facility->name }}</h3>
                                <p class="text-gray-700">
                                    {{ $facility->description }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="text-center mt-12">
                    <a href="{{ route('facilities') }}" class="inline-block px-6 py-3 bg-yellow-500 text-white font-medium rounded hover:bg-yellow-600 transition duration-300" data-aos="fade-up" data-aos-duration="800">
                        View All Facilities
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
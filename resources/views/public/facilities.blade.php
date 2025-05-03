@extends('layouts.public')

@section('title', 'Facilities')

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
                    Our Facilities
                </h1>
                <p class="text-xl text-white drop-shadow-lg mb-8 max-w-md">
                    We offer a variety of amenities to enhance your experience at our cafes.
                    Whether you're looking for a quiet space to work, a cozy corner to read, or a venue for your next event,
                    we have something for everyone.
                </p>
            </div>
        </div>
    </div>

    <!-- Facilities Overview Section - Semi-transparent Layer -->
    <div class="relative z-10">
        <div class="bg-white bg-opacity-80 backdrop-blur-md py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12" data-aos="fade-up" data-aos-duration="800">
                    <h2 class="text-3xl font-bold">Our Facilities</h2>
                    <div class="w-20 h-1 bg-gray-800 mx-auto mt-4 mb-2"></div>
                    <p class="text-gray-600 max-w-3xl mx-auto">
                        We offer a variety of amenities to enhance your experience at our cafes.
                        Whether you're looking for a quiet space to work, a cozy corner to read, or a venue for your next event,
                        we have something for everyone.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Facilities List Section - Semi-transparent Layer -->
    <div class="relative z-10">
        <div class="bg-black bg-opacity-70 backdrop-blur-md py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    @foreach($facilities as $index => $facility)
                        <div class="bg-white bg-opacity-90 shadow-lg rounded-lg overflow-hidden flex flex-col md:flex-row transition transform hover:scale-105"
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
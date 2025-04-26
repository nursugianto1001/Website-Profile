@extends('layouts.public')

@section('title', 'Outlets')

@section('content')
    <!-- Full-screen Parallax Background -->
    <div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('{{ Vite::asset('resources/images/copicop.jpg') }}');">
        <!-- Semi-transparent overlay for better text readability -->
        <div class="absolute inset-0 bg-black opacity-40"></div>
    </div>

    <!-- Main Content - With proper spacing for the fixed header -->
    <div class="relative z-10 pt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <h1 class="text-4xl font-bold mb-8 text-center text-white">Our Outlets</h1>

            <div class="mb-8" data-aos="fade-up" data-aos-duration="800">
                <p class="text-center text-white max-w-3xl mx-auto">
                    Visit us at any of our convenient locations. Each cafe offers the same quality and service you've come to expect from us,
                    with unique atmospheres that reflect the neighborhoods they serve.
                </p>
            </div>

            <div class="bg-white bg-opacity-80 backdrop-blur-md p-8 rounded-lg shadow-lg" data-aos="fade-up" data-aos-duration="800">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($outlets as $index => $outlet)
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden transition transform hover:scale-105"
                         data-aos="fade-up"
                         data-aos-duration="800"
                         data-aos-delay="{{ $index * 100 }}">
                        <img src="{{ asset('storage/' . $outlet->image_path) }}" alt="{{ $outlet->name }}" class="w-full h-64 object-cover">
                        <div class="p-6">
                            <h3 class="text-xl font-bold mb-3">{{ $outlet->name }}</h3>
                            <div class="mb-4">
                                <p class="text-gray-700 mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    {{ $outlet->address }}
                                </p>
                                <p class="text-gray-700 mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $outlet->opening_hours }}
                                </p>
                                <p class="text-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    {{ $outlet->contact }}
                                </p>
                            </div>
                            <div class="pt-4 border-t border-gray-200">
                                <a href="https://maps.google.com/?q={{ urlencode($outlet->address) }}" target="_blank" class="text-blue-600 hover:text-blue-800 font-medium flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                    </svg>
                                    View on Map
                                </a>
                            </div>
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

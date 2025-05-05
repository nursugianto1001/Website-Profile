@extends('layouts.public')

@section('title', 'Facilities')

@section('content')
    <!-- Hero Section with Video Background -->
    <div class="relative h-screen -mt-[70px] md:-mt-[80px]">
        <!-- Video Background - Positioned with lower z-index so header appears above it -->
        <div class="absolute inset-0 w-full h-full z-10 overflow-hidden">
            @php
                $backgroundVideo = \App\Models\BackgroundVideo::getActive();
            @endphp

            @if ($backgroundVideo)
                <video autoplay muted loop playsinline class="w-full h-full object-cover">
                    <source src="{{ Storage::url($backgroundVideo->path) }}" type="{{ $backgroundVideo->mime_type }}">
                    Your browser does not support the video tag.
                </video>
            @else
                <!-- Fallback to image -->
                <div class="absolute inset-0 bg-cover bg-center"
                    style="background-image: url('{{ Vite::asset('resources/images/copicop.jpg') }}');"></div>
            @endif
        </div>

        <!-- Hero Content (Centered) - With higher z-index than video but lower than header -->
        <div class="absolute inset-0 z-20 flex items-center justify-left text-white text-left px-4 md:px-72 pt-16">
            <div class="max-w-xl" data-aos="fade-up" data-aos-duration="1000">
                <h1 class="text-5xl font-bold drop-shadow-lg leading-tight"
                    style="font-family: 'Helvetica', sans-serif; font-weight: 700;">Fasilitas Kami</h1>
                <p class="text-m drop-shadow-lg mt-4" style="font-family: 'Helvetica', sans-serif;">
                    Kami menyediakan berbagai fasilitas untuk mendukung pengalaman bermain badminton terbaik Anda. Baik
                    untuk pertandingan santai, latihan rutin, maupun sekadar bermain bersama teman, tempat kami dirancang
                    untuk memenuhi segala kebutuhan Anda.
                </p>
            </div>
        </div>
    </div>

    <!-- Scrollable Content -->
    <div class="relative z-30 bg-white">
        <!-- Facilities Overview Section -->
        <div class="py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12" data-aos="fade-up" data-aos-duration="800">
                    <h2 class="text-3xl font-bold text-black">Fasilitas-Fasilitas Kami</h2>
                    <br>
                    <p class="text-gray-500 mb-4 max-w-3xl mx-auto">
                        Kami menyediakan berbagai fasilitas untuk mendukung pengalaman bermain badminton terbaik Anda. Baik
                        untuk pertandingan santai, latihan rutin, maupun sekadar bermain bersama teman, tempat kami
                        dirancang untuk memenuhi segala kebutuhan Anda.
                    </p>
                </div>
            </div>
        </div>

        <!-- Facilities List Section -->
        <div class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    @foreach ($facilities as $index => $facility)
                        <div class="bg-white shadow-lg rounded-lg overflow-hidden flex flex-col md:flex-row transition transform hover:scale-105"
                            data-aos="{{ $index % 2 == 0 ? 'fade-right' : 'fade-left' }}" data-aos-duration="800"
                            data-aos-delay="{{ $index * 100 }}">

                            <!-- Gambar + Kontainer -->
                            <div
                                class="w-full md:max-w-[250px] md:max-h-[250px] flex justify-center items-center bg-gray-100 mx-auto">
                                <img src="{{ asset('storage/' . $facility->image_path) }}" alt="{{ $facility->name }}"
                                    class="w-full h-auto md:h-[250px] object-cover aspect-square">
                            </div>

                            <!-- Konten -->
                            <div class="p-6 flex-1">
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

        <!-- Footer should render here because it's included in the layouts.public template -->
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

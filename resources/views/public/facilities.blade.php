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
                    style="background-image: url('{{ Vite::asset('resources/images/open.jpg') }}');"></div>
            @endif
        </div>

        <!-- Hero Content (Centered) - With higher z-index than video but lower than header -->
        <div class="absolute inset-0 z-20 flex items-center justify-left text-white text-left px-4 md:px-72 pt-16">
            <div class="max-w-xl" data-aos="fade-up" data-aos-duration="1000">
                <h1 class="text-5xl font-bold drop-shadow-lg leading-tight font-sans">
                    Fasilitas Kami
                </h1>
                <p class="text-m drop-shadow-lg mt-4 font-sans">
                    Kami menyediakan berbagai fasilitas untuk mendukung pengalaman bermain badminton terbaik kamu. Baik
                    untuk pertandingan santai, latihan rutin, maupun sekadar bermain bersama teman, tempat kami dirancang
                    untuk memenuhi segala kebutuhan Anda.
                </p>
            </div>
        </div>
    </div>

    <!-- Scrollable Content -->
    <div class="relative z-30 bg-white">
        <!-- Facilities Overview Section -->
        <div class="py-20 bg-amber-50/70">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12" data-aos="fade-up" data-aos-duration="800">
                    <h2 class="text-3xl font-bold text-[#A66E38]">Fasilitas-Fasilitas Kami</h2>
                    <br>
                    <p class="text-gray-500 mb-24 max-w-3xl mx-auto">
                        Karvin menyediakan lapangan bulu tangkis berkualitas dengan lingkungan yang nyaman dan bersih.
                        Dilengkapi dengan peralatan modern dan akses mudah, kami memastikan setiap pemain dapat menikmati
                        pengalaman bermain yang menyenangkan dan mendukung gaya hidup sehat.
                    </p>

                    <!-- Desktop View - Grid -->
                    <div class="hidden md:grid md:grid-cols-2 gap-12">
                        @foreach ($facilities as $index => $facility)
                            <div class="bg-gradient-to-br {{ $index % 3 == 0 ? 'from-amber-50 via-amber-100/30 to-[#fdf5e9]' : ($index % 3 == 1 ? 'from-[#fcf7f1] via-amber-50/40 to-[#faebd7]' : 'from-[#fcf9f2] via-[#f8e8d4] to-[#fef8f5]') }} shadow-lg rounded-lg overflow-hidden flex flex-row transition transform hover:scale-105"
                                data-aos="{{ $index % 2 == 0 ? 'fade-right' : 'fade-left' }}" data-aos-duration="800"
                                data-aos-delay="{{ $index * 100 }}">
                                <div
                                    class="max-w-[250px] max-h-[250px] flex justify-center items-center {{ $index % 3 == 0 ? 'bg-amber-50' : ($index % 3 == 1 ? 'bg-[#faebd7]' : 'bg-[#f8e8d4]') }}">
                                    <img src="{{ asset('storage/' . $facility->image_path) }}" alt="{{ $facility->name }}"
                                        class="w-full h-[250px] object-cover aspect-square">
                                </div>
                                <div class="p-6 w-3/5">
                                    <h3
                                        class="text-2xl font-bold mb-4 {{ $index % 3 == 0 ? 'text-[#A66E38]' : 'text-[#8B5A2B]' }}">
                                        {{ $facility->name }}</h3>
                                    <p class="text-gray-700">
                                        {{ $facility->description }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Mobile View - Swiper Slider -->
                    <div class="md:hidden">
                        <div class="swiper-container facilities-swiper">
                            <div class="swiper-wrapper">
                                @foreach ($facilities->take(3) as $index => $facility)
                                    <div class="swiper-slide px-1">
                                        <div
                                            class="bg-gradient-to-br {{ $index % 3 == 0 ? 'from-amber-50 via-amber-100/30 to-[#fdf5e9]' : ($index % 3 == 1 ? 'from-[#fcf7f1] via-amber-50/40 to-[#faebd7]' : 'from-[#fcf9f2] via-[#f8e8d4] to-[#fef8f5]') }} shadow-lg rounded-lg overflow-hidden flex flex-col h-[400px]">
                                            <div
                                                class="{{ $index % 3 == 0 ? 'bg-amber-50' : ($index % 3 == 1 ? 'bg-[#faebd7]' : 'bg-[#f8e8d4]') }} flex justify-center items-center h-[180px]">
                                                <img src="{{ asset('storage/' . $facility->image_path) }}"
                                                    alt="{{ $facility->name }}" class="w-full h-full object-cover">
                                            </div>
                                            <div class="p-4 flex-grow overflow-y-auto">
                                                <h3
                                                    class="text-lg font-bold mb-2 {{ $index % 3 == 0 ? 'text-[#A66E38]' : 'text-[#8B5A2B]' }}">
                                                    {{ $facility->name }}</h3>
                                                <p class="text-gray-700 text-sm">
                                                    {{ Str::limit($facility->description, 120) }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <!-- Add pagination bullets -->
                            <div class="swiper-pagination mt-4"></div>
                            <!-- Add navigation arrows -->
                            <div class="swiper-button-next text-[#A66E38]"></div>
                            <div class="swiper-button-prev text-[#A66E38]"></div>
                        </div>
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

@extends('layouts.public')

@section('title', 'Home')

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
                <h1 class="text-5xl font-bold drop-shadow-lg mb-6"> Waktunya Bergerak, Waktunya Karvin </h1>
                <h1 class="text-5xl font-bold drop-shadow-lg mb-6 h-[4rem] overflow-hidden">
                    <span id="slide-text"class="block transition-all duration-500 ease-in-out"></span>
                </h1>
                <p class="text-m drop-shadow-lg mb-8">
                    Raih semangat juara dan maksimalkan performamu di lapangan <br> badminton Karvin sekarang, mudah, cepat, dan tanpa antre!
                </p>
            </div>
        </div>
    </div>

    <!-- Scrollable Content -->
    <div class="relative z-30 bg-white" id="about">
        <!-- About Section -->
        <div class="py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                    <div data-aos="fade-right" data-aos-duration="1000">
                        <h2 class="text-3xl font-bold text-black mb-6">About Us</h2>
                        <p class="text-gray-500 mb-4">
                            Founded in 2010, our restaurant has been dedicated to providing exceptional dining experiences
                            with quality ingredients and warm service.
                        </p>
                        <p class="text-gray-500 mb-6">
                            Our team of passionate chefs crafts each dish with care, blending traditional techniques with
                            innovative approaches to create memorable flavors that keep our customers coming back.
                        </p>
                        <a href="{{ route('about') }}"
                            class="text-black font-medium border-b-2 border-black transition duration-300"
                            onmouseover="this.style.color='#A66E38'; this.style.borderColor='#A66E38';"
                            onmouseout="this.style.color=''; this.style.borderColor='';">
                            Learn more about our story
                        </a>
                    </div>
                    <div class="rounded-lg overflow-hidden shadow-xl" data-aos="fade-left" data-aos-duration="1000">
                        <img src="{{ Vite::asset('resources/images/copicop.jpg') }}" alt="About Us"
                            class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </div>

        <!-- Facilities Section -->
        <div class="py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12" data-aos="fade-up" data-aos-duration="800">
                    <h2 class="text-3xl font-bold text-black">Our Facilities</h2>
                    <br>
                    <p class="text-gray-500 mb-4">
                        We offer a variety of amenities to enhance your experience at our cafes.
                        Whether you're looking for a quiet space to work, a cozy corner to read, or a venue for your next
                        event, we have something for everyone.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    @foreach ($facilities as $index => $facility)
                        <div class="bg-white shadow-lg rounded-lg overflow-hidden flex flex-col md:flex-row transition transform hover:scale-105"
                            data-aos="{{ $index % 2 == 0 ? 'fade-right' : 'fade-left' }}" data-aos-duration="800"
                            data-aos-delay="{{ $index * 100 }}">
                            <div class="md:w-2/5">
                                <img src="{{ asset('storage/' . $facility->image_path) }}" alt="{{ $facility->name }}"
                                    class="h-full w-full object-cover">
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
                    <a href="{{ route('facilities') }}"
                        class="inline-block px-6 py-3 text-white font-medium rounded transition duration-300"
                        style="background-color: #A66E38;" onmouseover="this.style.backgroundColor='#1A1A19';"
                        onmouseout="this.style.backgroundColor='#A66E38';" data-aos="fade-up" data-aos-duration="800">
                        View All Facilities
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- AOS Scripts -->
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

    <script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                once: true,
                offset: 120,
                duration: 800
            });

            const phrases = [
                "Ayo Booking",
                "Ayo Gerak",
                "Siapkan Raketmu",
                "Kamu Siap?",
            ];

            let currentIndex = 0;
            const textEl = document.getElementById('slide-text');

            setInterval(() => {
                textEl.classList.remove('opacity-100', 'translate-y-0');
                textEl.classList.add('opacity-0', '-translate-y-5');

                setTimeout(() => {
                    currentIndex = (currentIndex + 1) % phrases.length;
                    textEl.textContent = phrases[currentIndex];
                    textEl.classList.remove('opacity-0', '-translate-y-5');
                    textEl.classList.add('opacity-100', 'translate-y-0');
                }, 500); // transition out and change text
            }, 3500);
        });
    </script>

@endsection

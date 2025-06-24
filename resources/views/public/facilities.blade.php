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
            <h1 class="text-3xl md:text-5xl font-bold drop-shadow-lg leading-tight font-sans">
                Fasilitas Kami
            </h1>
            <p class="text-sm md:text-base drop-shadow-lg mt-4 font-sans">
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
    <div class="py-12 md:py-20 bg-amber-50/70">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8 md:mb-12" data-aos="fade-up" data-aos-duration="800">
                <h2 class="text-2xl md:text-3xl font-bold text-[#A66E38] mb-4">Fasilitas-Fasilitas Kami</h2>
                <p class="text-gray-500 mb-12 md:mb-24 max-w-3xl mx-auto text-sm md:text-base">
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
                                {{ $facility->name }}
                            </h3>
                            <p class="text-gray-700">
                                {{ $facility->description }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Mobile View - Card Layout -->
                <div class="md:hidden space-y-6">
                    @foreach ($facilities as $index => $facility)
                    <div class="bg-gradient-to-br {{ $index % 3 == 0 ? 'from-amber-50 via-amber-100/30 to-[#fdf5e9]' : ($index % 3 == 1 ? 'from-[#fcf7f1] via-amber-50/40 to-[#faebd7]' : 'from-[#fcf9f2] via-[#f8e8d4] to-[#fef8f5]') }} shadow-lg rounded-lg overflow-hidden mx-2"
                        data-aos="fade-up" data-aos-duration="600" data-aos-delay="{{ $index * 100 }}">

                        <!-- Image Section -->
                        <div
                            class="{{ $index % 3 == 0 ? 'bg-amber-50' : ($index % 3 == 1 ? 'bg-[#faebd7]' : 'bg-[#f8e8d4]') }} flex justify-center items-center">
                            <img src="{{ asset('storage/' . $facility->image_path) }}" alt="{{ $facility->name }}"
                                class="w-full h-48 object-cover">
                        </div>

                        <!-- Content Section -->
                        <div class="p-4">
                            <h3
                                class="text-xl font-bold mb-3 {{ $index % 3 == 0 ? 'text-[#A66E38]' : 'text-[#8B5A2B]' }} text-center">
                                {{ $facility->name }}
                            </h3>
                            <p class="text-gray-700 text-sm leading-relaxed text-justify">
                                {{ $facility->description }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- WhatsApp Floating Button -->
<div class="fixed bottom-6 right-6 z-50">
    <a href="https://wa.me/6282210002256?text=Halo%20admin%20Karvin%20Badminton,%20saya%20ingin%20bertanya%20tentang%20booking%20lapangan."
        target="_blank"
        class="group flex items-center justify-center w-14 h-14 bg-green-500 hover:bg-green-600 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-110">
        <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
            <path
                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.893 3.690" />
        </svg>
    </a>

    <!-- Tooltip -->
    <div
        class="absolute right-16 top-1/2 transform -translate-y-1/2 bg-gray-800 text-white text-sm px-3 py-2 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap pointer-events-none">
        Chat dengan Admin
        <div
            class="absolute left-full top-1/2 transform -translate-y-1/2 border-4 border-transparent border-l-gray-800">
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
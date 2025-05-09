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
                    style="background-image: url('{{ Vite::asset('resources/images/open.jpg') }}');"></div>
            @endif
        </div>

        <!-- Hero Content - Adjusted for better mobile responsiveness -->
        <div
            class="absolute inset-0 z-20 flex flex-col items-start justify-center text-white px-6 sm:px-10 md:px-16 lg:px-72 pt-16">
            <div class="max-w-xl" data-aos="fade-up" data-aos-duration="1000">
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold drop-shadow-lg leading-tight"
                    style="font-family: 'Helvetica', sans-serif; font-weight: 700;">Waktunya Bergerak,</h1>
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold drop-shadow-lg leading-tight mb-2"
                    style="font-family: 'Helvetica', sans-serif; font-weight: 700;">Waktunya Karvin</h1>
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold drop-shadow-lg h-[3rem] md:h-[4rem] overflow-hidden leading-tight"
                    style="font-family: 'Helvetica', sans-serif; font-weight: 700;">
                    <span id="slide-text" class="block transition-all duration-500 ease-in-out">Nyalakan Semangatmu</span>
                </h1>
                <p class="text-sm sm:text-base drop-shadow-lg -mt-1 mb-6" style="font-family: 'Helvetica', sans-serif;">Raih
                    semangat juara dan maksimalkan performamu di lapangan badminton Karvin sekarang, mudah, cepat, dan tanpa
                    antre!</p>

                <!-- CTA Buttons - Only visible on mobile -->
                <div class="flex sm:hidden mt-4">
                    <a href="{{ route('booking.form') }}"
                        class="inline-block px-6 py-3 text-white font-medium rounded text-center transition duration-300"
                        style="background-color: #A66E38;" onmouseover="this.style.backgroundColor='#1A1A19';"
                        onmouseout="this.style.backgroundColor='#A66E38';">
                        Pesan Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Scrollable Content - Clear separation from hero section -->
    <div class="py-20 bg-amber-50/70">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div data-aos="fade-right" data-aos-duration="1000">
                    <h2 class="text-3xl font-bold text-[#A66E38] mb-6">Tentang Kami</h2>
                    <p class="text-gray-700 mb-4">
                        Karvin hadir pertama kali pada 30 Oktober 2024, lahir dari keinginan sederhana: menyediakan tempat
                        main bulu tangkis yang gak cuma nyaman, tapi juga bikin betah. Kami tahu, nyari lapangan yang enak
                        itu kadang susah yang nggak antre panjang, bersih, pencahayaannya oke, dan suasananya bikin
                        semangat. Maka dari itu, Karvin coba jadi jawaban.
                    </p>
                    <a href="{{ route('about') }}"
                        class="text-black font-medium border-b-2 border-black transition duration-300"
                        onmouseover="this.style.color='#A66E38'; this.style.borderColor='#A66E38';"
                        onmouseout="this.style.color='#000000'; this.style.borderColor='#000000';">
                        Pelajari lebih lanjut tentang perjalanan kami
                    </a>
                </div>
                <div class="order-2 md:order-1" data-aos="fade-left" data-aos-duration="1000">
                    <img src="{{ Vite::asset('resources/images/servis.jpg') }}" alt="About Us"
                        class="w-[500px] h-[500px] rounded-lg max-w-md mx-auto">
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Update Featured Posters Section -->
    @if ($featuredPosters->count() > 0)
        <div class="py-16 bg-gradient-to-b from-amber-50/90 to-[#f8e8d4]/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12" data-aos="fade-up" data-aos-duration="800">
                    <h2 class="text-3xl font-bold text-[#A66E38]">Poster Terbaru</h2>
                    <p class="text-gray-700 mt-4">
                        Temukan informasi terkini tentang turnamen, promosi spesial, dan program pelatihan menarik di
                        Karvin Badminton. Kami secara rutin mengadakan berbagai kegiatan untuk membangun komunitas
                        badminton yang aktif dan bersemangat.
                    </p>
                </div>

                <!-- Desktop Grid View -->
                <div class="hidden md:grid md:grid-cols-3 gap-8" data-aos="fade-up" data-aos-duration="800">
                    @foreach ($featuredPosters->take(3) as $index => $poster)
                        <div class="bg-white rounded-lg overflow-hidden shadow-lg transition transform hover:scale-[1.02] cursor-pointer poster-item hover:shadow-amber-200"
                            data-image="{{ asset('storage/' . $poster->image_path) }}" data-title="{{ $poster->title }}"
                            data-description="{{ $poster->description }}" data-index="{{ $index }}"
                            onclick="showPosterPreview(this)">
                            <div class="h-60 overflow-hidden">
                                <img src="{{ asset('storage/' . $poster->image_path) }}" alt="{{ $poster->title }}"
                                    class="w-full h-full object-cover transition-transform duration-700 hover:scale-110">
                            </div>
                            <div class="p-6 bg-gradient-to-b from-white to-amber-50">
                                <h3 class="text-xl font-semibold mb-2 text-[#8B5A2B]">{{ $poster->title }}</h3>
                                @if ($poster->description)
                                    <p class="text-gray-700 text-sm">{{ Str::limit($poster->description, 100) }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Mobile Swiper View -->
                <div class="md:hidden" data-aos="fade-up" data-aos-duration="800">
                    <div class="swiper-container posters-swiper">
                        <div class="swiper-wrapper">
                            @foreach ($featuredPosters as $index => $poster)
                                <div class="swiper-slide">
                                    <div class="bg-white rounded-lg overflow-hidden shadow-lg transition transform hover:shadow-amber-200 h-[420px] cursor-pointer poster-item"
                                        data-image="{{ asset('storage/' . $poster->image_path) }}"
                                        data-title="{{ $poster->title }}" data-description="{{ $poster->description }}"
                                        data-index="{{ $index }}" onclick="showPosterPreview(this)">
                                        <div class="h-60 overflow-hidden">
                                            <img src="{{ asset('storage/' . $poster->image_path) }}"
                                                alt="{{ $poster->title }}"
                                                class="w-full h-full object-cover transition-transform duration-700 poster-image">
                                        </div>
                                        <div class="p-5 bg-gradient-to-b from-white to-amber-50 h-[180px] flex flex-col">
                                            <h3 class="text-lg font-semibold mb-2 text-[#8B5A2B]">{{ $poster->title }}</h3>
                                            @if ($poster->description)
                                                <p class="text-gray-700 text-sm flex-grow overflow-y-auto">
                                                    {{ Str::limit($poster->description, 120) }}
                                                </p>
                                            @endif
                                            <div class="text-right mt-3">
                                                <span
                                                    class="inline-block px-4 py-1 bg-amber-100 text-amber-800 rounded-full text-xs font-medium mt-2 animate-pulse">
                                                    Ketuk untuk detail
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <!-- Add pagination dots -->
                        <div class="swiper-pagination mt-6"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for poster preview -->
        <div id="posterModal"
            class="fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-50 hidden transition-opacity duration-300 opacity-0">
            <div
                class="relative bg-white rounded-lg max-w-3xl w-full mx-4 overflow-hidden shadow-2xl transition-transform duration-500 transform scale-90">
                <button onclick="closePosterPreview()"
                    class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 z-10 bg-white rounded-full p-2 shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <div class="flex flex-col md:flex-row">
                    <div class="w-full md:w-1/2 bg-gray-100">
                        <img id="modalImage" src="" alt="Poster"
                            class="w-full h-full object-contain max-h-[60vh]">
                    </div>
                    <div class="w-full md:w-1/2 p-6 md:p-8 bg-gradient-to-br from-amber-50 to-white">
                        <h3 id="modalTitle" class="text-2xl font-bold text-[#8B5A2B] mb-4"></h3>
                        <div id="modalDescription" class="text-gray-700 prose max-w-none"></div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- 3. Update Facilities Section -->
    <div class="py-10 bg-[#fdf8f2]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12" data-aos="fade-up" data-aos-duration="800">
                <h2 class="text-3xl font-bold text-[#A66E38]">Fasilitas-Fasilitas Kami</h2>
                <br>
                <p class="text-gray-700 mb-4">
                    Karvin Badminton menyediakan fasilitas premium yang dirancang untuk memenuhi kebutuhan pemain
                    berbagai level. Dari sistem pencahayaan berstandar internasional hingga area istirahat yang nyaman,
                    setiap detail disiapkan untuk memastikan Anda mendapatkan pengalaman bermain terbaik.
                </p>
            </div>

            <!-- Desktop View - Grid -->
            <div class="hidden md:grid md:grid-cols-2 gap-12">
                @foreach ($facilities->take(3) as $index => $facility)
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

            <div class="text-center mt-12">
                <a href="{{ route('facilities') }}"
                    class="inline-block px-6 py-3 text-white font-medium rounded transition duration-300"
                    style="background-color: #A66E38;" onmouseover="this.style.backgroundColor='#8B5A2B';"
                    onmouseout="this.style.backgroundColor='#A66E38';" data-aos="fade-up" data-aos-duration="800">
                    Lihat Semua Fasilitas
                </a>
            </div>
        </div>
    </div>

    <!-- 4. Update Documentation Photos Gallery -->
    @if ($featuredDocumentations->count() > 0)
        <div class="py-16 bg-gradient-to-b from-[#faebd7]/80 to-amber-50/70">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12" data-aos="fade-up" data-aos-duration="800">
                    <h2 class="text-3xl font-bold text-[#A66E38]">Galeri Dokumentasi</h2>
                    <p class="text-gray-700 mt-4">
                        Lihat momen-momen berkesan dari berbagai kegiatan di Karvin Badminton. Kami menangkap semangat,
                        kegembiraan, dan sportivitas dalam setiap turnamen, pelatihan, dan aktivitas komunitas yang
                        berlangsung di fasilitas kami.
                    </p>
                </div>

                <!-- Improved carousel with proper layering and warmer colors -->
                <div class="relative">
                    <div class="flex overflow-x-auto snap-x snap-mandatory gap-4 px-4 py-12 scrollbar-hide"
                        id="imageCarousel">
                        @foreach ($featuredDocumentations as $index => $doc)
                            <a href="{{ asset('storage/' . $doc->image_path) }}" data-pswp-width="1200"
                                data-pswp-height="800" data-index="{{ $index }}"
                                class="carousel-img block w-[250px] h-[250px] flex-shrink-0 snap-center relative">
                                <div
                                    class="absolute inset-0 bg-gradient-to-b from-transparent to-[#A66E38]/40 opacity-0 hover:opacity-100 transition-opacity duration-300 rounded-xl z-10">
                                </div>
                                <img src="{{ asset('storage/' . $doc->image_path) }}" alt="{{ $doc->title }}"
                                    class="w-full h-full object-cover rounded-xl shadow-md transition-all duration-300 ease-in-out cursor-pointer" />
                                @if ($doc->title)
                                    <div
                                        class="absolute bottom-0 left-0 right-0 p-3 text-white opacity-0 transition-opacity duration-300 text-center title-overlay z-20">
                                        <span class="text-sm font-semibold drop-shadow-lg">{{ $doc->title }}</span>
                                    </div>
                                @endif
                            </a>
                        @endforeach
                    </div>

                    <!-- Navigation arrows for carousel with warm colors -->
                    <button
                        class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-[#A66E38]/80 hover:bg-[#A66E38] text-white rounded-full p-2 shadow-md z-30"
                        onclick="document.getElementById('imageCarousel').scrollBy({left: -300, behavior: 'smooth'})">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <button
                        class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-[#A66E38]/80 hover:bg-[#A66E38] text-white rounded-full p-2 shadow-md z-30"
                        onclick="document.getElementById('imageCarousel').scrollBy({left: 300, behavior: 'smooth'})">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- 5. Update Image Preview Modal with warmer colors -->
    <div id="previewModal"
        class="hidden fixed inset-0 z-[9999] bg-black bg-opacity-90 flex items-center justify-center p-4">
        <button id="prevButton" onclick="previewPrev()"
            class="absolute left-4 md:left-8 top-1/2 transform -translate-y-1/2 bg-white/90 border-2 border-[#A66E38] rounded-full w-10 h-10 md:w-12 md:h-12 flex items-center justify-center shadow-md hover:scale-105 transition z-[10000]">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="#A66E38" class="w-5 h-5 md:w-6 md:h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </button>

        <button id="nextButton" onclick="previewNext()"
            class="absolute right-4 md:right-8 top-1/2 transform -translate-y-1/2 bg-white/90 border-2 border-[#A66E38] rounded-full w-10 h-10 md:w-12 md:h-12 flex items-center justify-center shadow-md hover:scale-105 transition z-[10000]">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="#A66E38" class="w-5 h-5 md:w-6 md:h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
        </button>

        <!-- Image Preview Container with improved sizing -->
        <div class="relative max-w-[95vw] max-h-[90vh] flex flex-col items-center justify-center">
            <img id="previewFullImg" src="" class="max-w-full max-h-[85vh] object-contain rounded-xl shadow-lg"
                alt="Preview">

            <!-- Optional title and description for posters with warm color theme -->
            <div id="posterInfo" class="hidden bg-[#fdf5e9]/95 rounded-lg p-4 mt-4 max-w-lg text-center">
                <h3 id="posterTitle" class="text-xl font-semibold mb-2 text-[#8B5A2B]"></h3>
                <p id="posterDescription" class="text-gray-700"></p>
            </div>
        </div>

        <button onclick="closePreview()"
            class="absolute top-4 right-4 md:top-6 md:right-6 bg-white/90 border-2 border-red-500 rounded-full w-9 h-9 md:w-10 md:h-10 flex items-center justify-center shadow-md hover:scale-105 transition z-[10000]">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="red"
                class="w-4 h-4 md:w-5 md:h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- AOS Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />

    <!-- Improved JavaScript for carousel, animations, and poster preview -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                once: true,
                offset: 120,
                duration: 800
            });
        });
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.4.5/swiper-bundle.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.4.5/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                once: true,
                offset: 120,
                duration: 800
            });

            const phrases = [
                "Nyalakan Semangatmu",
                "Ayo Booking",
                "Siapkan Raketmu",
                "Kamu Siap?",
                "Ayo Main!",
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
                }, 300); // transition out and change text
            }, 3500);
        });

        // Improved carousel functionality with better positioning
        const carousel = document.getElementById('imageCarousel');
        if (carousel) {
            const images = carousel.querySelectorAll('.carousel-img');

            function updateImageScales() {
                const carouselRect = carousel.getBoundingClientRect();
                const centerX = carouselRect.left + carouselRect.width / 2;

                images.forEach(img => {
                    const imgRect = img.getBoundingClientRect();
                    const imgCenter = imgRect.left + imgRect.width / 2;

                    const distance = Math.abs(centerX - imgCenter);
                    // More gentle scale change to prevent aggressive overlapping
                    const scale = Math.max(1, 1.1 - distance / 500);

                    img.style.transform = `scale(${scale})`;
                    // Set elements in center with slightly higher z-index
                    img.style.zIndex = distance < 100 ? '5' : '1';
                });
            }

            carousel.addEventListener('scroll', () => {
                window.requestAnimationFrame(updateImageScales);
            });

            window.addEventListener('load', updateImageScales);
            window.addEventListener('resize', updateImageScales);
        }

        // Variables for different types of previews
        const previewModal = document.getElementById('previewModal');
        const previewImg = document.getElementById('previewFullImg');
        const posterInfoBlock = document.getElementById('posterInfo');
        const posterTitle = document.getElementById('posterTitle');
        const posterDescription = document.getElementById('posterDescription');
        const prevButton = document.getElementById('prevButton');
        const nextButton = document.getElementById('nextButton');

        // Gallery carousel images
        const imagesArray = Array.from(document.querySelectorAll('.carousel-img'));
        // Poster images
        const posterArray = Array.from(document.querySelectorAll('.poster-item'));

        let currentIndex = 0;
        let currentPreviewType = ''; // 'gallery' or 'poster'

        // Set up click handlers for gallery images
        imagesArray.forEach((img, index) => {
            img.addEventListener('click', (e) => {
                e.preventDefault();
                const imgSrc = img.querySelector('img').getAttribute('src');
                showGalleryPreview(imgSrc, index);
            });
        });

        // Function to show gallery image preview
        function showGalleryPreview(imgSrc, index) {
            currentPreviewType = 'gallery';
            currentIndex = index;

            // Show navigation buttons
            prevButton.style.display = 'flex';
            nextButton.style.display = 'flex';

            // Hide poster info
            posterInfoBlock.classList.add('hidden');

            previewImg.setAttribute('src', imgSrc);
            previewModal.classList.remove('hidden');

            // Hide the header when showing preview
            const header = document.querySelector('header');
            if (header) {
                header.style.zIndex = '1'; // Lower z-index so modal appears above it
            }

            // Ensure image is properly loaded before displaying
            previewImg.onload = function() {
                // Set optimal size once image is loaded
                setOptimalImageSize();
            };

            // Prevent body scrolling when modal is open
            document.body.style.overflow = 'hidden';
        }

        // Function to show poster preview
        function showPosterPreview(posterElement) {
            currentPreviewType = 'poster';

            // Hide navigation buttons for single poster preview
            prevButton.style.display = 'none';
            nextButton.style.display = 'none';

            const imgSrc = posterElement.getAttribute('data-image');
            const title = posterElement.getAttribute('data-title');
            const description = posterElement.getAttribute('data-description');

            previewImg.setAttribute('src', imgSrc);
            previewModal.classList.remove('hidden');

            // Update and show poster info if available
            if (title || description) {
                posterTitle.textContent = title || '';
                posterDescription.textContent = description || '';
                posterInfoBlock.classList.remove('hidden');
            } else {
                posterInfoBlock.classList.add('hidden');
            }

            // Hide the header when showing preview
            const header = document.querySelector('header');
            if (header) {
                header.style.zIndex = '1';
            }

            // Ensure image is properly loaded before displaying
            previewImg.onload = function() {
                setOptimalImageSize();
            };

            // Prevent body scrolling when modal is open
            document.body.style.overflow = 'hidden';
        }

        function closePreview() {
            previewModal.classList.add('hidden');
            // Re-enable body scrolling
            document.body.style.overflow = '';

            // Restore header z-index when closing preview
            const header = document.querySelector('header');
            if (header) {
                header.style.zIndex = '30'; // Restore original z-index (adjust as needed)
            }
        }

        function setOptimalImageSize() {
            // Set optimal size once image is loaded
            const imgRatio = previewImg.naturalWidth / previewImg.naturalHeight;
            const viewportRatio = window.innerWidth / window.innerHeight;

            if (imgRatio > viewportRatio) {
                // Image is wider than viewport ratio
                previewImg.style.width = 'min(95vw, 1200px)';
                previewImg.style.height = 'auto';
            } else {
                // Image is taller than viewport ratio
                previewImg.style.height = 'min(85vh, 900px)';
                previewImg.style.width = 'auto';
            }
        }

        function animateImageChange(newSrc) {
            previewImg.style.opacity = '0';
            setTimeout(() => {
                previewImg.setAttribute('src', newSrc);
                previewImg.style.opacity = '1';
            }, 200);
        }

        function previewPrev() {
            // Only works for gallery images
            if (currentPreviewType !== 'gallery') return;

            currentIndex = (currentIndex - 1 + imagesArray.length) % imagesArray.length;
            const newSrc = imagesArray[currentIndex].querySelector('img').getAttribute('src');
            animateImageChange(newSrc);
        }

        function previewNext() {
            // Only works for gallery images
            if (currentPreviewType !== 'gallery') return;

            currentIndex = (currentIndex + 1) % imagesArray.length;
            const newSrc = imagesArray[currentIndex].querySelector('img').getAttribute('src');
            animateImageChange(newSrc);
        }

        // Close modal when clicking outside the image
        previewModal.addEventListener('click', function(e) {
            if (e.target === previewModal) {
                closePreview();
            }
        });

        // Add keyboard navigation for modal
        document.addEventListener('keydown', function(e) {
            if (previewModal.classList.contains('hidden')) return;

            if (e.key === 'Escape') closePreview();
            if (currentPreviewType === 'gallery') {
                if (e.key === 'ArrowLeft') previewPrev();
                if (e.key === 'ArrowRight') previewNext();
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Swiper for posters on mobile
            const postersSwiper = new Swiper('.posters-swiper', {
                slidesPerView: 1,
                spaceBetween: 20,
                centeredSlides: true,
                loop: true,
                effect: "creative",
                creativeEffect: {
                    prev: {
                        shadow: true,
                        translate: ["-20%", 0, -1],
                        opacity: 0.8
                    },
                    next: {
                        translate: ["100%", 0, 0],
                        opacity: 0.8
                    },
                },
                speed: 800,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                    dynamicBullets: true,
                },
                on: {
                    slideChangeTransitionStart: function() {
                        document.querySelectorAll('.poster-image').forEach(img => {
                            img.classList.remove('scale-110');
                        });
                    },
                    slideChangeTransitionEnd: function() {
                        const activeSlide = this.slides[this.activeIndex];
                        if (activeSlide) {
                            const img = activeSlide.querySelector('.poster-image');
                            if (img) img.classList.add('scale-110');
                        }
                    }
                }
            });

            // Add zoom effect to active slide image on init
            const activeSlide = document.querySelector('.swiper-slide-active');
            if (activeSlide) {
                const img = activeSlide.querySelector('.poster-image');
                if (img) img.classList.add('scale-110');
            }
        });

        // Modal functions
        function showPosterPreview(element) {
            const modal = document.getElementById('posterModal');
            const modalImage = document.getElementById('modalImage');
            const modalTitle = document.getElementById('modalTitle');
            const modalDescription = document.getElementById('modalDescription');

            modalImage.src = element.getAttribute('data-image');
            modalTitle.textContent = element.getAttribute('data-title');
            modalDescription.textContent = element.getAttribute('data-description');

            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.add('opacity-100');
                modal.querySelector('.transform').classList.remove('scale-90');
            }, 10);

            document.body.style.overflow = 'hidden';
        }

        function closePosterPreview() {
            const modal = document.getElementById('posterModal');
            modal.classList.remove('opacity-100');
            modal.querySelector('.transform').classList.add('scale-90');

            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }, 300);
        }

        // Handle window resize for responsive preview
        window.addEventListener('resize', function() {
            if (!previewModal.classList.contains('hidden')) {
                setOptimalImageSize();
            }
        });

        let originalHeaderZIndex;

        document.addEventListener('DOMContentLoaded', function() {
            const header = document.querySelector('header');
            if (header) {
                // Store original z-index value
                originalHeaderZIndex = window.getComputedStyle(header).zIndex;
            }
        });
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Swiper for facilities on mobile
            const facilitiesSwiper = new Swiper('.facilities-swiper', {
                slidesPerView: 1.1,
                spaceBetween: 12,
                centeredSlides: false,
                loop: true,
                speed: 800, // Increased speed for smoother animation
                effect: "slide", // Default effect but with better performance
                autoplay: {
                    delay: 4000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                    dynamicBullets: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints: {
                    // when window width is >= 400px
                    400: {
                        slidesPerView: 1,
                        spaceBetween: 15,
                        centeredSlides: true
                    },
                    // when window width is >= 540px
                    540: {
                        slidesPerView: 2,
                        spaceBetween: 20,
                        centeredSlides: false
                    },
                    // when window width is >= 640px but smaller than md breakpoint
                    640: {
                        slidesPerView: 3,
                        spaceBetween: 20,
                        centeredSlides: false
                    }
                }
            });
        });
    </script>

    <!-- Add custom styles for smoother transitions -->
    <style>
        /* Hide scrollbar for carousel */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Smooth transition for preview image */
        #previewFullImg {
            transition: opacity 0.2s ease-in-out, transform 0.3s ease;
            max-width: 95vw;
            max-height: 85vh;
            object-fit: contain;
            margin: 0 auto;
        }

        /* Add responsive padding to modal */
        #previewModal {
            padding: 1rem;
        }

        @media (min-width: 768px) {
            #previewModal {
                padding: 2rem;
            }
        }

        /* Improve modal animation */
        #previewModal.hidden {
            opacity: 0;
            pointer-events: none;
        }

        #previewModal {
            opacity: 1;
            transition: opacity 0.3s ease;
        }

        /* Ensure modal is above everything else */
        #previewModal {
            z-index: 9999 !important;
        }

        /* Make posters look clickable */
        .poster-item {
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .poster-item:hover {
            transform: scale(1.02);
            box-shadow: 0 10px 25px rgba(166, 110, 56, 0.2);
        }

        /* Styling for poster info in preview */
        #posterInfo {
            transition: opacity 0.3s ease;
            max-width: 80vw;
        }

        /* Improved carousel item hover effects */
        .carousel-img:hover .title-overlay {
            opacity: 1 !important;
        }

        /* Change text color for better readability */
        .text-gray-500 {
            color: #6B5E50;
        }

        /* Custom styles for Swiper */
        .swiper-container {
            padding: 10px 0 40px 0;
            /* Space for pagination and better alignment */
            overflow: visible;
        }

        .swiper-slide {
            height: auto;
            /* Equal height slides */
            transition: transform 0.3s ease;
        }

        .swiper-slide-active {
            transform: translateY(-5px);
            /* Subtle lift effect for active slide */
        }

        .swiper-pagination {
            bottom: 0px;
        }

        .swiper-pagination-bullet {
            width: 8px;
            height: 8px;
            opacity: 0.6;
        }

        .swiper-pagination-bullet-active {
            background-color: #A66E38;
            opacity: 1;
            width: 10px;
            height: 10px;
        }

        .swiper-button-next,
        .swiper-button-prev {
            display: none !important;
        }

        @keyframes pulse {
            0% {
                opacity: 0.6;
            }

            50% {
                opacity: 1;
            }

            100% {
                opacity: 0.6;
            }
        }

        .animate-pulse {
            animation: pulse 2s infinite;
        }

        /* Modal animations */
        .transition-opacity {
            transition-property: opacity;
        }

        .transition-transform {
            transition-property: transform;
        }
    </style>
@endsection

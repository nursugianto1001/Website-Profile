@extends('layouts.public')

@section('title', 'Facilities')

@push('styles')
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />

    <!-- AOS Library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
@endpush

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
                    <div class="md:hidden" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                        <div class="swiper-container facilities-swiper">
                            <div class="swiper-wrapper">
                                @foreach ($facilities as $index => $facility)
                                    <div class="swiper-slide px-2" data-aos="zoom-in" data-aos-duration="800" data-aos-delay="{{ ($index * 150) + 400 }}">
                                        <div
                                            class="bg-gradient-to-br {{ $index % 3 == 0 ? 'from-amber-50 via-amber-100/30 to-[#fdf5e9]' : ($index % 3 == 1 ? 'from-[#fcf7f1] via-amber-50/40 to-[#faebd7]' : 'from-[#fcf9f2] via-[#f8e8d4] to-[#fef8f5]') }} shadow-lg rounded-lg overflow-hidden flex flex-col h-[400px] transform transition-all duration-500 hover:shadow-2xl hover:-translate-y-2 hover:scale-105 mobile-facility-card">
                                            <div
                                                class="{{ $index % 3 == 0 ? 'bg-amber-50' : ($index % 3 == 1 ? 'bg-[#faebd7]' : 'bg-[#f8e8d4]') }} flex justify-center items-center h-[180px] overflow-hidden relative group">
                                                <img src="{{ asset('storage/' . $facility->image_path) }}"
                                                    alt="{{ $facility->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 facility-image">
                                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                            </div>
                                            <div class="p-4 flex-grow overflow-y-auto relative">
                                                <div class="facility-content">
                                                    <h3
                                                        class="text-lg font-bold mb-2 {{ $index % 3 == 0 ? 'text-[#A66E38]' : 'text-[#8B5A2B]' }} transform transition-all duration-300 facility-title">
                                                        {{ $facility->name }}</h3>
                                                    <p class="text-gray-700 text-sm leading-relaxed transition-all duration-300 facility-description">
                                                        {{ Str::limit($facility->description, 120) }}
                                                    </p>
                                                </div>
                                                <!-- Decorative element -->
                                                <div class="absolute bottom-2 right-2 w-8 h-8 rounded-full {{ $index % 3 == 0 ? 'bg-amber-200' : ($index % 3 == 1 ? 'bg-amber-100' : 'bg-orange-100') }} opacity-20 transform scale-0 transition-all duration-500 facility-decoration"></div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <!-- Add pagination bullets -->
                            <div class="swiper-pagination mt-6 relative z-10" data-aos="fade-up" data-aos-duration="600" data-aos-delay="600"></div>
                            <!-- Add navigation arrows -->
                            <div class="swiper-button-next text-[#A66E38] hover:text-[#8B5A2B] transition-colors duration-300 transform hover:scale-110" data-aos="fade-left" data-aos-duration="600" data-aos-delay="700"></div>
                            <div class="swiper-button-prev text-[#A66E38] hover:text-[#8B5A2B] transition-colors duration-300 transform hover:scale-110" data-aos="fade-right" data-aos-duration="600" data-aos-delay="700"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

    <!-- AOS JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>

    <!-- Custom Mobile Animation Styles -->
    <style>
        /* Mobile Facility Card Animations */
        .mobile-facility-card {
            animation: cardFloat 6s ease-in-out infinite;
        }

        .mobile-facility-card:nth-child(odd) {
            animation-delay: -2s;
        }

        .mobile-facility-card:hover .facility-decoration {
            transform: scale(1);
            opacity: 0.4;
        }

        .mobile-facility-card:hover .facility-title {
            transform: translateX(4px);
            color: #8B5A2B;
        }

        .mobile-facility-card:hover .facility-description {
            color: #6B5B73;
        }

        @keyframes cardFloat {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            25% { transform: translateY(-3px) rotate(0.5deg); }
            50% { transform: translateY(-6px) rotate(0deg); }
            75% { transform: translateY(-3px) rotate(-0.5deg); }
        }

        /* Facility Image Hover Effects */
        .facility-image {
            filter: brightness(1) contrast(1);
        }

        .mobile-facility-card:hover .facility-image {
            filter: brightness(1.1) contrast(1.1);
        }

        /* Custom Swiper Styling for Mobile */
        @media (max-width: 768px) {
            .swiper-button-next,
            .swiper-button-prev {
                width: 35px !important;
                height: 35px !important;
                margin-top: -17px !important;
                background: rgba(166, 110, 56, 0.1) !important;
                border-radius: 50% !important;
                backdrop-filter: blur(10px) !important;
                border: 1px solid rgba(166, 110, 56, 0.2) !important;
                transition: all 0.3s ease !important;
            }

            .swiper-button-next:hover,
            .swiper-button-prev:hover {
                background: rgba(166, 110, 56, 0.2) !important;
                transform: scale(1.1) !important;
                box-shadow: 0 4px 15px rgba(166, 110, 56, 0.3) !important;
            }

            .swiper-button-next:after,
            .swiper-button-prev:after {
                font-size: 14px !important;
                font-weight: bold !important;
            }

            .swiper-pagination-bullet {
                width: 10px !important;
                height: 10px !important;
                margin: 0 4px !important;
                background: rgba(166, 110, 56, 0.3) !important;
                transition: all 0.3s ease !important;
            }

            .swiper-pagination-bullet-active {
                background: #A66E38 !important;
                transform: scale(1.3) !important;
                box-shadow: 0 2px 8px rgba(166, 110, 56, 0.4) !important;
            }
        }

        /* Loading animation for images */
        .facility-image {
            opacity: 0;
            animation: fadeInImage 0.8s ease-out forwards;
        }

        @keyframes fadeInImage {
            to {
                opacity: 1;
            }
        }

        /* Stagger animation delay for multiple images */
        .swiper-slide:nth-child(1) .facility-image { animation-delay: 0.1s; }
        .swiper-slide:nth-child(2) .facility-image { animation-delay: 0.2s; }
        .swiper-slide:nth-child(3) .facility-image { animation-delay: 0.3s; }
        .swiper-slide:nth-child(4) .facility-image { animation-delay: 0.4s; }
        .swiper-slide:nth-child(5) .facility-image { animation-delay: 0.5s; }
        .swiper-slide:nth-child(n+6) .facility-image { animation-delay: 0.6s; }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if Swiper is loaded
            if (typeof Swiper === 'undefined') {
                console.error('Swiper library not loaded');
                return;
            }

            // Initialize AOS
            if (typeof AOS !== 'undefined') {
                AOS.init({
                    once: true,
                    offset: 120,
                    duration: 800,
                    easing: 'ease-out-cubic'
                });
            }

            // Initialize Swiper with enhanced mobile animations
            const facilitiesSwiper = new Swiper('.facilities-swiper', {
                slidesPerView: 1,
                spaceBetween: 25,
                centeredSlides: true,
                grabCursor: true,
                loop: false,
                autoplay: {
                    delay: 4000,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                    dynamicBullets: true,
                    dynamicMainBullets: 3
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                effect: 'coverflow',
                coverflowEffect: {
                    rotate: 15,
                    stretch: 0,
                    depth: 100,
                    modifier: 1,
                    slideShadows: true,
                },
                on: {
                    slideChange: function () {
                        // Add animation to active slide
                        const activeSlide = this.slides[this.activeIndex];
                        if (activeSlide) {
                            const card = activeSlide.querySelector('.mobile-facility-card');
                            if (card) {
                                card.style.animation = 'none';
                                setTimeout(() => {
                                    card.style.animation = 'cardFloat 6s ease-in-out infinite';
                                }, 100);
                            }
                        }
                    },
                    init: function() {
                        // Trigger entrance animation for first slide
                        setTimeout(() => {
                            const firstCard = this.slides[0]?.querySelector('.mobile-facility-card');
                            if (firstCard) {
                                firstCard.classList.add('animate-pulse');
                                setTimeout(() => {
                                    firstCard.classList.remove('animate-pulse');
                                }, 1000);
                            }
                        }, 1000);
                    }
                },
                breakpoints: {
                    480: {
                        slidesPerView: 1.1,
                        spaceBetween: 30,
                    }
                }
            });

            // Add touch feedback for mobile cards
            document.querySelectorAll('.mobile-facility-card').forEach(card => {
                card.addEventListener('touchstart', function() {
                    this.style.transform = 'scale(0.98)';
                });

                card.addEventListener('touchend', function() {
                    this.style.transform = '';
                });
            });
        });
    </script>
@endpush

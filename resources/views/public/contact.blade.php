@extends('layouts.public')

@section('title', 'Contact Us')

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
                Kontak Kami
            </h1>
            <p class="text-m drop-shadow-lg mt-4 font-sans">
                Kami selalu ingin mendengar dari Anda! Butuh info, Pemesanan Lapangan, atau sekadar ingin menyapa? Kami
                di sini
                untuk membantu!
            </p>
        </div>
    </div>
</div>

<!-- Scrollable Content -->
<div class="relative z-30 bg-white">
    <!-- Contact Information Section - HIJAU -->
    <div class="py-20 bg-gradient-to-br from-starbucks-green via-starbucks-dark-green to-forest-green">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div data-aos="fade-right" data-aos-duration="1000">
                    <h2 class="text-3xl font-bold text-starbucks-cream mb-6">Informasi Kontak</h2>
                    <div class="space-y-4 mb-8">
                        <div class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-starbucks-light-green mt-1 mr-3"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <div>
                                <h3 class="font-semibold text-starbucks-cream">Email</h3>
                                <p class="text-starbucks-beige">saranasehatborneo2@gmail.com</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-starbucks-light-green mt-1 mr-3"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <div>
                                <h3 class="font-semibold text-starbucks-cream">No. HP</h3>
                                <p class="text-starbucks-beige"> +62 822-1000-2256</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-starbucks-light-green mt-1 mr-3"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <div>
                                <h3 class="font-semibold text-starbucks-cream">Kantor Kami</h3>
                                <p class="text-starbucks-beige"> Jalan Veteran, Jalan Karvin, Pontianak, Kalimantan Barat</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="order-2 md:order-1" data-aos="fade-left" data-aos-duration="1000">
                    <img src="{{ Vite::asset('resources/images/salam-karvin.jpg') }}" alt="Contact Us"
                        class="w-full h-auto rounded-lg max-w-md mx-auto shadow-lg">
                </div>
            </div>
        </div>
    </div>

    <!-- Business Hours and Social Media Section - PUTIH -->
    <div class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12" data-aos="fade-up" data-aos-duration="800">
                <h2 class="text-3xl font-bold text-starbucks-green">Hubungi Kami</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div class="bg-gradient-to-br from-starbucks-light-green via-sage-green to-mint-green shadow-xl rounded-xl overflow-hidden transition transform hover:scale-105 hover:shadow-2xl p-6 border border-starbucks-light-green"
                    data-aos="fade-right" data-aos-duration="800">
                    <div class="p-2">
                        <div
                            class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg bg-gradient-to-br from-white to-starbucks-cream">
                            <i class="fas fa-clock text-2xl text-starbucks-dark-green"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-6 text-center text-starbucks-dark-green">Jam Kerja</h3>
                        <ul class="space-y-4 text-white text-center">
                            <li class="flex justify-between p-3">
                                <span>Setiap Hari</span>
                                <span>06:00 - 23:00 </span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-sage-green via-mint-green to-starbucks-light-green shadow-xl rounded-xl overflow-hidden transition transform hover:scale-105 hover:shadow-2xl p-6 border border-sage-green"
                    data-aos="fade-left" data-aos-duration="800">
                    <div class="p-2">
                        <div
                            class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg bg-gradient-to-br from-white to-starbucks-cream">
                            <i class="fas fa-share-alt text-2xl text-starbucks-dark-green"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-6 text-center text-starbucks-dark-green">Ikuti Kami</h3>
                        <p class="text-white mb-6 text-center">
                            Ikuti kami di media sosial untuk info terbaru, event seru, dan banyak lagi!
                        </p>
                        <div class="flex space-x-6 justify-center">
                            <a href="https://www.instagram.com/karvin_badminton/"
                                class="text-starbucks-dark-green hover:text-starbucks-green transition-colors duration-300 bg-white/60 p-2 rounded-full shadow-sm backdrop-blur-sm">
                                <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"
                                        clip-rule="evenodd" />
                                </svg>
                            </a>
                            <a href="https://www.tiktok.com/@karvinbadminton?_t=ZS-8xlSGLPLg4V&_r=1 " class="text-starbucks-dark-green hover:text-starbucks-green transition-colors duration-300 bg-white/60 p-2 rounded-full shadow-sm backdrop-blur-sm">
                                <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M19.589 6.686a4.793 4.793 0 0 1-3.77-4.245V2h-3.445v13.672a2.896 2.896 0 0 1-2.909 2.909 2.896 2.896 0 0 1-2.909-2.909 2.896 2.896 0 0 1 2.909-2.909c.301 0 .583.056.849.142V9.379c-.33-.018-.66-.018-.849-.018A6.459 6.459 0 0 0 2.906 15.9a6.459 6.459 0 0 0 6.458 6.458A6.459 6.459 0 0 0 15.823 15.9V8.134a8.169 8.169 0 0 0 3.766.906V6.686Z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Location Section - HIJAU -->
    <div class="py-20 bg-gradient-to-b from-forest-green via-starbucks-dark-green to-starbucks-green">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12" data-aos="fade-up" data-aos-duration="800">
                <h2 class="text-3xl font-bold text-starbucks-cream">Temui Kami</h2>
                <p class="text-starbucks-beige mb-4 max-w-3xl mx-auto mt-4 leading-relaxed">
                    Datanglah ke tempat kami lokasinya mudah dijangkau!
                </p>
            </div>
            <div class="bg-gradient-to-br from-starbucks-light-green via-white to-sage-green rounded-lg overflow-hidden shadow-xl"
                data-aos="zoom-in" data-aos-duration="800">
                <div class="relative bg-gray-100 h-96">
                    <!-- Placeholder Image -->
                    <img src="{{ Vite::asset('resources/images/map.png') }}" alt="Map Location"
                        class="w-full h-full object-cover">

                    <!-- Map Button Overlay -->
                    <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-30">
                        <a href="https://maps.app.goo.gl/XryjjSfbFV3FjJJAA" target="_blank"
                            class="text-starbucks-dark-green bg-white hover:bg-starbucks-cream px-6 py-4 rounded-lg shadow-lg font-medium flex items-center transition duration-300 backdrop-blur-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                            </svg>
                            View on Map
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- WhatsApp Floating Button dengan Fallback -->
    <div class="fixed bottom-6 right-6 z-50">
        <a href="https://wa.me/6282210002256?text=Halo%20admin%20Karvin%20Badminton,%20saya%20ingin%20bertanya%20tentang%20booking%20lapangan."
            target="_blank"
            class="group flex items-center justify-center w-14 h-14 bg-starbucks-light-green hover:bg-starbucks-green rounded-full shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-110">

            <!-- Primary: SVG Icon (selalu tersedia) -->
            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.893 3.690" />
            </svg>
        </a>

        <!-- Tooltip -->
        <div class="absolute right-16 top-1/2 transform -translate-y-1/2 bg-starbucks-dark-green text-white text-sm px-3 py-2 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap pointer-events-none shadow-lg">
            Chat dengan Admin
            <div class="absolute left-full top-1/2 transform -translate-y-1/2 border-4 border-transparent border-l-starbucks-dark-green"></div>
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

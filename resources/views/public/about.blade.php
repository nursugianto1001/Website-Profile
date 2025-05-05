@extends('layouts.public')

@section('title', 'About Us')

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
                style="font-family: 'Helvetica', sans-serif; font-weight: 700;">Karvin<br>Badminton</h1>
            <p class="text-m drop-shadow-lg mt-4" style="font-family: 'Helvetica', sans-serif;">
                Bagi kami, bulu tangkis itu bukan cuma soal menang atau kalah, tapi soal kumpul, gerak bareng, dan nikmatin momen seru bareng teman atau keluarga.
            </p>
        </div>
    </div>
</div>

<!-- Scrollable Content -->
<div class="relative z-30 bg-white">
    <!-- Our Story Section -->
    <div class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div data-aos="fade-right" data-aos-duration="1000">
                    <h2 class="text-3xl font-bold text-black mb-6">Cerita Kami</h2>
                    <p class="text-gray-500 mb-4">
                        Karvin hadir pertama kali pada 30 Oktober 2024, lahir dari keinginan sederhana: menyediakan tempat main bulu tangkis yang gak cuma nyaman, tapi juga bikin betah. Kami tahu, nyari lapangan yang enak itu kadang susah yang nggak antre panjang, bersih, pencahayaannya oke, dan suasananya bikin semangat. Maka dari itu, Karvin coba jadi jawaban.
                    </p>
                    <p class="text-gray-500 mb-6">
                        Berlokasi di Jalan Veteran, Jalan Karvin, kami bangun ruang olahraga yang modern tapi tetap terasa akrab. Di sini, kamu bisa main serius, sparing santai, atau sekadar nongkrong sambil nunggu giliran main. Karvin bukan cuma tentang bulu tangkis tapi tentang komunitas, semangat gerak, dan tempat ngumpul yang sehat.
                        Fasilitas kami terus kami kembangkan, dan tim kami selalu siap bantu, karena kami percaya pengalaman yang baik itu dimulai dari pelayanan yang tulus. Jadi, kalau kamu pengin tempat main yang bersih, vibe-nya asik, dan gampang booking, Karvin tempatnya. Ayo main bareng!
                    </p>
                </div>
                <div class="rounded-lg overflow-hidden shadow-xl" data-aos="fade-left" data-aos-duration="1000">
                    <img src="{{ Vite::asset('resources/images/copicop.jpg') }}" alt="Our Cafe Story"
                        class="w-full h-full object-cover">
                </div>
            </div>
        </div>
    </div>

    <!-- Our Mission Section -->
    <div class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div class="order-2 md:order-1 rounded-lg overflow-hidden shadow-xl" data-aos="fade-right"
                    data-aos-duration="1000">
                    <img src="{{ Vite::asset('resources/images/owner.jpg') }}" alt="Our Mission"
                        class="w-full h-full object-cover">
                </div>
                <div class="order-1 md:order-2" data-aos="fade-left" data-aos-duration="1000">
                    <h2 class="text-3xl font-bold text-black mb-6">Misi Kami</h2>
                    <p class="text-gray-500 mb-4">
                        Misi kami di Karvin adalah menghadirkan lapangan bulu tangkis yang nyaman, bersih, dan mudah diakses oleh siapa saja. Kami ingin menciptakan ruang di mana orang bisa berkumpul, bergerak bersama, dan membangun koneksi lewat semangat olahraga.
                    <p class="text-gray-500 mb-6">
                        Lewat pengalaman bermain yang seru dan bebas ribet, kami mendorong gaya hidup yang aktif dan sehat. Selain itu, kami selalu berusaha memberikan pelayanan yang ramah dan profesional, supaya setiap orang yang datang merasa dihargai dan betah.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Our Values Section -->
    <div class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12" data-aos="fade-up" data-aos-duration="800">
                <h2 class="text-3xl font-bold text-black">Nilai Nilai Kami</h2>
                <br>
                <p class="text-gray-500 mb-4 max-w-3xl mx-auto">
                    Kami berpegang pada prinsip-prinsip utama dalam setiap langkah yang diambil.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Kualitas -->
                <div class="bg-white shadow-lg rounded-lg overflow-hidden transition transform hover:scale-105 p-6"
                    data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                    <div
                        class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 bg-gray-800 text-white">
                        <i class="fas fa-medal text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2 text-center">Sportivitas</h3>
                    <p class="text-gray-700 text-center">
                        Kami menanamkan semangat fair play dan sikap saling menghargai dalam setiap aktivitas di lapangan.
                    </p>
                </div>

                <!-- Komunitas -->
                <div class="bg-white shadow-lg rounded-lg overflow-hidden transition transform hover:scale-105 p-6"
                    data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
                    <div
                        class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 bg-gray-800 text-white">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2 text-center">Kepuasan Pelanggan</h3>
                    <p class="text-gray-700 text-center">
                        Kepuasan Anda adalah prioritas kami. Kami terus mendengarkan, memperbaiki, dan menyesuaikan layanan sesuai kebutuhan pengguna.
                    </p>
                </div>

                <!-- Aktif & Dinamis -->
                <div class="bg-white shadow-lg rounded-lg overflow-hidden transition transform hover:scale-105 p-6"
                    data-aos="fade-up" data-aos-duration="800" data-aos-delay="300">
                    <div
                        class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 bg-gray-800 text-white">
                        <i class="fas fa-person-running text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2 text-center">Gaya Hidup Aktif</h3>
                    <p class="text-gray-700 text-center">
                        Kami mendukung gaya hidup sehat dan aktif lewat kemudahan akses ke fasilitas olahraga.
                    </p>
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
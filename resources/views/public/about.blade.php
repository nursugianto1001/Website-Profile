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
            style="background-image: url('{{ Vite::asset('resources/images/open.jpg') }}');"></div>
        @endif
    </div>

    <!-- Hero Content (Centered) - With higher z-index than video but lower than header -->
    <div class="absolute inset-0 z-20 flex items-center justify-left text-white text-left px-4 md:px-72 pt-16">
        <div class="max-w-xl" data-aos="fade-up" data-aos-duration="1000">
            <h1 class="text-5xl font-bold drop-shadow-lg leading-tight font-sans">
                Karvin<br>Badminton
            </h1>
            <p class="text-m drop-shadow-lg mt-4 font-sans">
                Bagi kami, bulu tangkis itu bukan cuma soal menang atau kalah, tapi soal kumpul, gerak bareng, dan
                nikmatin momen seru bareng teman atau keluarga.
            </p>
        </div>
    </div>
</div>

<!-- Scrollable Content -->
<div class="relative z-30 bg-white">
    <!-- Our Story Section -->
    <div class="py-20 bg-amber-50/70">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div data-aos="fade-right" data-aos-duration="1000">
                    <h2 class="text-3xl font-bold text-[#A66E38] mb-6">Cerita Kami</h2>
                    <p class="text-gray-500 mb-4">
                        Karvin hadir pertama kali pada 30 Oktober 2024, lahir dari keinginan sederhana: menyediakan
                        tempat main bulu tangkis yang gak cuma nyaman, tapi juga bikin betah. Kami tahu, nyari lapangan
                        yang enak itu kadang susah yang nggak antre panjang, bersih, pencahayaannya oke, dan suasananya
                        bikin semangat. Maka dari itu, Karvin coba jadi jawaban.
                    </p>
                    <p class="text-gray-500 mb-6">
                        Berlokasi di Jalan Veteran, Jalan Karvin, kami bangun ruang olahraga yang modern tapi tetap
                        terasa akrab. Di sini, kamu bisa main serius, sparing santai, atau sekadar nongkrong sambil
                        nunggu giliran main. Karvin bukan cuma tentang bulu tangkis tapi tentang komunitas, semangat
                        gerak, dan tempat ngumpul yang sehat.
                        Fasilitas kami terus kami kembangkan, dan tim kami selalu siap bantu, karena kami percaya
                        pengalaman yang baik itu dimulai dari pelayanan yang tulus. Jadi, kalau kamu pengin tempat main
                        yang bersih, vibe-nya asik, dan gampang booking, Karvin tempatnya. Ayo main bareng!
                    </p>
                </div>
                <div class="order-1 md:order-2" data-aos="fade-left" data-aos-duration="1000">
                    <img src="{{ Vite::asset('resources/images/servis.jpg') }}" alt="Our Cafe Story"
                        class="w-full h-auto rounded-lg max-w-md mx-auto">
                </div>
            </div>
        </div>
    </div>

    <!-- Our Mission Section -->
    <div class="py-10 bg-[#fdf8f2]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div class="order-2 md:order-1" data-aos="fade-right" data-aos-duration="1000">
                    <img src="{{ Vite::asset('resources/images/tim 2.jpg') }}" alt="Our Mission"
                        class="w-full h-auto rounded-lg max-w-md mx-auto">
                </div>
                <div class="order-1 md:order-2" data-aos="fade-left" data-aos-duration="1000">
                    <h2 class="text-3xl font-bold text-[#A66E38] mb-6">Misi Kami</h2>
                    <p class="text-gray-500 mb-4">
                        Misi kami di Karvin adalah menghadirkan lapangan bulu tangkis yang nyaman, bersih, dan mudah
                        diakses oleh siapa saja. Kami ingin menciptakan ruang di mana orang bisa berkumpul, bergerak
                        bersama, dan membangun koneksi lewat semangat olahraga.
                    </p>
                    <p class="text-gray-500 mb-6">
                        Lewat pengalaman bermain yang seru dan bebas ribet, kami mendorong gaya hidup yang aktif dan
                        sehat. Selain itu, kami selalu berusaha memberikan pelayanan yang ramah dan profesional, supaya
                        setiap orang yang datang merasa dihargai dan betah.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Our Values Section with Box Gradients -->
    <div class="py-16 bg-gradient-to-b from-[#faebd7]/80 to-amber-50/70">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12" data-aos="fade-up" data-aos-duration="800">
                <h2 class="text-3xl font-bold text-[#A66E38]">Nilai Nilai Kami</h2>
                <br>
                <p class="text-gray-500 mb-4 max-w-3xl mx-auto">
                    Kami berpegang pada prinsip-prinsip utama dalam setiap langkah yang diambil.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Sportivitas -->
                <div class="bg-gradient-to-br from-amber-50 via-amber-100/30 to-[#fdf5e9] shadow-lg rounded-lg overflow-hidden transition transform hover:scale-105 p-6"
                    data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                    <div
                        class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 shadow-md bg-white">
                        <i class="fas fa-medal text-2xl text-[#A66E38]"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2 text-center text-[#A66E38]">Sportivitas</h3>
                    <p class="text-gray-700 text-center">
                        Kami menanamkan semangat fair play dan sikap saling menghargai dalam setiap aktivitas di
                        lapangan.
                    </p>
                </div>

                <!-- Kepuasan Pelanggan -->
                <div class="bg-gradient-to-br from-[#fcf7f1] via-amber-50/40 to-[#faebd7] shadow-lg rounded-lg overflow-hidden transition transform hover:scale-105 p-6"
                    data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
                    <div
                        class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 shadow-md bg-white">
                        <i class="fas fa-users text-2xl text-[#8B5A2B]"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2 text-center text-[#A66E38]">Kepuasan Pelanggan</h3>
                    <p class="text-gray-700 text-center">
                        Kepuasan Anda adalah prioritas kami. Kami terus mendengarkan, memperbaiki, dan menyesuaikan
                        layanan sesuai kebutuhan pengguna.
                    </p>
                </div>

                <!-- Gaya Hidup Aktif -->
                <div class="bg-gradient-to-br from-[#fcf9f2] via-[#f8e8d4] to-[#fef8f5] shadow-lg rounded-lg overflow-hidden transition transform hover:scale-105 p-6"
                    data-aos="fade-up" data-aos-duration="800" data-aos-delay="300">
                    <div
                        class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 shadow-md bg-white">
                        <i class="fas fa-person-running text-2xl text-[#8B5A2B]"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2 text-center text-[#A66E38]">Gaya Hidup Aktif</h3>
                    <p class="text-gray-700 text-center">
                        Kami mendukung gaya hidup sehat dan aktif lewat kemudahan akses ke fasilitas olahraga.
                    </p>
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
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.893 3.690" />
            </svg>
        </a>

        <!-- Tooltip -->
        <div class="absolute right-16 top-1/2 transform -translate-y-1/2 bg-gray-800 text-white text-sm px-3 py-2 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap pointer-events-none">
            Chat dengan Admin
            <div class="absolute left-full top-1/2 transform -translate-y-1/2 border-4 border-transparent border-l-gray-800"></div>
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
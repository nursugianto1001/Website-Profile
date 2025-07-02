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
            <div class="flex justify-center md:order-1" data-aos="fade-left" data-aos-duration="1000">
                <img src="{{ Vite::asset('resources/images/servis.jpg') }}" alt="About Us"
                    class="w-full h-auto rounded-lg max-w-md mx-auto">
            </div>
        </div>
    </div>
</div>

<!-- Featured Posters Section -->
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

<!-- Jadwal Ketersediaan Lapangan -->
<div class="py-16 bg-gradient-to-b from-amber-50/90 to-[#f8e8d4]/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12" data-aos="fade-up" data-aos-duration="800">
            <h2 class="text-3xl font-bold text-[#A66E38]">Jadwal Ketersediaan Lapangan</h2>
            <p class="text-gray-700 mt-4">
                Lihat ketersediaan lapangan secara real-time untuk membantu Anda merencanakan waktu bermain yang tepat
            </p>
        </div>

        <!-- Date Selection dengan Kalender 1 Bulan -->
        <div
            class="bg-gradient-to-br from-amber-50 via-amber-100/30 to-[#fdf5e9] rounded-lg shadow-md p-6 border border-amber-100 mb-8">
            <div class="flex items-center mb-4">
                <div class="bg-[#faebd7] p-2 rounded-full mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#A66E38]" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-[#A66E38]">Pilih Tanggal</h3>
            </div>

            <p class="text-sm text-gray-600 mb-4">Pilih tanggal untuk melihat ketersediaan lapangan</p>

            <div class="mb-3">
                <div class="flex items-center text-sm text-[#A66E38] mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Hari ini Tanggal: <span class="font-medium"
                            id="today-date">{{ date('d M, Y') }}</span></span>
                </div>
            </div>

            <div class="flex flex-wrap gap-2 mb-4" id="date-selector-container">
                <!-- Date buttons will be populated by JavaScript -->
            </div>
        </div>

        <!-- Legend -->
        <div class="flex justify-center mb-8" data-aos="fade-up" data-aos-duration="800">
            <div class="flex flex-wrap gap-4 text-sm">
                <div class="flex items-center px-3 py-2 bg-green-100 rounded-full text-green-800">
                    <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                    <span>Tersedia</span>
                </div>
                <div class="flex items-center px-3 py-2 bg-red-100 rounded-full text-red-800">
                    <span class="w-3 h-3 bg-red-500 rounded-full mr-2"></span>
                    <span>Terbooking</span>
                </div>
                <div class="flex items-center px-3 py-2 bg-yellow-100 rounded-full text-yellow-700">
                    <span class="w-3 h-3 bg-yellow-400 rounded-full mr-2"></span>
                    <span>Member Booking</span>
                </div>
                <div class="flex items-center px-3 py-2 bg-gray-100 rounded-full text-gray-600">
                    <span class="w-3 h-3 bg-gray-400 rounded-full mr-2"></span>
                    <span>Tidak Tersedia</span>
                </div>
            </div>
        </div>

        <!-- Tambahkan legend harga -->
        <div class="flex justify-center mb-8" data-aos="fade-up" data-aos-duration="800">
            <div class="bg-white rounded-lg shadow-md p-4 border border-amber-100">
                <h3 class="text-center font-semibold text-[#A66E38] mb-3">Harga per Jam</h3>
                <div class="flex flex-wrap justify-center gap-4 text-sm">
                    <div class="flex items-center px-3 py-2 bg-yellow-100 rounded-full text-yellow-800">
                        <span class="font-medium">06:00-12:00: Rp 40.000</span>
                    </div>
                    <div class="flex items-center px-3 py-2 bg-green-100 rounded-full text-green-800">
                        <span class="font-medium">12:00-17:00: Rp 25.000</span>
                    </div>
                    <div class="flex items-center px-3 py-2 bg-purple-100 rounded-full text-purple-800">
                        <span class="font-medium">17:00-23:00: Rp 60.000</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Desktop Table View -->
        <div class="hidden md:block" data-aos="fade-up" data-aos-duration="800">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-amber-100">
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse schedule-table">
                        <thead>
                            <tr>
                                <th class="border px-4 py-3 bg-gray-50 text-gray-700">
                                    <div class="flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-gray-500"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Waktu / Lapangan</span>
                                    </div>
                                </th>
                                @foreach ($fields as $field)
                                <th class="border px-4 py-3 bg-gray-50">
                                    <div class="flex flex-col items-center">
                                        <div class="flex items-center mb-1">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5 text-[#A66E38] mr-1" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                            </svg>
                                            <span class="font-medium text-gray-800">{{ $field->name }}</span>
                                        </div>
                                        <div class="text-sm text-[#A66E38] font-medium">
                                            Harga Dinamis
                                        </div>
                                    </div>
                                </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody id="schedule-table-body">
                            @foreach ($slots as $slot)
                            <tr>
                                <td class="border px-4 py-3 text-sm font-medium text-gray-700 bg-gray-50">
                                    <div class="flex flex-col items-center">
                                        <div class="flex items-center mb-1">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 text-gray-500 mr-2" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span
                                                class="font-semibold">{{ Carbon\Carbon::parse($slot['time'])->format('H:i') }}
                                                -
                                                {{ Carbon\Carbon::parse($slot['time'])->addHour()->format('H:i') }}</span>
                                        </div>
                                        <div class="text-xs font-medium px-2 py-1 rounded-full slot-price-badge"
                                            data-hour="{{ Carbon\Carbon::parse($slot['time'])->format('H') }}">
                                            <!-- Harga akan diisi oleh JavaScript -->
                                        </div>
                                    </div>
                                </td>
                                @foreach ($fields as $field)
                                <td class="p-2 border slot-cell" data-field-id="{{ $field->id }}"
                                    data-time-slot="{{ $slot['time'] }}"
                                    data-slot-hour="{{ Carbon\Carbon::parse($slot['time'])->format('H') }}">
                                    <!-- Content akan diisi oleh JavaScript -->
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden space-y-4" data-aos="fade-up" data-aos-duration="800">
            @foreach ($fields as $field)
            <div class="bg-white rounded-lg shadow-lg border border-amber-100 overflow-hidden">
                <div class="bg-gradient-to-r from-[#A66E38] to-[#8B5A2B] text-white p-4">
                    <h3 class="text-lg font-semibold">{{ $field->name }}</h3>
                    <p class="text-sm opacity-90">Harga Dinamis</p>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-3 gap-2 mobile-slots" data-field-id="{{ $field->id }}">
                        <!-- Content will be filled by JavaScript -->
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Info Text -->
        <div class="text-center mt-8" data-aos="fade-up" data-aos-duration="800">
            <p class="text-gray-600 mb-4">Ingin memesan lapangan? Klik tombol di bawah ini untuk melakukan booking</p>
            <a href="{{ route('booking.form') }}"
                class="inline-block px-6 py-3 text-white font-medium rounded transition duration-300"
                style="background-color: #A66E38;" onmouseover="this.style.backgroundColor='#8B5A2B';"
                onmouseout="this.style.backgroundColor='#A66E38';">
                Pesan Lapangan Sekarang
            </a>
        </div>
    </div>
</div>

<!-- JavaScript untuk Schedule Display -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data dari PHP ke JS
        const slotsData = @json($slots);
        const fieldsData = @json($fields);

        // Fungsi untuk mendapatkan harga berdasarkan jam
        function getPriceByHour(hour) {
            const hourInt = parseInt(hour);

            if (isNaN(hourInt)) {
                console.error('Invalid hour:', hour);
                return 40000; // Default fallback
            }

            if (hourInt >= 6 && hourInt < 12) {
                return 40000; // Pagi: 06:00-12:00
            } else if (hourInt >= 12 && hourInt < 17) {
                return 25000; // Siang: 12:00-17:00
            } else if (hourInt >= 17 && hourInt < 23) {
                return 60000; // Malam: 17:00-23:00
            }

            console.warn('Hour outside range:', hourInt);
            return 40000; // Default fallback
        }

        function formatTimeRange(startTime) {
            const [hours, minutes] = startTime.split(':');
            const startHour = parseInt(hours);
            const startMin = minutes;
            const endHour = (startHour + 1) % 24;
            return `${hours}:${startMin}-${String(endHour).padStart(2, '0')}:${startMin}`;
        }

        // Generate tanggal 1 bulan ke depan
        function generateMonthlyDates() {
            const monthlyDates = [];
            const today = new Date();
            for (let i = 0; i < 30; i++) {
                const date = new Date(today);
                date.setDate(today.getDate() + i);
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const dayOfMonth = String(date.getDate()).padStart(2, '0');
                const dateString = `${year}-${month}-${dayOfMonth}`;
                const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                const day = dayNames[date.getDay()];
                const formattedDate = date.toLocaleDateString('id-ID', {
                    month: 'short',
                    day: 'numeric'
                });
                monthlyDates.push({
                    date: dateString,
                    day: day,
                    formatted_date: formattedDate
                });
            }
            return monthlyDates;
        }

        const monthlyDates = generateMonthlyDates();
        const dateContainer = document.getElementById('date-selector-container');
        let currentDate = monthlyDates[0].date;
        let fieldAvailability = {};

        // Populate date buttons
        monthlyDates.forEach((dateObj, index) => {
            const dateButton = document.createElement('button');
            dateButton.type = 'button';
            dateButton.className = `date-selector px-4 py-2 border rounded-md transition-colors ${
            index === 0 ? 'bg-[#A66E38] text-white border-[#8B5A2B]' : 'bg-white text-gray-700 border-gray-300 hover:bg-amber-50'
        }`;
            dateButton.setAttribute('data-date', dateObj.date);
            dateButton.innerHTML = `
            <span class="block font-medium">${dateObj.day}</span>
            <span class="block text-sm">${dateObj.formatted_date}</span>
        `;
            dateContainer.appendChild(dateButton);
        });

        // Event listener untuk tombol tanggal
        document.querySelectorAll('.date-selector').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelectorAll('.date-selector').forEach(btn => {
                    btn.classList.remove('bg-[#A66E38]', 'text-white',
                        'border-[#8B5A2B]');
                    btn.classList.add('bg-white', 'text-gray-700', 'border-gray-300',
                        'hover:bg-amber-50');
                });
                this.classList.remove('bg-white', 'text-gray-700', 'border-gray-300',
                    'hover:bg-amber-50');
                this.classList.add('bg-[#A66E38]', 'text-white', 'border-[#8B5A2B]');
                currentDate = this.getAttribute('data-date');
                fetchAvailability(currentDate);
            });
        });

        // Helper function untuk format waktu
        function formatTime(timeString) {
            const time = new Date('2000-01-01 ' + timeString);
            return time.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            });
        }

        // Fetch availability function
        function fetchAvailability(date) {
            fetch(`/api/all-available-slots?date=${date}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        fieldAvailability = data.fieldAvailability;
                        updateScheduleDisplay();
                    }
                });
        }

        function updateScheduleDisplay() {
            // Update price badges first
            document.querySelectorAll('.slot-price-badge').forEach(badge => {
                const hour = parseInt(badge.getAttribute('data-hour'));
                const price = getPriceByHour(hour);

                badge.textContent = `Rp ${price.toLocaleString('id-ID')}`;

                // Add color coding based on time
                badge.classList.remove('bg-yellow-100', 'text-yellow-800', 'bg-green-100',
                    'text-green-800', 'bg-purple-100', 'text-purple-800');

                if (hour >= 6 && hour < 12) {
                    badge.classList.add('bg-yellow-100', 'text-yellow-800');
                } else if (hour >= 12 && hour < 17) {
                    badge.classList.add('bg-green-100', 'text-green-800');
                } else {
                    badge.classList.add('bg-purple-100', 'text-purple-800');
                }
            });

            // Desktop view
            document.querySelectorAll('.slot-cell').forEach(cell => {
                const fieldId = cell.getAttribute('data-field-id');
                const timeSlot = cell.getAttribute('data-time-slot');
                const slotData = fieldAvailability[fieldId]?.[timeSlot];

                const div = document.createElement('div');
                div.className =
                    'flex items-center justify-center rounded-md text-sm font-medium h-10 transition duration-200 shadow-sm';

                if (slotData && slotData.available) {
                    div.classList.add('bg-green-100', 'text-green-800', 'border', 'border-green-200');
                    div.innerHTML = `Tersedia`;
                } else if (slotData && slotData.customer_name) {
                    const customerName = slotData.customer_name;
                    const displayName = customerName.length > 10 ? customerName.substring(0, 10) +
                        '...' : customerName;
                    let bgColor = 'bg-red-100',
                        textColor = 'text-red-600',
                        borderColor = 'border-red-200';
                    if (slotData.type === 'member_manual') {
                        bgColor = 'bg-yellow-100';
                        textColor = 'text-yellow-700';
                        borderColor = 'border-yellow-200';
                    }
                    div.classList.add(bgColor, textColor, 'border', borderColor);
                    div.innerHTML =
                        `<span class="text-center leading-tight font-semibold text-xs">${displayName}</span>`;
                } else {
                    div.classList.add('bg-gray-100', 'text-gray-600', 'border', 'border-gray-200');
                    div.innerHTML = `Tidak Tersedia`;
                }
                cell.innerHTML = '';
                cell.appendChild(div);
            });

            // Mobile view
            document.querySelectorAll('.mobile-slots').forEach(container => {
                const fieldId = container.getAttribute('data-field-id');
                container.innerHTML = '';
                slotsData.forEach(slot => {
                    const slotData = fieldAvailability[fieldId]?.[slot.time];
                    const slotHour = parseInt(slot.time.split(':')[0]);
                    const slotPrice = getPriceByHour(slotHour);

                    const slotDiv = document.createElement('div');
                    slotDiv.className =
                        'p-2 rounded-md text-xs font-medium text-center transition duration-200';

                    if (slotData && slotData.available) {
                        slotDiv.classList.add('bg-green-100', 'text-green-800', 'border',
                            'border-green-200');
                        slotDiv.innerHTML = `
                            <div class="font-semibold">${formatTime(slot.time)}</div>
                            <div class="text-xs">Tersedia</div>
                            <div class="text-xs font-bold mt-1">Rp ${slotPrice.toLocaleString('id-ID')}</div>
                        `;
                    } else if (slotData && slotData.customer_name) {
                        const customerName = slotData.customer_name;
                        const displayName = customerName.length > 8 ? customerName.substring(0,
                            8) + '...' : customerName;
                        if (slotData.type === 'member_manual') {
                            slotDiv.classList.add('bg-yellow-100', 'text-yellow-700', 'border',
                                'border-yellow-200');
                        } else {
                            slotDiv.classList.add('bg-red-100', 'text-red-600', 'border',
                                'border-red-200');
                        }
                        slotDiv.innerHTML = `
                            <div class="font-semibold">${formatTime(slot.time)}</div>
                            <div class="text-xs">${displayName}</div>
                            <div class="text-xs font-bold mt-1">Rp ${slotPrice.toLocaleString('id-ID')}</div>
                        `;
                    } else {
                        slotDiv.classList.add('bg-gray-100', 'text-gray-600', 'border',
                            'border-gray-200');
                        slotDiv.innerHTML = `
                            <div class="font-semibold">${formatTime(slot.time)}</div>
                            <div class="text-xs">Tutup</div>
                            <div class="text-xs font-bold mt-1">Rp ${slotPrice.toLocaleString('id-ID')}</div>
                        `;
                    }
                    container.appendChild(slotDiv);
                });
            });
        }

        // Initial load
        fetchAvailability(currentDate);
    });
</script>

<style>
    .date-tab-btn.active {
        background-color: #A66E38 !important;
        color: white !important;
    }

    .schedule-table {
        min-width: 800px;
    }

    .slot-cell {
        min-width: 120px;
    }

    @media (max-width: 768px) {
        .mobile-slots {
            max-height: 300px;
            overflow-y: auto;
        }
    }
</style>

<!-- Facilities Section -->
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
                        {{ $facility->name }}
                    </h3>
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
                                    {{ $facility->name }}
                                </h3>
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

<!-- Documentation Photos Gallery -->
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

<!-- Image Preview Modal with warmer colors -->
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

<!-- WhatsApp Floating Button dengan Fallback -->
<div class="fixed bottom-6 right-6 z-50">
    <a href="https://wa.me/6282210002256?text=Halo%20admin%20Karvin%20Badminton,%20saya%20ingin%20bertanya%20tentang%20booking%20lapangan."
        target="_blank"
        class="group flex items-center justify-center w-14 h-14 bg-green-500 hover:bg-green-600 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-110">

        <!-- Primary: SVG Icon (selalu tersedia) -->
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
            modal.classList.remove('opacity-0');
            modal.querySelector('.transform').classList.remove('scale-90');
        }, 10);
    }

    function closePosterPreview() {
        const modal = document.getElementById('posterModal');
        modal.classList.add('opacity-0');
        modal.querySelector('.transform').classList.add('scale-90');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    // Initialize Swiper for facilities on mobile
    document.addEventListener('DOMContentLoaded', function() {
        const facilitiesSwiper = new Swiper('.facilities-swiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            centeredSlides: true,
            loop: true,
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
            effect: 'slide',
            speed: 600,
        });
    });
</script>

<style>
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }

    .carousel-img {
        transition: transform 0.3s ease-in-out, z-index 0.3s ease-in-out;
    }

    .carousel-img:hover .title-overlay {
        opacity: 1;
    }

    .carousel-img:hover {
        transform: scale(1.05) !important;
    }

    /* Swiper custom styles */
    .swiper-pagination-bullet {
        background: #A66E38;
        opacity: 0.5;
    }

    .swiper-pagination-bullet-active {
        opacity: 1;
        background: #8B5A2B;
    }

    .swiper-button-next,
    .swiper-button-prev {
        color: #A66E38 !important;
        font-weight: bold;
    }

    .swiper-button-next:after,
    .swiper-button-prev:after {
        font-size: 18px;
    }

    /* Modal animations */
    #posterModal {
        transition: opacity 0.3s ease-in-out;
    }

    #posterModal .transform {
        transition: transform 0.5s ease-out;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {

        .swiper-button-next,
        .swiper-button-prev {
            display: none;
        }
    }

    /* Image preview modal improvements */
    #previewModal {
        backdrop-filter: blur(5px);
    }

    #previewFullImg {
        transition: opacity 0.2s ease-in-out;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    /* Hover effects for navigation buttons */
    button:hover {
        transform: scale(1.05);
    }

    /* Smooth transitions for all interactive elements */
    .transition {
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 300ms;
    }
</style>
@endsection
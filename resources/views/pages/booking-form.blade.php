<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Your Field</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<style>
    .field-checkbox {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    label.flex.items-center {
        cursor: pointer;
    }

    /* Tooltip styling */
    [title] {
        position: relative;
    }

    [title]:hover::after {
        content: attr(title);
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        white-space: nowrap;
        z-index: 1000;
        pointer-events: none;
    }

    /* Loading state untuk slot */
    .slot-loading {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
    }

    @keyframes loading {
        0% {
            background-position: 200% 0;
        }

        100% {
            background-position: -200% 0;
        }
    }

    /* Success message styling */
    .success-message {
        animation: slideDown 0.5s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<body class="bg-emerald-900/95">
    <div class>

        <!-- Content -->
        <div class="relative z-10">
            <!-- Header with elegant styling -->
            <div class="text-center mb-6 sm:mb-8 pt-16 sm:pt-12">
                <div
                    class="inline-flex items-center justify-center w-12 h-12 sm:w-14 sm:h-14 bg-white/90 backdrop-blur-sm rounded-2xl mb-3 sm:mb-4 shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 sm:h-7 sm:w-7 text-emerald-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-green-400 mb-2 tracking-tight px-4">
                    Pesan Lapangan Badminton Karvin
                </h1>
                <div class="w-8 sm:w-20 h-0.5 bg-gradient-to-r from-green-600 to-green-300 mx-auto"></div>
            </div>

            <!-- Button Kembali - Pojok Kanan Atas -->
            <a href="/"
                class="absolute top-4 right-4 sm:top-6 sm:right-6 inline-flex items-center px-3 py-2 sm:px-4 sm:py-2.5 bg-white/90 backdrop-blur-sm hover:bg-white/90 text-green-600 font-medium rounded-lg sm:rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 border border-green-600/10 hover:border-green-600/20 text-sm sm:text-base">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 sm:mr-2" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span class="hidden sm:inline">Kembali ke Beranda</span>
                <span class="sm:hidden">Kembali</span>
            </a>
        </div>
    </div>

    <!-- Error Container -->
    <div id="error-container" class="hidden mb-4 bg-red-50 border border-red-200 text-red-800 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">Terjadi Kesalahan</h3>
                <div class="mt-2 text-sm text-red-700">
                    <p id="error-message">Gagal mengambil data slot. Silakan refresh halaman.</p>
                </div>
            </div>
        </div>
    </div>

    <form id="bookingForm" action="/booking/process" method="POST" class="space-y-6">
        @csrf

        <!-- Date Selection -->
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <div class="flex items-center mb-4">
                <div class="bg-green-100 p-2 rounded-full mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-green-600">Pilih Tanggal</h2>
            </div>

            <p class="text-sm text-gray-600 mb-4">Pilih tanggal pemesanan yang Anda inginkan dari pilihan yang tersedia
                di bawah ini.</p>

            <div class="mb-3">
                <div class="flex items-center text-sm text-gray-600 mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>hari ini Tanggal: <span class="font-medium" id="today-date">{{ date('d M, Y') }}</span></span>
                </div>
            </div>

            <div class="flex flex-wrap gap-2 mb-4" id="date-selector-container">
                <!-- Date buttons will be populated by JavaScript -->
            </div>

            <input type="hidden" name="booking_date" id="booking_date" required>
        </div>

        <!-- Field and Time Selection -->
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <div class="flex items-center mb-4">
                <div class="bg-green-100 p-2 rounded-full mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-green-600">Pilih Lapangan & Waktu Bermain</h2>
            </div>

            <div class="flex items-center mb-4 p-3 bg-green-50 rounded-lg border border-green-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600 mr-2" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm text-green-700">Klik pada slot waktu yang tersedia untuk memesan. <span
                        class="font-medium">Slot hijau tersedia</span>, <span class="font-medium">slot merah tidak
                        tersedia</span>.</p>
            </div>

            <!-- Legend dengan keterangan member -->
            <div class="flex flex-wrap gap-3 mb-4">
                <div class="flex items-center px-3 py-1 bg-green-100 rounded-full text-sm text-green-800">
                    <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                    <span>Tersedia</span>
                </div>
                <div class="flex items-center px-3 py-1 bg-yellow-100 rounded-full text-sm text-yellow-800">
                    <span class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></span>
                    <span>Terpilih</span>
                </div>
                <div class="flex items-center px-3 py-1 bg-red-100 rounded-full text-sm text-red-800">
                    <span class="w-3 h-3 bg-red-500 rounded-full mr-2"></span>
                    <span>Tidak Tersedia</span>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="w-full border-collapse booking-table">
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
                        <!-- Field headers will be populated by JavaScript -->
                    </tr>
                </thead>
                <tbody id="booking-table-body">
                    <!-- Time slots will be populated by JavaScript -->
                </tbody>
            </table>
        </div>

        <div id="selected-slots-container" class="mt-5 hidden">
            <div class="flex items-center mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600 mr-2" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="font-medium text-green-600">Kamu Pilih Slot:</h3>
            </div>
            <div id="selected-slots-summary" class="p-4 bg-green-50 rounded-lg border border-green-100 text-sm">
            </div>
        </div>
        </div>

        <!-- Customer Information -->
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <div class="flex items-center mb-4">
                <div class="bg-green-100 p-2 rounded-full mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-green-600">Informasi Pelanggan</h2>
            </div>

            <p class="text-sm text-gray-600 mb-4">Silakan berikan detail kontak Anda untuk konfirmasi pemesanan.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="space-y-1">
                    <label for="customer_name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <input type="text" name="customer_name" id="customer_name" placeholder="John Doe"
                            class="w-full pl-10 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-green-600"
                            required>
                    </div>
                    <span class="text-red-500 text-sm customer_name-error"></span>
                </div>

                <div class="space-y-1">
                    <label for="customer_email" class="block text-sm font-medium text-gray-700">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <input type="email" name="customer_email" id="customer_email"
                            placeholder="johndoe@example.com"
                            class="w-full pl-10 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-green-600"
                            required>
                    </div>
                    <p class="text-xs text-gray-500">Kami akan mengirimkan konfirmasi pemesanan ke email ini</p>
                    <span class="text-red-500 text-sm customer_email-error"></span>
                </div>

                <div class="space-y-1">
                    <label for="customer_phone" class="block text-sm font-medium text-gray-700">Nomor
                        Handphone</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <input type="text" name="customer_phone" id="customer_phone"
                            placeholder="+62 812-3456-7890"
                            class="w-full pl-10 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-green-600"
                            required>
                    </div>
                    <p class="text-xs text-gray-500">Untuk pemberitahuan mendesak tentang pemesanan Anda</p>
                    <span class="text-red-500 text-sm customer_phone-error"></span>
                </div>
            </div>
        </div>

        <!-- Payment Method -->
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <div class="flex items-center mb-4">
                <div class="bg-green-100 p-2 rounded-full mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-green-600">Metode Pembayaran</h2>
            </div>

            <div class="space-y-3">
                <label
                    class="block p-4 border rounded-lg transition-all hover:border-green-600 hover:bg-green-50 cursor-pointer border-gray-300 bg-gray-50">
                    <div class="flex items-center">
                        <input type="radio" name="payment_method" value="online" checked
                            class="h-5 w-5 text-green-600 focus:ring-green-600">
                        <div class="ml-3 flex-1">
                            <div class="flex items-center justify-between">
                                <span class="text-green-600 font-medium">Pembayaran Online</span>
                            </div>
                            <p class="text-gray-600 text-sm mt-1">Bayar dengan aman secara online dengan kartu kredit,
                                transfer bank, atau dompet elektronik</p>
                            <div class="mt-2 flex flex-wrap gap-2">
                                <span class="inline-block px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Credit
                                    Card</span>
                                <span class="inline-block px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Bank
                                    Transfer</span>
                                <span
                                    class="inline-block px-2 py-1 bg-green-100 text-green-800 text-xs rounded">GoPay</span>
                                <span
                                    class="inline-block px-2 py-1 bg-green-100 text-green-800 text-xs rounded">OVO</span>
                            </div>
                        </div>
                    </div>
                </label>
            </div>
        </div>

        <!-- Booking Summary and Total -->
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <h2 class="text-xl font-semibold mb-4 text-green-600">Ringkasan Pemesanan</h2>
            <div id="booking-summary" class="mb-4">
                <p class="text-gray-600 italic">Please select field(s) and time slot(s) to see the summary</p>
            </div>
            <div class="flex justify-between items-center border-t border-gray-300 pt-4 mt-4">
                <span class="text-xl font-bold text-green-600">Total Biaya:</span>
                <span id="total-price" class="text-xl font-bold text-green-600">Rp -</span>
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit"
            class="w-full py-4 px-6 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg shadow-lg transition-colors flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
            Lanjutkan ke Pembayaran
        </button>

        <p class="text-center text-sm text-gray-500 mt-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            Secure payment powered by Midtrans
        </p>
    </form>

    <!-- Data untuk JavaScript -->
    <script>
        const fieldsData = <?php echo json_encode($fields); ?>;
        const slotsData = <?php echo json_encode($slots); ?>;
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const fields = fieldsData;
            const slots = slotsData;
            let fieldAvailability = {};
            const selectedSlots = {};
            const fieldPrices = {};
            let currentDate = '';
            let totalAmount = 0;

            fields.forEach(field => {
                fieldPrices[field.id] = field.price_per_hour;
            });

            // ===== CACHE MANAGEMENT FUNCTIONS =====
            function clearAllCaches() {
                // Clear browser caches
                if ('caches' in window) {
                    caches.keys().then(function(names) {
                        names.forEach(function(name) {
                            caches.delete(name);
                        });
                    });
                }

                // Clear localStorage and sessionStorage
                if (typeof(Storage) !== "undefined") {
                    localStorage.removeItem('fieldAvailability');
                    localStorage.removeItem('lastFetchTime');
                    sessionStorage.clear();
                }

                // Clear any cached availability data
                fieldAvailability = {};
            }

            function forceRefreshAvailability(date = null) {
                const targetDate = date || currentDate;

                // Clear caches first
                clearAllCaches();

                // Clear error container
                document.getElementById("error-container")?.classList.add("hidden");

                // Add cache-busting parameter
                const timestamp = new Date().getTime();
                const url = `/api/all-available-slots?date=${targetDate}&_t=${timestamp}&_refresh=true`;

                console.log('Force refreshing availability for date:', targetDate);

                fetch(url, {
                        method: 'GET',
                        headers: {
                            'Cache-Control': 'no-cache, no-store, must-revalidate',
                            'Pragma': 'no-cache',
                            'Expires': '0'
                        }
                    })
                    .then(response => {
                        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            fieldAvailability = data.fieldAvailability;
                            renderTimeSlots();
                            updateSelectedSlotsDisplay();
                            updateBookingSummary();
                            updateTotalPrice();
                            console.log('Availability refreshed successfully');
                        } else {
                            throw new Error("API returned success: false");
                        }
                    })
                    .catch(error => {
                        console.error("Error force refreshing availability:", error);
                        const errorContainer = document.getElementById("error-container");
                        if (errorContainer) {
                            document.getElementById("error-message").textContent =
                                "Gagal memuat data terbaru. Silakan refresh halaman.";
                            errorContainer.classList.remove("hidden");
                        }
                    });
            }

            // Update fetchAvailability function dengan cache busting
            function fetchAvailability(date) {
                document.getElementById("error-container")?.classList.add("hidden");

                // Add cache-busting parameter
                const timestamp = new Date().getTime();
                const url = `/api/all-available-slots?date=${date}&_t=${timestamp}`;

                fetch(url, {
                        method: 'GET',
                        headers: {
                            'Cache-Control': 'no-cache, no-store, must-revalidate',
                            'Pragma': 'no-cache',
                            'Expires': '0'
                        }
                    })
                    .then(response => {
                        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            fieldAvailability = data.fieldAvailability;
                            renderTimeSlots();
                            updateSelectedSlotsDisplay();
                            updateBookingSummary();
                            updateTotalPrice();
                        } else {
                            throw new Error("API returned success: false");
                        }
                    })
                    .catch(error => {
                        console.error("Error fetching availability:", error);
                        const errorContainer = document.getElementById("error-container");
                        if (errorContainer) {
                            document.getElementById("error-message").textContent =
                                "Gagal mengambil data slot. Silakan refresh halaman.";
                            errorContainer.classList.remove("hidden");
                        }
                    });
            }

            // Auto-refresh function untuk real-time updates
            function setupAutoRefresh() {
                const today = new Date().toISOString().split('T')[0];

                // Refresh setiap 30 detik jika tanggal hari ini
                if (currentDate === today) {
                    setInterval(function() {
                        console.log('Auto-refreshing availability...');
                        forceRefreshAvailability(currentDate);
                    }, 30000); // 30 seconds
                }
            }

            // Handle browser back/forward navigation
            window.addEventListener('pageshow', function(event) {
                if (event.persisted) {
                    console.log('Page loaded from cache, force refreshing...');
                    forceRefreshAvailability(currentDate);
                }
            });

            // Handle visibility change (when user switches tabs)
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden) {
                    console.log('Page became visible, refreshing availability...');
                    forceRefreshAvailability(currentDate);
                }
            });

            // Handle focus event (when user returns to page)
            window.addEventListener('focus', function() {
                console.log('Window focused, refreshing availability...');
                setTimeout(() => {
                    forceRefreshAvailability(currentDate);
                }, 500);
            });

            // Handle mobile touch events
            let touchStartTime = 0;
            document.addEventListener('touchstart', function() {
                touchStartTime = Date.now();
            });

            document.addEventListener('touchend', function() {
                const touchDuration = Date.now() - touchStartTime;
                // If quick tap (less than 200ms), refresh availability
                if (touchDuration < 200) {
                    setTimeout(() => {
                        forceRefreshAvailability(currentDate);
                    }, 100);
                }
            });

            // Handle orientation change
            window.addEventListener('orientationchange', function() {
                setTimeout(() => {
                    console.log('Orientation changed, refreshing...');
                    forceRefreshAvailability(currentDate);
                }, 500);
            });

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
            monthlyDates.forEach((dateObj, index) => {
                const dateButton = document.createElement('button');
                dateButton.type = 'button';
                dateButton.className = `date-selector px-4 py-2 border rounded-md transition-colors ${
                    index === 0 ? 'bg-[#059669] text-white border-[#10b981]' : 'bg-white text-black border-white hover:bg-green-50'
                }`;
                dateButton.setAttribute('data-date', dateObj.date);
                dateButton.innerHTML = `
                    <span class="block font-medium">${dateObj.day}</span>
                    <span class="block text-sm">${dateObj.formatted_date}</span>
                `;
                dateContainer.appendChild(dateButton);
            });

            // Event listener untuk tombol tanggal - UPDATED
            document.querySelectorAll('.date-selector').forEach(button => {
                button.addEventListener('click', function() {
                    // Reset semua tombol ke warna default
                    document.querySelectorAll('.date-selector').forEach(btn => {
                        btn.classList.remove('bg-[#059669]', 'bg-blue-500', 'text-white',
                            'border-[#10b981]', 'border-blue-600');
                        btn.classList.add('bg-white', 'text-black', 'border-white',
                            'hover:bg-green-50');
                    });

                    // Ubah warna tombol yang diklik ke coklat keemasan
                    this.classList.remove('bg-white', 'text-black', 'border-white',
                        'hover:bg-green-50');
                    this.classList.add('bg-[#059669]', 'text-white', 'border-[#10b981]');

                    // Update tanggal yang dipilih
                    currentDate = this.getAttribute('data-date');
                    document.getElementById('booking_date').value = currentDate;

                    // Clear selections saat ganti tanggal
                    clearAllSelections();

                    // UPDATED: Force refresh dengan cache clearing
                    console.log('Date changed, force refreshing availability...');
                    forceRefreshAvailability(currentDate);
                });
            });

            currentDate = monthlyDates[0].date;
            document.getElementById('booking_date').value = currentDate;

            // Membuat header tabel untuk lapangan
            const tableHeader = document.querySelector('.booking-table thead tr');
            fields.forEach(field => {
                const th = document.createElement('th');
                th.className = 'border px-4 py-3 bg-gray-50';
                th.innerHTML = `
        <div class="flex flex-col items-center">
            <div class="flex items-center mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                </svg>
                <span class="font-medium text-gray-800">${field.name}</span>
            </div>
            <label class="flex items-center bg-gray-100 px-2 py-1 rounded-full text-xs hover:bg-gray-200 cursor-pointer transition-colors">
                <input type="checkbox" class="mr-1 field-checkbox"
                    id="field_${field.id}"
                    data-field-id="${field.id}"
                    data-field-name="${field.name}"
                    data-field-price="${field.price_per_hour}"
                    name="selected_fields[]"
                    value="${field.id}">
                <span class="text-gray-600">Pilih</span>
            </label>
        </div>
    `;
                tableHeader.appendChild(th);
            });

            function formatTimeRange(startTime) {
                const [hours, minutes] = startTime.split(':');
                const startHour = parseInt(hours);
                const startMin = minutes;
                const endHour = (startHour + 1) % 24;
                return `${hours}:${startMin}-${String(endHour).padStart(2, '0')}:${startMin}`;
            }

            function getPriceByHour(hour) {
                // Pastikan hour adalah integer
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


            function renderTimeSlots() {
                const tableBody = document.getElementById('booking-table-body');
                tableBody.innerHTML = '';

                slots.forEach(slot => {
                    const tr = document.createElement('tr');
                    const slotHour = parseInt(slot.time.split(':')[0]);
                    const slotPrice = getPriceByHour(
                        slotHour); // Pastikan fungsi ini return angka yang valid

                    // Kolom waktu dengan harga
                    const tdTime = document.createElement('td');
                    tdTime.className = 'border px-4 py-3 text-sm font-medium text-gray-700 bg-gray-50';
                    tdTime.innerHTML = `
            <div class="flex flex-col items-center">
                <div class="flex items-center mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-semibold">${formatTimeRange(slot.time)}</span>
                </div>
                <div class="text-xs font-medium px-2 py-1 rounded-full ${
                    slotHour >= 6 && slotHour < 12 ? 'bg-yellow-100 text-yellow-800' :
                    slotHour >= 12 && slotHour < 17 ? 'bg-green-100 text-green-800' :
                    'bg-purple-100 text-purple-800'
                }">
                    Rp ${slotPrice.toLocaleString('id-ID')}
                </div>
            </div>
        `;
                    tr.appendChild(tdTime);

                    // Kolom untuk setiap lapangan
                    fields.forEach(field => {
                        const slotData = fieldAvailability && fieldAvailability[field.id] &&
                            fieldAvailability[field.id][slot.time];
                        const isSelected = selectedSlots[field.id]?.includes(slot.time);

                        const td = document.createElement('td');
                        td.className = 'p-2 border';
                        td.setAttribute('data-field-id', field.id);
                        td.setAttribute('data-time-slot', slot.time);
                        td.setAttribute('data-slot-price', slotPrice);

                        const div = document.createElement('div');
                        div.className =
                            'flex flex-col items-center justify-center rounded-md text-xs font-medium h-12 transition duration-200 shadow-sm';

                        if (isSelected) {
                            div.classList.add('bg-blue-500', 'text-white', 'border',
                                'border-blue-600');
                            div.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Terpilih</span>
                `;
                            td.setAttribute('data-available', 'true');
                            td.classList.add('time-slot');
                        } else if (slotData && slotData.available) {
                            div.classList.add('bg-green-100', 'hover:bg-green-200',
                                'text-green-800', 'cursor-pointer', 'border',
                                'border-green-200', 'hover:border-green-300');
                            div.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    <span>Tersedia</span>
                `;
                            td.setAttribute('data-available', 'true');
                            td.classList.add('time-slot');
                        } else {
                            const customerName = slotData?.customer_name || 'Tidak Tersedia';
                            const displayName = customerName.length > 12 ?
                                customerName.substring(0, 12) + '...' : customerName;

                            div.classList.add('bg-red-100', 'text-red-600', 'cursor-not-allowed',
                                'border', 'border-red-200');
                            div.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span class="text-center leading-tight font-semibold">${displayName}</span>
                `;
                            td.setAttribute('data-available', 'false');
                        }

                        td.appendChild(div);
                        tr.appendChild(td);
                    });

                    tableBody.appendChild(tr);
                });

                addSlotClickEvents();
            }

            function clearAllSelections() {
                for (const fieldId in selectedSlots) {
                    delete selectedSlots[fieldId];
                }
                totalAmount = 0;
                document.querySelectorAll('.field-checkbox').forEach(cb => cb.checked = false);
                renderTimeSlots();
                updateSelectedSlotsDisplay();
                updateBookingSummary();
                updateTotalPrice();
                updateFormInputs();
            }

            function addSlotClickEvents() {
                document.querySelectorAll(".time-slot[data-available='true']").forEach(slot => {
                    slot.addEventListener("click", function() {
                        const fieldId = parseInt(this.getAttribute("data-field-id"));
                        const timeSlot = this.getAttribute("data-time-slot");
                        handleSlotClick(fieldId, timeSlot);
                    });
                });
            }

            function handleSlotClick(fieldId, timeSlot) {
                const fieldCheckbox = document.getElementById(`field_${fieldId}`);
                const slotElement = document.querySelector(
                    `[data-field-id="${fieldId}"][data-time-slot="${timeSlot}"]`);
                const slotPriceAttr = slotElement.getAttribute('data-slot-price');
                const slotPrice = parseInt(slotPriceAttr) || 0; // Validasi untuk mencegah NaN

                // Debug log untuk memastikan harga valid
                console.log('Slot Price:', slotPrice, 'Type:', typeof slotPrice);

                if (isNaN(slotPrice) || slotPrice <= 0) {
                    console.error('Invalid slot price:', slotPriceAttr);
                    return; // Jangan lanjutkan jika harga tidak valid
                }

                if (!selectedSlots[fieldId]) {
                    selectedSlots[fieldId] = [];
                }

                const slotIndex = selectedSlots[fieldId].indexOf(timeSlot);

                if (slotIndex === -1) {
                    selectedSlots[fieldId].push(timeSlot);
                    totalAmount += slotPrice;
                    fieldCheckbox.checked = true;
                } else {
                    selectedSlots[fieldId].splice(slotIndex, 1);
                    totalAmount -= slotPrice;

                    if (selectedSlots[fieldId].length === 0) {
                        delete selectedSlots[fieldId];
                        fieldCheckbox.checked = false;
                    }
                }

                // Debug log untuk memastikan totalAmount valid
                console.log('Total Amount:', totalAmount, 'Type:', typeof totalAmount);

                renderTimeSlots();
                updateSelectedSlotsDisplay();
                updateBookingSummary();
                updateTotalPrice();
                updateFormInputs();
            }

            document.querySelectorAll(".field-checkbox").forEach(checkbox => {
                checkbox.addEventListener("change", function() {
                    const fieldId = parseInt(this.getAttribute("data-field-id"));
                    if (!this.checked) {
                        if (selectedSlots[fieldId]) {
                            totalAmount -= selectedSlots[fieldId].length * fieldPrices[fieldId];
                            delete selectedSlots[fieldId];
                        }
                    }
                    renderTimeSlots();
                    updateSelectedSlotsDisplay();
                    updateBookingSummary();
                    updateTotalPrice();
                    updateFormInputs();
                });
            });

            function updateSelectedSlotsDisplay() {
                const container = document.getElementById("selected-slots-container");
                const summary = document.getElementById("selected-slots-summary");
                const hasSelections = Object.keys(selectedSlots).length > 0;
                if (hasSelections) {
                    let summaryHTML = "";
                    for (const fieldId in selectedSlots) {
                        const fieldName = document.querySelector(`th input[data-field-id="${fieldId}"]`)
                            .getAttribute("data-field-name");
                        const slots = selectedSlots[fieldId].sort();
                        const formattedTimes = slots.map(slot => formatTimeRange(slot)).join(", ");
                        summaryHTML += `<div class="mb-2"><strong>${fieldName}:</strong> ${formattedTimes}</div>`;
                    }
                    summary.innerHTML = summaryHTML;
                    container.classList.remove("hidden");
                } else {
                    container.classList.add("hidden");
                }
            }

            function updateFormInputs() {
                document.querySelectorAll('input[name^="selected_slots["]').forEach(input => input.remove());
                for (const fieldId in selectedSlots) {
                    selectedSlots[fieldId].forEach(slot => {
                        const input = document.createElement("input");
                        input.type = "hidden";
                        input.name = `selected_slots[${fieldId}][]`;
                        input.value = slot;
                        document.getElementById("bookingForm").appendChild(input);
                    });
                }
            }

            function updateBookingSummary() {
                const summaryContainer = document.getElementById("booking-summary");
                if (Object.keys(selectedSlots).length === 0) {
                    summaryContainer.innerHTML =
                        '<p class="text-gray-500 italic text-center py-6">Please select field(s) and time slot(s) to see the summary</p>';
                    return;
                }

                let summaryHTML = '<div class="space-y-4">';

                for (const fieldId in selectedSlots) {
                    const fieldCheckbox = document.getElementById(`field_${fieldId}`);
                    const fieldName = fieldCheckbox.getAttribute("data-field-name");
                    const slots = selectedSlots[fieldId].sort();

                    // PERBAIKAN: Hitung subtotal berdasarkan harga dinamis per slot
                    let subtotal = 0;
                    slots.forEach(slot => {
                        const hour = parseInt(slot.split(':')[0]);
                        const slotPrice = getPriceByHour(hour);
                        subtotal += slotPrice;
                    });

                    const formattedTimes = slots.map(slot => formatTimeRange(slot)).join(", ");

                    summaryHTML += `
            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                <div class="flex items-center justify-between mb-2">
                    <div class="font-medium text-gray-800">${fieldName}</div>
                    <div class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded">
                        ${slots.length} hour${slots.length > 1 ? 's' : ''}
                    </div>
                </div>
                <div class="text-sm text-gray-600 mb-3">
                    <div class="flex items-center mb-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        ${formattedTimes}
                    </div>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        ${currentDate}
                    </div>
                </div>
                <div class="space-y-1 text-sm text-gray-600 py-2 border-t border-gray-200">
                    ${slots.map(slot => {
                        const hour = parseInt(slot.split(':')[0]);
                        const slotPrice = getPriceByHour(hour);
                        return `<div class="flex justify-between">
                                    <span>${formatTimeRange(slot)}:</span>
                                    <span>Rp ${slotPrice.toLocaleString("id-ID")}</span>
                                </div>`;
                    }).join('')}
                </div>
                <div class="flex justify-between font-medium text-gray-800 pt-2 border-t border-gray-300">
                    <span>Subtotal:</span>
                    <span>Rp ${subtotal.toLocaleString("id-ID")}</span>
                </div>
            </div>
        `;
                }

                summaryHTML += "</div>";
                summaryContainer.innerHTML = summaryHTML;
            }


            function updateTotalPrice() {
                document.getElementById("total-price").textContent = `Rp ${totalAmount.toLocaleString("id-ID")}`;
            }

            // Fitur: Cash hanya bisa untuk hari ini
            const paymentRadios = document.querySelectorAll('input[name="payment_method"]');

            function restrictToTodayIfCash() {
                const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
                const today = new Date().toISOString().split('T')[0];
                const cashNote = document.getElementById('cash-payment-note');

                if (paymentMethod === 'cash') {
                    document.querySelectorAll('.date-selector').forEach(btn => {
                        const isToday = btn.getAttribute('data-date') === today;
                        btn.style.display = isToday ? 'block' : 'none';

                        if (isToday) {
                            btn.classList.add('bg-[#A66E38]', 'text-white', 'border-[#8B5A2B]');
                            btn.classList.remove('bg-white', 'text-gray-700', 'border-gray-300',
                                'hover:bg-amber-50');
                            currentDate = today;
                            document.getElementById('booking_date').value = today;
                            clearAllSelections();
                            fetchAvailability(today);
                        }
                    });

                    if (!cashNote) {
                        const noteDiv = document.createElement('div');
                        noteDiv.id = 'cash-payment-note';
                        noteDiv.className =
                            'mt-3 p-3 bg-yellow-50 border-l-4 border-yellow-400 text-yellow-800 text-sm';
                        noteDiv.innerHTML = `
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p>Note: Cash payment is only available for today's bookings.</p>
                                </div>
                            </div>
                        `;
                        document.getElementById('date-selector-container').after(noteDiv);
                    }
                } else {
                    document.querySelectorAll('.date-selector').forEach(btn => {
                        btn.style.display = 'block';
                    });

                    if (cashNote) {
                        cashNote.remove();
                    }
                }
            }

            paymentRadios.forEach(radio => radio.addEventListener('change', restrictToTodayIfCash));
            restrictToTodayIfCash();

            // Validasi form sebelum submit
            document.getElementById("bookingForm").addEventListener("submit", function(e) {
                const hasSelections = Object.keys(selectedSlots).length > 0;
                if (!hasSelections) {
                    e.preventDefault();
                    alert("Please select at least one time slot for a field.");
                    return false;
                }
            });

            // Check for URL parameters yang menandakan pembatalan atau error
            const urlParams = new URLSearchParams(window.location.search);
            const isCancelled = urlParams.get('cancelled') === 'true';
            const hasError = urlParams.get('error') === 'payment';
            const forceRefresh = urlParams.get('refresh') === 'true';

            // Show success message jika ada pembatalan
            if (isCancelled) {
                const successDiv = document.createElement('div');
                successDiv.className =
                    'mb-4 bg-green-50 border border-green-200 text-green-800 rounded-lg p-4 success-message';
                successDiv.innerHTML = `
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-800">Pembayaran Dibatalkan</h3>
                            <div class="mt-2 text-sm text-green-700">
                                <p>Slot waktu telah dibebaskan dan tersedia kembali untuk pemesanan.</p>
                            </div>
                        </div>
                    </div>
                `;
                document.querySelector('form').insertBefore(successDiv, document.querySelector('form').firstChild);

                // Auto-hide after 5 seconds
                setTimeout(() => {
                    successDiv.style.transition = 'opacity 0.5s';
                    successDiv.style.opacity = '0';
                    setTimeout(() => successDiv.remove(), 500);
                }, 5000);
            }

            // Force refresh jika ada parameter khusus
            if (isCancelled || hasError || forceRefresh) {
                console.log('Special condition detected, force refreshing...');
                setTimeout(() => {
                    forceRefreshAvailability(currentDate);
                }, 1000);

                // Clean URL parameters
                if (window.history && window.history.replaceState) {
                    const cleanUrl = window.location.pathname;
                    window.history.replaceState({}, document.title, cleanUrl);
                }
            }

            // Setup auto-refresh
            setupAutoRefresh();

            // Initial load dengan force refresh jika perlu
            if (isCancelled || hasError || forceRefresh) {
                forceRefreshAvailability(currentDate);
            } else {
                fetchAvailability(currentDate);
            }

            // Debug functions - remove in production
            window.debugBooking = {
                clearCache: clearAllCaches,
                forceRefresh: forceRefreshAvailability,
                showAvailability: () => console.log('Current availability:', fieldAvailability),
                showSelections: () => console.log('Selected slots:', selectedSlots)
            };

            // Console log untuk monitoring
            console.log('Booking form initialized with cache management');
        });
    </script>
</body>

</html>

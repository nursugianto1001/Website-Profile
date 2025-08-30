<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemesanan Berhasil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'starbucks-green': '#00704A',
                        'starbucks-dark-green': '#1e3932',
                        'starbucks-light-green': '#00A862',
                        'starbucks-cream': '#f7f5f3',
                        'starbucks-beige': '#d4af37',
                        'forest-green': '#2d5939',
                        'sage-green': '#87A96B',
                        'mint-green': '#A8D8B9'
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gradient-to-br from-starbucks-green via-starbucks-dark-green to-forest-green">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-br from-starbucks-green to-starbucks-dark-green text-white px-6 py-4">
                <h1 class="text-2xl font-bold flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Pemesanan Berhasil!
                </h1>
            </div>
            <div class="p-6">
                <div class="bg-gradient-to-br from-starbucks-green to-starbucks-dark-green rounded-lg p-4 mb-6 border border-starbucks-light-green">
                    <h2 class="font-semibold text-lg mb-2 text-white">Kode Pemesanan</h2>
                    <p class="text-xl font-mono text-white">{{ $bookings->first()->booking_code }}</p>
                    <p class="text-sm text-starbucks-cream mt-1">Silakan simpan kode referensi ini untuk catatan Anda</p>
                </div>

                <div class="space-y-4">
                    @if ($bookings->count() > 1)
                        <div class="flex justify-between pb-2 border-b border-starbucks-light-green">
                            <span class="font-medium text-starbucks-dark-green">Total Blok Waktu:</span>
                            <span class="text-starbucks-green">{{ $bookings->count() }} Blok</span>
                        </div>
                    @endif
                    <div class="flex justify-between pb-2 border-b border-starbucks-light-green">
                        <span class="font-medium text-starbucks-dark-green">Nama Pemesan:</span>
                        <span class="text-starbucks-green">{{ $bookings->first()->customer_name }}</span>
                    </div>
                    <div class="flex justify-between pb-2 border-b border-starbucks-light-green">
                        <span class="font-medium text-starbucks-dark-green">Email:</span>
                        <span class="text-starbucks-green">{{ $bookings->first()->customer_email }}</span>
                    </div>
                    <div class="flex justify-between pb-2 border-b border-starbucks-light-green">
                        <span class="font-medium text-starbucks-dark-green">Nomor Handphone:</span>
                        <span class="text-starbucks-green">{{ $bookings->first()->customer_phone }}</span>
                    </div>
                    <div class="flex justify-between pb-2 border-b border-starbucks-light-green">
                        <span class="font-medium text-starbucks-dark-green">Tanggal Pemesanan:</span>
                        <span
                            class="text-starbucks-green">{{ \Carbon\Carbon::parse($bookings->first()->booking_date)->format('d M Y') }}</span>
                    </div>

                    <!-- [FINAL-FIX] Rincian harga dari data yang sudah pasti -->
                    <div class="border-t border-starbucks-light-green pt-4 mb-2">
                        <h3 class="font-semibold mb-3 text-starbucks-dark-green">Detail Pemesanan:</h3>
                        <div class="space-y-3">
                            @foreach ($bookings as $booking)
                                <div class="bg-gradient-to-br from-starbucks-green to-starbucks-dark-green rounded-lg p-4 border border-sage-green">
                                    <div class="flex justify-between items-start mb-2">
                                        <span class="font-bold text-white">{{ $booking->field->name }}</span>
                                        <span class="text-xs bg-forest-green text-starbucks-cream px-2 py-1 rounded">
                                            {{ $booking->payment_status === 'settlement' ? 'Lunas' : ucfirst($booking->payment_status) }}
                                        </span>
                                    </div>
                                    <div class="text-sm text-starbucks-cream space-y-1">
                                        <div class="flex items-center justify-between">
                                            <span class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                Waktu:
                                            </span>
                                            <span class="font-medium text-white">{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                Durasi:
                                            </span>
                                            <span class="font-medium text-white">{{ $booking->duration_hours }} jam</span>
                                        </div>
                                        
                                        <div class="mt-3 pt-2 border-t border-forest-green">
                                            <span class="text-xs text-starbucks-cream mb-2 block">Rincian Harga per Jam:</span>
                                            {{-- Menggunakan relasi 'slots' yang sudah menyimpan harga pasti --}}
                                            @foreach($booking->slots as $slot)
                                                <div class="flex justify-between text-xs">
                                                    <span>{{ \Carbon\Carbon::parse($slot->slot_time)->format('H:i') }}-{{ \Carbon\Carbon::parse($slot->slot_time)->addHour()->format('H:i') }}:</span>
                                                    <span>Rp {{ number_format($slot->price_per_slot, 0, ',', '.') }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                        
                                        <div class="flex justify-between font-medium text-white pt-2 border-t border-forest-green mt-2">
                                            <span>Subtotal Blok:</span>
                                            {{-- Menampilkan total_price dari blok booking yang bersangkutan --}}
                                            <span>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @php
                        $tax = 5000;
                        // $totalPrice sudah final dari controller, kita hanya hitung mundur subtotalnya.
                        $subtotalAllBookings = $totalPrice - $tax;
                    @endphp
                    <div class="flex justify-between text-base font-medium mt-4 pt-4 border-t border-starbucks-light-green">
                        <span class="text-starbucks-dark-green">Subtotal Pemesanan:</span>
                        <span class="text-starbucks-dark-green">Rp {{ number_format($subtotalAllBookings, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between text-base font-medium">
                        <span class="text-starbucks-dark-green">Biaya Admin:</span>
                        <span class="text-starbucks-dark-green">Rp {{ number_format($tax, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between text-lg font-bold mt-2 pt-4 border-t border-starbucks-light-green">
                        <span class="text-starbucks-dark-green">Total Biaya:</span>
                        <span id="total-amount" class="text-starbucks-dark-green">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="mt-8 space-y-4">
                    <div class="p-3 bg-gradient-to-br from-starbucks-green to-starbucks-dark-green border-l-4 border-forest-green text-white rounded">
                        <b>Terima kasih atas pemesanan Anda!</b><br>
                        Setiap transaksi yang dilakukan akan dikenakan biaya Admin sebesar Rp 5.000.<br>
                        <div class="mt-3 text-sm">
                            <p><strong>Catatan Penting:</strong></p>
                            <ul class="list-disc list-inside mt-1 space-y-1">
                                <li>Harap datang 15 menit sebelum waktu bermain</li>
                                <li>Bawa kode pemesanan dan identitas diri</li>
                                <li>Pembatalan dapat dilakukan maksimal 2 jam sebelum waktu bermain</li>
                            </ul>
                        </div>
                    </div>

                    <div class="text-center">
                        <button id="whatsapp-redirect-button"
                            class="inline-flex items-center justify-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg shadow-lg transition-colors w-full sm:w-auto">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12.04 2.5C6.58 2.5 2.13 6.95 2.13 12.41C2.13 14.28 2.66 16.03 3.61 17.5L2.5 21.5L6.65 20.44C8.07 21.32 9.74 21.82 11.51 21.82H12.03C17.49 21.82 21.94 17.37 21.94 11.91C21.94 9.25 20.87 6.8 19.01 4.94C17.15 3.08 14.7 2.01 12.04 2.01V2.5ZM12.04 4.01C14.2 4.01 16.21 4.83 17.76 6.38C19.31 7.93 20.13 9.94 20.13 12.1C20.13 16.5 16.54 20.1 12.04 20.1C10.4 20.1 8.84 19.59 7.59 18.7L7.43 18.6L4.88 19.26L5.54 16.78L5.42 16.62C4.52 15.28 4.02 13.72 4.02 12.1C4.02 7.6 7.61 4.01 12.04 4.01ZM16.99 14.89C16.74 15.54 15.74 16.08 15.34 16.14C14.94 16.2 14.39 16.25 12.84 15.64C11 14.91 9.54 13.29 9.4 13.15C9.26 13.01 8.24 11.98 8.24 10.95C8.24 9.92 8.85 9.36 9.07 9.14C9.29 8.92 9.59 8.86 9.81 8.86C9.91 8.86 10.01 8.86 10.1 8.87C10.29 8.89 10.41 8.9 10.56 9.22C10.71 9.54 11.13 10.59 11.19 10.7C11.25 10.81 11.3 10.9 11.2 11.02C11.1 11.14 11.04 11.2 10.9 11.34C10.76 11.48 10.63 11.58 10.5 11.7C10.37 11.82 10.24 11.96 10.43 12.25C10.62 12.54 11.27 13.43 12.11 14.16C13.14 14.99 13.92 15.25 14.16 15.35C14.4 15.45 14.63 15.43 14.79 15.24C14.95 15.05 15.39 14.53 15.58 14.24C15.77 13.95 15.96 13.93 16.19 14C16.42 14.07 17.24 14.49 17.42 14.67C17.6 14.85 17.72 15.01 17.74 15.15C17.76 15.29 17.74 15.91 17.49 16.56L16.99 14.89Z"></path></svg>
                            Konfirmasi via WhatsApp Sekarang
                        </button>
                    </div>
                    
                    <div class="flex justify-center space-x-4 mt-6">
                        <a href="/"
                            class="inline-flex items-center px-5 py-3 bg-gradient-to-br from-starbucks-light-green via-sage-green to-mint-green hover:from-sage-green hover:to-starbucks-light-green text-starbucks-dark-green font-medium rounded-lg shadow transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                            Kembali ke Beranda
                        </a>
                        <a href="{{ route('booking.form') }}"
                            class="inline-flex items-center px-5 py-3 bg-gradient-to-br from-starbucks-green to-starbucks-dark-green hover:from-starbucks-dark-green hover:to-forest-green text-white font-medium rounded-lg shadow transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                            Buat Pemesanan Lain
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center text-starbucks-cream text-sm mt-4">
            <span>Anda akan diarahkan ke WhatsApp Admin untuk konfirmasi pemesanan dalam <span id="countdown">5 menit</span>.</span>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const waNumber = "6281258811801"; 
            const customerName = "{{ $bookings->first()->customer_name }}";
            const kodeBooking = "{{ $bookings->first()->booking_code ?? '-' }}";
            const total = "{{ number_format($totalPrice, 0, ',', '.') }}";
            let countdownInterval;
            
            const bookings = @json($bookings->map(function($b) {
                return [
                    'field' => $b->field->name,
                    'date' => \Carbon\Carbon::parse($b->booking_date)->format('d M Y'),
                    'time' => \Carbon\Carbon::parse($b->start_time)->format('H:i') . ' - ' . \Carbon\Carbon::parse($b->end_time)->format('H:i')
                ];
            }));

            function redirectToWhatsApp() {
                clearInterval(countdownInterval); // Hentikan countdown jika redirect terjadi
                let detailsText = "";
                bookings.forEach(b => {
                    detailsText += `\n- ${b.field}: ${b.date} (${b.time})`;
                });

                const message = encodeURIComponent(
                    `Halo Admin, saya (${customerName}) telah melakukan pembayaran booking.\n\n` +
                    `*Kode Booking:* ${kodeBooking}\n` +
                    `*Detail Pemesanan:*${detailsText}\n\n` +
                    `*Total:* Rp ${total}\n\n` +
                    `Mohon konfirmasinya ya.`
                );

                window.location.href = `https://wa.me/${waNumber}?text=${message}`;
            }

            function startCountdown() {
                let duration = 5 * 60; // 5 menit dalam detik
                const countdownElement = document.getElementById('countdown');

                countdownInterval = setInterval(function() {
                    let minutes = parseInt(duration / 60, 10);
                    let seconds = parseInt(duration % 60, 10);

                    minutes = minutes < 10 ? "0" + minutes : minutes;
                    seconds = seconds < 10 ? "0" + seconds : seconds;

                    countdownElement.textContent = minutes + " menit " + seconds + " detik";

                    if (--duration < 0) {
                        clearInterval(countdownInterval);
                        redirectToWhatsApp();
                    }
                }, 1000);
            }

            // Mulai countdown otomatis
            startCountdown();

            // Tambahkan event listener untuk tombol redirect manual
            document.getElementById('whatsapp-redirect-button').addEventListener('click', redirectToWhatsApp);
        });
    </script>
</body>

</html>

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
                        amber: {
                            50: '#fdf8f2',
                            100: '#faebd7',
                            200: '#f5deb3',
                        },
                        golden: {
                            DEFAULT: '#A66E38',
                            dark: '#8B5A2B',
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-[#fdf8f2]">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto bg-gradient-to-br from-amber-50 via-amber-100/30 to-[#fdf5e9] rounded-lg shadow-lg overflow-hidden">
            <div class="bg-[#A66E38] text-white px-6 py-4">
                <h1 class="text-2xl font-bold flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Pemesanan Berhasil!
                </h1>
            </div>
            <div class="p-6">
                <div class="bg-amber-100 rounded-lg p-4 mb-6 border border-amber-200">
                    <h2 class="font-semibold text-lg mb-2 text-[#8B5A2B]">Kode Pemesanan</h2>
                    <p class="text-xl font-mono text-[#A66E38]">{{ $bookings->first()->booking_code }}</p>
                    <p class="text-sm text-gray-600 mt-1">Silakan simpan kode referensi ini untuk catatan Anda</p>
                </div>
                
                <div class="space-y-4">
                    @if ($bookings->count() > 1)
                    <div class="flex justify-between pb-2 border-b border-amber-100">
                        <span class="font-medium">Total Pemesanan:</span>
                        <span>{{ $bookings->count() }} Lapangan</span>
                    </div>
                    @endif
                    <div class="flex justify-between pb-2 border-b border-amber-100">
                        <span class="font-medium">Nama Pemesan:</span>
                        <span>{{ $bookings->first()->customer_name }}</span>
                    </div>
                    <div class="flex justify-between pb-2 border-b border-amber-100">
                        <span class="font-medium">Email:</span>
                        <span>{{ $bookings->first()->customer_email }}</span>
                    </div>
                    <div class="flex justify-between pb-2 border-b border-amber-100">
                        <span class="font-medium">Nomor Handphone:</span>
                        <span>{{ $bookings->first()->customer_phone }}</span>
                    </div>
                    <div class="flex justify-between pb-2 border-b border-amber-100">
                        <span class="font-medium">Tanggal Pemesanan:</span>
                        <span>{{ \Carbon\Carbon::parse($bookings->first()->booking_date)->format('d M Y') }}</span>
                    </div>
                    
                    <!-- Detail Lapangan dengan Harga Dinamis -->
                    <div class="border-t border-amber-100 pt-4 mb-2">
                        <h3 class="font-semibold mb-3 text-[#8B5A2B]">Detail Pemesanan:</h3>
                        <div class="space-y-3">
                            @foreach($bookings as $booking)
                            <div class="bg-white rounded-lg p-4 border border-amber-200">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="font-bold text-[#A66E38]">{{ $booking->field->name }}</span>
                                    <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">
                                        {{ $booking->payment_status === 'settlement' ? 'Lunas' : ucfirst($booking->payment_status) }}
                                    </span>
                                </div>
                                
                                <div class="text-sm text-gray-600 space-y-1">
                                    <div class="flex items-center justify-between">
                                        <span class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Waktu:
                                        </span>
                                        <span class="font-medium">{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</span>
                                    </div>
                                    
                                    <div class="flex items-center justify-between">
                                        <span class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Durasi:
                                        </span>
                                        <span class="font-medium">{{ $booking->duration_hours }} jam</span>
                                    </div>
                                    
                                    <!-- Breakdown Harga per Slot Waktu -->
                                    @if($booking->slots && $booking->slots->count() > 0)
                                    <div class="mt-3 pt-2 border-t border-gray-200">
                                        <span class="text-xs text-gray-500 mb-2 block">Rincian Harga per Jam:</span>
                                        @foreach($booking->slots as $slot)
                                        <div class="flex justify-between text-xs">
                                            <span>{{ \Carbon\Carbon::parse($slot->slot_time)->format('H:i') }}-{{ \Carbon\Carbon::parse($slot->slot_time)->addHour()->format('H:i') }}:</span>
                                            <span>Rp {{ number_format($slot->price_per_slot ?? 0, 0, ',', '.') }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                    @else
                                    <!-- Fallback jika tidak ada data slots -->
                                    <div class="mt-3 pt-2 border-t border-gray-200">
                                        <span class="text-xs text-gray-500 mb-2 block">Rincian Harga:</span>
                                        @php
                                            $startHour = \Carbon\Carbon::parse($booking->start_time)->format('H');
                                            $endHour = \Carbon\Carbon::parse($booking->end_time)->format('H');
                                            $totalSlots = $endHour - $startHour;
                                        @endphp
                                        @for($i = 0; $i < $totalSlots; $i++)
                                            @php
                                                $currentHour = $startHour + $i;
                                                $nextHour = $currentHour + 1;
                                                // Hitung harga berdasarkan waktu (sesuai sistem booking form)
                                                if($currentHour >= 6 && $currentHour < 12) {
                                                    $slotPrice = 40000; // Pagi
                                                } elseif($currentHour >= 12 && $currentHour < 17) {
                                                    $slotPrice = 25000; // Siang
                                                } elseif($currentHour >= 17 && $currentHour < 23) {
                                                    $slotPrice = 60000; // Malam
                                                } else {
                                                    $slotPrice = 40000; // Default
                                                }
                                            @endphp
                                            <div class="flex justify-between text-xs">
                                                <span>{{ sprintf('%02d:00-%02d:00', $currentHour, $nextHour) }}:</span>
                                                <span>Rp {{ number_format($slotPrice, 0, ',', '.') }}</span>
                                            </div>
                                        @endfor
                                    </div>
                                    @endif
                                    
                                    <div class="flex justify-between font-medium text-[#A66E38] pt-2 border-t border-gray-300 mt-2">
                                        <span>Subtotal:</span>
                                        <span>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="flex justify-between text-lg font-bold mt-4 pt-4 border-t border-amber-200">
                        <span>Total Biaya:</span>
                        <span id="total-amount" class="text-[#A66E38]">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                    </div>
                </div>
                
                <div class="mt-8 space-y-4">
                    <div class="p-3 bg-amber-100 border-l-4 border-[#A66E38] text-[#8B5A2B] rounded">
                        <b>Terima kasih atas pemesanan Anda!</b><br>
                        Kami telah mengirimkan detail pemesanan ke email Anda. Silakan tunjukkan kode pemesanan saat Anda tiba di lokasi.
                        
                        <div class="mt-3 text-sm">
                            <p><strong>Catatan Penting:</strong></p>
                            <ul class="list-disc list-inside mt-1 space-y-1">
                                <li>Harap datang 15 menit sebelum waktu bermain</li>
                                <li>Bawa kode pemesanan dan identitas diri</li>
                                <li>Pembatalan dapat dilakukan maksimal 2 jam sebelum waktu bermain</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="flex justify-center space-x-4 mt-6">
                        <a href="/" class="inline-flex items-center px-5 py-3 bg-amber-100 hover:bg-amber-200 text-[#8B5A2B] font-medium rounded-lg shadow transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Kembali ke Beranda
                        </a>
                        <a href="/fields/book" class="inline-flex items-center px-5 py-3 bg-[#A66E38] hover:bg-[#8B5A2B] text-white font-medium rounded-lg shadow transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Buat Pemesanan Lain
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        console.log('Booking success page loaded at:', new Date().toISOString());
    </script>
</body>

</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pembayaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Midtrans Snap.js -->
    @if(config('midtrans.is_production'))
    <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    @else
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    @endif
</head>

<body class="bg-[#fdf8f2]">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <h1 class="text-3xl font-bold mb-6 text-[#A66E38]">Detail Pembayaran</h1>
            <div class="bg-gradient-to-br from-amber-50 via-amber-100/30 to-[#fdf5e9] rounded-lg shadow-lg overflow-hidden mb-6">
                <div class="bg-[#A66E38] text-white px-6 py-4">
                    <h2 class="text-xl font-semibold">Ringkasan Pemesanan</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between border-b border-amber-100 pb-3">
                        <span class="font-medium">Nama Pemesan:</span>
                        <span>{{ $bookings[0]['customer_name'] }}</span>
                    </div>
                    <div class="flex justify-between border-b border-amber-100 pb-3">
                        <span class="font-medium">Email:</span>
                        <span>{{ $bookings[0]['customer_email'] }}</span>
                    </div>
                    <div class="flex justify-between border-b border-amber-100 pb-3">
                        <span class="font-medium">Nomor Handphone:</span>
                        <span>{{ $bookings[0]['customer_phone'] }}</span>
                    </div>
                    <div class="flex justify-between border-b border-amber-100 pb-3">
                        <span class="font-medium">Tanggal:</span>
                        <span>{{ \Carbon\Carbon::parse($bookings[0]['booking_date'])->format('d M Y') }}</span>
                    </div>
                    
                    <div class="border-t border-amber-100 pt-4 mb-2">
                        <h3 class="font-semibold mb-2 text-[#8B5A2B]">Lapangan yang Dipesan:</h3>
                        <div class="space-y-1">
                            @foreach($bookings as $booking)
                            <div>
                                <span class="font-bold text-[#A66E38]">{{ $booking['field_name'] }}</span>:
                                {{ \Carbon\Carbon::parse($booking['start_time'])->format('H:i') }}-{{ \Carbon\Carbon::parse($booking['end_time'])->format('H:i') }}
                                (Rp {{ number_format($booking['total_price'], 0, ',', '.') }})
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="flex justify-between text-lg font-bold mt-4">
                        <span>Total Biaya:</span>
                        <span class="text-[#A66E38]">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="mt-4 p-3 bg-amber-100 border-l-4 border-[#A66E38] text-[#8B5A2B] rounded">
                        <b>Informasi Pembayaran:</b><br>
                        Data booking Anda belum tersimpan di sistem. Booking akan otomatis tersimpan setelah pembayaran berhasil.<br>
                        Jika membatalkan pembayaran, slot waktu akan tetap tersedia untuk customer lain.
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-6">
                <a href="/fields/book" class="inline-flex items-center px-5 py-3 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg shadow transition-colors mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Form Booking
                </a>
                
                <button id="pay-button" class="inline-flex items-center px-5 py-3 bg-[#A66E38] hover:bg-[#8B5A2B] text-white font-medium rounded-lg shadow transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-3a2 2 0 00-2-2H9a2 2 0 00-2 2v3a2 2 0 002 2z" />
                    </svg>
                    Bayar Sekarang
                </button>
            </div>

            <!-- Loading indicator -->
            <div id="loading-indicator" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                <div class="bg-[#fdf8f2] p-6 rounded-lg shadow-lg text-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#A66E38] mx-auto mb-4"></div>
                    <p class="text-[#8B5A2B] font-medium">Memproses pembayaran...</p>
                    <p class="text-gray-600 text-sm mt-2">Mohon tunggu sebentar</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loadingIndicator = document.getElementById('loading-indicator');
            const payButton = document.getElementById('pay-button');
            const snapToken = "{{ $snapToken }}";

            console.log('Payment pending page loaded');
            console.log('Snap token:', snapToken ? 'Available' : 'Not available');

            payButton.addEventListener('click', function() {
                if (snapToken) {
                    console.log('Starting payment process...');
                    loadingIndicator.classList.remove('hidden');
                    
                    snap.pay(snapToken, {
                        onSuccess: function(result) {
                            console.log('Payment success:', result);
                            loadingIndicator.classList.add('hidden');
                            
                            // Debug log
                            console.log('Redirecting to success handler with order_id:', result.order_id);
                            
                            // Redirect ke success handler yang akan menyimpan data ke database
                            const successUrl = '/payment/success-handler?order_id=' + result.order_id;
                            console.log('Success URL:', successUrl);
                            
                            window.location.href = successUrl;
                        },
                        onPending: function(result) {
                            console.log('Payment pending:', result);
                            loadingIndicator.classList.add('hidden');
                            
                            const pendingUrl = '/payment/pending-handler?order_id=' + result.order_id;
                            console.log('Pending URL:', pendingUrl);
                            
                            window.location.href = pendingUrl;
                        },
                        onError: function(result) {
                            console.error('Payment error:', result);
                            loadingIndicator.classList.add('hidden');
                            alert('Pembayaran gagal. Silakan coba lagi.');
                        },
                        onClose: function() {
                            console.log('Payment popup closed');
                            loadingIndicator.classList.add('hidden');
                            // Tidak melakukan apa-apa, biarkan user tetap di halaman ini
                        }
                    });
                } else {
                    console.error('Snap token not available');
                    alert('Token pembayaran tidak ditemukan. Silakan coba lagi.');
                    window.location.href = '/booking/form';
                }
            });

            // Debug: Log current session state
            console.log('Current URL:', window.location.href);
            console.log('Page loaded at:', new Date().toISOString());
        });
    </script>
</body>
</html>

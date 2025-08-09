<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pembayaran</title>
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
    <!-- Midtrans Snap.js -->
    @if(config('midtrans.is_production'))
    <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    @else
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    @endif
</head>

<body class="bg-gradient-to-br from-starbucks-green via-starbucks-dark-green to-forest-green min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <h1 class="text-3xl font-bold mb-6 text-starbucks-cream">Detail Pembayaran</h1>
            <div class="bg-gradient-to-br from-starbucks-light-green via-sage-green to-mint-green rounded-lg shadow-xl overflow-hidden mb-6 border border-starbucks-light-green">
                <div class="bg-gradient-to-r from-starbucks-green to-starbucks-dark-green text-white px-6 py-4">
                    <h2 class="text-xl font-semibold">Ringkasan Pemesanan</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between border-b border-starbucks-green pb-3">
                        <span class="font-medium text-starbucks-dark-green">Nama Pemesan:</span>
                        <span class="text-forest-green">{{ $bookings->first()->customer_name }}</span>
                    </div>
                    <div class="flex justify-between border-b border-starbucks-green pb-3">
                        <span class="font-medium text-starbucks-dark-green">Email:</span>
                        <span class="text-forest-green">{{ $bookings->first()->customer_email }}</span>
                    </div>
                    <div class="flex justify-between border-b border-starbucks-green pb-3">
                        <span class="font-medium text-starbucks-dark-green">Nomor Handphone:</span>
                        <span class="text-forest-green">{{ $bookings->first()->customer_phone }}</span>
                    </div>
                    <div class="flex justify-between border-b border-starbucks-green pb-3">
                        <span class="font-medium text-starbucks-dark-green">Waktu:</span>
                        <span class="text-forest-green">{{ \Carbon\Carbon::parse($bookings->first()->booking_date)->format('d M Y') }}</span>
                    </div>

                    <!-- Ringkasan multi-lapangan -->
                    <div class="border-t border-starbucks-green pt-4 mb-2">
                        <h3 class="font-semibold mb-2 text-starbucks-dark-green">Lapangan yang Dipesan:</h3>
                        <div class="space-y-1">
                            @foreach($bookings as $b)
                            <div>
                                <span class="font-bold text-starbucks-dark-green">{{ $b->field->name }}</span>:
                                {{ \Carbon\Carbon::parse($b->start_time)->format('H:i') }}-{{ \Carbon\Carbon::parse($b->end_time)->format('H:i') }}
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @php
                    $tax = $tax ?? 5000;
                    $subtotal = $totalPrice - $tax;
                    @endphp
                    <div class="flex justify-between text-base font-medium mt-4 pt-2 border-t border-starbucks-green">
                        <span class="text-starbucks-dark-green">Subtotal:</span>
                        <span class="text-forest-green">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-base font-medium">
                        <span class="text-starbucks-dark-green">Biaya Admin:</span>
                        <span class="text-forest-green">Rp {{ number_format($tax, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold mt-2 pt-2 border-t border-starbucks-green">
                        <span class="text-starbucks-dark-green">Total Biaya:</span>
                        <span id="total-amount" class="text-starbucks-dark-green">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                    </div>

                    <div class="mt-4 p-3 bg-gradient-to-br from-starbucks-green to-starbucks-dark-green border-l-4 border-forest-green text-white rounded">
                        <b>Informasi Pembayaran:</b><br>
                        Anda akan diarahkan ke halaman pembayaran Midtrans untuk menyelesaikan transaksi.<br>
                        Pastikan untuk tidak menutup halaman sebelum proses pembayaran selesai.<br>
                        <span class="text-sm text-starbucks-cream">Setiap transaksi dikenakan biaya admin Rp5.000.</span>
                    </div>
                </div>
            </div>

            <div class="text-center mt-6">
                <a href="/" class="inline-flex items-center px-5 py-3 bg-gradient-to-r from-starbucks-green to-starbucks-dark-green hover:from-starbucks-dark-green hover:to-forest-green text-white font-medium rounded-lg shadow-lg transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke halaman utama
                </a>
            </div>

            <!-- Loading indicator -->
            <div id="loading-indicator" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-gradient-to-br from-starbucks-light-green via-sage-green to-mint-green p-6 rounded-lg shadow-lg text-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-starbucks-dark-green mx-auto mb-4"></div>
                    <p class="text-starbucks-dark-green font-medium">Mempersiapkan pembayaran...</p>
                    <p class="text-forest-green text-sm mt-2">Mohon tunggu sebentar</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loadingIndicator = document.getElementById('loading-indicator');
            const snapToken = "{{ $snapToken }}";

            // Dapatkan base URL dinamis
            const baseUrl = `${window.location.origin}`;

            setTimeout(function() {
                if (snapToken) {
                    snap.pay(snapToken, {
                        onSuccess: function(result) {
                            loadingIndicator.classList.add('hidden');
                            window.location.href = baseUrl + '/payment/finish?order_id=' + result.order_id;
                        },
                        onPending: function(result) {
                            loadingIndicator.classList.add('hidden');
                            window.location.href = baseUrl + '/payment/unfinish?order_id=' + result.order_id;
                        },
                        onError: function(result) {
                            loadingIndicator.classList.add('hidden');
                            window.location.href = baseUrl + '/payment/error?order_id=' + result.order_id;
                        },
                        onClose: function() {
                            loadingIndicator.classList.add('hidden');
                            alert('Pembayaran dibatalkan. Silakan coba lagi untuk menyelesaikan pemesanan Anda.');
                        }
                    });
                } else {
                    loadingIndicator.classList.add('hidden');
                    alert('Token pembayaran tidak ditemukan. Silakan coba lagi.');
                    window.location.href = '/booking/form';
                }
            }, 1000);
        });
    </script>
</body>
</html>

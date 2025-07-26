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
                        green: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        }
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

<body class="bg-gradient-to-br from-emerald-50 via-green-100 to-teal-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <h1 class="text-3xl font-bold mb-6 text-emerald-600">Detail Pembayaran</h1>
            <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6 border border-emerald-100">
                <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white px-6 py-4">
                    <h2 class="text-xl font-semibold">Ringkasan Pemesanan</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between border-b border-emerald-200 pb-3">
                        <span class="font-medium text-slate-700">Nama Pemesan:</span>
                        <span class="text-slate-700">{{ $bookings->first()->customer_name }}</span>
                    </div>
                    <div class="flex justify-between border-b border-emerald-200 pb-3">
                        <span class="font-medium text-slate-700">Email:</span>
                        <span class="text-slate-700">{{ $bookings->first()->customer_email }}</span>
                    </div>
                    <div class="flex justify-between border-b border-emerald-200 pb-3">
                        <span class="font-medium text-slate-700">Nomor Handphone:</span>
                        <span class="text-slate-700">{{ $bookings->first()->customer_phone }}</span>
                    </div>
                    <div class="flex justify-between border-b border-emerald-200 pb-3">
                        <span class="font-medium text-slate-700">Waktu:</span>
                        <span class="text-slate-700">{{ \Carbon\Carbon::parse($bookings->first()->booking_date)->format('d M Y') }}</span>
                    </div>

                    <!-- Ringkasan multi-lapangan -->
                    <div class="border-t border-emerald-200 pt-4 mb-2">
                        <h3 class="font-semibold mb-2 text-emerald-700">Lapangan yang Dipesan:</h3>
                        <div class="space-y-1">
                            @foreach($bookings as $b)
                            <div>
                                <span class="font-bold text-emerald-700">{{ $b->field->name }}</span>:
                                {{ \Carbon\Carbon::parse($b->start_time)->format('H:i') }}-{{ \Carbon\Carbon::parse($b->end_time)->format('H:i') }}
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @php
                    $tax = $tax ?? 5000;
                    $subtotal = $totalPrice - $tax;
                    @endphp
                    <div class="flex justify-between text-base font-medium mt-4 pt-2 border-t border-emerald-200">
                        <span class="text-slate-700">Subtotal:</span>
                        <span class="text-slate-700">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-base font-medium">
                        <span class="text-slate-700">Biaya Admin:</span>
                        <span class="text-slate-700">Rp {{ number_format($tax, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold mt-2 pt-2 border-t border-emerald-200">
                        <span class="text-slate-700">Total Biaya:</span>
                        <span id="total-amount" class="text-emerald-700">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                    </div>

                    <div class="mt-4 p-3 bg-gradient-to-br from-emerald-50 via-emerald-100 to-green-100 border-l-4 border-emerald-600 text-slate-700 rounded">
                        <b>Informasi Pembayaran:</b><br>
                        Anda akan diarahkan ke halaman pembayaran Midtrans untuk menyelesaikan transaksi.<br>
                        Pastikan untuk tidak menutup halaman sebelum proses pembayaran selesai.<br>
                        <span class="text-sm text-slate-600">Setiap transaksi dikenakan biaya admin Rp5.000.</span>
                    </div>
                </div>
            </div>

            <div class="text-center mt-6">
                <a href="/" class="inline-flex items-center px-5 py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-medium rounded-lg shadow-lg transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke halaman utama
                </a>
            </div>

            <!-- Loading indicator -->
            <div id="loading-indicator" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-emerald-600 mx-auto mb-4"></div>
                    <p class="text-emerald-700 font-medium">Mempersiapkan pembayaran...</p>
                    <p class="text-slate-600 text-sm mt-2">Mohon tunggu sebentar</p>
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

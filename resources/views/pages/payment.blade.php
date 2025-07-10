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

<body class="bg-white">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <h1 class="text-3xl font-bold mb-6 text-green-600">Detail Pembayaran</h1>
            <div class="bg-gradient-to-br from-green-50 via-green-100/30 to-white rounded-lg shadow-lg overflow-hidden mb-6">
                <div class="bg-green-600 text-white px-6 py-4">
                    <h2 class="text-xl font-semibold">Ringkasan Pemesanan</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between border-b border-green-100 pb-3">
                        <span class="font-medium">Nama Pemesan:</span>
                        <span>{{ $bookings->first()->customer_name }}</span>
                    </div>
                    <div class="flex justify-between border-b border-green-100 pb-3">
                        <span class="font-medium">Email:</span>
                        <span>{{ $bookings->first()->customer_email }}</span>
                    </div>
                    <div class="flex justify-between border-b border-green-100 pb-3">
                        <span class="font-medium">Nomor Handphone:</span>
                        <span>{{ $bookings->first()->customer_phone }}</span>
                    </div>
                    <div class="flex justify-between border-b border-green-100 pb-3">
                        <span class="font-medium">Waktu:</span>
                        <span>{{ \Carbon\Carbon::parse($bookings->first()->booking_date)->format('d M Y') }}</span>
                    </div>
                    <!-- Ringkasan multi-lapangan tanpa bullet/titik -->
                    <div class="border-t border-green-100 pt-4 mb-2">
                        <h3 class="font-semibold mb-2 text-green-700">Lapangan yang Dipesan:</h3>
                        <div class="space-y-1">
                            @foreach($bookings as $b)
                            <div>
                                <span class="font-bold text-green-600">{{ $b->field->name }}</span>:
                                {{ \Carbon\Carbon::parse($b->start_time)->format('H:i') }}-{{ \Carbon\Carbon::parse($b->end_time)->format('H:i') }}
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="flex justify-between text-lg font-bold mt-4">
                        <span>Total Biaya:</span>
                        <span id="total-amount" class="text-green-600">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                    </div>

                    <div class="mt-4 p-3 bg-green-100 border-l-4 border-green-600 text-green-700 rounded">
                        <b>Informasi Pembayaran:</b><br>
                        Anda akan diarahkan ke halaman pembayaran Midtrans untuk menyelesaikan transaksi.<br>
                        Pastikan untuk tidak menutup halaman sebelum proses pembayaran selesai.
                    </div>
                </div>
            </div>
            <div class="text-center mt-6">
                <a href="/" class="inline-flex items-center px-5 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg shadow transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke halaman utama
                </a>
            </div>
            <!-- Loading indicator -->
            <div id="loading-indicator" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-green-600 mx-auto mb-4"></div>
                    <p class="text-green-700 font-medium">Mempersiapkan pembayaran...</p>
                    <p class="text-gray-600 text-sm mt-2">Mohon tunggu sebentar</p>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loadingIndicator = document.getElementById('loading-indicator');
            const snapToken = "{{ $snapToken }}";
            setTimeout(function() {
                if (snapToken) {
                    snap.pay(snapToken, {
                        onSuccess: function(result) {
                            loadingIndicator.classList.add('hidden');
                            window.location.href = '/payment/finish';
                        },
                        onPending: function(result) {
                            loadingIndicator.classList.add('hidden');
                            window.location.href = '/payment/unfinish';
                        },
                        onError: function(result) {
                            loadingIndicator.classList.add('hidden');
                            window.location.href = '/payment/error';
                        },
                        onClose: function() {
                            loadingIndicator.classList.add('hidden');
                            alert('Pembayaran dibatalkan. Silakan coba lagi untuk menyelesaikan pemesanan Anda.');
                            window.location.href = '/';
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

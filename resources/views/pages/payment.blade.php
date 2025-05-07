<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Midtrans Snap.js -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <h1 class="text-3xl font-bold mb-6">Payment Details</h1>

            <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
                <div class="bg-blue-600 text-white px-6 py-4">
                    <h2 class="text-xl font-semibold">Booking Summary</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between border-b pb-3">
                        <span class="font-medium">Customer Name:</span>
                        <span id="customer-name">{{ $booking->customer_name }}</span>
                    </div>
                    <div class="flex justify-between border-b pb-3">
                        <span class="font-medium">Email:</span>
                        <span id="customer-email">{{ $booking->customer_email }}</span>
                    </div>
                    <div class="flex justify-between border-b pb-3">
                        <span class="font-medium">Phone:</span>
                        <span id="customer-phone">{{ $booking->customer_phone }}</span>
                    </div>
                    <div class="flex justify-between border-b pb-3">
                        <span class="font-medium">Date:</span>
                        <span id="booking-date">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between border-b pb-3">
                        <span class="font-medium">Number of Bookings:</span>
                        <span id="total-bookings">{{ $totalBookings }}</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold">
                        <span>Total Amount:</span>
                        <span id="total-amount">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="text-center mt-6">
                <a href="/" class="text-blue-600 hover:text-blue-800">← Back to Home</a>
            </div>

            <!-- Loading indicator -->
            <div id="loading-indicator" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white p-5 rounded-lg shadow-lg text-center">
                    <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-green-500 mx-auto mb-3"></div>
                    <p>Initializing payment...</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loadingIndicator = document.getElementById('loading-indicator');
            const snapToken = "{{ $snapToken }}";

            // Automatically open Midtrans popup when page loads
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
                            alert('Payment cancelled. Please try again to complete your booking.');
                            window.location.href = '/';
                        }
                    });
                } else {
                    loadingIndicator.classList.add('hidden');
                    alert('Payment token not found. Please try again.');
                    window.location.href = '/booking/form';
                }
            }, 1000); // Delay 1 second to show loading indicator
        });
    </script>
</body>

</html>
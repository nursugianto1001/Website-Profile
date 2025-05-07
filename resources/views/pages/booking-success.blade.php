<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Successful</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-green-600 text-white px-6 py-4">
                <h1 class="text-2xl font-bold flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Booking Successful!
                </h1>
            </div>

            @if (isset($warning) && $warning)
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4">
                <p class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ $warning }}</span>
                </p>
            </div>
            @endif

            <div class="p-6">
                <div class="bg-gray-50 rounded-lg p-4 mb-6 border border-gray-200">
                    <h2 class="font-semibold text-lg mb-2">Booking Reference</h2>
                    <p class="text-xl font-mono">{{ $booking->booking_code }}</p>
                    <p class="text-sm text-gray-500 mt-1">Please save this reference number for your records</p>
                </div>

                <div class="space-y-4">
                    @if (isset($totalBookings) && $totalBookings > 1)
                    <div class="flex justify-between pb-2 border-b">
                        <span class="font-medium">Total Bookings:</span>
                        <span>{{ $totalBookings }}</span>
                    </div>
                    @endif

                    <div class="flex justify-between pb-2 border-b">
                        <span class="font-medium">Customer Name:</span>
                        <span>{{ $booking->customer_name }}</span>
                    </div>

                    <div class="flex justify-between pb-2 border-b">
                        <span class="font-medium">Email:</span>
                        <span>{{ $booking->customer_email }}</span>
                    </div>

                    <div class="flex justify-between pb-2 border-b">
                        <span class="font-medium">Phone:</span>
                        <span>{{ $booking->customer_phone }}</span>
                    </div>

                    <div class="flex justify-between pb-2 border-b">
                        <span class="font-medium">Booking Date:</span>
                        <span>{{ isset($booking->booking_date) ? \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') : 'N/A' }}</span>
                    </div>

                    <div class="flex justify-between pb-2 border-b">
                        <span class="font-medium">Field:</span>
                        <span>{{ $booking->field->name ?? $booking->field_name ?? 'N/A' }}</span>
                    </div>

                    <div class="flex justify-between pb-2 border-b">
                        <span class="font-medium">Time:</span>
                        <span>{{ isset($booking->start_time) ? \Carbon\Carbon::parse($booking->start_time)->format('H:i') : '' }}
                            {{ (isset($booking->start_time) && isset($booking->end_time)) ? '-' : '' }}
                            {{ isset($booking->end_time) ? \Carbon\Carbon::parse($booking->end_time)->format('H:i') : '' }}
                        </span>
                    </div>

                    <div class="flex justify-between pb-2 border-b">
                        <span class="font-medium">Duration:</span>
                        <span>{{ $booking->duration_hours }} hour(s)</span>
                    </div>

                    <div class="flex justify-between pb-2 border-b">
                        <span class="font-medium">Payment Method:</span>
                        <span>{{ $booking->payment_method }}</span>
                    </div>

                    <div class="flex justify-between pb-2 border-b">
                        <span class="font-medium">Payment Status:</span>
                        @if ($booking->payment_status == 'settlement' || $booking->payment_status == 'paid')
                        <span class="py-1 px-2 rounded-full text-sm bg-green-100 text-green-800">
                            {{ ucfirst($booking->payment_status) }}
                        </span>
                        @elseif ($booking->payment_status == 'pending')
                        <span class="py-1 px-2 rounded-full text-sm bg-yellow-100 text-yellow-800">
                            {{ ucfirst($booking->payment_status) }}
                        </span>
                        @else
                        <span class="py-1 px-2 rounded-full text-sm bg-red-100 text-red-800">
                            {{ ucfirst($booking->payment_status) }}
                        </span>
                        @endif
                    </div>

                    <div class="flex justify-between text-lg font-bold">
                        <span>Total Amount:</span>
                        <span>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="mt-8 space-y-4">

                    @if ($booking->payment_status == 'pending')
                    <div class="bg-blue-50 p-4 border border-blue-100 rounded-lg">
                        <p class="text-blue-700 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            Please complete your payment to confirm this booking.
                        </p>
                        <div class="mt-2 text-center">
                            <a href="/booking/{{ $booking->id }}/payment" class="inline-block px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md shadow-sm transition-colors">
                                Complete Payment
                            </a>
                        </div>
                    </div>
                    @else
                    <div class="bg-green-50 p-4 border border-green-100 rounded-lg">
                        <p class="text-green-700 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            Your booking has been confirmed. Thank you for your payment!
                        </p>
                    </div>
                    @endif

                    <div class="flex justify-center space-x-4 mt-6">
                        <a href="/" class="px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-md shadow-sm transition-colors">
                            Return to Home
                        </a>

                        <a href="/fields/book" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md shadow-sm transition-colors">
                            Make Another Booking
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
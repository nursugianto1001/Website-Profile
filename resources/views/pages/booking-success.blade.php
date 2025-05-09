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
            <div class="p-6">
                <div class="bg-gray-50 rounded-lg p-4 mb-6 border border-gray-200">
                    <h2 class="font-semibold text-lg mb-2">Booking Reference</h2>
                    <p class="text-xl font-mono">{{ $bookings->first()->booking_code }}</p>
                    <p class="text-sm text-gray-500 mt-1">Please save this reference number for your records</p>
                </div>
                <div class="space-y-4">
                    @if ($bookings->count() > 1)
                    <div class="flex justify-between pb-2 border-b">
                        <span class="font-medium">Total Bookings:</span>
                        <span>{{ $bookings->count() }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between pb-2 border-b">
                        <span class="font-medium">Customer Name:</span>
                        <span>{{ $bookings->first()->customer_name }}</span>
                    </div>
                    <div class="flex justify-between pb-2 border-b">
                        <span class="font-medium">Email:</span>
                        <span>{{ $bookings->first()->customer_email }}</span>
                    </div>
                    <div class="flex justify-between pb-2 border-b">
                        <span class="font-medium">Phone:</span>
                        <span>{{ $bookings->first()->customer_phone }}</span>
                    </div>
                    <div class="flex justify-between pb-2 border-b">
                        <span class="font-medium">Booking Date:</span>
                        <span>{{ \Carbon\Carbon::parse($bookings->first()->booking_date)->format('d M Y') }}</span>
                    </div>
                    <!-- Ringkasan multi-lapangan tanpa bullet/titik -->
                    <div class="border-t pt-4 mb-2">
                        <h3 class="font-semibold mb-2">Lapangan yang Dipesan:</h3>
                        <div class="space-y-1">
                            @foreach($bookings as $b)
                            <div>
                                <span class="font-bold">{{ $b->field->name }}</span>:
                                {{ \Carbon\Carbon::parse($b->start_time)->format('H:i') }}-{{ \Carbon\Carbon::parse($b->end_time)->format('H:i') }}
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="flex justify-between text-lg font-bold mt-4">
                        <span>Total Amount:</span>
                        <span id="total-amount">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="mt-8 space-y-4">
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
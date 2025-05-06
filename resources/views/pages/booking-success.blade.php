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

            <div id="warning-message" class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4 hidden">
                <p class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <span id="warning-text"></span>
                </p>
            </div>

            <div class="p-6">
                <div class="bg-gray-50 rounded-lg p-4 mb-6 border border-gray-200">
                    <h2 class="font-semibold text-lg mb-2">Booking Reference</h2>
                    <p id="booking-code" class="text-xl font-mono">ORD-123-1746543824</p>
                    <p class="text-sm text-gray-500 mt-1">Please save this reference number for your records</p>
                </div>

                <div class="space-y-4">
                    <div id="multiple-bookings-container" class="flex justify-between pb-2 border-b hidden">
                        <span class="font-medium">Total Bookings:</span>
                        <span id="total-bookings">3</span>
                    </div>

                    <div class="flex justify-between pb-2 border-b">
                        <span class="font-medium">Customer Name:</span>
                        <span id="customer-name">John Doe</span>
                    </div>

                    <div class="flex justify-between pb-2 border-b">
                        <span class="font-medium">Email:</span>
                        <span id="customer-email">john.doe@example.com</span>
                    </div>

                    <div class="flex justify-between pb-2 border-b">
                        <span class="font-medium">Phone:</span>
                        <span id="customer-phone">+62 812-3456-7890</span>
                    </div>

                    <div class="flex justify-between pb-2 border-b">
                        <span class="font-medium">Booking Date:</span>
                        <span id="booking-date">10 May 2025</span>
                    </div>

                    <div class="flex justify-between pb-2 border-b">
                        <span class="font-medium">Field:</span>
                        <span id="field-name">Field A</span>
                    </div>

                    <div class="flex justify-between pb-2 border-b">
                        <span class="font-medium">Time:</span>
                        <span id="booking-time">08:00 AM - 10:00 AM</span>
                    </div>

                    <div class="flex justify-between pb-2 border-b">
                        <span class="font-medium">Duration:</span>
                        <span id="booking-duration">2 hour(s)</span>
                    </div>

                    <div class="flex justify-between pb-2 border-b">
                        <span class="font-medium">Payment Method:</span>
                        <span id="payment-method">Online</span>
                    </div>

                    <div class="flex justify-between pb-2 border-b">
                        <span class="font-medium">Payment Status:</span>
                        <span id="payment-status" class="py-1 px-2 rounded-full text-sm bg-yellow-100 text-yellow-800">Pending</span>
                    </div>

                    <div class="flex justify-between text-lg font-bold">
                        <span>Total Amount:</span>
                        <span id="total-amount">Rp 200.000</span>
                    </div>
                </div>

                <div class="mt-8 space-y-4">
                    <p class="text-gray-700">A confirmation email has been sent to your email address.</p>

                    <div id="pending-payment-notice" class="bg-blue-50 p-4 border border-blue-100 rounded-lg">
                        <p class="text-blue-700 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            Please complete your payment to confirm this booking.
                        </p>
                        <div class="mt-2 text-center">
                            <a href="#" id="complete-payment-link" class="inline-block px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md shadow-sm transition-colors">
                                Complete Payment
                            </a>
                        </div>
                    </div>

                    <div id="paid-payment-notice" class="bg-green-50 p-4 border border-green-100 rounded-lg hidden">
                        <p class="text-green-700 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            Your booking has been confirmed. Thank you for your payment!
                        </p>
                    </div>

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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get URL parameters
            const urlParams = new URLSearchParams(window.location.search);

            // Set booking details from URL parameters or use defaults
            const bookingCode = urlParams.get('booking_code') || 'ORD-123-1746543824';
            const customerName = urlParams.get('customer_name') || 'John Doe';
            const customerEmail = urlParams.get('customer_email') || 'john.doe@example.com';
            const customerPhone = urlParams.get('customer_phone') || '+62 812-3456-7890';
            const bookingDate = urlParams.get('booking_date') || '10 May 2025';
            const fieldName = urlParams.get('field_name') || 'Field A';
            const bookingTime = urlParams.get('booking_time') || '08:00 AM - 10:00 AM';
            const bookingDuration = urlParams.get('booking_duration') || '2';
            const paymentMethod = urlParams.get('payment_method') || 'Online';
            const paymentStatus = urlParams.get('payment_status') || 'pending';
            const totalAmount = urlParams.get('total_amount') || '200000';
            const multipleBookings = urlParams.get('multiple_bookings') === 'true';
            const totalBookings = urlParams.get('total_bookings') || '3';
            const warningMessage = urlParams.get('warning');

            // Format amount with thousand separators
            const formattedAmount = new Intl.NumberFormat('id-ID').format(totalAmount);

            // Update the DOM with booking information
            document.getElementById('booking-code').textContent = bookingCode;
            document.getElementById('customer-name').textContent = customerName;
            document.getElementById('customer-email').textContent = customerEmail;
            document.getElementById('customer-phone').textContent = customerPhone;
            document.getElementById('booking-date').textContent = bookingDate;
            document.getElementById('field-name').textContent = fieldName;
            document.getElementById('booking-time').textContent = bookingTime;
            document.getElementById('booking-duration').textContent = `${bookingDuration} hour(s)`;
            document.getElementById('payment-method').textContent = paymentMethod;
            document.getElementById('total-amount').textContent = `Rp ${formattedAmount}`;

            // Show multiple bookings if applicable
            if (multipleBookings) {
                document.getElementById('multiple-bookings-container').classList.remove('hidden');
                document.getElementById('total-bookings').textContent = totalBookings;
            }

            // Set payment status with appropriate styling
            const paymentStatusElement = document.getElementById('payment-status');
            paymentStatusElement.textContent = paymentStatus.charAt(0).toUpperCase() + paymentStatus.slice(1);

            if (paymentStatus === 'paid') {
                paymentStatusElement.className = 'py-1 px-2 rounded-full text-sm bg-green-100 text-green-800';
                document.getElementById('pending-payment-notice').classList.add('hidden');
                document.getElementById('paid-payment-notice').classList.remove('hidden');
            } else if (paymentStatus === 'pending') {
                paymentStatusElement.className = 'py-1 px-2 rounded-full text-sm bg-yellow-100 text-yellow-800';
                document.getElementById('complete-payment-link').href = `/booking/${bookingCode}/payment`;
            } else {
                paymentStatusElement.className = 'py-1 px-2 rounded-full text-sm bg-red-100 text-red-800';
            }

            // Show warning message if present
            if (warningMessage) {
                document.getElementById('warning-text').textContent = warningMessage;
                document.getElementById('warning-message').classList.remove('hidden');
            }
        });
    </script>
</body>

</html>
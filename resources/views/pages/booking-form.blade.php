<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Your Field</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Book Your Field</h1>

        <div id="error-container" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 hidden">
            <span id="error-message"></span>
        </div>

        <form id="bookingForm" action="/booking/process" method="POST" class="space-y-6">
            <!-- CSRF Token -->
            @csrf

            <!-- Customer Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Customer Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" name="customer_name" id="customer_name"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                        <span class="text-red-500 text-sm customer_name-error"></span>
                    </div>

                    <div>
                        <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="customer_email" id="customer_email"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                        <span class="text-red-500 text-sm customer_email-error"></span>
                    </div>

                    <div>
                        <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                        <input type="text" name="customer_phone" id="customer_phone"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                        <span class="text-red-500 text-sm customer_phone-error"></span>
                    </div>
                </div>
            </div>

            <!-- Date Selection -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Select Date</h2>
                <div class="flex flex-wrap gap-2" id="date-selector-container">
                    <!-- Date buttons will be populated by JavaScript -->
                </div>
                <input type="hidden" name="booking_date" id="booking_date" required>
            </div>

            <!-- Field and Time Selection -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Select Fields & Time Slots</h2>
                <p class="mb-4 text-sm text-gray-600">Click on the available time slots to book. Green slots are available.</p>

                <div class="overflow-x-auto">
                    <table class="w-full border-collapse booking-table">
                        <thead>
                            <tr>
                                <th class="border px-4 py-2 bg-gray-100">Time / Field</th>
                                <!-- Field headers will be populated by JavaScript -->
                            </tr>
                        </thead>
                        <tbody id="booking-table-body">
                            <!-- Time slots will be populated by JavaScript -->
                        </tbody>
                    </table>
                </div>

                <div id="selected-slots-container" class="mt-4 hidden">
                    <h3 class="font-medium mb-2">Selected Slots:</h3>
                    <div id="selected-slots-summary" class="p-3 bg-gray-50 rounded text-sm"></div>
                </div>
            </div>

            <!-- Payment Method -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Payment Method</h2>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="radio" name="payment_method" value="online" checked class="mr-2">
                        <span>Online Payment (Midtrans)</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="payment_method" value="cash" class="mr-2">
                        <span>Cash Payment</span>
                    </label>
                </div>
            </div>

            <!-- Booking Summary and Total -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Booking Summary</h2>

                <div id="booking-summary" class="mb-4">
                    <p class="text-gray-500 italic">Please select field(s) and time slot(s) to see the summary</p>
                </div>

                <div class="flex justify-between items-center border-t pt-4 mt-4">
                    <span class="text-xl font-bold">Total Amount:</span>
                    <span id="total-price" class="text-xl font-bold text-blue-600">Rp 0</span>
                    <input type="hidden" name="total_amount" id="total_amount" value="0">
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-6">
                <button type="submit" class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-md shadow-md transition-colors">
                    Proceed to Payment
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sample data (in a real application, this would come from your backend)
            const fields = [{
                    id: 1,
                    name: "Field A",
                    price_per_hour: 100000
                },
                {
                    id: 2,
                    name: "Field B",
                    price_per_hour: 150000
                },
                {
                    id: 3,
                    name: "Field C",
                    price_per_hour: 120000
                }
            ];

            const slots = [{
                    time: "08:00:00",
                    formatted_time: "08:00 AM"
                },
                {
                    time: "09:00:00",
                    formatted_time: "09:00 AM"
                },
                {
                    time: "10:00:00",
                    formatted_time: "10:00 AM"
                },
                {
                    time: "11:00:00",
                    formatted_time: "11:00 AM"
                },
                {
                    time: "12:00:00",
                    formatted_time: "12:00 PM"
                },
                {
                    time: "13:00:00",
                    formatted_time: "01:00 PM"
                },
                {
                    time: "14:00:00",
                    formatted_time: "02:00 PM"
                },
                {
                    time: "15:00:00",
                    formatted_time: "03:00 PM"
                },
                {
                    time: "16:00:00",
                    formatted_time: "04:00 PM"
                },
                {
                    time: "17:00:00",
                    formatted_time: "05:00 PM"
                },
                {
                    time: "18:00:00",
                    formatted_time: "06:00 PM"
                },
                {
                    time: "19:00:00",
                    formatted_time: "07:00 PM"
                }
            ];

            // Sample field availability (would come from backend)
            let fieldAvailability = {};
            fields.forEach(field => {
                fieldAvailability[field.id] = {};
                slots.forEach(slot => {
                    // Randomly set some slots as available (for demo purposes)
                    fieldAvailability[field.id][slot.time] = Math.random() > 0.3;
                });
            });

            // Variables to track selected slots and pricing
            const selectedSlots = {};
            const fieldPrices = {}; // Will store the price per hour for each field
            let currentDate = '';
            let totalAmount = 0;

            // Initialize field prices
            fields.forEach(field => {
                fieldPrices[field.id] = field.price_per_hour;
            });

            // Generate dates for a week starting from today
            function generateWeeklyDates() {
                const weeklyDates = [];
                const today = new Date();

                for (let i = 0; i < 7; i++) {
                    const date = new Date(today);
                    date.setDate(today.getDate() + i);

                    const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                    const day = dayNames[date.getDay()];

                    const dateString = date.toISOString().split('T')[0];
                    const formattedDate = date.toLocaleDateString('en-US', {
                        month: 'short',
                        day: 'numeric'
                    });

                    weeklyDates.push({
                        date: dateString,
                        day: day,
                        formatted_date: formattedDate
                    });
                }

                return weeklyDates;
            }

            // Generate weekly dates and populate date selector
            const weeklyDates = generateWeeklyDates();
            const dateContainer = document.getElementById('date-selector-container');

            weeklyDates.forEach((dateObj, index) => {
                const dateButton = document.createElement('button');
                dateButton.type = 'button';
                dateButton.className = `date-selector px-4 py-2 border rounded-md transition-colors ${index === 0 ? 'bg-blue-500 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'}`;
                dateButton.setAttribute('data-date', dateObj.date);

                dateButton.innerHTML = `
                <span class="block font-medium">${dateObj.day}</span>
                <span class="block text-sm">${dateObj.formatted_date}</span>
            `;

                dateContainer.appendChild(dateButton);
            });

            // Set initial date
            currentDate = weeklyDates[0].date;
            document.getElementById('booking_date').value = currentDate;

            // Populate field headers
            const tableHeader = document.querySelector('.booking-table thead tr');
            fields.forEach(field => {
                const th = document.createElement('th');
                th.className = 'border px-4 py-2 bg-gray-100';
                th.innerHTML = `
                ${field.name}
                <div class="text-sm text-gray-600">Rp ${field.price_per_hour.toLocaleString('id-ID')}/hour</div>
                <input type="checkbox" class="ml-2 field-checkbox"
                    id="field_${field.id}"
                    data-field-id="${field.id}"
                    data-field-name="${field.name}"
                    data-field-price="${field.price_per_hour}"
                    name="selected_fields[]"
                    value="${field.id}">
            `;
                tableHeader.appendChild(th);
            });

            // Populate time slots
            const tableBody = document.getElementById('booking-table-body');
            slots.forEach(slot => {
                const tr = document.createElement('tr');

                // Time column
                const tdTime = document.createElement('td');
                tdTime.className = 'border px-4 py-2 font-medium';
                tdTime.textContent = slot.formatted_time;
                tr.appendChild(tdTime);

                // Field columns
                fields.forEach(field => {
                    const isAvailable = fieldAvailability[field.id][slot.time];
                    const tdSlot = document.createElement('td');
                    tdSlot.className = `border px-1 py-1 text-center time-slot
                                    ${isAvailable ? 'bg-green-100 hover:bg-green-200 cursor-pointer' : 'bg-red-100'}`;
                    tdSlot.setAttribute('data-field-id', field.id);
                    tdSlot.setAttribute('data-time-slot', slot.time);
                    tdSlot.setAttribute('data-available', isAvailable ? 'true' : 'false');

                    tdSlot.innerHTML = `
                    <div class="h-8 w-full flex items-center justify-center">
                        <span class="slot-status">${isAvailable ? 'Available' : 'Booked'}</span>
                    </div>
                `;

                    tr.appendChild(tdSlot);
                });

                tableBody.appendChild(tr);
            });

            // Date selector functionality
            document.querySelectorAll('.date-selector').forEach(button => {
                button.addEventListener('click', function() {
                    // Update visual selection
                    document.querySelectorAll('.date-selector').forEach(btn => {
                        btn.classList.remove('bg-blue-500', 'text-white', 'border-blue-600');
                        btn.classList.add('bg-white', 'text-gray-700', 'border-gray-300', 'hover:bg-gray-50');
                    });

                    this.classList.remove('bg-white', 'text-gray-700', 'border-gray-300', 'hover:bg-gray-50');
                    this.classList.add('bg-blue-500', 'text-white', 'border-blue-600');

                    // Set selected date and fetch availability
                    const selectedDate = this.getAttribute('data-date');
                    document.getElementById('booking_date').value = selectedDate;
                    currentDate = selectedDate;

                    // Clear all selections on date change
                    clearAllSelections();

                    // In a real application, you would fetch availability for the selected date
                    // For demo purposes, we'll randomize the availability
                    randomizeAvailability();
                });
            });

            // Field checkbox functionality
            document.querySelectorAll('.field-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const fieldId = this.getAttribute('data-field-id');

                    if (!this.checked) {
                        // Deselect all slots for this field
                        if (selectedSlots[fieldId]) {
                            // Subtract from total amount
                            totalAmount -= selectedSlots[fieldId].length * fieldPrices[fieldId];

                            delete selectedSlots[fieldId];

                            // Deselect visually
                            document.querySelectorAll(`.time-slot[data-field-id="${fieldId}"].selected`).forEach(slot => {
                                slot.classList.remove('selected', 'bg-blue-500', 'text-white');
                                if (slot.getAttribute('data-available') === 'true') {
                                    slot.classList.add('bg-green-100', 'hover:bg-green-200');
                                } else {
                                    slot.classList.add('bg-red-100');
                                }
                                slot.querySelector('.slot-status').textContent = 'Available';
                            });
                        }
                    }

                    updateSelectedSlotsDisplay();
                    updateBookingSummary();
                    updateTotalPrice();
                });
            });

            // Time slot selection functionality
            document.querySelectorAll('.time-slot').forEach(slot => {
                slot.addEventListener('click', function() {
                    if (this.getAttribute('data-available') !== 'true') {
                        return; // Skip if not available
                    }

                    const fieldId = this.getAttribute('data-field-id');
                    const timeSlot = this.getAttribute('data-time-slot');
                    const fieldPrice = fieldPrices[fieldId];

                    // Ensure field checkbox is checked
                    const fieldCheckbox = document.getElementById(`field_${fieldId}`);
                    fieldCheckbox.checked = true;

                    // Toggle selection
                    if (!selectedSlots[fieldId]) {
                        selectedSlots[fieldId] = [];
                    }

                    const slotIndex = selectedSlots[fieldId].indexOf(timeSlot);
                    if (slotIndex === -1) {
                        // Select this slot
                        selectedSlots[fieldId].push(timeSlot);
                        this.classList.remove('bg-green-100', 'hover:bg-green-200');
                        this.classList.add('selected', 'bg-blue-500', 'text-white');
                        this.querySelector('.slot-status').textContent = 'Selected';

                        // Add to total amount
                        totalAmount += fieldPrice;
                    } else {
                        // Deselect this slot
                        selectedSlots[fieldId].splice(slotIndex, 1);
                        this.classList.remove('selected', 'bg-blue-500', 'text-white');
                        this.classList.add('bg-green-100', 'hover:bg-green-200');
                        this.querySelector('.slot-status').textContent = 'Available';

                        // Subtract from total amount
                        totalAmount -= fieldPrice;

                        // If no slots selected for this field, uncheck the field
                        if (selectedSlots[fieldId].length === 0) {
                            delete selectedSlots[fieldId];
                            fieldCheckbox.checked = false;
                        }
                    }

                    updateSelectedSlotsDisplay();
                    updateBookingSummary();
                    updateTotalPrice();
                    updateFormInputs();
                });
            });

            // Function to update slot availability display
            function updateAvailabilityDisplay() {
                document.querySelectorAll('.time-slot').forEach(slot => {
                    const fieldId = slot.getAttribute('data-field-id');
                    const timeSlot = slot.getAttribute('data-time-slot');

                    if (fieldAvailability[fieldId] && fieldAvailability[fieldId][timeSlot]) {
                        // Slot is available
                        slot.setAttribute('data-available', 'true');
                        slot.classList.remove('bg-red-100', 'selected', 'bg-blue-500', 'text-white');
                        slot.classList.add('bg-green-100', 'hover:bg-green-200', 'cursor-pointer');
                        slot.querySelector('.slot-status').textContent = 'Available';
                    } else {
                        // Slot is not available
                        slot.setAttribute('data-available', 'false');
                        slot.classList.remove('bg-green-100', 'hover:bg-green-200', 'cursor-pointer', 'selected', 'bg-blue-500', 'text-white');
                        slot.classList.add('bg-red-100');
                        slot.querySelector('.slot-status').textContent = 'Booked';
                    }
                });
            }

            // Function to randomize availability (for demo only)
            function randomizeAvailability() {
                fields.forEach(field => {
                    fieldAvailability[field.id] = {};
                    slots.forEach(slot => {
                        fieldAvailability[field.id][slot.time] = Math.random() > 0.3;
                    });
                });
                updateAvailabilityDisplay();
            }

            // Function to update the display of selected slots
            function updateSelectedSlotsDisplay() {
                const container = document.getElementById('selected-slots-container');
                const summary = document.getElementById('selected-slots-summary');

                // Check if any slots are selected
                const hasSelections = Object.keys(selectedSlots).length > 0;
                if (hasSelections) {
                    let summaryHTML = '';

                    for (const fieldId in selectedSlots) {
                        const fieldName = document.querySelector(`th input[data-field-id="${fieldId}"]`).getAttribute('data-field-name');
                        const slots = selectedSlots[fieldId].sort();

                        // Format times nicely
                        const formattedTimes = slots.map(slot => {
                            const time = new Date(`2000-01-01T${slot}`);
                            return time.toLocaleTimeString([], {
                                hour: '2-digit',
                                minute: '2-digit'
                            });
                        }).join(', ');

                        summaryHTML += `<div class="mb-2"><strong>${fieldName}:</strong> ${formattedTimes}</div>`;
                    }

                    summary.innerHTML = summaryHTML;
                    container.classList.remove('hidden');
                } else {
                    container.classList.add('hidden');
                }
            }

            // Function to update form inputs for submission
            function updateFormInputs() {
                // Remove any existing dynamic inputs
                document.querySelectorAll('input[name^="selected_slots["]').forEach(input => input.remove());

                // Create inputs for selected slots
                for (const fieldId in selectedSlots) {
                    selectedSlots[fieldId].forEach(slot => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = `selected_slots[${fieldId}][]`;
                        input.value = slot;
                        document.getElementById('bookingForm').appendChild(input);
                    });
                }
            }

            // Function to update booking summary with detailed breakdown
            function updateBookingSummary() {
                const summaryContainer = document.getElementById('booking-summary');

                if (Object.keys(selectedSlots).length === 0) {
                    summaryContainer.innerHTML = '<p class="text-gray-500 italic">Please select field(s) and time slot(s) to see the summary</p>';
                    return;
                }

                let summaryHTML = '<div class="space-y-3">';

                for (const fieldId in selectedSlots) {
                    const fieldCheckbox = document.getElementById(`field_${fieldId}`);
                    const fieldName = fieldCheckbox.getAttribute('data-field-name');
                    const fieldPrice = parseFloat(fieldCheckbox.getAttribute('data-field-price'));
                    const slots = selectedSlots[fieldId].sort();
                    const subtotal = slots.length * fieldPrice;

                    // Format times
                    const formattedTimes = slots.map(slot => {
                        const time = new Date(`2000-01-01T${slot}`);
                        return time.toLocaleTimeString([], {
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                    }).join(', ');

                    summaryHTML += `
                <div class="p-3 bg-gray-50 rounded border">
                    <div class="font-medium">${fieldName}</div>
                    <div class="text-sm text-gray-600">Time: ${formattedTimes}</div>
                    <div class="text-sm text-gray-600">Hours: ${slots.length}</div>
                    <div class="flex justify-between mt-1">
                        <span>Price per hour:</span>
                        <span>Rp ${fieldPrice.toLocaleString('id-ID')}</span>
                    </div>
                    <div class="flex justify-between font-medium">
                        <span>Subtotal:</span>
                        <span>Rp ${subtotal.toLocaleString('id-ID')}</span>
                    </div>
                </div>
            `;
                }

                summaryHTML += '</div>';
                summaryContainer.innerHTML = summaryHTML;
            }

            // Function to update total price display
            function updateTotalPrice() {
                document.getElementById('total-price').textContent = `Rp ${totalAmount.toLocaleString('id-ID')}`;
                document.getElementById('total_amount').value = totalAmount;
            }

            // Function to clear all selections
            function clearAllSelections() {
                for (const fieldId in selectedSlots) {
                    delete selectedSlots[fieldId];
                }

                // Reset total amount
                totalAmount = 0;

                // Uncheck all field checkboxes
                document.querySelectorAll('.field-checkbox').forEach(checkbox => {
                    checkbox.checked = false;
                });

                // Reset slot display
                document.querySelectorAll('.time-slot.selected').forEach(slot => {
                    slot.classList.remove('selected', 'bg-blue-500', 'text-white');
                    if (slot.getAttribute('data-available') === 'true') {
                        slot.classList.add('bg-green-100', 'hover:bg-green-200');
                        slot.querySelector('.slot-status').textContent = 'Available';
                    }
                });

                // Update displays
                updateSelectedSlotsDisplay();
                updateBookingSummary();
                updateTotalPrice();
                updateFormInputs();
            }

            // Form submission validation
            document.getElementById('bookingForm').addEventListener('submit', function(e) {
                const hasSelections = Object.keys(selectedSlots).length > 0;

                if (!hasSelections) {
                    e.preventDefault(); // Prevent form submission only if validation fails
                    alert('Please select at least one time slot for a field.');
                    return false;
                }

                if (totalAmount <= 0) {
                    e.preventDefault(); // Prevent form submission only if validation fails
                    alert('Total amount cannot be zero. Please select valid time slots.');
                    return false;
                }

                // If validation passes, form will be submitted normally
                // No need to call preventDefault() or return false
            });

            // Initialize form inputs and displays
            updateFormInputs();
            updateBookingSummary();
            updateTotalPrice();
        });
    </script>
</body>

</html>
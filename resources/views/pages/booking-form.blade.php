@extends('layouts.booking-app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-center">Book Your Fields</h1>

    <form id="bookingForm" action="{{ route('booking.process') }}" method="POST">
        @csrf
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <!-- Date Selection -->
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Select Date</label>

                <div class="flex space-x-2 overflow-x-auto pb-2">
                    @foreach($weeklyDates as $index => $dateInfo)
                    <button type="button"
                        class="date-selector p-2 border rounded-md min-w-[100px] text-center {{ $index === 0 ? 'bg-blue-500 text-white' : 'bg-gray-100' }}"
                        data-date="{{ $dateInfo['date'] }}">
                        <div class="font-medium">{{ $dateInfo['day'] }}</div>
                        <div class="text-sm">{{ $dateInfo['formatted_date'] }}</div>
                    </button>
                    @endforeach
                </div>
                <input type="hidden" name="booking_date" id="booking_date" value="{{ $weeklyDates[0]['date'] }}">
            </div>

            <!-- Field Selection Table -->
            <div class="mb-6 overflow-x-auto">
                <label class="block text-gray-700 font-semibold mb-2">Select Field(s) and Time Slot(s)</label>
                <p class="text-sm text-gray-600 mb-4">You can select multiple fields for the same time slot(s).</p>

                <table class="w-full border-collapse">
                    <thead>
                        <tr>
                            <th class="border p-2 bg-gray-100">Time</th>
                            @for($i = 1; $i <= 6; $i++)
                                <th class="border p-2 bg-gray-100">Field {{ $i }}</th>
                                @endfor
                        </tr>
                    </thead>
                    <tbody id="slot-matrix">
                        @foreach($slots as $slot)
                        <tr class="slot-row">
                            <td class="border p-2 font-medium">{{ $slot['formatted_time'] }}</td>
                            @for($i = 1; $i <= 6; $i++)
                                <td class="border p-2 text-center">
                                <button type="button" class="field-selector w-full cursor-pointer p-2 rounded-md 
                                        {{ isset($fieldAvailability[$i][$slot['time']]) && !$fieldAvailability[$i][$slot['time']] ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800 hover:bg-green-200' }}"
                                    data-field-id="{{ $i }}"
                                    data-time="{{ $slot['time'] }}"
                                    data-formatted-time="{{ $slot['formatted_time'] }}"
                                    data-available="{{ isset($fieldAvailability[$i][$slot['time']]) && !$fieldAvailability[$i][$slot['time']] ? 'false' : 'true' }}">
                                    @if(isset($fieldAvailability[$i][$slot['time']]) && !$fieldAvailability[$i][$slot['time']])
                                    <span>Booked</span>
                                    @else
                                    <span>Available</span>
                                    @endif
                                </button>
                                </td>
                                @endfor
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div id="selected-slots-container" class="mt-4 hidden">
                    <div class="font-semibold mb-2">Selected Fields and Time Slots:</div>
                    <ul id="selected-slots-list" class="list-disc ml-5 text-sm"></ul>
                </div>
                <div id="no-selection" class="mt-4 text-red-600">Please select at least one field and time slot.</div>

                <!-- Hidden inputs to store selected fields and slots -->
                <div id="selected-fields-container"></div>
            </div>

            <!-- Customer Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="customer_name" class="block text-gray-700 font-semibold mb-2">Name</label>
                    <input type="text" name="customer_name" id="customer_name" class="w-full px-4 py-2 border rounded-md" required value="{{ old('customer_name') }}">
                    @error('customer_name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="customer_email" class="block text-gray-700 font-semibold mb-2">Email</label>
                    <input type="email" name="customer_email" id="customer_email" class="w-full px-4 py-2 border rounded-md" required value="{{ old('customer_email') }}">
                    @error('customer_email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="customer_phone" class="block text-gray-700 font-semibold mb-2">Phone</label>
                    <input type="tel" name="customer_phone" id="customer_phone" class="w-full px-4 py-2 border rounded-md" required value="{{ old('customer_phone') }}">
                    @error('customer_phone')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Payment Method</label>
                    <div class="flex space-x-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="payment_method" value="online" class="form-radio" checked>
                            <span class="ml-2">Online Payment</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="payment_method" value="cash" class="form-radio">
                            <span class="ml-2">Cash</span>
                        </label>
                    </div>
                    @error('payment_method')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" id="submitBtn" class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600 transition disabled:opacity-50 disabled:cursor-not-allowed">Book Now</button>
            </div>

            <!-- Debug section (can be removed in production) -->
            <div id="debug-info" class="mt-4 p-4 bg-gray-100 rounded-md text-xs text-gray-700 hidden">
                <div class="font-semibold mb-2">Debug Information:</div>
                <div id="debug-selections"></div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dateSelectors = document.querySelectorAll('.date-selector');
        const bookingDateInput = document.getElementById('booking_date');
        const selectedSlotsContainer = document.getElementById('selected-slots-container');
        const selectedSlotsList = document.getElementById('selected-slots-list');
        const selectedFieldsContainer = document.getElementById('selected-fields-container');
        const noSelectionWarning = document.getElementById('no-selection');
        const submitBtn = document.getElementById('submitBtn');
        const slotMatrix = document.getElementById('slot-matrix');

        let selectedBookings = {};

        // Set initial booking date
        bookingDateInput.value = dateSelectors[0].dataset.date;

        // Initialize form validation
        submitBtn.disabled = true;

        // Debug log to verify script loading
        console.log("Booking form script loaded");

        // Date Selection
        dateSelectors.forEach(btn => {
            btn.addEventListener('click', function() {
                // Update visual selection
                dateSelectors.forEach(b => {
                    b.classList.remove('bg-blue-500', 'text-white');
                    b.classList.add('bg-gray-100');
                });
                btn.classList.remove('bg-gray-100');
                btn.classList.add('bg-blue-500', 'text-white');

                // Update hidden input with selected date
                const selectedDate = btn.dataset.date;
                bookingDateInput.value = selectedDate;

                // Reload available slots for the selected date via AJAX
                fetch(`/api/fields/available-slots?date=${selectedDate}`)
                    .then(response => response.json())
                    .then(data => {
                        // Update the slot matrix with new availability data
                        updateSlotMatrix(data.fieldAvailability);

                        // Clear current selections when changing date
                        clearSelections();
                    })
                    .catch(error => {
                        console.error('Error fetching availability data:', error);
                    });
            });
        });

        // Initialize field selectors with a slight delay to ensure DOM is fully loaded
        setTimeout(function() {
            initializeFieldSelectors();
        }, 100);

        function initializeFieldSelectors() {
            console.log("Initializing field selectors");
            const fieldSelectors = document.querySelectorAll('.field-selector');
            console.log("Found " + fieldSelectors.length + " field selectors");

            fieldSelectors.forEach(selector => {
                if (selector.dataset.available === 'true') {
                    // Remove any existing event listeners
                    const newSelector = selector.cloneNode(true);
                    selector.parentNode.replaceChild(newSelector, selector);

                    // Add new click event listener
                    newSelector.addEventListener('click', function(event) {
                        event.preventDefault();
                        event.stopPropagation();
                        console.log("Field clicked:", newSelector.dataset.fieldId, "Time:", newSelector.dataset.formattedTime);
                        toggleFieldSelection(newSelector);
                    });
                }
            });
        }

        function toggleFieldSelection(selector) {
            const fieldId = selector.dataset.fieldId;
            const timeSlot = selector.dataset.time;
            const formattedTime = selector.dataset.formattedTime || timeSlot;
            const isSelected = selector.classList.contains('selected');

            console.log("Toggling selection for Field:", fieldId, "Time:", timeSlot);
            console.log("Current state:", isSelected ? "Selected" : "Not Selected");

            if (isSelected) {
                // Remove selection
                selector.classList.remove('selected', 'bg-blue-500', 'text-white');
                selector.classList.add('bg-green-100', 'text-green-800');
                selector.querySelector('span').textContent = 'Available';

                // Remove from tracking
                if (selectedBookings[fieldId]) {
                    const index = selectedBookings[fieldId].indexOf(timeSlot);
                    if (index > -1) {
                        selectedBookings[fieldId].splice(index, 1);
                        console.log("Removed time slot from tracking");
                    }

                    if (selectedBookings[fieldId].length === 0) {
                        delete selectedBookings[fieldId];
                        console.log("Removed field from tracking as no slots remain");
                    }
                }
            } else {
                // Add selection
                selector.classList.remove('bg-green-100', 'text-green-800');
                selector.classList.add('selected', 'bg-blue-500', 'text-white');
                selector.querySelector('span').textContent = 'Selected';

                // Add to tracking
                if (!selectedBookings[fieldId]) {
                    selectedBookings[fieldId] = [];
                    console.log("Created new array for field", fieldId);
                }

                // Only add if not already in the array
                if (!selectedBookings[fieldId].includes(timeSlot)) {
                    selectedBookings[fieldId].push(timeSlot);
                    console.log("Added time slot to tracking");
                }
            }

            // Update UI and form data
            updateSelectedSlotsList();
            updateHiddenInputs();
            validateSelection();

            // For debugging: Show current selections
            const debugInfo = document.getElementById('debug-info');
            const debugSelections = document.getElementById('debug-selections');
            if (debugInfo && debugSelections) {
                debugInfo.classList.remove('hidden');
                debugSelections.innerHTML = `Selected bookings: ${JSON.stringify(selectedBookings)}`;
            }
        }

        // Form Validation
        function validateSelection() {
            const hasSelection = Object.keys(selectedBookings).length > 0;

            if (hasSelection) {
                noSelectionWarning.classList.add('hidden');
                submitBtn.disabled = false;
            } else {
                noSelectionWarning.classList.remove('hidden');
                submitBtn.disabled = true;
            }
        }

        // Update slot matrix with new availability data
        function updateSlotMatrix(fieldAvailability) {
            console.log("Updating slot matrix with new availability data");
            const rows = document.querySelectorAll('.slot-row');

            if (!fieldAvailability) {
                console.warn("No field availability data provided");
                fieldAvailability = {};
            }

            rows.forEach(row => {
                const timeCell = row.querySelector('td');
                const timeSlot = timeCell.textContent.trim();
                const cells = row.querySelectorAll('.field-selector');

                cells.forEach((selector, index) => {
                    const fieldId = selector.dataset.fieldId;
                    const time = selector.dataset.time;
                    const formattedTime = selector.dataset.formattedTime || timeSlot;

                    // Check if the field is available at this time slot
                    const isBooked = fieldAvailability[fieldId] &&
                        !fieldAvailability[fieldId][time];

                    if (isBooked) {
                        // Booked
                        selector.classList.remove('bg-green-100', 'text-green-800', 'hover:bg-green-200', 'selected', 'bg-blue-500', 'text-white');
                        selector.classList.add('bg-red-100', 'text-red-800');
                        selector.disabled = true;
                        selector.dataset.available = 'false';
                        selector.querySelector('span').textContent = 'Booked';

                        // Create a new button to replace the old one (to remove event listeners)
                        const newSelector = selector.cloneNode(true);
                        selector.parentNode.replaceChild(newSelector, selector);
                    } else {
                        // Available
                        selector.classList.remove('bg-red-100', 'text-red-800', 'selected', 'bg-blue-500', 'text-white');
                        selector.classList.add('bg-green-100', 'text-green-800', 'hover:bg-green-200');
                        selector.disabled = false;
                        selector.dataset.available = 'true';
                        selector.querySelector('span').textContent = 'Available';

                        // Create a new button to ensure clean event listeners
                        const newSelector = selector.cloneNode(true);
                        selector.parentNode.replaceChild(newSelector, selector);

                        // Add click event with explicit parameters
                        newSelector.addEventListener('click', function(event) {
                            event.preventDefault();
                            event.stopPropagation();
                            console.log("Available field clicked:", fieldId, "Time:", formattedTime);
                            toggleFieldSelection(newSelector);
                        });
                    }
                });
            });

            // Reinitialize selectors after update
            console.log("Field matrix updated, slots should now be clickable");
        }

        // Update the selected slots list
        function updateSelectedSlotsList() {
            selectedSlotsList.innerHTML = '';

            const fieldIds = Object.keys(selectedBookings).sort((a, b) => parseInt(a) - parseInt(b));

            if (fieldIds.length > 0) {
                selectedSlotsContainer.classList.remove('hidden');

                fieldIds.forEach(fieldId => {
                    const times = selectedBookings[fieldId].sort();
                    const li = document.createElement('li');
                    li.textContent = `Field ${fieldId}: ${formatTimeSlots(times)}`;
                    selectedSlotsList.appendChild(li);
                });
            } else {
                selectedSlotsContainer.classList.add('hidden');
            }
        }

        // Update hidden inputs for form submission
        function updateHiddenInputs() {
            // Clear existing hidden inputs
            selectedFieldsContainer.innerHTML = '';

            const fieldIds = Object.keys(selectedBookings);

            fieldIds.forEach(fieldId => {
                // Create hidden input for field ID
                const fieldInput = document.createElement('input');
                fieldInput.type = 'hidden';
                fieldInput.name = 'selected_fields[]';
                fieldInput.value = fieldId;
                selectedFieldsContainer.appendChild(fieldInput);

                // Create hidden inputs for time slots
                selectedBookings[fieldId].forEach(slot => {
                    const slotInput = document.createElement('input');
                    slotInput.type = 'hidden';
                    slotInput.name = `selected_slots[${fieldId}][]`;
                    slotInput.value = slot;
                    selectedFieldsContainer.appendChild(slotInput);
                });
            });
        }

        // Format time slots for display
        function formatTimeSlots(slots) {
            if (slots.length === 0) return '';

            // Convert time slots to formatted time
            const formattedSlots = slots.map(slot => {
                // Handle cases where the time might be already formatted
                if (slot.includes(':')) {
                    const timeParts = slot.split(':');
                    if (timeParts.length >= 2) {
                        const hour = parseInt(timeParts[0]);
                        const minute = timeParts[1].substring(0, 2);
                        return `${hour}:${minute}`;
                    }
                    return slot;
                }

                try {
                    const time = new Date(`2000-01-01T${slot}`);
                    return time.toLocaleTimeString([], {
                        hour: 'numeric',
                        minute: '2-digit'
                    });
                } catch (e) {
                    return slot; // Fallback to original value if parsing fails
                }
            });

            return formattedSlots.join(', ');
        }

        // Clear all selections
        function clearSelections() {
            const selectedElements = document.querySelectorAll('.field-selector.selected');
            selectedElements.forEach(el => {
                el.classList.remove('selected', 'bg-blue-500', 'text-white');
                el.classList.add('bg-green-100', 'text-green-800');
                el.querySelector('span').textContent = 'Available';
            });

            selectedBookings = {};
            updateSelectedSlotsList();
            updateHiddenInputs();
            validateSelection();
        }

        // Form submission
        const bookingForm = document.getElementById('bookingForm');
        bookingForm.addEventListener('submit', function(e) {
            // Check if any fields are selected
            if (Object.keys(selectedBookings).length === 0) {
                e.preventDefault();
                alert('Please select at least one field and time slot.');
                return false;
            }

            // Log what we're submitting
            console.log("Submitting booking form with the following selections:", selectedBookings);
            console.log("Form data:", new FormData(bookingForm));

            // Form is valid, it will submit naturally
            return true;
        });

        // Initialize validation
        validateSelection();
    });
</script>
@endpush
@endsection
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
                <div class="flex flex-wrap gap-2" id="date-selector-container"></div>
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

    <!-- Data untuk JavaScript -->
    <script>
        const fieldsData = <?php echo json_encode($fields); ?>;
        const slotsData = <?php echo json_encode($slots); ?>;
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const fields = fieldsData;
            const slots = slotsData;
            let fieldAvailability = {};
            const selectedSlots = {};
            const fieldPrices = {};
            let currentDate = '';
            let totalAmount = 0;

            fields.forEach(field => {
                fieldPrices[field.id] = field.price_per_hour;
            });

            function generateWeeklyDates() {
                const weeklyDates = [];
                const today = new Date();
                for (let i = 0; i < 7; i++) {
                    const date = new Date(today);
                    date.setDate(today.getDate() + i);
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const dayOfMonth = String(date.getDate()).padStart(2, '0');
                    const dateString = `${year}-${month}-${dayOfMonth}`;
                    const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                    const day = dayNames[date.getDay()];
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

            currentDate = weeklyDates[0].date;
            document.getElementById('booking_date').value = currentDate;

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

            function formatTimeRange(startTime) {
                const [hours, minutes] = startTime.split(':');
                const startHour = parseInt(hours);
                const startMin = minutes;
                const endHour = (startHour + 1) % 24;
                return `${hours}:${startMin}-${String(endHour).padStart(2, '0')}:${startMin}`;
            }

            function renderTimeSlots() {
                const tableBody = document.getElementById('booking-table-body');
                tableBody.innerHTML = '';
                slots.forEach(slot => {
                    const tr = document.createElement('tr');
                    const tdTime = document.createElement('td');
                    tdTime.className = 'border px-4 py-2 font-medium';
                    tdTime.textContent = formatTimeRange(slot.time);
                    tr.appendChild(tdTime);

                    fields.forEach(field => {
                        const isAvailable = fieldAvailability && fieldAvailability[field.id] &&
                            fieldAvailability[field.id][slot.time] === true;
                        const isSelected = selectedSlots[field.id]?.includes(slot.time);
                        const td = document.createElement('td');

                        let tdClass = 'border px-1 py-1 text-center time-slot';
                        let statusText = 'Available';

                        if (isSelected) {
                            tdClass += ' bg-blue-500 text-white';
                            statusText = 'Selected';
                        } else if (isAvailable) {
                            tdClass += ' bg-green-100 hover:bg-green-200 cursor-pointer';
                        } else {
                            tdClass += ' bg-red-100 cursor-not-allowed';
                            statusText = 'Unavailable';
                        }

                        td.className = tdClass;
                        td.setAttribute('data-field-id', field.id);
                        td.setAttribute('data-time-slot', slot.time);
                        td.setAttribute('data-available', isAvailable ? 'true' : 'false');

                        td.innerHTML = `
                <div class="h-8 w-full flex items-center justify-center">
                    <span class="slot-status">${statusText}</span>
                </div>
            `;

                        tr.appendChild(td);
                    });
                    tableBody.appendChild(tr);
                });
                addSlotClickEvents();
            }

            function addSlotClickEvents() {
                document.querySelectorAll(".time-slot[data-available='true']").forEach(slot => {
                    slot.addEventListener("click", function() {
                        const fieldId = parseInt(this.getAttribute("data-field-id"));
                        const timeSlot = this.getAttribute("data-time-slot");
                        handleSlotClick(fieldId, timeSlot);
                    });
                });
            }

            function handleSlotClick(fieldId, timeSlot) {
                const fieldCheckbox = document.getElementById(`field_${fieldId}`);
                fieldCheckbox.checked = true;
                if (!selectedSlots[fieldId]) {
                    selectedSlots[fieldId] = [];
                }
                const slotIndex = selectedSlots[fieldId].indexOf(timeSlot);
                if (slotIndex === -1) {
                    selectedSlots[fieldId].push(timeSlot);
                    totalAmount += fieldPrices[fieldId];
                } else {
                    selectedSlots[fieldId].splice(slotIndex, 1);
                    totalAmount -= fieldPrices[fieldId];
                    if (selectedSlots[fieldId].length === 0) {
                        delete selectedSlots[fieldId];
                        fieldCheckbox.checked = false;
                    }
                }
                renderTimeSlots();
                updateSelectedSlotsDisplay();
                updateBookingSummary();
                updateTotalPrice();
                updateFormInputs();
            }

            document.querySelectorAll(".date-selector").forEach((button) => {
                button.addEventListener("click", function() {
                    document.querySelectorAll(".date-selector").forEach(btn => {
                        btn.classList.remove("bg-blue-500", "text-white", "border-blue-600");
                        btn.classList.add("bg-white", "text-gray-700", "border-gray-300", "hover:bg-gray-50");
                    });
                    this.classList.remove("bg-white", "text-gray-700", "border-gray-300", "hover:bg-gray-50");
                    this.classList.add("bg-blue-500", "text-white", "border-blue-600");
                    const selectedDate = this.getAttribute("data-date");
                    document.getElementById("booking_date").value = selectedDate;
                    currentDate = selectedDate;
                    clearAllSelections();
                    fetchAvailability(selectedDate);
                });
            });

            function fetchAvailability(date) {
                document.getElementById("error-container")?.classList.add("hidden");
                fetch(`/api/available-slots?date=${date}`)
                    .then(response => {
                        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            fieldAvailability = data.fieldAvailability;
                            renderTimeSlots();
                            updateSelectedSlotsDisplay();
                            updateBookingSummary();
                            updateTotalPrice();
                        } else {
                            throw new Error("API returned success: false");
                        }
                    })
                    .catch(error => {
                        console.error("Error fetching availability:", error);
                        document.getElementById("error-message").textContent =
                            "Gagal mengambil data slot. Silakan refresh halaman.";
                        document.getElementById("error-container")?.classList.remove("hidden");
                    });
            }

            document.querySelectorAll(".field-checkbox").forEach(checkbox => {
                checkbox.addEventListener("change", function() {
                    const fieldId = parseInt(this.getAttribute("data-field-id"));
                    if (!this.checked) {
                        if (selectedSlots[fieldId]) {
                            totalAmount -= selectedSlots[fieldId].length * fieldPrices[fieldId];
                            delete selectedSlots[fieldId];
                        }
                    }
                    renderTimeSlots();
                    updateSelectedSlotsDisplay();
                    updateBookingSummary();
                    updateTotalPrice();
                    updateFormInputs();
                });
            });

            function updateSelectedSlotsDisplay() {
                const container = document.getElementById("selected-slots-container");
                const summary = document.getElementById("selected-slots-summary");
                const hasSelections = Object.keys(selectedSlots).length > 0;
                if (hasSelections) {
                    let summaryHTML = "";
                    for (const fieldId in selectedSlots) {
                        const fieldName = document.querySelector(`th input[data-field-id="${fieldId}"]`).getAttribute("data-field-name");
                        const slots = selectedSlots[fieldId].sort();
                        const formattedTimes = slots.map(slot => formatTimeRange(slot)).join(", ");
                        summaryHTML += `<div class="mb-2"><strong>${fieldName}:</strong> ${formattedTimes}</div>`;
                    }
                    summary.innerHTML = summaryHTML;
                    container.classList.remove("hidden");
                } else {
                    container.classList.add("hidden");
                }
            }

            function updateFormInputs() {
                document.querySelectorAll('input[name^="selected_slots["]').forEach(input => input.remove());
                for (const fieldId in selectedSlots) {
                    selectedSlots[fieldId].forEach(slot => {
                        const input = document.createElement("input");
                        input.type = "hidden";
                        input.name = `selected_slots[${fieldId}][]`;
                        input.value = slot;
                        document.getElementById("bookingForm").appendChild(input);
                    });
                }
            }

            function updateBookingSummary() {
                const summaryContainer = document.getElementById("booking-summary");
                if (Object.keys(selectedSlots).length === 0) {
                    summaryContainer.innerHTML =
                        '<p class="text-gray-500 italic">Please select field(s) and time slot(s) to see the summary</p>';
                    return;
                }
                let summaryHTML = '<div class="space-y-3">';
                for (const fieldId in selectedSlots) {
                    const fieldCheckbox = document.getElementById(`field_${fieldId}`);
                    const fieldName = fieldCheckbox.getAttribute("data-field-name");
                    const fieldPrice = parseFloat(fieldCheckbox.getAttribute("data-field-price"));
                    const slots = selectedSlots[fieldId].sort();
                    const subtotal = slots.length * fieldPrice;
                    const formattedTimes = slots.map(slot => formatTimeRange(slot)).join(", ");
                    summaryHTML += `
                <div class="p-3 bg-gray-50 rounded border">
                    <div class="font-medium">${fieldName}</div>
                    <div class="text-sm text-gray-600">Time: ${formattedTimes}</div>
                    <div class="text-sm text-gray-600">Hours: ${slots.length}</div>
                    <div class="flex justify-between mt-1">
                        <span>Price per hour:</span>
                        <span>Rp ${fieldPrice.toLocaleString("id-ID")}</span>
                    </div>
                    <div class="flex justify-between font-medium">
                        <span>Subtotal:</span>
                        <span>Rp ${subtotal.toLocaleString("id-ID")}</span>
                    </div>
                </div>
            `;
                }
                summaryHTML += "</div>";
                summaryContainer.innerHTML = summaryHTML;
            }

            function updateTotalPrice() {
                document.getElementById("total-price").textContent = `Rp ${totalAmount.toLocaleString("id-ID")}`;
            }

            function clearAllSelections() {
                for (const fieldId in selectedSlots) {
                    delete selectedSlots[fieldId];
                }
                totalAmount = 0;
                document.querySelectorAll(".field-checkbox").forEach(checkbox => {
                    checkbox.checked = false;
                });
                updateSelectedSlotsDisplay();
                updateBookingSummary();
                updateTotalPrice();
                updateFormInputs();
            }

            // Fitur: Cash hanya bisa untuk hari ini
            const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
            const bookingDateInput = document.getElementById('booking_date');

            function restrictToTodayIfCash() {
                const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
                const today = new Date().toISOString().split('T')[0];
                if (paymentMethod === 'cash') {
                    bookingDateInput.value = today;
                    document.querySelectorAll('.date-selector').forEach(btn => {
                        btn.disabled = btn.getAttribute('data-date') !== today;
                        if (btn.getAttribute('data-date') === today) {
                            btn.classList.add('bg-blue-500', 'text-white');
                        } else {
                            btn.classList.remove('bg-blue-500', 'text-white');
                        }
                    });
                } else {
                    document.querySelectorAll('.date-selector').forEach(btn => {
                        btn.disabled = false;
                    });
                }
            }
            paymentRadios.forEach(radio => radio.addEventListener('change', restrictToTodayIfCash));
            restrictToTodayIfCash();

            // Validasi form sebelum submit
            document.getElementById("bookingForm").addEventListener("submit", function(e) {
                const hasSelections = Object.keys(selectedSlots).length > 0;
                if (!hasSelections) {
                    e.preventDefault();
                    alert("Please select at least one time slot for a field.");
                    return false;
                }
            });

            // Init
            updateFormInputs();
            updateBookingSummary();
            updateTotalPrice();
            fetchAvailability(currentDate);

            // Jika tanggal yang dipilih adalah hari ini, refresh slot setiap menit
            const today = new Date().toISOString().split('T')[0];
            if (currentDate === today) {
                setInterval(function() {
                    fetchAvailability(currentDate);
                }, 60000); // 60000 ms = 1 menit
            }

        });
    </script>
</body>

</html>
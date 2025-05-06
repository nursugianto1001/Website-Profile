import "./bootstrap";

import "@fortawesome/fontawesome-free/css/all.min.css";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

// Calendar and booking functionality
document.addEventListener("DOMContentLoaded", function () {
    // Check if we're on the booking page
    const bookingForm = document.querySelector(
        'form[action*="booking/process"]'
    );
    if (bookingForm) {
        initializeBookingForm();
    }
});

function initializeBookingForm() {
    const timeSlots = document.querySelectorAll(".time-slot:not(.unavailable)");
    const selectedSlotsContainer = document.getElementById(
        "selectedSlotsContainer"
    );

    if (!timeSlots.length || !selectedSlotsContainer) return;

    let selectedSlots = [];

    timeSlots.forEach((slot) => {
        slot.addEventListener("click", function () {
            const fieldId = this.getAttribute("data-field");
            const date = this.getAttribute("data-date");
            const time = this.getAttribute("data-time");

            if (this.classList.contains("selected")) {
                // Deselect
                this.classList.remove("selected");
                selectedSlots = selectedSlots.filter(
                    (s) =>
                        !(
                            s.fieldId === fieldId &&
                            s.date === date &&
                            s.time === time
                        )
                );
            } else {
                // Select
                this.classList.add("selected");
                selectedSlots.push({ fieldId, date, time });
            }

            updateSelectedSlots();
        });
    });

    function updateSelectedSlots() {
        selectedSlotsContainer.innerHTML = "";

        selectedSlots.forEach((slot, index) => {
            const fieldInput = document.createElement("input");
            fieldInput.type = "hidden";
            fieldInput.name = `bookings[${index}][field_id]`;
            fieldInput.value = slot.fieldId;

            const dateInput = document.createElement("input");
            dateInput.type = "hidden";
            dateInput.name = `bookings[${index}][date]`;
            dateInput.value = slot.date;

            const timeInput = document.createElement("input");
            timeInput.type = "hidden";
            timeInput.name = `bookings[${index}][time]`;
            timeInput.value = slot.time;

            selectedSlotsContainer.appendChild(fieldInput);
            selectedSlotsContainer.appendChild(dateInput);
            selectedSlotsContainer.appendChild(timeInput);
        });
    }
}

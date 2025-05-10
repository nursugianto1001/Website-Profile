console.log("app.js loaded!");

document.addEventListener("DOMContentLoaded", function () {
    // Ambil data dari variabel global yang sudah didefinisikan di view
    const fields = typeof fieldsData !== "undefined" ? fieldsData : [];
    const slots = typeof slotsData !== "undefined" ? slotsData : [];

    console.log("FIELDS:", fields);
    console.log("SLOTS:", slots);

    let fieldAvailability = {};
    let selectedSlots = {};
    const fieldPrices = {};
    let currentDate = "";
    let totalAmount = 0;

    // Inisialisasi harga lapangan per field id
    fields.forEach((field) => {
        fieldPrices[field.id] = field.price_per_hour;
    });

    // Generate tanggal 7 hari ke depan untuk pemilih tanggal
    function generateWeeklyDates() {
        const weeklyDates = [];
        const today = new Date();
        for (let i = 0; i < 7; i++) {
            const date = new Date(today);
            date.setDate(today.getDate() + i);
            weeklyDates.push({
                date: date.toISOString().split("T")[0],
                day: date.toLocaleDateString("en-US", { weekday: "short" }),
                formatted_date: date.toLocaleDateString("en-US", {
                    month: "short",
                    day: "numeric",
                }),
            });
        }
        return weeklyDates;
    }

    // Render tombol pemilih tanggal
    const weeklyDates = generateWeeklyDates();
    const dateContainer = document.getElementById("date-selector-container");
    weeklyDates.forEach((dateObj, index) => {
        const dateButton = document.createElement("button");
        dateButton.type = "button";
        dateButton.className = `date-selector px-4 py-2 border rounded-md transition-colors ${
            index === 0
                ? "bg-blue-500 text-white border-blue-600"
                : "bg-white text-gray-700 border-gray-300 hover:bg-gray-50"
        }`;
        dateButton.setAttribute("data-date", dateObj.date);
        dateButton.innerHTML = `
            <span class="block font-medium">${dateObj.day}</span>
            <span class="block text-sm">${dateObj.formatted_date}</span>
        `;
        dateContainer.appendChild(dateButton);
    });

    // Set tanggal awal ke tanggal pertama di daftar
    currentDate = weeklyDates[0].date;
    document.getElementById("booking_date").value = currentDate;

    // Render header tabel lapangan (field)
    const tableHeader = document.querySelector(".booking-table thead tr");
    fields.forEach((field) => {
        const th = document.createElement("th");
        th.className = "border px-4 py-2 bg-gray-100";
        th.innerHTML = `
            ${field.name}
            <div class="text-sm text-gray-600">Rp ${field.price_per_hour.toLocaleString(
                "id-ID"
            )}/hour</div>
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

    // Render slot waktu dan status per lapangan
    function renderTimeSlots() {
        const tableBody = document.getElementById("booking-table-body");
        tableBody.innerHTML = "";

        slots.forEach((slot) => {
            const tr = document.createElement("tr");

            // Kolom waktu
            const tdTime = document.createElement("td");
            tdTime.className = "border px-4 py-2 font-medium";
            tdTime.textContent = slot.formatted_time;
            tr.appendChild(tdTime);

            // Kolom status slot per lapangan
            fields.forEach((field) => {
                const isAvailable =
                    fieldAvailability &&
                    fieldAvailability[field.id] &&
                    fieldAvailability[field.id][slot.time] === true;

                const isSelected = selectedSlots[field.id]?.includes(slot.time);

                const td = document.createElement("td");
                td.className = `border px-1 py-1 text-center time-slot 
                    ${
                        isAvailable
                            ? "bg-green-100 hover:bg-green-200 cursor-pointer"
                            : "bg-red-100"
                    }
                    ${isSelected ? " bg-blue-500 text-white" : ""}`;
                td.setAttribute("data-field-id", field.id);
                td.setAttribute("data-time-slot", slot.time);
                td.setAttribute("data-available", isAvailable);

                td.innerHTML = `
                    <div class="h-8 w-full flex items-center justify-center">
                        <span class="slot-status">
                            ${
                                isAvailable
                                    ? isSelected
                                        ? "Selected"
                                        : "Available"
                                    : "Booked"
                            }
                        </span>
                    </div>
                `;

                if (isAvailable) {
                    td.addEventListener("click", function () {
                        handleSlotClick(field.id, slot.time);
                    });
                }

                tr.appendChild(td);
            });

            tableBody.appendChild(tr);
        });
    }

    // Fungsi klik slot: toggle pilihan dan update total harga
    function handleSlotClick(fieldId, time) {
        if (!fieldAvailability[fieldId]?.[time]) return;

        if (!selectedSlots[fieldId]) selectedSlots[fieldId] = [];

        const index = selectedSlots[fieldId].indexOf(time);
        if (index === -1) {
            selectedSlots[fieldId].push(time);
            totalAmount += fieldPrices[fieldId];
        } else {
            selectedSlots[fieldId].splice(index, 1);
            totalAmount -= fieldPrices[fieldId];
        }

        updateSelectedDisplay();
        updateTotalPrice();
        renderTimeSlots();
    }

    // Ambil data ketersediaan slot dari API backend
    async function fetchAvailability(date) {
        try {
            const response = await fetch(`/api/available-slots?date=${date}`);
            const data = await response.json();

            if (data.success) {
                fieldAvailability = data.fieldAvailability;
                renderTimeSlots();
                updateSelectedDisplay();
            } else {
                throw new Error("Data tidak valid");
            }
        } catch (error) {
            console.error("Gagal mengambil data:", error);
            document.getElementById("error-message").textContent =
                "Gagal memuat data slot. Silakan refresh halaman.";
            document
                .getElementById("error-container")
                .classList.remove("hidden");
        }
    }

    // Event klik tombol tanggal: ubah tanggal dan refresh slot
    document.querySelectorAll(".date-selector").forEach((button) => {
        button.addEventListener("click", function () {
            document.querySelectorAll(".date-selector").forEach((btn) => {
                btn.classList.remove(
                    "bg-blue-500",
                    "text-white",
                    "border-blue-600"
                );
                btn.classList.add(
                    "bg-white",
                    "text-gray-700",
                    "border-gray-300",
                    "hover:bg-gray-50"
                );
            });
            this.classList.add("bg-blue-500", "text-white", "border-blue-600");
            currentDate = this.dataset.date;
            document.getElementById("booking_date").value = currentDate;

            // Reset pilihan dan total harga
            selectedSlots = {};
            totalAmount = 0;
            updateSelectedDisplay();
            updateTotalPrice();

            fetchAvailability(currentDate);
        });
    });

    // Update tampilan daftar slot yang dipilih
    function updateSelectedDisplay() {
        const container = document.getElementById("selected-slots-container");
        const summary = document.getElementById("selected-slots-summary");
        let html = "";

        Object.entries(selectedSlots).forEach(([fieldId, times]) => {
            if (times.length > 0) {
                const field = fields.find((f) => f.id == fieldId);
                html += `<div class="mb-2"><strong>${
                    field.name
                }:</strong> ${times.sort().join(", ")}</div>`;
            }
        });

        summary.innerHTML = html;
        container.classList.toggle("hidden", !html);
    }

    // Update tampilan total harga
    function updateTotalPrice() {
        document.getElementById(
            "total-price"
        ).textContent = `Rp ${totalAmount.toLocaleString("id-ID")}`;
    }

    // Validasi form sebelum submit
    document
        .getElementById("bookingForm")
        .addEventListener("submit", function (e) {
            console.log("Form submit triggered");
            console.log("selectedSlots:", selectedSlots);
            const hasSelections = Object.values(selectedSlots).some(
                (arr) => arr.length > 0
            );

            if (!hasSelections) {
                e.preventDefault();
                alert("Please select at least one time slot for a field.");
                return false;
            }
        });

    // Inisialisasi pertama: fetch data slot untuk tanggal hari ini
    fetchAvailability(currentDate);
});

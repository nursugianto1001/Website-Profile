<!-- resources/views/admin/bookings/create.blade.php -->
@extends('layouts.admin')

@section('content')
<div class="bg-gradient-to-b from-gray-50 to-white p-6 rounded-xl shadow-lg">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-indigo-800">Buat Booking Baru</h2>
            <p class="text-gray-500 mt-1">Tambahkan booking lapangan baru ke sistem</p>
            <p class="text-gray-500 mt-1"> Khusus dihari Sabtu & Minggu Harga Otomatis Menjadi Rp60.000,00</p>
        </div>
        <a href="{{ route('admin.bookings.index') }}"
            class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors duration-150 ease-in-out">
            <i class="bi bi-arrow-left mr-2"></i>Kembali ke Daftar
        </a>
    </div>

    <!-- Form Booking Reguler -->
    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm mb-6">
        <form action="{{ route('admin.bookings.store') }}" method="POST">
            @csrf

            @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="bi bi-exclamation-circle text-red-600"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Terdapat beberapa kesalahan:</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc space-y-1 pl-5">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold text-indigo-800 mb-4 flex items-center">
                        <i class="bi bi-calendar-event mr-2 text-indigo-600"></i>
                        Informasi Booking
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label for="field_id" class="block text-sm font-medium text-gray-700 mb-1">Lapangan <span class="text-red-600">*</span></label>
                            <select name="field_id" id="field_id" required
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Pilih Lapangan</option>
                                @foreach($fields as $field)
                                <option value="{{ $field->id }}" {{ old('field_id') == $field->id ? 'selected' : '' }}>
                                    {{ $field->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="booking_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Booking <span class="text-red-600">*</span></label>
                            <input type="date" name="booking_date" id="booking_date" required
                                min="{{ date('Y-m-d') }}"
                                value="{{ old('booking_date', date('Y-m-d')) }}"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">Waktu Mulai <span class="text-red-600">*</span></label>
                                <select name="start_time" id="start_time" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Pilih Waktu</option>
                                    @for($hour = 6; $hour < 24; $hour++)
                                        <option value="{{ sprintf('%02d', $hour) }}:00:00" {{ old('start_time') == sprintf('%02d', $hour) . ':00:00' ? 'selected' : '' }}>
                                        {{ sprintf('%02d', $hour) }}:00
                                        </option>
                                        @endfor
                                </select>
                            </div>
                            <div>
                                <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">Waktu Selesai <span class="text-red-600">*</span></label>
                                <select name="end_time" id="end_time" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Pilih Waktu</option>
                                    @for($hour = 7; $hour <= 24; $hour++)
                                        <option value="{{ sprintf('%02d', $hour) }}:00:00" {{ old('end_time') == sprintf('%02d', $hour) . ':00:00' ? 'selected' : '' }}>
                                        {{ sprintf('%02d', $hour) }}:00
                                        </option>
                                        @endfor
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran <span class="text-red-600">*</span></label>
                            <div class="flex space-x-4 mt-1">
                                <div class="flex items-center">
                                    <input type="radio" name="payment_method" id="payment_cash" value="cash"
                                        {{ old('payment_method', 'cash') == 'cash' ? 'checked' : '' }}
                                        class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                    <label for="payment_cash" class="ml-2 block text-sm text-gray-700">Cash</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-indigo-800 mb-4 flex items-center">
                        <i class="bi bi-person mr-2 text-indigo-600"></i>
                        Informasi Pelanggan
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Pelanggan <span class="text-red-600">*</span></label>
                            <input type="text" name="customer_name" id="customer_name"
                                value="{{ old('customer_name') }}" required
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-1">Email Pelanggan <span class="text-red-600">*</span></label>
                            <input type="email" name="customer_email" id="customer_email"
                                value="{{ old('customer_email') }}" required
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon <span class="text-red-600">*</span></label>
                            <input type="text" name="customer_phone" id="customer_phone"
                                value="{{ old('customer_phone') }}" required
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <!-- TAMBAHAN: Field Admin Name untuk Booking Reguler -->
                        <div>
                            <label for="admin_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Admin <span class="text-red-600">*</span></label>
                            <input type="text" name="admin_name" id="admin_name"
                                value="{{ old('admin_name', auth()->user()->name) }}" required
                                placeholder="Masukkan nama admin yang menginput"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <p class="text-xs text-gray-500 mt-1">Default: {{ auth()->user()->name }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-8">
                <button type="submit" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-lg text-white font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-sm transition-colors duration-150 ease-in-out">
                    <i class="bi bi-save mr-2"></i>
                    Buat Booking
                </button>
            </div>
        </form>
    </div>

    <!-- Form Member Booking - UPDATED: Bebas Jam -->
    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl border border-yellow-200 p-6 shadow-sm">
        <div class="flex items-center mb-4">
            <i class="bi bi-star-fill text-yellow-500 mr-2"></i>
            <h3 class="text-lg font-semibold text-yellow-800">Booking Member (Bebas Jam)</h3>
        </div>
        <p class="text-yellow-700 text-sm mb-6">Form khusus untuk membuat booking member pada jam berapapun dengan durasi fleksibel</p>

        <form action="{{ route('admin.bookings.store-member') }}" method="POST" id="memberBookingForm">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="text-md font-semibold text-yellow-800 mb-4 flex items-center">
                        <i class="bi bi-calendar-event mr-2 text-yellow-600"></i>
                        Informasi Booking Member
                    </h4>

                    <div class="space-y-4">
                        <div>
                            <label for="member_field_id" class="block text-sm font-medium text-gray-700 mb-1">Lapangan <span class="text-red-600">*</span></label>
                            <select name="field_id" id="member_field_id" required
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                                <option value="">Pilih Lapangan</option>
                                @foreach($fields as $field)
                                <option value="{{ $field->id }}" data-price="{{ $field->price_per_hour }}">
                                    {{ $field->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="member_booking_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Booking <span class="text-red-600">*</span></label>
                            <input type="date" name="booking_date" id="member_booking_date" required
                                min="{{ date('Y-m-d') }}"
                                value="{{ date('Y-m-d') }}"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                        </div>

                        <!-- UPDATED: Bebas Jam 06:00-24:00 -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="member_start_time" class="block text-sm font-medium text-gray-700 mb-1">Waktu Mulai <span class="text-red-600">*</span></label>
                                <select name="start_time" id="member_start_time" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                                    <option value="">Pilih Waktu Mulai</option>
                                    @for($hour = 6; $hour < 24; $hour++)
                                        <option value="{{ sprintf('%02d', $hour) }}:00:00">{{ sprintf('%02d', $hour) }}:00</option>
                                        @endfor
                                </select>
                            </div>
                            <div>
                                <label for="member_end_time" class="block text-sm font-medium text-gray-700 mb-1">Waktu Selesai <span class="text-red-600">*</span></label>
                                <select name="end_time" id="member_end_time" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                                    <option value="">Pilih Waktu Selesai</option>
                                </select>
                            </div>
                        </div>

                        <div class="bg-yellow-100 border border-yellow-300 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-yellow-800">Durasi Booking</p>
                                    <p class="text-xs text-yellow-600">Durasi akan dihitung otomatis</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-yellow-800" id="member_duration_display">0 jam</p>
                                    <input type="hidden" name="duration_hours" id="member_duration_hours" value="0">
                                </div>
                            </div>
                        </div>

                        <div class="bg-green-100 border border-green-300 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-green-800">Total Harga</p>
                                    <p class="text-xs text-green-600">Harga per jam × Durasi</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-green-800" id="member_total_price_display">Rp 0</p>
                                    <input type="hidden" name="total_price" id="member_total_price" value="0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="text-md font-semibold text-yellow-800 mb-4 flex items-center">
                        <i class="bi bi-person-badge mr-2 text-yellow-600"></i>
                        Informasi Member
                    </h4>

                    <div class="space-y-4">
                        <div>
                            <label for="member_customer_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Member <span class="text-red-600">*</span></label>
                            <input type="text" name="customer_name" id="member_customer_name" required
                                placeholder="Masukkan nama member"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="member_customer_email" class="block text-sm font-medium text-gray-700 mb-1">Email Member <span class="text-red-600">*</span></label>
                            <input type="email" name="customer_email" id="member_customer_email" required
                                placeholder="member@example.com"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="member_customer_phone" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon Member <span class="text-red-600">*</span></label>
                            <input type="text" name="customer_phone" id="member_customer_phone" required
                                placeholder="08xxxxxxxxxx"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                        </div>

                        <!-- TAMBAHAN: Field Admin Name untuk Member Booking -->
                        <div>
                            <label for="member_admin_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Admin <span class="text-red-600">*</span></label>
                            <input type="text" name="admin_name" id="member_admin_name" required
                                value="{{ auth()->user()->name }}"
                                placeholder="Masukkan nama admin yang menginput"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                            <p class="text-xs text-gray-500 mt-1">Default: {{ auth()->user()->name }}</p>
                        </div>

                        <div>
                            <label for="member_payment_method" class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran <span class="text-red-600">*</span></label>
                            <div class="flex space-x-4 mt-1">
                                <div class="flex items-center">
                                    <input type="radio" name="payment_method" id="member_payment_cash" value="cash" checked
                                        class="h-4 w-4 text-yellow-600 border-gray-300 focus:ring-yellow-500">
                                    <label for="member_payment_cash" class="ml-2 block text-sm text-gray-700">Cash</label>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="member_notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                            <textarea name="notes" id="member_notes" rows="3"
                                placeholder="Catatan tambahan untuk booking member"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200 focus:ring-opacity-50"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <button type="submit" class="inline-flex items-center px-6 py-3 bg-yellow-600 border border-transparent rounded-lg text-white font-medium hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 shadow-sm transition-colors duration-150 ease-in-out">
                    <i class="bi bi-star mr-2"></i>
                    Buat Booking Member
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // JavaScript untuk form booking reguler dengan dynamic pricing
        const startTimeSelect = document.getElementById('start_time');
        const endTimeSelect = document.getElementById('end_time');
        const fieldSelect = document.getElementById('field_id');

        // Dynamic pricing calculation
        function getPriceByHour(hour) {
            const hourInt = parseInt(hour);
            if (hourInt >= 6 && hourInt < 12) {
                return 40000; // Pagi: 06:00-12:00
            } else if (hourInt >= 12 && hourInt < 17) {
                return 25000; // Siang: 12:00-17:00
            } else if (hourInt >= 17 && hourInt < 23) {
                return 60000; // Malam: 17:00-23:00
            }
            return 40000; // Default
        }

        function calculateTotalPrice() {
            const startTime = startTimeSelect.value;
            const endTime = endTimeSelect.value;

            if (startTime && endTime) {
                const startHour = parseInt(startTime.split(':')[0]);
                const endHour = parseInt(endTime.split(':')[0]);

                let totalPrice = 0;
                let priceBreakdown = '';

                for (let hour = startHour; hour < endHour; hour++) {
                    const slotPrice = getPriceByHour(hour);
                    totalPrice += slotPrice;
                    const nextHour = hour + 1;
                    priceBreakdown += `${hour.toString().padStart(2, '0')}:00-${nextHour.toString().padStart(2, '0')}:00: Rp ${slotPrice.toLocaleString('id-ID')}\n`;
                }

                updatePriceDisplay(totalPrice, priceBreakdown);
            } else {
                updatePriceDisplay(0, '');
            }
        }

        function updatePriceDisplay(totalPrice, breakdown) {
            let priceContainer = document.getElementById('price-display');
            if (!priceContainer) {
                priceContainer = document.createElement('div');
                priceContainer.id = 'price-display';
                priceContainer.className = 'mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg';

                const paymentSection = document.querySelector('input[name="payment_method"]').closest('.space-y-4');
                paymentSection.parentNode.insertBefore(priceContainer, paymentSection.nextSibling);
            }

            if (totalPrice > 0) {
                priceContainer.innerHTML = `
                    <h4 class="text-sm font-medium text-blue-800 mb-2">Estimasi Harga</h4>
                    <div class="text-xs text-blue-600 space-y-1 mb-2">
                        ${breakdown.split('\n').filter(line => line.trim()).map(line => `<div>${line}</div>`).join('')}
                    </div>
                    <div class="border-t border-blue-200 pt-2">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-blue-800">Total:</span>
                            <span class="text-lg font-bold text-blue-900">Rp ${totalPrice.toLocaleString('id-ID')}</span>
                        </div>
                    </div>
                `;
                priceContainer.style.display = 'block';
            } else {
                priceContainer.style.display = 'none';
            }
        }

        startTimeSelect.addEventListener('change', function() {
            const selectedStartHour = parseInt(this.value.split(':')[0]);
            endTimeSelect.innerHTML = '<option value="">Pilih Waktu</option>';

            for (let hour = selectedStartHour + 1; hour <= 24; hour++) {
                const option = document.createElement('option');
                option.value = `${hour.toString().padStart(2, '0')}:00:00`;
                option.textContent = `${hour.toString().padStart(2, '0')}:00`;
                endTimeSelect.appendChild(option);
            }
            endTimeSelect.value = '';
            calculateTotalPrice();
        });

        endTimeSelect.addEventListener('change', calculateTotalPrice);

        // JavaScript untuk form member booking - DIPERBAIKI: Gunakan dynamic pricing
        const memberStartTimeSelect = document.getElementById('member_start_time');
        const memberEndTimeSelect = document.getElementById('member_end_time');
        const memberFieldSelect = document.getElementById('member_field_id');
        const memberDurationDisplay = document.getElementById('member_duration_display');
        const memberDurationHours = document.getElementById('member_duration_hours');
        const memberTotalPriceDisplay = document.getElementById('member_total_price_display');
        const memberTotalPrice = document.getElementById('member_total_price');

        memberStartTimeSelect.addEventListener('change', function() {
            const selectedStartHour = parseInt(this.value.split(':')[0]);
            memberEndTimeSelect.innerHTML = '<option value="">Pilih Waktu Selesai</option>';

            const maxEndHour = 23;

            for (let hour = selectedStartHour + 1; hour <= maxEndHour; hour++) {
                const option = document.createElement('option');
                option.value = `${hour.toString().padStart(2, '0')}:00:00`;
                option.textContent = `${hour.toString().padStart(2, '0')}:00`;
                memberEndTimeSelect.appendChild(option);
            }

            memberEndTimeSelect.value = '';
            updateMemberCalculation();
        });

        memberEndTimeSelect.addEventListener('change', updateMemberCalculation);
        memberFieldSelect.addEventListener('change', updateMemberCalculation);

        // DIPERBAIKI: Gunakan dynamic pricing yang sama
        function updateMemberCalculation() {
            const startTime = memberStartTimeSelect.value;
            const endTime = memberEndTimeSelect.value;
            const fieldOption = memberFieldSelect.options[memberFieldSelect.selectedIndex];

            if (startTime && endTime && fieldOption.value) {
                const startHour = parseInt(startTime.split(':')[0]);
                const endHour = parseInt(endTime.split(':')[0]);
                const duration = endHour - startHour;

                // PERBAIKAN: Gunakan dynamic pricing yang sama dengan booking reguler
                let totalPrice = 0;
                for (let hour = startHour; hour < endHour; hour++) {
                    const slotPrice = getPriceByHour(hour); // Gunakan function yang sama
                    totalPrice += slotPrice;
                }

                // Update display
                memberDurationDisplay.textContent = `${duration} jam`;
                memberDurationHours.value = duration;
                memberTotalPriceDisplay.textContent = `Rp ${totalPrice.toLocaleString('id-ID')}`;
                memberTotalPrice.value = totalPrice;
            } else {
                memberDurationDisplay.textContent = '0 jam';
                memberDurationHours.value = 0;
                memberTotalPriceDisplay.textContent = 'Rp 0';
                memberTotalPrice.value = 0;
            }
        }
    });
</script>

@endsection
<!-- resources/views/admin/bookings/create.blade.php -->
@extends('layouts.admin')

@section('content')
<div class="bg-gradient-to-b from-gray-50 to-white p-6 rounded-xl shadow-lg">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-indigo-800">Buat Booking Baru</h2>
            <p class="text-gray-500 mt-1">Tambahkan booking lapangan baru ke sistem</p>
        </div>
        <a href="{{ route('admin.bookings.index') }}"
            class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors duration-150 ease-in-out">
            <i class="bi bi-arrow-left mr-2"></i>Kembali ke Daftar
        </a>
    </div>

    <!-- Form Booking Reguler (kode yang sudah ada tetap sama) -->
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
                                    {{ $field->name }} - Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}/jam
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
                                    <input type="radio" name="payment_method" id="payment_online" value="online"
                                        {{ old('payment_method') == 'online' ? 'checked' : '' }}
                                        class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                    <label for="payment_online" class="ml-2 block text-sm text-gray-700">Online</label>
                                </div>
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

    <!-- Form Member Booking Khusus Slot 17-19 -->
    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl border border-yellow-200 p-6 shadow-sm">
        <div class="flex items-center mb-4">
            <i class="bi bi-star-fill text-yellow-500 mr-2"></i>
            <h3 class="text-lg font-semibold text-yellow-800">Booking Member Khusus (17:00 - 20:00)</h3>
        </div>
        <p class="text-yellow-700 text-sm mb-6">Form khusus untuk membuat booking member pada slot waktu 17:00-20:00</p>
        
        <form action="{{ route('admin.bookings.store-member') }}" method="POST">
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
                                <option value="{{ $field->id }}">{{ $field->name }}</option>
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

                        <div>
                            <label for="member_start_time" class="block text-sm font-medium text-gray-700 mb-1">Jam Member <span class="text-red-600">*</span></label>
                            <select name="start_time" id="member_start_time" required
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                                <option value="">Pilih Jam</option>
                                <option value="17:00:00">17:00 - 18:00</option>
                                <option value="18:00:00">18:00 - 19:00</option>
                                <option value="19:00:00">19:00 - 20:00</option>
                            </select>
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
                            <label for="member_customer_email" class="block text-sm font-medium text-gray-700 mb-1">Email Member</label>
                            <input type="email" name="customer_email" id="member_customer_email"
                                placeholder="member@example.com (opsional)"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="member_customer_phone" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon Member</label>
                            <input type="text" name="customer_phone" id="member_customer_phone"
                                placeholder="08xxxxxxxxxx (opsional)"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
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
        const startTimeSelect = document.getElementById('start_time');
        const endTimeSelect = document.getElementById('end_time');

        // Update end time options based on start time
        startTimeSelect.addEventListener('change', function() {
            const selectedStartHour = parseInt(this.value.split(':')[0]);

            // Clear and rebuild end time options
            endTimeSelect.innerHTML = '<option value="">Pilih Waktu</option>';

            for (let hour = selectedStartHour + 1; hour <= 24; hour++) {
                const option = document.createElement('option');
                option.value = `${hour.toString().padStart(2, '0')}:00:00`;
                option.textContent = `${hour.toString().padStart(2, '0')}:00`;
                endTimeSelect.appendChild(option);
            }

            // Reset selection
            endTimeSelect.value = '';
        });
    });
</script>
@endsection

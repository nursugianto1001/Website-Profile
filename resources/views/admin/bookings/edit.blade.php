<!-- resources/views/admin/bookings/edit.blade.php -->
@extends('layouts.admin')

@section('content')
<div class="bg-gradient-to-b from-gray-50 to-white p-6 rounded-xl shadow-lg">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-indigo-800">Edit Booking #{{ $booking->id }}</h2>
            <p class="text-gray-500 mt-1">Perbarui informasi booking pelanggan</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.bookings.show', $booking->id) }}"
                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors duration-150 ease-in-out">
                <i class="bi bi-eye mr-2"></i>Lihat Detail
            </a>
            <a href="{{ route('admin.bookings.index') }}"
                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors duration-150 ease-in-out">
                <i class="bi bi-arrow-left mr-2"></i>Kembali ke Daftar
            </a>
        </div>
    </div>

    <!-- Status Bar -->
    <div class="mb-8 bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between">
            <div class="flex items-center mb-4 sm:mb-0">
                <div class="mr-4">
                    @if($booking->payment_status == 'settlement')
                    <div class="h-12 w-12 rounded-full bg-green-100 flex items-center justify-center">
                        <i class="bi bi-check-circle text-2xl text-green-600"></i>
                    </div>
                    @elseif($booking->payment_status == 'pending')
                    <div class="h-12 w-12 rounded-full bg-yellow-100 flex items-center justify-center">
                        <i class="bi bi-hourglass-split text-2xl text-yellow-600"></i>
                    </div>
                    @elseif($booking->payment_status == 'expired')
                    <div class="h-12 w-12 rounded-full bg-red-100 flex items-center justify-center">
                        <i class="bi bi-x-circle text-2xl text-red-600"></i>
                    </div>
                    @else
                    <div class="h-12 w-12 rounded-full bg-gray-100 flex items-center justify-center">
                        <i class="bi bi-dash-circle text-2xl text-gray-600"></i>
                    </div>
                    @endif
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">
                        Status Pembayaran:
                        <span class="
                            @if($booking->payment_status == 'settlement') text-green-600 
                            @elseif($booking->payment_status == 'pending') text-yellow-600
                            @elseif($booking->payment_status == 'expired') text-red-600
                            @else text-gray-600 @endif">
                            {{ ucfirst($booking->payment_status) }}
                        </span>
                    </h3>
                    <p class="text-sm text-gray-500">
                        Booking untuk {{ $booking->field->name }} pada {{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
        <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST">
            @csrf
            @method('PUT')

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
                <!-- Basic Booking Information (Non-editable) -->
                <div>
                    <h3 class="text-lg font-semibold text-indigo-800 mb-4 flex items-center">
                        <i class="bi bi-calendar-event mr-2 text-indigo-600"></i>
                        Informasi Booking
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lapangan</label>
                            <div class="px-4 py-2 bg-gray-50 rounded-lg border border-gray-200 text-gray-700">
                                {{ $booking->field->name }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Booking</label>
                            <div class="px-4 py-2 bg-gray-50 rounded-lg border border-gray-200 text-gray-700">
                                {{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Mulai</label>
                                <div class="px-4 py-2 bg-gray-50 rounded-lg border border-gray-200 text-gray-700">
                                    {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Selesai</label>
                                <div class="px-4 py-2 bg-gray-50 rounded-lg border border-gray-200 text-gray-700">
                                    {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Total Harga</label>
                            <div class="px-4 py-2 bg-gray-50 rounded-lg border border-gray-200 text-gray-700 font-semibold">
                                Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                            <div class="px-4 py-2 bg-gray-50 rounded-lg border border-gray-200 text-gray-700">
                                @if($booking->payment_method == 'online')
                                <span class="inline-flex items-center">
                                    <i class="bi bi-credit-card mr-1 text-indigo-600"></i> Online Payment
                                </span>
                                @else
                                <span class="inline-flex items-center">
                                    <i class="bi bi-cash mr-1 text-green-600"></i> Cash
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Editable Information -->
                <div>
                    <h3 class="text-lg font-semibold text-indigo-800 mb-4 flex items-center">
                        <i class="bi bi-person mr-2 text-indigo-600"></i>
                        Informasi Pelanggan
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Pelanggan</label>
                            <input type="text" name="customer_name" id="customer_name"
                                value="{{ old('customer_name', $booking->customer_name) }}"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-1">Email Pelanggan</label>
                            <input type="email" name="customer_email" id="customer_email"
                                value="{{ old('customer_email', $booking->customer_email) }}"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                            <input type="text" name="customer_phone" id="customer_phone"
                                value="{{ old('customer_phone', $booking->customer_phone) }}"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-1">Status Pembayaran</label>
                            <select name="payment_status" id="payment_status"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="pending" {{ old('payment_status', $booking->payment_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="settlement" {{ old('payment_status', $booking->payment_status) == 'settlement' ? 'selected' : '' }}>Settlement</option>
                                <option value="expired" {{ old('payment_status', $booking->payment_status) == 'expired' ? 'selected' : '' }}>Expired</option>
                                <option value="cancel" {{ old('payment_status', $booking->payment_status) == 'cancel' ? 'selected' : '' }}>Cancel</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="bi bi-info-circle"></i>
                                Mengubah status ke 'Settlement' akan mengonfirmasi booking. Mengubah ke 'Cancel' atau 'Expired' akan membatalkan booking.
                            </p>
                        </div>
                    </div>

                    @if($booking->transaction)
                    <h3 class="text-lg font-semibold text-indigo-800 mt-8 mb-4 flex items-center">
                        <i class="bi bi-credit-card mr-2 text-indigo-600"></i>
                        Informasi Transaksi
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Order ID</label>
                            <div class="px-4 py-2 bg-gray-50 rounded-lg border border-gray-200 text-gray-700 font-mono">
                                {{ $booking->transaction->order_id }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status Transaksi</label>
                            <div class="px-4 py-2 bg-gray-50 rounded-lg border border-gray-200 text-gray-700">
                                @if(in_array($booking->transaction->transaction_status, ['settlement', 'capture']))
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <span class="w-1.5 h-1.5 inline-block bg-green-500 rounded-full mr-1.5"></span>
                                    {{ ucfirst($booking->transaction->transaction_status) }}
                                </span>
                                @elseif($booking->transaction->transaction_status == 'pending')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <span class="w-1.5 h-1.5 inline-block bg-yellow-500 rounded-full mr-1.5"></span>
                                    {{ ucfirst($booking->transaction->transaction_status) }}
                                </span>
                                @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <span class="w-1.5 h-1.5 inline-block bg-red-500 rounded-full mr-1.5"></span>
                                    {{ ucfirst($booking->transaction->transaction_status) }}
                                </span>
                                @endif
                            </div>
                        </div>
                        @if($booking->payment_method == 'online')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Payment Type</label>
                            <div class="px-4 py-2 bg-gray-50 rounded-lg border border-gray-200 text-gray-700">
                                {{ ucfirst($booking->transaction->payment_type ?? 'Tidak ada data') }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Transaksi</label>
                            <div class="px-4 py-2 bg-gray-50 rounded-lg border border-gray-200 text-gray-700">
                                @if($booking->transaction->transaction_time)
                                {{ \Carbon\Carbon::parse($booking->transaction->transaction_time)->format('d/m/Y H:i:s') }}
                                @else
                                Tidak ada data
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            <!-- Slot Booking Information -->
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-indigo-800 mb-4 flex items-center">
                    <i class="bi bi-clock mr-2 text-indigo-600"></i>
                    Booking Slots
                </h3>

                <div class="bg-gray-50 rounded-lg border border-gray-200 overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Slot ID
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Waktu
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($booking->slots as $slot)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #{{ $slot->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }} -
                                    {{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($slot->status == 'booked')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Booked
                                    </span>
                                    @elseif($slot->status == 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                    @elseif($slot->status == 'cancelled')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Cancelled
                                    </span>
                                    @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ ucfirst($slot->status) }}
                                    </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    Tidak ada data slot booking
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <p class="text-xs text-gray-500 mt-2">
                    <i class="bi bi-info-circle"></i>
                    Status slot akan otomatis diperbarui saat mengubah status pembayaran booking.
                </p>
            </div>

            <!-- Notes Section -->
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-indigo-800 mb-4 flex items-center">
                    <i class="bi bi-sticky mr-2 text-indigo-600"></i>
                    Catatan Admin
                </h3>
                <div>
                    <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan Internal</label>
                    <textarea name="admin_notes" id="admin_notes" rows="3"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('admin_notes', $booking->admin_notes ?? '') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="bi bi-info-circle"></i>
                        Catatan ini hanya akan terlihat oleh admin, tidak oleh pelanggan.
                    </p>
                </div>
            </div>

            <div class="flex justify-end mt-8">
                <button type="submit" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-lg text-white font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-sm transition-colors duration-150 ease-in-out">
                    <i class="bi bi-save mr-2"></i>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <!-- Danger Zone -->
    <div class="mt-8 bg-white rounded-xl border border-red-200 p-6 shadow-sm">
        <h3 class="text-lg font-semibold text-red-700 mb-4 flex items-center">
            <i class="bi bi-exclamation-triangle mr-2 text-red-600"></i>
            Danger Zone
        </h3>
        <p class="text-gray-600 mb-6">Tindakan di bawah ini tidak dapat dibatalkan. Harap hati-hati.</p>

        <form action="{{ route('admin.bookings.destroy', $booking->id) }}" method="POST"
            onsubmit="return confirm('Anda yakin ingin menghapus booking ini? Tindakan ini tidak dapat dibatalkan.')">
            @csrf
            @method('DELETE')
            <button type="submit"
                class="inline-flex items-center px-4 py-2 bg-red-100 border border-red-300 rounded-lg text-sm font-medium text-red-700 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 shadow-sm transition-colors duration-150 ease-in-out">
                <i class="bi bi-trash mr-2"></i>
                Hapus Booking
            </button>
        </form>
    </div>

    <!-- Activity Log -->
    <div class="mt-8 bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
        <h3 class="text-lg font-semibold text-indigo-800 mb-4 flex items-center">
            <i class="bi bi-activity mr-2 text-indigo-600"></i>
            Riwayat Aktivitas
        </h3>

        <div class="relative pl-8 border-l-2 border-indigo-100 space-y-6">
            <!-- Payment Status History -->
            @if($booking->transaction)
            <div class="relative">

                <div class="mb-1 text-sm text-indigo-600 font-medium">Transaction Created</div>
                <div class="text-xs text-gray-500">
                    {{ \Carbon\Carbon::parse($booking->transaction->created_at)->format('d/m/Y H:i:s') }}
                </div>
                <div class="mt-2 text-sm text-gray-600">
                    Order ID: {{ $booking->transaction->order_id }}
                </div>
            </div>
            @endif

            <!-- Booking Creation -->
            <div class="relative">

                <div class="mb-1 text-sm text-green-600 font-medium">Booking Created</div>
                <div class="text-xs text-gray-500">
                    {{ \Carbon\Carbon::parse($booking->created_at)->format('d/m/Y H:i:s') }}
                </div>
                <div class="mt-2 text-sm text-gray-600">
                    Status: {{ ucfirst($booking->payment_status) }}
                </div>
            </div>

            <!-- Last Update -->
            @if($booking->updated_at && $booking->updated_at->gt($booking->created_at))
            <div class="relative">

                <div class="mb-1 text-sm text-blue-600 font-medium">Last Updated</div>
                <div class="text-xs text-gray-500">
                    {{ \Carbon\Carbon::parse($booking->updated_at)->format('d/m/Y H:i:s') }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Add confirmation for status changes
    document.getElementById('payment_status').addEventListener('change', function() {
        const selectedStatus = this.value;
        const currentStatus = '{{ $booking->payment_status }}';

        if (selectedStatus !== currentStatus) {
            if (selectedStatus === 'settlement') {
                if (!confirm('Mengubah status menjadi Settlement akan mengonfirmasi booking ini. Lanjutkan?')) {
                    this.value = currentStatus;
                }
            } else if (['cancel', 'expired'].includes(selectedStatus)) {
                if (!confirm('Mengubah status menjadi ' + selectedStatus + ' akan membatalkan booking ini. Lanjutkan?')) {
                    this.value = currentStatus;
                }
            }
        }
    });
</script>
@endsection
@extends('layouts.admin')

@section('content')
<div class="bg-gradient-to-b from-gray-50 to-white p-6 rounded-xl shadow-lg">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-indigo-800">Detail Booking #{{ $booking->id }}</h2>
            <p class="text-gray-500 mt-1">
                Dibuat pada: {{ $booking->created_at->format('d M Y, H:i') }}
            </p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.bookings.edit', $booking->id) }}"
                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors duration-150 ease-in-out">
                <i class="bi bi-pencil mr-2"></i>Edit Booking
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
                        @if($booking->payment_status == 'settlement')
                        Pembayaran telah selesai dan booking telah dikonfirmasi
                        @elseif($booking->payment_status == 'pending')
                        Menunggu pembayaran dari pelanggan
                        @elseif($booking->payment_status == 'expired')
                        Batas waktu pembayaran telah berakhir
                        @else
                        Booking telah dibatalkan
                        @endif
                    </p>
                </div>
            </div>

            <div class="flex space-x-3">
                @if($booking->payment_status == 'pending')
                <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST" class="inline-block">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="payment_status" value="settlement">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg shadow-sm transition-colors duration-150 ease-in-out"
                        onclick="return confirm('Konfirmasi pembayaran booking ini?')">
                        <i class="bi bi-check-circle mr-2"></i>Konfirmasi Pembayaran
                    </button>
                </form>
                @endif

                @if($booking->payment_status == 'pending')
                <form action="{{ route('admin.bookings.cancel', $booking->id) }}" method="POST" class="inline-block">
                    @csrf
                    @method('PUT')
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg shadow-sm transition-colors duration-150 ease-in-out"
                        onclick="return confirm('Apakah Anda yakin ingin membatalkan booking ini?')">
                        <i class="bi bi-x-circle mr-2"></i>Batalkan Booking
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Informasi Booking -->
            <div>
                <h3 class="text-lg font-semibold text-indigo-800 mb-4 flex items-center">
                    <i class="bi bi-calendar-event mr-2 text-indigo-600"></i>
                    Informasi Booking
                </h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ID Booking</label>
                        <div class="px-4 py-2 bg-gray-50 rounded-lg border border-gray-200 text-gray-700 font-medium">
                            #{{ $booking->id }}
                        </div>
                    </div>

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
                        <label class="block text-sm font-medium text-gray-700 mb-1">Durasi</label>
                        <div class="px-4 py-2 bg-gray-50 rounded-lg border border-gray-200 text-gray-700">
                            {{ $booking->duration_hours }} jam
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

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pembuatan</label>
                        <div class="px-4 py-2 bg-gray-50 rounded-lg border border-gray-200 text-gray-700">
                            {{ $booking->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Pelanggan -->
            <div>
                <h3 class="text-lg font-semibold text-indigo-800 mb-4 flex items-center">
                    <i class="bi bi-person mr-2 text-indigo-600"></i>
                    Informasi Pelanggan
                </h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pelanggan</label>
                        <div class="px-4 py-2 bg-gray-50 rounded-lg border border-gray-200 text-gray-700 font-medium">
                            {{ $booking->customer_name }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Pelanggan</label>
                        <div class="px-4 py-2 bg-gray-50 rounded-lg border border-gray-200 text-gray-700">
                            <a href="mailto:{{ $booking->customer_email }}" class="text-indigo-600 hover:text-indigo-800">
                                {{ $booking->customer_email }}
                            </a>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                        <div class="px-4 py-2 bg-gray-50 rounded-lg border border-gray-200 text-gray-700">
                            <a href="tel:{{ $booking->customer_phone }}" class="text-indigo-600 hover:text-indigo-800">
                                {{ $booking->customer_phone }}
                            </a>
                        </div>
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
                                {{ \Carbon\Carbon::parse($slot->slot_time)->format('H:i') }}
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
        </div>
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

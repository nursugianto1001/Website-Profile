@extends('layouts.admin')

@section('content')
<div class="bg-gradient-to-b from-gray-50 to-white p-6 rounded-xl shadow-lg">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-indigo-800">Detail Booking #{{ $booking->id }}</h2>
            <p class="text-gray-500 mt-1">Informasi lengkap reservasi lapangan</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.bookings.edit', $booking->id) }}"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-indigo-700 transition-colors duration-150 ease-in-out">
                <i class="bi bi-pencil mr-2"></i>Edit Booking
            </a>
            <a href="{{ route('admin.bookings.index') }}"
                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors duration-150 ease-in-out">
                <i class="bi bi-arrow-left mr-2"></i>Kembali ke Daftar
            </a>
        </div>
    </div>

    <!-- Status Bar -->
    <div class="mb-8 bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between">
            <div class="flex items-center mb-4 sm:mb-0">
                <div class="mr-6">
                    @if($booking->payment_status == 'settlement')
                    <div class="h-16 w-16 rounded-full bg-green-100 flex items-center justify-center">
                        <i class="bi bi-check-circle text-3xl text-green-600"></i>
                    </div>
                    @elseif($booking->payment_status == 'pending')
                    <div class="h-16 w-16 rounded-full bg-yellow-100 flex items-center justify-center">
                        <i class="bi bi-hourglass-split text-3xl text-yellow-600"></i>
                    </div>
                    @elseif($booking->payment_status == 'expired')
                    <div class="h-16 w-16 rounded-full bg-red-100 flex items-center justify-center">
                        <i class="bi bi-x-circle text-3xl text-red-600"></i>
                    </div>
                    @else
                    <div class="h-16 w-16 rounded-full bg-gray-100 flex items-center justify-center">
                        <i class="bi bi-dash-circle text-3xl text-gray-600"></i>
                    </div>
                    @endif
                </div>
                <div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-1">
                        Booking Code: <span class="text-indigo-600">{{ $booking->booking_code }}</span>
                    </h3>
                    <p class="text-lg font-medium
                        @if($booking->payment_status == 'settlement') text-green-600 
                        @elseif($booking->payment_status == 'pending') text-yellow-600
                        @elseif($booking->payment_status == 'expired') text-red-600
                        @else text-gray-600 @endif">
                        Status: {{ ucfirst($booking->payment_status) }}
                    </p>
                    <p class="text-sm text-gray-500">
                        Dibuat pada {{ \Carbon\Carbon::parse($booking->created_at)->format('d/m/Y H:i:s') }}
                    </p>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="flex space-x-2">
                @if($booking->payment_status == 'pending')
                <form action="{{ route('admin.bookings.update-status', $booking->id) }}" method="POST" class="inline">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="settlement">
                    <button type="submit"
                        class="inline-flex items-center px-3 py-2 bg-green-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-green-700 transition-colors"
                        onclick="return confirm('Konfirmasi pembayaran untuk booking ini?')">
                        <i class="bi bi-check-circle mr-1"></i>Konfirmasi
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Booking Information -->
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-indigo-800 mb-6 flex items-center">
                <i class="bi bi-calendar-event mr-2 text-indigo-600"></i>
                Informasi Booking
            </h3>

            <div class="space-y-4">
                <div class="flex justify-between border-b border-gray-100 pb-3">
                    <span class="text-sm font-medium text-gray-600">Lapangan:</span>
                    <span class="text-sm text-gray-900 font-semibold">{{ $booking->field->name }}</span>
                </div>

                <div class="flex justify-between border-b border-gray-100 pb-3">
                    <span class="text-sm font-medium text-gray-600">Tanggal:</span>
                    <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</span>
                </div>

                <div class="flex justify-between border-b border-gray-100 pb-3">
                    <span class="text-sm font-medium text-gray-600">Waktu:</span>
                    <span class="text-sm text-gray-900">
                        {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} -
                        {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                    </span>
                </div>

                <div class="flex justify-between border-b border-gray-100 pb-3">
                    <span class="text-sm font-medium text-gray-600">Durasi:</span>
                    <span class="text-sm text-gray-900">{{ $booking->duration_hours }} jam</span>
                </div>

                <div class="flex justify-between border-b border-gray-100 pb-3">
                    <span class="text-sm font-medium text-gray-600">Payment Method:</span>
                    <span class="text-sm text-gray-900">
                        @if($booking->payment_method == 'online')
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <i class="bi bi-credit-card mr-1"></i> Online Payment
                        </span>
                        @else
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="bi bi-cash mr-1"></i> Cash
                        </span>
                        @endif
                    </span>
                </div>

                <div class="flex justify-between pt-3">
                    <span class="text-lg font-semibold text-gray-900">Total Biaya:</span>
                    <span class="text-lg font-bold text-indigo-600">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-indigo-800 mb-6 flex items-center">
                <i class="bi bi-person mr-2 text-indigo-600"></i>
                Informasi Pelanggan
            </h3>

            <div class="space-y-4">
                <div class="flex items-center space-x-4">
                    <div class="h-12 w-12 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold text-lg">
                        {{ substr($booking->customer_name, 0, 1) }}
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900">{{ $booking->customer_name }}</h4>
                        <p class="text-sm text-gray-500">Pelanggan</p>
                    </div>
                </div>

                <div class="space-y-3 pt-4 border-t border-gray-100">
                    <div class="flex items-center space-x-3">
                        <i class="bi bi-envelope text-gray-400"></i>
                        <span class="text-sm text-gray-900">{{ $booking->customer_email ?: 'Tidak ada email' }}</span>
                    </div>

                    <div class="flex items-center space-x-3">
                        <i class="bi bi-telephone text-gray-400"></i>
                        <span class="text-sm text-gray-900">{{ $booking->customer_phone }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Price Breakdown -->
    <div class="mt-8 bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
        <h3 class="text-lg font-semibold text-indigo-800 mb-6 flex items-center">
            <i class="bi bi-calculator mr-2 text-indigo-600"></i>
            Rincian Harga (Dynamic Pricing)
        </h3>

        @if($booking->slots && $booking->slots->count() > 0)
        <!-- Booking Slots Breakdown -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <h4 class="text-sm font-medium text-gray-700 mb-3">Berdasarkan Booking Slots:</h4>
            <div class="space-y-2">
                @foreach($booking->slots as $slot)
                <div class="flex justify-between items-center py-2 border-b border-gray-200 last:border-b-0">
                    <div>
                        <span class="text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($slot->slot_time)->format('H:i') }}-{{ \Carbon\Carbon::parse($slot->slot_time)->addHour()->format('H:i') }}
                        </span>
                        @php
                        $hour = \Carbon\Carbon::parse($slot->slot_time)->format('H');
                        if($hour >= 6 && $hour < 12) {
                            $period='Pagi' ;
                            $periodClass='bg-yellow-100 text-yellow-800' ;
                            } elseif($hour>= 12 && $hour < 17) {
                                $period='Siang' ;
                                $periodClass='bg-green-100 text-green-800' ;
                                } else {
                                $period='Malam' ;
                                $periodClass='bg-purple-100 text-purple-800' ;
                                }
                                @endphp
                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $periodClass }}">
                                {{ $period }}
                                </span>
                    </div>
                    <span class="text-sm font-medium text-gray-900">
                        Rp {{ number_format($slot->price_per_slot ?? 0, 0, ',', '.') }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <!-- Fallback: Calculate from start/end time -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <h4 class="text-sm font-medium text-gray-700 mb-3">Estimasi Berdasarkan Waktu Booking:</h4>
            <div class="space-y-2">
                @php
                $startHour = \Carbon\Carbon::parse($booking->start_time)->format('H');
                $endHour = \Carbon\Carbon::parse($booking->end_time)->format('H');
                $totalEstimated = 0;
                @endphp
                @for($hour = $startHour; $hour < $endHour; $hour++)
                    @php
                    $nextHour=$hour + 1;
                    // Calculate price based on time slot
                    if($hour>= 6 && $hour < 12) {
                        $slotPrice=40000;
                        $period='Pagi' ;
                        $periodClass='bg-yellow-100 text-yellow-800' ;
                        } elseif($hour>= 12 && $hour < 17) {
                            $slotPrice=25000;
                            $period='Siang' ;
                            $periodClass='bg-green-100 text-green-800' ;
                            } else {
                            $slotPrice=60000;
                            $period='Malam' ;
                            $periodClass='bg-purple-100 text-purple-800' ;
                            }
                            $totalEstimated +=$slotPrice;
                            @endphp
                            <div class="flex justify-between items-center py-2 border-b border-gray-200 last:border-b-0">
                            <div>
                                <span class="text-sm text-gray-900">
                                    {{ sprintf('%02d:00-%02d:00', $hour, $nextHour) }}
                                </span>
                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $periodClass }}">
                                    {{ $period }}
                                </span>
                            </div>
                            <span class="text-sm font-medium text-gray-900">
                                Rp {{ number_format($slotPrice, 0, ',', '.') }}
                            </span>
            </div>
            @endfor
        </div>

        @if($totalEstimated != $booking->total_price)
        <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
            <p class="text-xs text-yellow-700">
                <i class="bi bi-info-circle mr-1"></i>
                Harga estimasi (Rp {{ number_format($totalEstimated, 0, ',', '.') }}) berbeda dengan total booking.
                Ini mungkin karena booking dibuat sebelum sistem dynamic pricing.
            </p>
        </div>
        @endif
    </div>
    @endif

    <!-- Total -->
    <div class="border-t border-gray-200 pt-4">
        <div class="flex justify-between items-center">
            <span class="text-xl font-semibold text-gray-900">Total Biaya:</span>
            <span class="text-2xl font-bold text-indigo-600">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
        </div>
    </div>
</div>

<!-- Booking Slots Detail -->
<div class="mt-8 bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
    <h3 class="text-lg font-semibold text-indigo-800 mb-6 flex items-center">
        <i class="bi bi-clock mr-2 text-indigo-600"></i>
        Detail Booking Slots
    </h3>

    @if($booking->slots && $booking->slots->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Slot ID
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Waktu Slot
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Harga per Slot
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($booking->slots as $slot)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        #{{ $slot->id }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ \Carbon\Carbon::parse($slot->slot_time)->format('H:i') }} -
                        {{ \Carbon\Carbon::parse($slot->slot_time)->addHour()->format('H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        Rp {{ number_format($slot->price_per_slot ?? 0, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @if($slot->status == 'booked')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <span class="w-1.5 h-1.5 mr-1.5 bg-green-500 rounded-full"></span>
                            Booked
                        </span>
                        @elseif($slot->status == 'pending')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <span class="w-1.5 h-1.5 mr-1.5 bg-yellow-500 rounded-full"></span>
                            Pending
                        </span>
                        @elseif($slot->status == 'cancelled')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            <span class="w-1.5 h-1.5 mr-1.5 bg-red-500 rounded-full"></span>
                            Cancelled
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            <span class="w-1.5 h-1.5 mr-1.5 bg-gray-500 rounded-full"></span>
                            {{ ucfirst($slot->status) }}
                        </span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="text-center py-8">
        <div class="text-gray-400 text-4xl mb-4">
            <i class="bi bi-clock-history"></i>
        </div>
        <h4 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data booking slots</h4>
        <p class="text-sm text-gray-500">Booking ini mungkin dibuat sebelum sistem slot diimplementasikan</p>
    </div>
    @endif
</div>

<!-- Transaction Information -->
@if($booking->transaction)
<div class="mt-8 bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
    <h3 class="text-lg font-semibold text-indigo-800 mb-6 flex items-center">
        <i class="bi bi-credit-card mr-2 text-indigo-600"></i>
        Informasi Transaksi
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-4">
            <div class="flex justify-between border-b border-gray-100 pb-3">
                <span class="text-sm font-medium text-gray-600">Order ID:</span>
                <span class="text-sm text-gray-900 font-mono">{{ $booking->transaction->order_id }}</span>
            </div>

            <div class="flex justify-between border-b border-gray-100 pb-3">
                <span class="text-sm font-medium text-gray-600">Payment Type:</span>
                <span class="text-sm text-gray-900">{{ ucfirst($booking->transaction->payment_type ?? 'N/A') }}</span>
            </div>

            <div class="flex justify-between border-b border-gray-100 pb-3">
                <span class="text-sm font-medium text-gray-600">Status Transaksi:</span>
                <span class="text-sm">
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
                </span>
            </div>
        </div>

        <div class="space-y-4">
            <div class="flex justify-between border-b border-gray-100 pb-3">
                <span class="text-sm font-medium text-gray-600">Gross Amount:</span>
                <span class="text-sm text-gray-900 font-semibold">Rp {{ number_format($booking->transaction->gross_amount, 0, ',', '.') }}</span>
            </div>

            @if($booking->transaction->payment_channel)
            <div class="flex justify-between border-b border-gray-100 pb-3">
                <span class="text-sm font-medium text-gray-600">Payment Channel:</span>
                <span class="text-sm text-gray-900">{{ ucfirst($booking->transaction->payment_channel) }}</span>
            </div>
            @endif

            <div class="flex justify-between border-b border-gray-100 pb-3">
                <span class="text-sm font-medium text-gray-600">Waktu Transaksi:</span>
                <span class="text-sm text-gray-900">
                    @if($booking->transaction->transaction_time)
                    {{ \Carbon\Carbon::parse($booking->transaction->transaction_time)->format('d/m/Y H:i:s') }}
                    @else
                    Tidak ada data
                    @endif
                </span>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Activity Timeline -->
<div class="mt-8 bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
    <h3 class="text-lg font-semibold text-indigo-800 mb-6 flex items-center">
        <i class="bi bi-activity mr-2 text-indigo-600"></i>
        Timeline Aktivitas
    </h3>

    <div class="relative">
        <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200"></div>

        <div class="relative space-y-6">
            <!-- Booking Created -->
            <div class="flex items-start space-x-4">
                <div class="relative z-10 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="bi bi-plus-circle text-green-600 text-sm"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <div class="text-sm font-medium text-gray-900">Booking Dibuat</div>
                    <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($booking->created_at)->format('d/m/Y H:i:s') }}</div>
                    <div class="text-xs text-gray-600 mt-1">
                        Booking dibuat dengan status: {{ ucfirst($booking->payment_status) }}
                    </div>
                </div>
            </div>

            <!-- Transaction Created -->
            @if($booking->transaction)
            <div class="flex items-start space-x-4">
                <div class="relative z-10 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="bi bi-credit-card text-blue-600 text-sm"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <div class="text-sm font-medium text-gray-900">Transaksi Dibuat</div>
                    <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($booking->transaction->created_at)->format('d/m/Y H:i:s') }}</div>
                    <div class="text-xs text-gray-600 mt-1">
                        Order ID: {{ $booking->transaction->order_id }}
                    </div>
                </div>
            </div>
            @endif

            <!-- Last Update -->
            @if($booking->updated_at && $booking->updated_at->gt($booking->created_at))
            <div class="flex items-start space-x-4">
                <div class="relative z-10 w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                    <i class="bi bi-arrow-repeat text-indigo-600 text-sm"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <div class="text-sm font-medium text-gray-900">Terakhir Diperbarui</div>
                    <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($booking->updated_at)->format('d/m/Y H:i:s') }}</div>
                    <div class="text-xs text-gray-600 mt-1">
                        Status: {{ ucfirst($booking->payment_status) }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
</div>
@endsection

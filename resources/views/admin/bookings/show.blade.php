@extends('layouts.admin')

@section('content')
<div class="bg-gradient-to-b from-gray-50 to-white p-8 rounded-xl shadow-lg ml-24">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-indigo-800">Detail Booking #{{ $booking->id }}</h2>
            <p class="text-gray-500 mt-1">
                Dibuat pada: {{ $booking->created_at->format('d M Y, H:i') }}
            </p>
        </div>
        <a href="{{ route('admin.bookings.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors duration-150 ease-in-out">
            <i class="bi bi-arrow-left mr-2"></i>Kembali ke Daftar
        </a>
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
            
            @if($booking->payment_status == 'pending')
            <form action="{{ route('admin.bookings.cancel', $booking->id) }}" method="POST">
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

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-indigo-800 mb-4 flex items-center">
                <i class="bi bi-calendar-event mr-2 text-indigo-600"></i>
                Informasi Booking
            </h3>
            <dl class="grid grid-cols-1 gap-y-4">
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500">ID Booking</dt>
                    <dd class="text-sm text-gray-900 col-span-2 font-medium">#{{ $booking->id }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500">Lapangan</dt>
                    <dd class="text-sm text-gray-900 col-span-2">{{ $booking->field->name }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500">Tanggal</dt>
                    <dd class="text-sm text-gray-900 col-span-2">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500">Waktu</dt>
                    <dd class="text-sm text-gray-900 col-span-2">
                        {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - 
                        {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                    </dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500">Durasi</dt>
                    <dd class="text-sm text-gray-900 col-span-2">{{ $booking->duration_hours }} jam</dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500">Total Harga</dt>
                    <dd class="text-sm text-gray-900 col-span-2 font-semibold">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500">Metode Pembayaran</dt>
                    <dd class="text-sm text-gray-900 col-span-2">
                        @if($booking->payment_method == 'online')
                            <span class="inline-flex items-center">
                                <i class="bi bi-credit-card mr-1 text-indigo-600"></i> Online Payment
                            </span>
                        @else
                            <span class="inline-flex items-center">
                                <i class="bi bi-cash mr-1 text-green-600"></i> Cash
                            </span>
                        @endif
                    </dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500">Tanggal Pembuatan</dt>
                    <dd class="text-sm text-gray-900 col-span-2">{{ $booking->created_at->format('d/m/Y H:i') }}</dd>
                </div>
            </dl>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-indigo-800 mb-4 flex items-center">
                <i class="bi bi-person mr-2 text-indigo-600"></i>
                Informasi Pelanggan
            </h3>
            <dl class="grid grid-cols-1 gap-y-4">
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500">Nama</dt>
                    <dd class="text-sm text-gray-900 col-span-2 font-medium">{{ $booking->customer_name }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                    <dd class="text-sm text-gray-900 col-span-2">
                        <a href="mailto:{{ $booking->customer_email }}" class="text-indigo-600 hover:text-indigo-800">
                            {{ $booking->customer_email }}
                        </a>
                    </dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500">Telepon</dt>
                    <dd class="text-sm text-gray-900 col-span-2">
                        <a href="tel:{{ $booking->customer_phone }}" class="text-indigo-600 hover:text-indigo-800">
                            {{ $booking->customer_phone }}
                        </a>
                    </dd>
                </div>
            </dl>
            
            @if($booking->transaction)
            <h3 class="text-lg font-semibold text-indigo-800 mt-8 mb-4 flex items-center">
                <i class="bi bi-credit-card mr-2 text-indigo-600"></i>
                Informasi Transaksi
            </h3>
            <dl class="grid grid-cols-1 gap-y-4">
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500">Order ID</dt>
                    <dd class="text-sm text-gray-900 col-span-2 font-mono bg-gray-50 px-2 py-1 rounded">{{ $booking->transaction->order_id }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500">Status Transaksi</dt>
                    <dd class="text-sm col-span-2">
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
                        @elseif(in_array($booking->transaction->transaction_status, ['cancel', 'deny', 'expire']))
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <span class="w-1.5 h-1.5 inline-block bg-red-500 rounded-full mr-1.5"></span>
                                {{ ucfirst($booking->transaction->transaction_status) }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <span class="w-1.5 h-1.5 inline-block bg-gray-500 rounded-full mr-1.5"></span>
                                {{ ucfirst($booking->transaction->transaction_status) }}
                            </span>
                        @endif
                    </dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500">Tipe Pembayaran</dt>
                    <dd class="text-sm text-gray-900 col-span-2 capitalize">{{ $booking->transaction->payment_type }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500">Waktu Transaksi</dt>
                    <dd class="text-sm text-gray-900 col-span-2">
                        {{ $booking->transaction->transaction_time ? $booking->transaction->transaction_time->format('d/m/Y H:i') : '-' }}
                    </dd>
                </div>
            </dl>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm mb-8">
        <h3 class="text-lg font-semibold text-indigo-800 mb-4 flex items-center">
            <i class="bi bi-clock mr-2 text-indigo-600"></i>
            Slot Booking
        </h3>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-100 rounded-lg">
                <thead class="bg-indigo-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-800 uppercase tracking-wider border-b">Waktu Slot</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-800 uppercase tracking-wider border-b">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($booking->slots as $slot)
                    <tr class="hover:bg-indigo-50/30 transition-colors duration-150 ease-in-out">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ \Carbon\Carbon::parse($slot->slot_time)->format('H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($slot->status == 'booked')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                    <span class="w-1.5 h-1.5 inline-block bg-green-500 rounded-full mr-1.5"></span>
                                    Booked
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                    <span class="w-1.5 h-1.5 inline-block bg-red-500 rounded-full mr-1.5"></span>
                                    {{ ucfirst($slot->status) }}
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@extends('layouts.admin')

@section('content')
<div class="bg-gradient-to-br from-white to-gray-50 p-6 md:p-8 rounded-xl shadow-lg ml-24 max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 border-b border-gray-200 pb-4">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-800 flex items-center">
            <span class="bg-blue-500 text-white p-2 rounded-full mr-3 shadow-md">
                <i class="bi bi-receipt text-lg"></i>
            </span>
            Detail Transaksi #{{ $transaction->id }}
        </h2>
        <a href="{{ route('admin.transactions.index') }}" class="mt-3 md:mt-0 flex items-center text-blue-600 hover:text-blue-800 transition duration-200 font-medium">
            <i class="bi bi-arrow-left-circle-fill mr-2"></i>Kembali ke Daftar Transaksi
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Panel Informasi Transaksi -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="bi bi-credit-card mr-2"></i>Informasi Transaksi
                </h3>
            </div>
            
            <div class="p-6">
                <dl class="divide-y divide-gray-200">
                    <div class="py-3 flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">ID Transaksi</dt>
                        <dd class="text-sm font-semibold text-gray-900 ml-3">{{ $transaction->id }}</dd>
                    </div>
                    <div class="py-3 flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Order ID</dt>
                        <dd class="text-sm font-semibold text-gray-900 ml-3">{{ $transaction->order_id }}</dd>
                    </div>
                    <div class="py-3 flex justify-between items-center">
                        <dt class="text-sm font-medium text-gray-500">Status Transaksi</dt>
                        <dd class="ml-3">
                            <span class="px-3 py-1 rounded-full text-xs font-medium 
                                @if(in_array($transaction->transaction_status, ['settlement', 'capture'])) bg-green-100 text-green-800 border border-green-200
                                @elseif($transaction->transaction_status == 'pending') bg-yellow-100 text-yellow-800 border border-yellow-200
                                @elseif(in_array($transaction->transaction_status, ['cancel', 'deny', 'expire'])) bg-red-100 text-red-800 border border-red-200
                                @else bg-gray-100 text-gray-800 border border-gray-200 @endif">
                                <i class="bi 
                                    @if(in_array($transaction->transaction_status, ['settlement', 'capture'])) bi-check-circle-fill 
                                    @elseif($transaction->transaction_status == 'pending') bi-hourglass-split
                                    @elseif(in_array($transaction->transaction_status, ['cancel', 'deny', 'expire'])) bi-x-circle-fill
                                    @else bi-question-circle @endif mr-1"></i>
                                {{ ucfirst($transaction->transaction_status) }}
                            </span>
                        </dd>
                    </div>
                    <div class="py-3 flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Metode Pembayaran</dt>
                        <dd class="text-sm font-semibold text-gray-900 ml-3 capitalize">{{ $transaction->payment_type }}</dd>
                    </div>
                    <div class="py-3 flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Jumlah</dt>
                        <dd class="text-sm font-bold text-blue-600 ml-3">Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}</dd>
                    </div>
                    <div class="py-3 flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Waktu Transaksi</dt>
                        <dd class="text-sm font-semibold text-gray-900 ml-3">
                            @if($transaction->transaction_time)
                                <span class="flex items-center">
                                    <i class="bi bi-clock mr-1"></i>
                                    {{ $transaction->transaction_time->format('d/m/Y H:i') }}
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </dd>
                    </div>
                    <div class="py-3 flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Tanggal Pembuatan</dt>
                        <dd class="text-sm font-semibold text-gray-900 ml-3">
                            <span class="flex items-center">
                                <i class="bi bi-calendar-event mr-1"></i>
                                {{ $transaction->created_at->format('d/m/Y H:i') }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
        
        <!-- Panel Informasi Booking -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
            <div class="bg-gradient-to-r from-indigo-600 to-indigo-800 px-6 py-4">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="bi bi-calendar-check mr-2"></i>Informasi Booking
                </h3>
            </div>
            
            <div class="p-6">
                @if($transaction->booking)
                    <dl class="divide-y divide-gray-200">
                        <div class="py-3 flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">ID Booking</dt>
                            <dd class="text-sm font-semibold text-gray-900 ml-3">{{ $transaction->booking->id }}</dd>
                        </div>
                        <div class="py-3 flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Lapangan</dt>
                            <dd class="text-sm font-semibold text-gray-900 ml-3">{{ $transaction->booking->field->name }}</dd>
                        </div>
                        <div class="py-3 flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Nama Pelanggan</dt>
                            <dd class="text-sm font-semibold text-gray-900 ml-3">{{ $transaction->booking->customer_name }}</dd>
                        </div>
                        <div class="py-3 flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="text-sm font-semibold text-gray-900 ml-3 flex items-center">
                                <i class="bi bi-envelope mr-1 text-gray-400"></i>
                                {{ $transaction->booking->customer_email }}
                            </dd>
                        </div>
                        <div class="py-3 flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Telepon</dt>
                            <dd class="text-sm font-semibold text-gray-900 ml-3 flex items-center">
                                <i class="bi bi-telephone mr-1 text-gray-400"></i>
                                {{ $transaction->booking->customer_phone }}
                            </dd>
                        </div>
                        <div class="py-3 flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Tanggal Booking</dt>
                            <dd class="text-sm font-semibold text-gray-900 ml-3 flex items-center">
                                <i class="bi bi-calendar3 mr-1 text-gray-400"></i>
                                {{ \Carbon\Carbon::parse($transaction->booking->booking_date)->format('d/m/Y') }}
                            </dd>
                        </div>
                        <div class="py-3 flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Waktu</dt>
                            <dd class="text-sm font-semibold text-gray-900 ml-3 flex items-center">
                                <i class="bi bi-clock-history mr-1 text-gray-400"></i>
                                {{ \Carbon\Carbon::parse($transaction->booking->start_time)->format('H:i') }} - 
                                {{ \Carbon\Carbon::parse($transaction->booking->end_time)->format('H:i') }}
                            </dd>
                        </div>
                    </dl>
                    <div class="mt-6 border-t border-gray-200 pt-6">
                        <a href="{{ route('admin.bookings.show', $transaction->booking->id) }}" 
                           class="w-full flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-3 rounded-lg text-sm font-medium transition duration-200 shadow-md">
                            <i class="bi bi-eye mr-2"></i>Lihat Detail Booking
                        </a>
                    </div>
                @else
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-yellow-700 flex items-center">
                        <i class="bi bi-exclamation-triangle-fill mr-3 text-yellow-500 text-lg"></i>
                        <span>Booking terkait tidak ditemukan atau telah dihapus.</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Tombol Aksi -->
    <div class="mt-8 flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4">
        <button onclick="window.print()" class="flex items-center justify-center bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200">
            <i class="bi bi-printer mr-2"></i>Cetak Detail
        </button>
        
        @if(in_array($transaction->transaction_status, ['pending']))
            <button class="flex items-center justify-center bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200">
                <i class="bi bi-x-circle mr-2"></i>Batalkan Transaksi
            </button>
        @endif
    </div>
</div>
@endsection
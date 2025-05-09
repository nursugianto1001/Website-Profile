<!-- resources/views/admin/bookings/index.blade.php -->
@extends('layouts.admin')

@section('content')
<div class="bg-gradient-to-b from-gray-50 to-white p-6 rounded-xl shadow-lg">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-indigo-800">Daftar Booking</h2>
            <p class="text-gray-500 mt-1">Kelola semua booking lapangan dalam satu tempat</p>
        </div>
        <div class="w-full md:w-auto">
            <form action="{{ route('admin.bookings.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-grow">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="bi bi-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" placeholder="Cari nama, id..." 
                        class="pl-10 w-full rounded-lg border-gray-200 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                        value="{{ request('search') }}">
                </div>
                <select name="status" onchange="this.form.submit()" 
                    class="rounded-lg border-gray-200 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 bg-white">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="settlement" {{ request('status') == 'settlement' ? 'selected' : '' }}>Settlement</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                    <option value="cancel" {{ request('status') == 'cancel' ? 'selected' : '' }}>Cancel</option>
                </select>
            </form>
        </div>
    </div>

    <div class="overflow-hidden bg-white rounded-xl shadow-md">
        <div class="inline-block min-w-full align-middle">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-indigo-50">
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-indigo-800 uppercase tracking-wider">ID</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-indigo-800 uppercase tracking-wider">Lapangan</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-indigo-800 uppercase tracking-wider">Nama Pelanggan</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-indigo-800 uppercase tracking-wider">Tanggal</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-indigo-800 uppercase tracking-wider">Waktu</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-indigo-800 uppercase tracking-wider">Total</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-indigo-800 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-indigo-800 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($bookings as $booking)
                    <tr class="hover:bg-indigo-50/30 transition-colors duration-150 ease-in-out">
                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-indigo-800">#{{ $booking->id }}</td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">{{ $booking->field->name }}</td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">{{ $booking->customer_name }}</td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}</td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - 
                            {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-700">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm">
                            @if($booking->payment_status == 'settlement')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                    <span class="w-1.5 h-1.5 inline-block bg-green-500 rounded-full mr-1.5"></span>
                                    Lunas
                                </span>
                            @elseif($booking->payment_status == 'pending')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                    <span class="w-1.5 h-1.5 inline-block bg-yellow-500 rounded-full mr-1.5"></span>
                                    Menunggu
                                </span>
                            @elseif($booking->payment_status == 'expired')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                    <span class="w-1.5 h-1.5 inline-block bg-red-500 rounded-full mr-1.5"></span>
                                    Kadaluarsa
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                    <span class="w-1.5 h-1.5 inline-block bg-gray-500 rounded-full mr-1.5"></span>
                                    {{ ucfirst($booking->payment_status) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-3">
                                <a href="{{ route('admin.bookings.show', $booking->id) }}" 
                                   class="inline-flex items-center text-indigo-600 hover:text-indigo-900 transition-colors duration-150 ease-in-out">
                                    <span class="flex items-center justify-center bg-indigo-100 rounded-lg w-8 h-8">
                                        <i class="bi bi-eye text-indigo-600"></i>
                                    </span>
                                </a>
                                
                                @if($booking->payment_status == 'pending')
                                <form action="{{ route('admin.bookings.cancel', $booking->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" 
                                            class="inline-flex items-center text-red-600 hover:text-red-900 transition-colors duration-150 ease-in-out" 
                                            onclick="return confirm('Apakah Anda yakin ingin membatalkan booking ini?')">
                                        <span class="flex items-center justify-center bg-red-100 rounded-lg w-8 h-8">
                                            <i class="bi bi-x-circle text-red-600"></i>
                                        </span>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    @if(count($bookings) == 0)
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="bi bi-calendar-x text-4xl text-gray-300 mb-2"></i>
                                <p>Tidak ada data booking yang ditemukan</p>
                                <p class="text-sm text-gray-400 mt-1">Coba ubah filter pencarian Anda</p>
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $bookings->links() }}
    </div>
</div>
@endsection
@extends('layouts.admin') 

@section('content')
<div class="bg-gradient-to-b from-gray-50 to-white p-6 rounded-xl shadow-lg">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-indigo-800">Kelola semua booking lapangan dalam satu tempat</h2>
            <p class="text-gray-500 mt-1">Lihat dan kelola semua reservasi lapangan</p>
        </div>
        <a href="{{ route('admin.bookings.create') }}" 
           class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-indigo-700 transition-colors duration-150 ease-in-out">
            <i class="bi bi-plus-circle mr-2"></i>Tambah Booking Baru
        </a>
    </div>

    <!-- Bagian filter dan pencarian tetap dipertahankan -->
    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm mb-6">
        <form action="{{ route('admin.bookings.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Pencarian</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                       placeholder="Nama pelanggan atau ID">
            </div>
            <div>
                <label for="field_id" class="block text-sm font-medium text-gray-700 mb-1">Lapangan</label>
                <select name="field_id" id="field_id" 
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">Semua Lapangan</option>
                    <!-- Loop untuk opsi lapangan -->
                </select>
            </div>
            <div>
                <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                <input type="date" name="date" id="date" value="{{ request('date') }}" 
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status" 
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">Semua Status</option>
                    <option value="settlement" {{ request('status') == 'settlement' ? 'selected' : '' }}>Lunas</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
                </select>
            </div>
            <div class="md:col-span-4 flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-indigo-700">
                    <i class="bi bi-search mr-2"></i>Filter
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm overflow-x-auto">
        @if(count($bookings) > 0)
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lapangan</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pelanggan</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($bookings as $booking)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $booking->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $booking->field->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $booking->customer_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($booking->payment_status == 'settlement')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Lunas</span>
                        @elseif($booking->payment_status == 'pending')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu</span>
                        @elseif($booking->payment_status == 'expired')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Kadaluarsa</span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucfirst($booking->payment_status) }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.bookings.show', $booking->id) }}" 
                               class="text-indigo-600 hover:text-indigo-900" title="Lihat Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.bookings.edit', $booking->id) }}" 
                               class="text-yellow-600 hover:text-yellow-900" title="Edit Booking">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.bookings.destroy', $booking->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" 
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus booking ini?')" title="Hapus Booking">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Pagination Section -->
        <div class="mt-6">
            {{ $bookings->appends(request()->query())->links('pagination::tailwind') }}
        </div>
        @else
        <div class="text-center py-10">
            <div class="text-gray-500 mb-3">
                <i class="bi bi-calendar-x text-4xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900">Tidak ada data booking yang ditemukan</h3>
            <p class="mt-1 text-sm text-gray-500">Coba ubah filter pencarian Anda</p>
        </div>
        @endif
    </div>
</div>
@endsection
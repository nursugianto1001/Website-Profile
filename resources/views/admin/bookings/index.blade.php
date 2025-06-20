@extends('layouts.admin')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 w-full">
        <div class="container mx-auto px-2 sm:px-4 lg:px-8 py-4 lg:py-8 max-w-7xl">
            {{-- Header Section - Mobile Responsive --}}
            <div class="bg-white rounded-xl lg:rounded-2xl shadow-lg lg:shadow-xl p-4 sm:p-6 lg:p-8 mb-6 lg:mb-8">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h2 class="text-xl sm:text-2xl lg:text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                            Kelola Booking Lapangan
                        </h2>
                        <p class="text-sm sm:text-base text-gray-600 mt-1 lg:mt-2">Lihat dan kelola semua reservasi lapangan</p>
                    </div>
                    <a href="{{ route('admin.bookings.create') }}" 
                       class="inline-flex items-center px-3 sm:px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs sm:text-sm text-white hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-800 focus:ring ring-indigo-300 transition ease-in-out duration-150 shadow-lg hover:shadow-xl">
                        <i class="bi bi-plus-circle mr-1 sm:mr-2"></i>
                        <span class="hidden sm:inline">Tambah Booking Baru</span>
                        <span class="sm:hidden">Tambah</span>
                    </a>
                </div>
            </div>

            {{-- Filter and Search Section - Mobile Responsive --}}
            <div class="bg-white rounded-xl lg:rounded-2xl shadow-lg lg:shadow-xl p-4 sm:p-6 mb-6 lg:mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Filter & Pencarian</h3>
                <form action="{{ route('admin.bookings.index') }}" method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                        <div>
                            <label for="search" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Pencarian</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" 
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm"
                                   placeholder="Nama pelanggan atau ID">
                        </div>
                        <div>
                            <label for="field_id" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Lapangan</label>
                            <select name="field_id" id="field_id" 
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                                <option value="">Semua Lapangan</option>
                                @foreach(\App\Models\Field::all() as $field)
                                    <option value="{{ $field->id }}" {{ request('field_id') == $field->id ? 'selected' : '' }}>
                                        {{ $field->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="date" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                            <input type="date" name="date" id="date" value="{{ request('date') }}" 
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                        </div>
                        <div>
                            <label for="status" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" id="status" 
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                                <option value="">Semua Status</option>
                                <option value="settlement" {{ request('status') == 'settlement' ? 'selected' : '' }}>Lunas</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-4 sm:justify-end">
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-indigo-700 transition-colors">
                            <i class="bi bi-search mr-2"></i>Filter
                        </button>
                        <a href="{{ route('admin.bookings.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-gray-700 transition-colors">
                            <i class="bi bi-arrow-clockwise mr-2"></i>Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Stats Cards - Mobile Responsive --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6 lg:mb-8">
                <div class="bg-white rounded-xl lg:rounded-2xl shadow-lg lg:shadow-xl p-4 sm:p-6 border-l-4 border-blue-500 group hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 p-2 sm:p-3 rounded-lg lg:rounded-xl bg-blue-100 group-hover:bg-blue-200 transition-colors">
                            <i class="bi bi-calendar-check text-blue-600 text-lg sm:text-xl"></i>
                        </div>
                        <div class="ml-3 sm:ml-4">
                            <h3 class="text-xs sm:text-sm text-gray-500 font-medium">Total Booking</h3>
                            <p class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-800">{{ $bookings->total() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl lg:rounded-2xl shadow-lg lg:shadow-xl p-4 sm:p-6 border-l-4 border-green-500 group hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 p-2 sm:p-3 rounded-lg lg:rounded-xl bg-green-100 group-hover:bg-green-200 transition-colors">
                            <i class="bi bi-check-circle text-green-600 text-lg sm:text-xl"></i>
                        </div>
                        <div class="ml-3 sm:ml-4">
                            <h3 class="text-xs sm:text-sm text-gray-500 font-medium">Lunas</h3>
                            <p class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-800">{{ $bookings->where('payment_status', 'settlement')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl lg:rounded-2xl shadow-lg lg:shadow-xl p-4 sm:p-6 border-l-4 border-yellow-500 group hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 p-2 sm:p-3 rounded-lg lg:rounded-xl bg-yellow-100 group-hover:bg-yellow-200 transition-colors">
                            <i class="bi bi-hourglass-split text-yellow-600 text-lg sm:text-xl"></i>
                        </div>
                        <div class="ml-3 sm:ml-4">
                            <h3 class="text-xs sm:text-sm text-gray-500 font-medium">Menunggu</h3>
                            <p class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-800">{{ $bookings->where('payment_status', 'pending')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl lg:rounded-2xl shadow-lg lg:shadow-xl p-4 sm:p-6 border-l-4 border-red-500 group hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 p-2 sm:p-3 rounded-lg lg:rounded-xl bg-red-100 group-hover:bg-red-200 transition-colors">
                            <i class="bi bi-x-circle text-red-600 text-lg sm:text-xl"></i>
                        </div>
                        <div class="ml-3 sm:ml-4">
                            <h3 class="text-xs sm:text-sm text-gray-500 font-medium">Kadaluarsa</h3>
                            <p class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-800">{{ $bookings->where('payment_status', 'expired')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table Section - Mobile Responsive --}}
            <div class="bg-white rounded-xl lg:rounded-2xl shadow-lg lg:shadow-xl overflow-hidden">
                @if(count($bookings) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-indigo-50 to-purple-50">
                            <tr>
                                <th scope="col" class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-indigo-800 uppercase tracking-wider">ID</th>
                                <th scope="col" class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-indigo-800 uppercase tracking-wider">Pelanggan</th>
                                <th scope="col" class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-indigo-800 uppercase tracking-wider hidden md:table-cell">Lapangan</th>
                                <th scope="col" class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-indigo-800 uppercase tracking-wider">Tanggal & Waktu</th>
                                <th scope="col" class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-indigo-800 uppercase tracking-wider hidden lg:table-cell">Total & Breakdown</th>
                                <th scope="col" class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-indigo-800 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-3 sm:px-6 py-3 sm:py-4 text-right text-xs font-bold text-indigo-800 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($bookings as $booking)
                            <tr class="hover:bg-blue-50 transition-colors">
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm font-medium text-gray-900">
                                    #{{ $booking->id }}
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8 sm:h-10 sm:w-10">
                                            <div class="h-8 w-8 sm:h-10 sm:w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center text-white font-bold text-xs sm:text-sm">
                                                {{ substr($booking->customer_name, 0, 1) }}
                                            </div>
                                        </div>
                                        <div class="ml-2 sm:ml-4">
                                            <div class="text-xs sm:text-sm font-medium text-gray-900">
                                                {{ $booking->customer_name }}
                                            </div>
                                            <div class="text-xs text-gray-500 hidden sm:block">
                                                {{ $booking->customer_email ?? 'No email' }}
                                            </div>
                                            {{-- Mobile: Show field and total --}}
                                            <div class="md:hidden">
                                                <div class="text-xs text-gray-600 font-medium">{{ $booking->field->name }}</div>
                                                <div class="lg:hidden text-xs text-gray-500">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap hidden md:table-cell">
                                    <div class="text-xs sm:text-sm text-gray-900 font-medium">{{ $booking->field->name }}</div>
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                    <div class="text-xs sm:text-sm text-gray-900">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</div>
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap hidden lg:table-cell">
                                    <div class="text-xs sm:text-sm text-gray-900 font-medium">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                                    <div class="text-xs text-gray-500">{{ $booking->duration_hours }} jam</div>
                                    
                                    @if($booking->slots && $booking->slots->count() > 0)
                                    <!-- Show dynamic pricing breakdown -->
                                    <div class="mt-1">
                                        <button class="text-xs text-indigo-600 hover:text-indigo-800" 
                                                onclick="toggleBreakdown('breakdown-{{ $booking->id }}')"
                                                type="button">
                                            <i class="bi bi-eye text-xs mr-1"></i>Lihat Breakdown
                                        </button>
                                        <div id="breakdown-{{ $booking->id }}" class="hidden mt-2 p-2 bg-gray-50 rounded text-xs">
                                            @foreach($booking->slots->take(3) as $slot)
                                            <div class="flex justify-between">
                                                <span>{{ \Carbon\Carbon::parse($slot->slot_time)->format('H:i') }}:</span>
                                                <span>Rp {{ number_format($slot->price_per_slot ?? 0, 0, ',', '.') }}</span>
                                            </div>
                                            @endforeach
                                            @if($booking->slots->count() > 3)
                                            <div class="text-center text-gray-500 mt-1">
                                                +{{ $booking->slots->count() - 3 }} slot lainnya
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    @else
                                    <!-- Fallback for old bookings without slots -->
                                    <div class="text-xs text-gray-500">
                                        @php
                                            $startHour = \Carbon\Carbon::parse($booking->start_time)->format('H');
                                            $endHour = \Carbon\Carbon::parse($booking->end_time)->format('H');
                                            $avgPrice = $booking->total_price / ($endHour - $startHour);
                                        @endphp
                                        ~Rp {{ number_format($avgPrice, 0, ',', '.') }}/jam
                                    </div>
                                    @endif
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                    @if($booking->payment_status == 'settlement')
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Lunas</span>
                                    @elseif($booking->payment_status == 'pending')
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu</span>
                                    @elseif($booking->payment_status == 'expired')
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Kadaluarsa</span>
                                    @else
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucfirst($booking->payment_status) }}</span>
                                    @endif
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-right text-xs sm:text-sm font-medium">
                                    <div class="flex justify-end space-x-1 sm:space-x-2">
                                        <a href="{{ route('admin.bookings.show', $booking->id) }}" 
                                           class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 p-1.5 sm:p-2 rounded-lg transition-colors duration-200" 
                                           title="Lihat Detail">
                                            <i class="bi bi-eye text-xs sm:text-sm"></i>
                                        </a>
                                        <a href="{{ route('admin.bookings.edit', $booking->id) }}" 
                                           class="text-yellow-600 hover:text-yellow-900 bg-yellow-50 hover:bg-yellow-100 p-1.5 sm:p-2 rounded-lg transition-colors duration-200" 
                                           title="Edit Booking">
                                            <i class="bi bi-pencil text-xs sm:text-sm"></i>
                                        </a>
                                        <form action="{{ route('admin.bookings.destroy', $booking->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-1.5 sm:p-2 rounded-lg transition-colors duration-200" 
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus booking ini?')" 
                                                    title="Hapus Booking">
                                                <i class="bi bi-trash text-xs sm:text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination Section - Mobile Responsive -->
                <div class="px-4 sm:px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $bookings->appends(request()->query())->links('pagination::tailwind') }}
                </div>
                @else
                <div class="text-center py-8 sm:py-12">
                    <div class="text-gray-400 text-4xl sm:text-6xl mb-4 sm:mb-6">
                        <i class="bi bi-calendar-x"></i>
                    </div>
                    <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Tidak ada data booking yang ditemukan</h3>
                    <p class="text-sm text-gray-500 mb-4 sm:mb-6">Coba ubah filter pencarian Anda atau tambah booking baru</p>
                    <a href="{{ route('admin.bookings.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-indigo-700 transition-colors">
                        <i class="bi bi-plus-circle mr-2"></i>
                        Tambah Booking Pertama
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection

<script>
function toggleBreakdown(elementId) {
    const element = document.getElementById(elementId);
    if (element.classList.contains('hidden')) {
        element.classList.remove('hidden');
    } else {
        element.classList.add('hidden');
    }
}
</script>

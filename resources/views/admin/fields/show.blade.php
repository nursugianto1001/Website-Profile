@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Detail Lapangan</h1>
            <p class="text-gray-500 mt-1">Informasi lengkap untuk "{{ $field->name }}"</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.fields.index') }}" class="flex items-center px-3 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 text-sm font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
            <a href="{{ route('admin.fields.edit', $field->id) }}" class="flex items-center px-3 py-2 bg-blue-600 border border-transparent rounded-lg text-white text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit
            </a>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-6">
        <!-- Main Info Column -->
        <div class="col-span-12 lg:col-span-7">
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="border-b border-gray-200">
                    <div class="flex justify-between items-center p-6">
                        <h2 class="text-lg font-semibold text-gray-800">Informasi Lapangan</h2>
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $field->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $field->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    <dl class="grid grid-cols-3 gap-x-4 gap-y-6">
                        <dt class="text-sm font-medium text-gray-500">ID Lapangan</dt>
                        <dd class="text-sm text-gray-900 col-span-2 font-mono bg-gray-100 px-2 py-1 rounded">{{ $field->id }}</dd>
                        
                        <dt class="text-sm font-medium text-gray-500">Nama</dt>
                        <dd class="text-sm text-gray-900 col-span-2 font-medium">{{ $field->name }}</dd>
                        
                        <dt class="text-sm font-medium text-gray-500">Harga per Jam</dt>
                        <dd class="text-sm text-gray-900 col-span-2 font-semibold text-green-600">
                            Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}
                        </dd>
                        
                        <dt class="text-sm font-medium text-gray-500">Jam Operasional</dt>
                        <dd class="text-sm text-gray-900 col-span-2">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ sprintf('%02d:00', $field->opening_hour) }} - {{ sprintf('%02d:00', $field->closing_hour) }}
                            </div>
                        </dd>
                        
                        <dt class="text-sm font-medium text-gray-500">Tanggal Dibuat</dt>
                        <dd class="text-sm text-gray-500 col-span-2">{{ $field->created_at->format('d M Y, H:i') }}</dd>
                        
                        <dt class="text-sm font-medium text-gray-500">Terakhir Diperbarui</dt>
                        <dd class="text-sm text-gray-500 col-span-2">{{ $field->updated_at->format('d M Y, H:i') }}</dd>
                    </dl>

                    @if($field->description)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h3 class="text-sm font-medium text-gray-900 mb-2">Deskripsi</h3>
                            <div class="prose prose-sm max-w-none text-gray-700">
                                {{ $field->description }}
                            </div>
                        </div>
                    @endif
                    
                    @if($field->image_url)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h3 class="text-sm font-medium text-gray-900 mb-3">Gambar Lapangan</h3>
                            <div class="relative rounded-lg overflow-hidden shadow-sm">
                                <img src="{{ $field->image_url }}" alt="{{ $field->name }}" class="w-full h-auto rounded-lg">
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Availability and Bookings -->
        <div class="col-span-12 lg:col-span-5 space-y-6">
            <!-- Availability Today Card -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="border-b border-gray-200">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-800">Ketersediaan Hari Ini</h2>
                        <p class="text-sm text-gray-500 mt-1">{{ now()->format('d M Y') }}</p>
                    </div>
                </div>
                
                <div class="p-6">
                    @php
                    $openingHour = $field->opening_hour ?? 8;
                    $closingHour = $field->closing_hour ?? 22;
                    $date = now()->format('Y-m-d');
                    $bookedSlots = App\Models\BookingSlot::join('bookings', 'booking_slots.booking_id', '=', 'bookings.id')
                    ->where('bookings.field_id', $field->id)
                    ->where('bookings.booking_date', $date)
                    ->whereNotIn('bookings.payment_status', ['expired', 'cancel'])
                    ->pluck('booking_slots.slot_time')
                    ->toArray();
                    @endphp

                    <div class="grid grid-cols-3 sm:grid-cols-4 gap-3">
                        @for($hour = $openingHour; $hour < $closingHour; $hour++)
                            @php
                            $slotTime=sprintf('%02d:00:00', $hour);
                            $isBooked=in_array($slotTime, $bookedSlots);
                            $isCurrentHour = (int)now()->format('H') === $hour;
                            $isPastHour = (int)now()->format('H') > $hour;
                            $statusClass = $isBooked 
                                ? 'bg-red-100 text-red-800 border-red-200'
                                : ($isPastHour 
                                    ? 'bg-gray-100 text-gray-500 border-gray-200'
                                    : 'bg-green-100 text-green-800 border-green-200');
                            $iconClass = $isBooked 
                                ? 'bi-x-circle-fill text-red-600'
                                : ($isPastHour 
                                    ? 'bi-dash-circle text-gray-500'
                                    : 'bi-check-circle-fill text-green-600');
                            @endphp
                            
                            <div class="relative">
                                <div class="p-3 rounded-lg border {{ $statusClass }} {{ $isCurrentHour ? 'ring-2 ring-blue-500' : '' }}">
                                    <div class="text-sm font-medium text-center">{{ sprintf('%02d:00', $hour) }}</div>
                                    <div class="flex justify-center mt-1">
                                        <i class="bi {{ $iconClass }}"></i>
                                    </div>
                                    <div class="text-xs text-center mt-1">
                                        {{ $isBooked ? 'Terisi' : ($isPastHour ? 'Lewat' : 'Kosong') }}
                                    </div>
                                </div>
                                @if($isCurrentHour)
                                    <div class="absolute -top-1 -right-1 h-3 w-3">
                                        <span class="flex h-3 w-3">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-blue-500"></span>
                                        </span>
                                    </div>
                                @endif
                            </div>
                        @endfor
                    </div>
                    
                    <div class="mt-4 flex justify-center space-x-6 text-xs">
                        <div class="flex items-center">
                            <span class="h-3 w-3 rounded-full bg-green-500 mr-1"></span>
                            <span class="text-gray-600">Tersedia</span>
                        </div>
                        <div class="flex items-center">
                            <span class="h-3 w-3 rounded-full bg-red-500 mr-1"></span>
                            <span class="text-gray-600">Terisi</span>
                        </div>
                        <div class="flex items-center">
                            <span class="h-3 w-3 rounded-full bg-gray-400 mr-1"></span>
                            <span class="text-gray-600">Lewat</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upcoming Bookings Card -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="border-b border-gray-200">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-800">Booking Mendatang</h2>
                        <p class="text-sm text-gray-500 mt-1">5 booking terakhir aktif</p>
                    </div>
                </div>
                
                <div class="p-6">
                    @php
                    $upcomingBookings = App\Models\Booking::where('field_id', $field->id)
                    ->where('booking_date', '>=', now()->format('Y-m-d'))
                    ->whereIn('payment_status', ['pending', 'settlement'])
                    ->orderBy('booking_date')
                    ->orderBy('start_time')
                    ->take(5)
                    ->get();
                    @endphp

                    @if($upcomingBookings->count() > 0)
                        <div class="space-y-4">
                            @foreach($upcomingBookings as $booking)
                                <div class="p-4 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors border border-gray-200">
                                    <div class="flex justify-between">
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $booking->customer_name }}</div>
                                            <div class="flex items-center mt-1 text-sm text-gray-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}
                                            </div>
                                            <div class="flex items-center mt-1 text-sm text-gray-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - 
                                                {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                                            </div>
                                        </div>
                                        <div>
                                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $booking->payment_status == 'settlement' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ $booking->payment_status == 'settlement' ? 'Lunas' : 'Menunggu' }}
                                            </span>
                                            @if(\Carbon\Carbon::parse($booking->booking_date)->isToday())
                                                <div class="mt-2 text-center">
                                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                                                        Hari Ini
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            @if($upcomingBookings->count() >= 5)
                                <div class="text-center pt-2">
                                    <a href="#" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                        Lihat Semua Booking →
                                    </a>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="py-8 flex flex-col items-center justify-center text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="font-medium">Tidak ada booking mendatang</p>
                            <p class="text-sm mt-1">Lapangan ini belum memiliki reservasi aktif</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
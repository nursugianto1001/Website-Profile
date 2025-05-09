@extends('layouts.admin')

@section('content')
<div class="bg-gradient-to-br from-white to-gray-50 p-6 md:p-8 rounded-xl shadow-lg ml-24 mb-8">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-3xl font-bold text-gray-800">Admin Dashboard</h2>
        <div class="hidden md:flex items-center space-x-2 bg-white py-2 px-4 rounded-lg shadow-sm">
            <i class="bi bi-calendar-event text-blue-600"></i>
            <span class="text-gray-700">{{ now()->format('l, d F Y') }}</span>
        </div>
    </div>

    {{-- Main Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <div class="bg-white rounded-2xl shadow-md hover:shadow-lg transition-all p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Facilities</p>
                    <h3 class="text-3xl font-extrabold text-gray-800">{{ \App\Models\Facility::count() }}</h3>
                </div>
                <div class="bg-blue-100 p-4 rounded-xl">
                    <i class="bi bi-building text-blue-600 text-2xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.facilities.index') }}" class="text-blue-600 text-sm font-medium hover:underline flex items-center">
                    Manage Facilities <i class="bi bi-arrow-right ml-1"></i>
                </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-md hover:shadow-lg transition-all p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Posters</p>
                    <h3 class="text-3xl font-extrabold text-gray-800">{{ \App\Models\Gallery::where('type', 'poster')->count() }}</h3>
                </div>
                <div class="bg-green-100 p-4 rounded-xl">
                    <i class="bi bi-image text-green-600 text-2xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.gallery.index') }}?type=poster" class="text-green-600 text-sm font-medium hover:underline flex items-center">
                    View Posters <i class="bi bi-arrow-right ml-1"></i>
                </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-md hover:shadow-lg transition-all p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Documentation Photos</p>
                    <h3 class="text-3xl font-extrabold text-gray-800">{{ \App\Models\Gallery::where('type', 'documentation')->count() }}</h3>
                </div>
                <div class="bg-purple-100 p-4 rounded-xl">
                    <i class="bi bi-camera text-purple-600 text-2xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.gallery.index') }}?type=documentation" class="text-purple-600 text-sm font-medium hover:underline flex items-center">
                    View Documentation <i class="bi bi-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Booking System Stats --}}
    <div class="mb-12">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-800">Booking System Analytics</h3>
            <a href="{{ route('admin.bookings.index') }}" class="text-blue-600 text-sm font-medium hover:underline flex items-center">
                View All <i class="bi bi-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-2xl shadow-md hover:shadow-lg transition-all p-6">
                <div class="flex items-center space-x-4">
                    <div class="bg-blue-100 p-3 rounded-xl">
                        <i class="bi bi-calendar-check text-blue-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Bookings</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ \App\Models\Booking::count() }}</h3>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center text-sm">
                        @php
                            $lastMonth = \App\Models\Booking::whereMonth('created_at', now()->subMonth()->month)->count();
                            $thisMonth = \App\Models\Booking::whereMonth('created_at', now()->month)->count();
                            $percentChange = $lastMonth > 0 ? (($thisMonth - $lastMonth) / $lastMonth) * 100 : 0;
                        @endphp
                        @if($percentChange > 0)
                            <span class="text-green-600 flex items-center"><i class="bi bi-graph-up-arrow mr-1"></i> {{ number_format(abs($percentChange), 1) }}%</span>
                        @elseif($percentChange < 0)
                            <span class="text-red-600 flex items-center"><i class="bi bi-graph-down-arrow mr-1"></i> {{ number_format(abs($percentChange), 1) }}%</span>
                        @else
                            <span class="text-gray-600 flex items-center"><i class="bi bi-dash mr-1"></i> 0%</span>
                        @endif
                        <span class="text-gray-500 ml-2">from last month</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-md hover:shadow-lg transition-all p-6">
                <div class="flex items-center space-x-4">
                    <div class="bg-yellow-100 p-3 rounded-xl">
                        <i class="bi bi-hourglass-split text-yellow-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Pending Bookings</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ \App\Models\Booking::where('payment_status', 'pending')->count() }}</h3>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center text-sm">
                        <span class="text-yellow-600 flex items-center">
                            <i class="bi bi-exclamation-circle mr-1"></i> Requires attention
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-md hover:shadow-lg transition-all p-6">
                <div class="flex items-center space-x-4">
                    <div class="bg-green-100 p-3 rounded-xl">
                        <i class="bi bi-check-circle text-green-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Completed Bookings</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ \App\Models\Booking::where('payment_status', 'settlement')->count() }}</h3>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center text-sm">
                        @php
                            $completionRate = \App\Models\Booking::count() > 0 ? 
                                (\App\Models\Booking::where('payment_status', 'settlement')->count() / \App\Models\Booking::count()) * 100 : 0;
                        @endphp
                        <span class="text-green-600 flex items-center">
                            <i class="bi bi-bar-chart-fill mr-1"></i> {{ number_format($completionRate, 1) }}%
                        </span>
                        <span class="text-gray-500 ml-2">completion rate</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-md hover:shadow-lg transition-all p-6">
                <div class="flex items-center space-x-4">
                    <div class="bg-red-100 p-3 rounded-xl">
                        <i class="bi bi-currency-dollar text-red-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Revenue</p>
                        <h3 class="text-2xl font-bold text-gray-800">Rp {{ number_format(\App\Models\Transaction::whereIn('transaction_status', ['settlement', 'capture'])->sum('gross_amount'), 0, ',', '.') }}</h3>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center text-sm">
                        @php
                            $lastMonthRevenue = \App\Models\Transaction::whereIn('transaction_status', ['settlement', 'capture'])
                                                ->whereMonth('created_at', now()->subMonth()->month)
                                                ->sum('gross_amount');
                            $thisMonthRevenue = \App\Models\Transaction::whereIn('transaction_status', ['settlement', 'capture'])
                                                ->whereMonth('created_at', now()->month)
                                                ->sum('gross_amount');
                            $revenuePercentChange = $lastMonthRevenue > 0 ? (($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 : 0;
                        @endphp
                        @if($revenuePercentChange > 0)
                            <span class="text-green-600 flex items-center"><i class="bi bi-graph-up-arrow mr-1"></i> {{ number_format(abs($revenuePercentChange), 1) }}%</span>
                        @elseif($revenuePercentChange < 0)
                            <span class="text-red-600 flex items-center"><i class="bi bi-graph-down-arrow mr-1"></i> {{ number_format(abs($revenuePercentChange), 1) }}%</span>
                        @else
                            <span class="text-gray-600 flex items-center"><i class="bi bi-dash mr-1"></i> 0%</span>
                        @endif
                        <span class="text-gray-500 ml-2">from last month</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Featured Content Preview --}}
    <div class="mb-12">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-800">Featured Content</h3>
            <a href="{{ route('admin.gallery.index') }}?featured=1" class="text-blue-600 text-sm font-medium hover:underline flex items-center">
                View All Featured <i class="bi bi-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach(\App\Models\Gallery::where('is_featured', true)->take(3)->get() as $item)
            <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-lg transition-all group">
                <div class="h-48 overflow-hidden relative">
                    <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute top-3 right-3">
                        <span class="bg-white bg-opacity-80 text-xs font-semibold px-2 py-1 rounded-full uppercase">{{ $item->type }}</span>
                    </div>
                </div>
                <div class="p-5">
                    <h4 class="font-semibold text-gray-800 text-lg mb-2">{{ $item->title }}</h4>
                    <p class="text-gray-600 text-sm line-clamp-2 mb-4">{{ $item->description ?? 'No description available' }}</p>
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-500">
                            <i class="bi bi-calendar3 mr-1"></i> {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}
                        </span>
                        <a href="{{ route('admin.gallery.edit', $item->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            <i class="bi bi-pencil-square mr-1"></i> Edit
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Recent Bookings --}}
    <div class="mb-12">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-800">Recent Bookings</h3>
            <a href="{{ route('admin.bookings.index') }}" class="text-blue-600 text-sm font-medium hover:underline flex items-center">
                View All Bookings <i class="bi bi-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 text-sm uppercase">
                            <th class="py-4 px-6 text-left font-semibold">ID</th>
                            <th class="py-4 px-6 text-left font-semibold">Customer</th>
                            <th class="py-4 px-6 text-left font-semibold">Field</th>
                            <th class="py-4 px-6 text-left font-semibold">Date</th>
                            <th class="py-4 px-6 text-left font-semibold">Status</th>
                            <th class="py-4 px-6 text-center font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach(\App\Models\Booking::with('field')->latest()->take(5)->get() as $booking)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-6 text-left">
                                <span class="font-medium text-gray-700">#{{ $booking->id }}</span>
                            </td>
                            <td class="py-4 px-6 text-left">
                                <div class="flex items-center">
                                    <div class="bg-blue-100 text-blue-600 rounded-full w-8 h-8 flex items-center justify-center font-semibold mr-3">
                                        {{ substr($booking->customer_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $booking->customer_name }}</p>
                                        <p class="text-xs text-gray-500">{{ $booking->customer_email ?? 'No email' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-left">
                                <span class="font-medium text-gray-700">{{ $booking->field->name }}</span>
                            </td>
                            <td class="py-4 px-6 text-left">
                                <div class="flex flex-col">
                                    <span class="text-gray-700">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</span>
                                    <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-left">
                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                    @if($booking->payment_status == 'settlement') bg-green-100 text-green-800 
                                    @elseif($booking->payment_status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($booking->payment_status == 'expired') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($booking->payment_status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <a href="{{ route('admin.bookings.show', $booking->id) }}" class="text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-lg text-sm transition-colors">
                                    <i class="bi bi-eye mr-1"></i> View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if(\App\Models\Booking::count() == 0)
            <div class="text-center py-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                    <i class="bi bi-calendar-x text-gray-400 text-2xl"></i>
                </div>
                <h4 class="text-gray-600 font-medium">No bookings found</h4>
                <p class="text-gray-500 text-sm mt-1">New bookings will appear here</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-white rounded-xl shadow-md p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-6">Quick Actions</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('admin.facilities.create') }}" class="group flex flex-col items-center p-6 bg-blue-50 border border-blue-100 rounded-xl hover:bg-blue-100 hover:border-blue-200 transition-all">
                <div class="bg-white p-4 rounded-full shadow-sm mb-4 group-hover:shadow-md transition-all">
                    <i class="bi bi-building-add text-blue-600 text-2xl"></i>
                </div>
                <span class="text-blue-800 font-medium text-center">Add New Facility</span>
            </a>
            
            <a href="{{ route('admin.gallery.create') }}?type=poster" class="group flex flex-col items-center p-6 bg-green-50 border border-green-100 rounded-xl hover:bg-green-100 hover:border-green-200 transition-all">
                <div class="bg-white p-4 rounded-full shadow-sm mb-4 group-hover:shadow-md transition-all">
                    <i class="bi bi-image-fill text-green-600 text-2xl"></i>
                </div>
                <span class="text-green-800 font-medium text-center">Add New Poster</span>
            </a>
            
            <a href="{{ route('admin.fields.create') }}" class="group flex flex-col items-center p-6 bg-yellow-50 border border-yellow-100 rounded-xl hover:bg-yellow-100 hover:border-yellow-200 transition-all">
                <div class="bg-white p-4 rounded-full shadow-sm mb-4 group-hover:shadow-md transition-all">
                    <i class="bi bi-plus-square text-yellow-600 text-2xl"></i>
                </div>
                <span class="text-yellow-800 font-medium text-center">Add New Field</span>
            </a>
            
            <a href="{{ route('admin.bookings.index') }}" class="group flex flex-col items-center p-6 bg-purple-50 border border-purple-100 rounded-xl hover:bg-purple-100 hover:border-purple-200 transition-all">
                <div class="bg-white p-4 rounded-full shadow-sm mb-4 group-hover:shadow-md transition-all">
                    <i class="bi bi-calendar-week text-purple-600 text-2xl"></i>
                </div>
                <span class="text-purple-800 font-medium text-center">Manage Bookings</span>
            </a>
        </div>
    </div>
</div>
@endsection
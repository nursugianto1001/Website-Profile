@extends('layouts.admin')

@section('content')
    {{-- Main Content - Mobile Responsive --}}
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 w-full">
        <div class="container mx-auto px-2 sm:px-4 lg:px-8 py-4 lg:py-8 max-w-7xl">
            {{-- Header Section - Mobile Responsive --}}
            <div class="bg-white rounded-xl lg:rounded-2xl shadow-lg lg:shadow-xl p-4 sm:p-6 lg:p-8 mb-6 lg:mb-8">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h1 class="text-xl sm:text-2xl lg:text-4xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                            Dashboard Admin
                        </h1>
                        <p class="text-sm sm:text-base text-gray-600 mt-1 lg:mt-2">Kelola semua aktivitas dan data sistem</p>
                    </div>
                    <div class="flex items-center space-x-2 bg-gradient-to-r from-blue-50 to-indigo-50 py-2 sm:py-3 px-3 sm:px-4 rounded-lg lg:rounded-xl border border-blue-100">
                        <i class="bi bi-calendar-event text-blue-600 text-sm sm:text-base"></i>
                        <span class="text-xs sm:text-sm lg:text-base text-gray-700 font-medium">{{ now()->format('l, d F Y') }}</span>
                    </div>
                </div>
            </div>

            {{-- Main Stats - Mobile Responsive Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 lg:gap-6 mb-6 lg:mb-8">
                {{-- Total Fasilitas --}}
                <div class="bg-white rounded-xl lg:rounded-2xl shadow-md lg:shadow-lg hover:shadow-lg lg:hover:shadow-xl transition-all duration-300 p-4 sm:p-6 border-l-4 border-blue-500 group">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-xs sm:text-sm font-medium text-blue-600 mb-1 sm:mb-2">Total Fasilitas</p>
                            <h3 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-800">{{ \App\Models\Facility::count() }}</h3>
                        </div>
                        <div class="bg-blue-100 group-hover:bg-blue-200 p-3 sm:p-4 rounded-lg lg:rounded-xl transition-colors">
                            <i class="bi bi-building text-blue-600 text-lg sm:text-xl lg:text-2xl"></i>
                        </div>
                    </div>
                    <div class="mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-gray-100">
                        <a href="{{ route('admin.facilities.index') }}" class="text-blue-600 text-xs sm:text-sm font-medium hover:text-blue-800 flex items-center group-hover:translate-x-1 transition-transform">
                            Kelola Fasilitas <i class="bi bi-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>

                {{-- Poster --}}
                <div class="bg-white rounded-xl lg:rounded-2xl shadow-md lg:shadow-lg hover:shadow-lg lg:hover:shadow-xl transition-all duration-300 p-4 sm:p-6 border-l-4 border-green-500 group">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-xs sm:text-sm font-medium text-green-600 mb-1 sm:mb-2">Poster</p>
                            <h3 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-800">
                                {{ \App\Models\Gallery::where('type', 'poster')->count() }}
                            </h3>
                        </div>
                        <div class="bg-green-100 group-hover:bg-green-200 p-3 sm:p-4 rounded-lg lg:rounded-xl transition-colors">
                            <i class="bi bi-image text-green-600 text-lg sm:text-xl lg:text-2xl"></i>
                        </div>
                    </div>
                    <div class="mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-gray-100">
                        <a href="{{ route('admin.gallery.index') }}?type=poster" class="text-green-600 text-xs sm:text-sm font-medium hover:text-green-800 flex items-center group-hover:translate-x-1 transition-transform">
                            Lihat Poster <i class="bi bi-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>

                {{-- Foto Dokumentasi --}}
                <div class="bg-white rounded-xl lg:rounded-2xl shadow-md lg:shadow-lg hover:shadow-lg lg:hover:shadow-xl transition-all duration-300 p-4 sm:p-6 border-l-4 border-purple-500 group sm:col-span-2 lg:col-span-1">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-xs sm:text-sm font-medium text-purple-600 mb-1 sm:mb-2">Foto Dokumentasi</p>
                            <h3 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-800">
                                {{ \App\Models\Gallery::where('type', 'dokumentasi')->count() }}
                            </h3>
                        </div>
                        <div class="bg-purple-100 group-hover:bg-purple-200 p-3 sm:p-4 rounded-lg lg:rounded-xl transition-colors">
                            <i class="bi bi-camera text-purple-600 text-lg sm:text-xl lg:text-2xl"></i>
                        </div>
                    </div>
                    <div class="mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-gray-100">
                        <a href="{{ route('admin.gallery.index') }}?type=documentation" class="text-purple-600 text-xs sm:text-sm font-medium hover:text-purple-800 flex items-center group-hover:translate-x-1 transition-transform">
                            Lihat Dokumentasi <i class="bi bi-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Booking System Stats - Mobile Responsive --}}
            <div class="bg-white rounded-xl lg:rounded-2xl shadow-lg lg:shadow-xl p-4 sm:p-6 lg:p-8 mb-6 lg:mb-8">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between sm:gap-4 mb-4 sm:mb-6">
                    <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-indigo-800">Sistem Analitik Pemesanan</h3>
                    <a href="{{ route('admin.bookings.index') }}" class="text-blue-600 text-xs sm:text-sm font-medium hover:text-blue-800 hover:underline flex items-center">
                        Lihat Selengkapnya <i class="bi bi-arrow-right ml-1"></i>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3 sm:gap-4 lg:gap-6">
                    {{-- Total Pemesanan --}}
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg lg:rounded-xl p-4 sm:p-6 border border-blue-200">
                        <div class="flex items-center space-x-3 sm:space-x-4">
                            <div class="bg-blue-600 p-2 sm:p-3 rounded-lg lg:rounded-xl shadow-lg">
                                <i class="bi bi-calendar-check text-white text-lg sm:text-xl lg:text-2xl"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs sm:text-sm font-medium text-blue-700">Total Pemesanan</p>
                                <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-blue-900">{{ \App\Models\Booking::count() }}</h3>
                            </div>
                        </div>
                        <div class="mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-blue-200">
                            <div class="flex items-center text-xs sm:text-sm">
                                @php
                                    $lastMonth = \App\Models\Booking::whereMonth('created_at', now()->subMonth()->month)->count();
                                    $thisMonth = \App\Models\Booking::whereMonth('created_at', now()->month)->count();
                                    $percentChange = $lastMonth > 0 ? (($thisMonth - $lastMonth) / $lastMonth) * 100 : 0;
                                @endphp
                                @if ($percentChange > 0)
                                    <span class="text-green-700 flex items-center bg-green-100 px-2 py-1 rounded-full">
                                        <i class="bi bi-graph-up-arrow mr-1"></i>
                                        {{ number_format(abs($percentChange), 1) }}%
                                    </span>
                                @elseif($percentChange < 0)
                                    <span class="text-red-700 flex items-center bg-red-100 px-2 py-1 rounded-full">
                                        <i class="bi bi-graph-down-arrow mr-1"></i>
                                        {{ number_format(abs($percentChange), 1) }}%
                                    </span>
                                @else
                                    <span class="text-gray-700 flex items-center bg-gray-100 px-2 py-1 rounded-full">
                                        <i class="bi bi-dash mr-1"></i> 0%
                                    </span>
                                @endif
                                <span class="text-blue-600 ml-2 hidden sm:inline">dari bulan lalu</span>
                            </div>
                        </div>
                    </div>

                    {{-- Pemesanan Tertunda --}}
                    <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg lg:rounded-xl p-4 sm:p-6 border border-yellow-200">
                        <div class="flex items-center space-x-3 sm:space-x-4">
                            <div class="bg-yellow-600 p-2 sm:p-3 rounded-lg lg:rounded-xl shadow-lg">
                                <i class="bi bi-hourglass-split text-white text-lg sm:text-xl lg:text-2xl"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs sm:text-sm font-medium text-yellow-700">Pemesanan Tertunda</p>
                                <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-yellow-900">
                                    {{ \App\Models\Booking::where('payment_status', 'pending')->count() }}
                                </h3>
                            </div>
                        </div>
                        <div class="mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-yellow-200">
                            <div class="flex items-center text-xs sm:text-sm">
                                <span class="text-yellow-700 flex items-center bg-yellow-200 px-2 py-1 rounded-full">
                                    <i class="bi bi-exclamation-circle mr-1"></i> <span class="hidden sm:inline">perlu </span>perhatian
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Pemesanan Berhasil --}}
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg lg:rounded-xl p-4 sm:p-6 border border-green-200">
                        <div class="flex items-center space-x-3 sm:space-x-4">
                            <div class="bg-green-600 p-2 sm:p-3 rounded-lg lg:rounded-xl shadow-lg">
                                <i class="bi bi-check-circle text-white text-lg sm:text-xl lg:text-2xl"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs sm:text-sm font-medium text-green-700">Pemesanan Berhasil</p>
                                <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-green-900">
                                    {{ \App\Models\Booking::where('payment_status', 'settlement')->count() }}
                                </h3>
                            </div>
                        </div>
                        <div class="mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-green-200">
                            <div class="flex items-center text-xs sm:text-sm">
                                @php
                                    $completionRate = \App\Models\Booking::count() > 0 
                                        ? (\App\Models\Booking::where('payment_status', 'settlement')->count() / \App\Models\Booking::count()) * 100 
                                        : 0;
                                @endphp
                                <span class="text-green-700 flex items-center bg-green-200 px-2 py-1 rounded-full">
                                    <i class="bi bi-bar-chart-fill mr-1"></i> {{ number_format($completionRate, 1) }}%
                                </span>
                                <span class="text-green-600 ml-2 hidden sm:inline">tingkat penyelesaian</span>
                            </div>
                        </div>
                    </div>

                    {{-- Total Pendapatan --}}
                    <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-lg lg:rounded-xl p-4 sm:p-6 border border-red-200">
                        <div class="flex items-center space-x-3 sm:space-x-4">
                            <div class="bg-red-600 p-2 sm:p-3 rounded-lg lg:rounded-xl shadow-lg">
                                <i class="bi bi-currency-dollar text-white text-lg sm:text-xl lg:text-2xl"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs sm:text-sm font-medium text-red-700">Total Pendapatan</p>
                                <h3 class="text-sm sm:text-lg lg:text-xl font-bold text-red-900">Rp
                                    {{ number_format(\App\Models\Transaction::whereIn('transaction_status', ['settlement', 'capture'])->sum('gross_amount'), 0, ',', '.') }}
                                </h3>
                            </div>
                        </div>
                        <div class="mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-red-200">
                            <div class="flex items-center text-xs sm:text-sm">
                                @php
                                    $lastMonthRevenue = \App\Models\Transaction::whereIn('transaction_status', ['settlement', 'capture'])
                                        ->whereMonth('created_at', now()->subMonth()->month)
                                        ->sum('gross_amount');
                                    $thisMonthRevenue = \App\Models\Transaction::whereIn('transaction_status', ['settlement', 'capture'])
                                        ->whereMonth('created_at', now()->month)
                                        ->sum('gross_amount');
                                    $revenuePercentChange = $lastMonthRevenue > 0 
                                        ? (($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 
                                        : 0;
                                @endphp
                                @if ($revenuePercentChange > 0)
                                    <span class="text-green-700 flex items-center bg-green-100 px-2 py-1 rounded-full">
                                        <i class="bi bi-graph-up-arrow mr-1"></i>
                                        {{ number_format(abs($revenuePercentChange), 1) }}%
                                    </span>
                                @elseif($revenuePercentChange < 0)
                                    <span class="text-red-700 flex items-center bg-red-100 px-2 py-1 rounded-full">
                                        <i class="bi bi-graph-down-arrow mr-1"></i>
                                        {{ number_format(abs($revenuePercentChange), 1) }}%
                                    </span>
                                @else
                                    <span class="text-gray-700 flex items-center bg-gray-100 px-2 py-1 rounded-full">
                                        <i class="bi bi-dash mr-1"></i> 0%
                                    </span>
                                @endif
                                <span class="text-red-600 ml-2 hidden sm:inline">dari bulan lalu</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Featured Content Preview - Mobile Responsive --}}
            <div class="bg-white rounded-xl lg:rounded-2xl shadow-lg lg:shadow-xl p-4 sm:p-6 lg:p-8 mb-6 lg:mb-8">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between sm:gap-4 mb-4 sm:mb-6">
                    <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-indigo-800">Konten Unggulan</h3>
                    <a href="{{ route('admin.gallery.index') }}?featured=1" class="text-blue-600 text-xs sm:text-sm font-medium hover:text-blue-800 hover:underline flex items-center">
                        Lihat Semua Konten <i class="bi bi-arrow-right ml-1"></i>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6">
                    @foreach (\App\Models\Gallery::where('is_featured', true)->take(3)->get() as $item)
                        <div class="bg-white rounded-xl lg:rounded-2xl overflow-hidden shadow-md lg:shadow-lg hover:shadow-lg lg:hover:shadow-xl transition-all duration-300 group border border-gray-100">
                            <div class="h-40 sm:h-48 lg:h-56 overflow-hidden relative">
                                <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                <div class="absolute top-2 sm:top-4 right-2 sm:right-4">
                                    <span class="bg-white/90 backdrop-blur-sm text-xs font-semibold px-2 sm:px-3 py-1 rounded-full uppercase shadow-lg">{{ $item->type }}</span>
                                </div>
                            </div>
                            <div class="p-4 sm:p-6">
                                <h4 class="font-bold text-gray-800 text-base sm:text-lg mb-2 sm:mb-3 line-clamp-1">{{ $item->title }}</h4>
                                <p class="text-gray-600 text-xs sm:text-sm line-clamp-2 mb-3 sm:mb-4">
                                    {{ $item->description ?? 'Tidak ada deskripsi yang tersedia' }}
                                </p>
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-gray-500 flex items-center">
                                        <i class="bi bi-calendar3 mr-1"></i>
                                        {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}
                                    </span>
                                    <a href="{{ route('admin.gallery.edit', $item->id) }}" class="text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-2 sm:px-3 py-1 rounded-lg text-xs sm:text-sm font-medium transition-colors">
                                        <i class="bi bi-pencil-square mr-1"></i> Edit
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Recent Bookings - Mobile Responsive --}}
            <div class="bg-white rounded-xl lg:rounded-2xl shadow-lg lg:shadow-xl p-4 sm:p-6 lg:p-8 mb-6 lg:mb-8">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between sm:gap-4 mb-4 sm:mb-6">
                    <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-indigo-800">Daftar Pemesanan Terbaru</h3>
                    <a href="{{ route('admin.bookings.index') }}" class="text-blue-600 text-xs sm:text-sm font-medium hover:text-blue-800 hover:underline flex items-center">
                        Lihat Semua Pemesanan <i class="bi bi-arrow-right ml-1"></i>
                    </a>
                </div>

                <div class="overflow-x-auto bg-gray-50 rounded-lg lg:rounded-xl">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 text-xs sm:text-sm uppercase">
                                <th class="py-3 sm:py-4 px-3 sm:px-6 text-left font-semibold">ID</th>
                                <th class="py-3 sm:py-4 px-3 sm:px-6 text-left font-semibold">Pelanggan</th>
                                <th class="py-3 sm:py-4 px-3 sm:px-6 text-left font-semibold hidden md:table-cell">Lapangan</th>
                                <th class="py-3 sm:py-4 px-3 sm:px-6 text-left font-semibold">Tanggal</th>
                                <th class="py-3 sm:py-4 px-3 sm:px-6 text-left font-semibold">Status</th>
                                <th class="py-3 sm:py-4 px-3 sm:px-6 text-center font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach (\App\Models\Booking::with('field')->latest()->take(5)->get() as $booking)
                                <tr class="hover:bg-blue-50 transition-colors">
                                    <td class="py-3 sm:py-4 px-3 sm:px-6 text-left">
                                        <span class="font-bold text-gray-700 bg-gray-100 px-2 py-1 rounded text-xs sm:text-sm">#{{ $booking->id }}</span>
                                    </td>
                                    <td class="py-3 sm:py-4 px-3 sm:px-6 text-left">
                                        <div class="flex items-center">
                                            <div class="bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-full w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center font-bold mr-2 sm:mr-3 shadow-md text-xs sm:text-sm">
                                                {{ substr($booking->customer_name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-800 text-xs sm:text-sm">{{ $booking->customer_name }}</p>
                                                <p class="text-xs text-gray-500 hidden sm:block">{{ $booking->customer_email ?? 'No email' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 sm:py-4 px-3 sm:px-6 text-left hidden md:table-cell">
                                        <span class="font-medium text-gray-700 bg-gray-100 px-2 py-1 rounded text-xs sm:text-sm">{{ $booking->field->name }}</span>
                                    </td>
                                    <td class="py-3 sm:py-4 px-3 sm:px-6 text-left">
                                        <div class="flex flex-col">
                                            <span class="text-gray-800 font-medium text-xs sm:text-sm">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</span>
                                            <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 sm:py-4 px-3 sm:px-6 text-left">
                                        <span class="px-2 sm:px-3 py-1 rounded-full text-xs font-bold shadow-sm
                                            @if ($booking->payment_status == 'settlement') bg-green-100 text-green-800 border border-green-200
                                            @elseif($booking->payment_status == 'pending') bg-yellow-100 text-yellow-800 border border-yellow-200
                                            @elseif($booking->payment_status == 'expired') bg-red-100 text-red-800 border border-red-200
                                            @else bg-gray-100 text-gray-800 border border-gray-200 @endif">
                                            {{ ucfirst($booking->payment_status) }}
                                        </span>
                                    </td>
                                    <td class="py-3 sm:py-4 px-3 sm:px-6 text-center">
                                        <a href="{{ route('admin.bookings.show', $booking->id) }}" class="text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-2 sm:px-3 py-1 sm:py-2 rounded-lg text-xs sm:text-sm font-medium transition-colors shadow-sm">
                                            <i class="bi bi-eye mr-1"></i> <span class="hidden sm:inline">Lihat</span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if (\App\Models\Booking::count() == 0)
                    <div class="text-center py-8 sm:py-12">
                        <div class="inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 bg-gray-100 rounded-full mb-4">
                            <i class="bi bi-calendar-x text-gray-400 text-2xl sm:text-3xl"></i>
                        </div>
                        <h4 class="text-gray-600 font-semibold text-base sm:text-lg">Pemesanan tidak ditemukan</h4>
                        <p class="text-gray-500 text-sm mt-2">Pemesanan terbaru akan muncul disini</p>
                    </div>
                @endif
            </div>

            {{-- Quick Actions - Mobile Responsive --}}
            <div class="bg-white rounded-xl lg:rounded-2xl shadow-lg lg:shadow-xl p-4 sm:p-6 lg:p-8">
                <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-indigo-800 mb-4 sm:mb-6">Tindakan Cepat</h3>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6">
                    <a href="{{ route('admin.facilities.create') }}" class="group flex flex-col items-center p-3 sm:p-6 bg-gradient-to-br from-blue-50 to-blue-100 border-2 border-blue-200 rounded-xl lg:rounded-2xl hover:from-blue-100 hover:to-blue-200 hover:border-blue-300 transition-all duration-300 hover:scale-105">
                        <div class="bg-white p-3 sm:p-4 rounded-full shadow-lg mb-2 sm:mb-4 group-hover:shadow-xl transition-shadow">
                            <i class="bi bi-building text-blue-600 text-lg sm:text-2xl"></i>
                        </div>
                        <span class="text-blue-800 font-semibold text-center text-xs sm:text-sm">Tambah Fasilitas Baru</span>
                    </a>

                    <a href="{{ route('admin.gallery.create') }}?type=poster" class="group flex flex-col items-center p-3 sm:p-6 bg-gradient-to-br from-green-50 to-green-100 border-2 border-green-200 rounded-xl lg:rounded-2xl hover:from-green-100 hover:to-green-200 hover:border-green-300 transition-all duration-300 hover:scale-105">
                        <div class="bg-white p-3 sm:p-4 rounded-full shadow-lg mb-2 sm:mb-4 group-hover:shadow-xl transition-shadow">
                            <i class="bi bi-image-fill text-green-600 text-lg sm:text-2xl"></i>
                        </div>
                        <span class="text-green-800 font-semibold text-center text-xs sm:text-sm">Tambah Poster Baru</span>
                    </a>

                    <a href="{{ route('admin.fields.create') }}" class="group flex flex-col items-center p-3 sm:p-6 bg-gradient-to-br from-yellow-50 to-yellow-100 border-2 border-yellow-200 rounded-xl lg:rounded-2xl hover:from-yellow-100 hover:to-yellow-200 hover:border-yellow-300 transition-all duration-300 hover:scale-105">
                        <div class="bg-white p-3 sm:p-4 rounded-full shadow-lg mb-2 sm:mb-4 group-hover:shadow-xl transition-shadow">
                            <i class="bi bi-plus-square text-yellow-600 text-lg sm:text-2xl"></i>
                        </div>
                        <span class="text-yellow-800 font-semibold text-center text-xs sm:text-sm">Tambah Lapangan Baru</span>
                    </a>

                    <a href="{{ route('admin.bookings.index') }}" class="group flex flex-col items-center p-3 sm:p-6 bg-gradient-to-br from-purple-50 to-purple-100 border-2 border-purple-200 rounded-xl lg:rounded-2xl hover:from-purple-100 hover:to-purple-200 hover:border-purple-300 transition-all duration-300 hover:scale-105">
                        <div class="bg-white p-3 sm:p-4 rounded-full shadow-lg mb-2 sm:mb-4 group-hover:shadow-xl transition-shadow">
                            <i class="bi bi-calendar-week text-purple-600 text-lg sm:text-2xl"></i>
                        </div>
                        <span class="text-purple-800 font-semibold text-center text-xs sm:text-sm">Kelola Pemesanan</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

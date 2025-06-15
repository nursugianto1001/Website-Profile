@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 w-full overflow-x-hidden">
    <div class="container mx-auto px-2 sm:px-4 lg:px-8 py-4 lg:py-8 max-w-7xl">
        {{-- Header Section - Mobile Responsive --}}
        <div class="bg-white rounded-xl lg:rounded-2xl shadow-lg lg:shadow-xl p-4 sm:p-6 lg:p-8 mb-6 lg:mb-8">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h2 class="text-xl sm:text-2xl lg:text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                        Daftar Transaksi
                    </h2>
                    <p class="text-sm sm:text-base text-gray-600 mt-1 lg:mt-2">Kelola dan pantau semua transaksi pembayaran (Terbaru ke Lama)</p>
                </div>
            </div>
        </div>

        {{-- Stats Cards - Mobile Responsive --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 lg:gap-6 mb-6 lg:mb-8">
            <div class="bg-white rounded-xl lg:rounded-2xl shadow-lg lg:shadow-xl p-4 sm:p-6 border-l-4 border-green-500 group hover:shadow-xl transition-all duration-300">
                <div class="flex items-center">
                    <div class="flex-shrink-0 p-2 sm:p-3 rounded-lg lg:rounded-xl bg-green-100 group-hover:bg-green-200 transition-colors">
                        <i class="bi bi-check-circle text-green-600 text-lg sm:text-xl"></i>
                    </div>
                    <div class="ml-3 sm:ml-4">
                        <h3 class="text-xs sm:text-sm text-gray-500 font-medium">Berhasil</h3>
                        <p class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-800">{{ $totalSettlement ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl lg:rounded-2xl shadow-lg lg:shadow-xl p-4 sm:p-6 border-l-4 border-yellow-500 group hover:shadow-xl transition-all duration-300">
                <div class="flex items-center">
                    <div class="flex-shrink-0 p-2 sm:p-3 rounded-lg lg:rounded-xl bg-yellow-100 group-hover:bg-yellow-200 transition-colors">
                        <i class="bi bi-hourglass-split text-yellow-600 text-lg sm:text-xl"></i>
                    </div>
                    <div class="ml-3 sm:ml-4">
                        <h3 class="text-xs sm:text-sm text-gray-500 font-medium">Pending</h3>
                        <p class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-800">{{ $totalPending ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl lg:rounded-2xl shadow-lg lg:shadow-xl p-4 sm:p-6 border-l-4 border-red-500 group hover:shadow-xl transition-all duration-300 sm:col-span-3 lg:col-span-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0 p-2 sm:p-3 rounded-lg lg:rounded-xl bg-red-100 group-hover:bg-red-200 transition-colors">
                        <i class="bi bi-x-circle text-red-600 text-lg sm:text-xl"></i>
                    </div>
                    <div class="ml-3 sm:ml-4">
                        <h3 class="text-xs sm:text-sm text-gray-500 font-medium">Gagal</h3>
                        <p class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-800">{{ $totalFailed ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter and Search Section - Mobile Responsive --}}
        <div class="bg-white rounded-xl lg:rounded-2xl shadow-lg lg:shadow-xl p-4 sm:p-6 mb-6 lg:mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Filter & Pencarian</h3>
            <form action="{{ route('admin.transactions.index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                    {{-- Pencarian --}}
                    <div>
                        <label for="search" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Cari</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="bi bi-search text-gray-400 text-sm"></i>
                            </div>
                            <input type="text" id="search" name="search" placeholder="Order ID / Nama..."
                                class="pl-8 sm:pl-10 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm"
                                value="{{ request('search') }}">
                        </div>
                    </div>

                    {{-- Filter Status --}}
                    <div>
                        <label for="status" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="status" name="status"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 bg-white text-sm">
                            <option value="">Semua Status</option>
                            <option value="settlement" {{ request('status') == 'settlement' ? 'selected' : '' }}>Settlement</option>
                            <option value="capture" {{ request('status') == 'capture' ? 'selected' : '' }}>Capture</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="cancel" {{ request('status') == 'cancel' ? 'selected' : '' }}>Cancel</option>
                            <option value="deny" {{ request('status') == 'deny' ? 'selected' : '' }}>Deny</option>
                            <option value="expire" {{ request('status') == 'expire' ? 'selected' : '' }}>Expire</option>
                        </select>
                    </div>

                    {{-- Filter Tanggal --}}
                    <div>
                        <label for="date_from" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="bi bi-calendar3 text-gray-400 text-sm"></i>
                            </div>
                            <input type="date" id="date_from" name="date_from"
                                class="pl-8 sm:pl-10 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm"
                                value="{{ request('date_from') }}">
                        </div>
                    </div>
                    <div>
                        <label for="date_to" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="bi bi-calendar3 text-gray-400 text-sm"></i>
                            </div>
                            <input type="date" id="date_to" name="date_to"
                                class="pl-8 sm:pl-10 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm"
                                value="{{ request('date_to') }}">
                        </div>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex flex-col sm:flex-row gap-2 sm:gap-4 sm:justify-end">
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-indigo-700 transition-colors">
                        <i class="bi bi-filter mr-2"></i>Filter
                    </button>
                    <a href="{{ route('admin.transactions.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-gray-700 transition-colors">
                        <i class="bi bi-x-circle mr-2"></i>Reset
                    </a>
                    <a href="{{ route('admin.transactions.export', request()->query()) }}"
                        class="inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-green-700 transition-colors">
                        <i class="bi bi-download mr-2"></i><span class="hidden sm:inline">Export</span>
                    </a>
                </div>
            </form>
        </div>

        {{-- Table Section - NO HORIZONTAL SCROLL --}}
        <div class="bg-white rounded-xl lg:rounded-2xl shadow-lg lg:shadow-xl overflow-hidden">
            @if(count($transactions) > 0)
            {{-- Mobile Card View --}}
            <div class="block lg:hidden">
                <div class="p-4 space-y-4">
                    @forelse($transactions as $transaction)
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex justify-between items-start mb-3">
                            <div class="min-w-0 flex-1 mr-3">
                                <div class="flex items-center">
                                    <span class="text-sm font-bold text-gray-900">#{{ $transaction->id }}</span>
                                    @if($loop->first)
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                        <i class="bi bi-clock mr-1"></i>Terbaru
                                    </span>
                                    @endif
                                </div>
                                <div class="text-xs text-gray-600 font-mono mt-1 truncate">{{ $transaction->order_id }}</div>
                            </div>
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full flex-shrink-0
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
                        </div>

                        @if($transaction->booking)
                        <div class="mb-3">
                            <div class="text-sm font-medium text-gray-900 truncate">{{ $transaction->booking->customer_name }}</div>
                            <div class="text-xs text-gray-500 truncate">{{ $transaction->booking->customer_email }}</div>
                        </div>
                        @endif

                        <div class="grid grid-cols-2 gap-3 text-xs">
                            <div>
                                <span class="text-gray-500">Pembayaran:</span>
                                <div class="flex items-center mt-1">
                                    @if($transaction->payment_type == 'credit_card')
                                    <i class="bi bi-credit-card mr-1 text-blue-500"></i>
                                    @elseif($transaction->payment_type == 'bank_transfer')
                                    <i class="bi bi-bank mr-1 text-green-500"></i>
                                    @elseif(strpos($transaction->payment_type, 'wallet') !== false || strpos($transaction->payment_type, 'pay') !== false)
                                    <i class="bi bi-wallet2 mr-1 text-purple-500"></i>
                                    @else
                                    <i class="bi bi-cash mr-1 text-gray-500"></i>
                                    @endif
                                    <span class="text-gray-700 truncate">{{ $transaction->payment_type ? str_replace('_', ' ', $transaction->payment_type) : 'Cash' }}</span>
                                </div>
                            </div>
                            <div>
                                <span class="text-gray-500">Jumlah:</span>
                                <div class="text-blue-600 font-medium mt-1">Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}</div>
                            </div>
                            <div class="col-span-2">
                                <span class="text-gray-500">Waktu:</span>
                                <div class="text-gray-700 mt-1">
                                    @if($transaction->transaction_time)
                                    {{ $transaction->transaction_time->format('d/m/Y H:i') }}
                                    @else
                                    -
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end mt-3 pt-3 border-t border-gray-200">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.transactions.show', $transaction->id) }}"
                                    class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition-colors duration-200"
                                    title="Lihat Detail">
                                    <i class="bi bi-eye text-sm"></i>
                                </a>
                                @if($transaction->transaction_status == 'pending')
                                <button
                                    class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition-colors duration-200"
                                    title="Batalkan Transaksi">
                                    <i class="bi bi-x-circle text-sm"></i>
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <i class="bi bi-inbox text-4xl text-gray-300 mb-3"></i>
                        <p class="text-base font-medium text-gray-500">Tidak ada data transaksi ditemukan</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Desktop Table View - Fixed Width Columns --}}
            <div class="hidden lg:block">
                <div class="w-full">
                    <table class="w-full table-fixed divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-indigo-50 to-purple-50">
                            <tr>
                                <th scope="col" class="w-16 px-4 py-4 text-left text-xs font-bold text-indigo-800 uppercase tracking-wider">
                                    ID
                                    <i class="bi bi-arrow-down text-indigo-600 ml-1" title="Terbaru ke Lama"></i>
                                </th>
                                <th scope="col" class="w-32 px-4 py-4 text-left text-xs font-bold text-indigo-800 uppercase tracking-wider">Order ID</th>
                                <th scope="col" class="w-40 px-4 py-4 text-left text-xs font-bold text-indigo-800 uppercase tracking-wider">Pelanggan</th>
                                <th scope="col" class="w-24 px-4 py-4 text-left text-xs font-bold text-indigo-800 uppercase tracking-wider">Status</th>
                                <th scope="col" class="w-32 px-4 py-4 text-left text-xs font-bold text-indigo-800 uppercase tracking-wider">Pembayaran</th>
                                <th scope="col" class="w-28 px-4 py-4 text-left text-xs font-bold text-indigo-800 uppercase tracking-wider">Jumlah</th>
                                <th scope="col" class="w-36 px-4 py-4 text-left text-xs font-bold text-indigo-800 uppercase tracking-wider">
                                    Waktu Transaksi
                                    <i class="bi bi-arrow-down text-indigo-600 ml-1" title="Terbaru ke Lama"></i>
                                </th>
                                <th scope="col" class="w-20 px-4 py-4 text-center text-xs font-bold text-indigo-800 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($transactions as $transaction)
                            <tr class="hover:bg-blue-50 transition-colors">
                                <td class="px-4 py-4 text-sm font-medium text-gray-900">
                                    <div class="flex items-center">
                                        {{ $transaction->id }}
                                        @if($loop->first)
                                        <span class="ml-1 inline-flex items-center px-1 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                            <i class="bi bi-clock mr-1"></i>New
                                        </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-700 font-mono">
                                    <div class="truncate">{{ $transaction->order_id }}</div>
                                </td>
                                <td class="px-4 py-4">
                                    @if($transaction->booking)
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-900 truncate">{{ $transaction->booking->customer_name }}</span>
                                        <span class="text-xs text-gray-500 truncate">{{ $transaction->booking->customer_email }}</span>
                                    </div>
                                    @else
                                    <span class="text-xs text-gray-400 italic">Data tidak tersedia</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
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
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-700">
                                    <div class="flex items-center">
                                        @if($transaction->payment_type == 'credit_card')
                                        <i class="bi bi-credit-card mr-2 text-blue-500"></i>
                                        @elseif($transaction->payment_type == 'bank_transfer')
                                        <i class="bi bi-bank mr-2 text-green-500"></i>
                                        @elseif(strpos($transaction->payment_type, 'wallet') !== false || strpos($transaction->payment_type, 'pay') !== false)
                                        <i class="bi bi-wallet2 mr-2 text-purple-500"></i>
                                        @else
                                        <i class="bi bi-cash mr-2 text-gray-500"></i>
                                        @endif
                                        <span class="capitalize truncate">{{ $transaction->payment_type ? str_replace('_', ' ', $transaction->payment_type) : 'Cash' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-sm font-medium text-blue-600">
                                    <div class="truncate">Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-700">
                                    @if($transaction->transaction_time)
                                    <div class="flex flex-col">
                                        <span class="font-medium">{{ $transaction->transaction_time->format('d/m/Y') }}</span>
                                        <span class="text-xs text-gray-500">{{ $transaction->transaction_time->format('H:i:s') }}</span>
                                        <span class="text-xs text-blue-600 truncate">{{ $transaction->transaction_time->diffForHumans() }}</span>
                                    </div>
                                    @else
                                    <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <div class="flex items-center justify-center space-x-1">
                                        <a href="{{ route('admin.transactions.show', $transaction->id) }}"
                                            class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition-colors duration-200"
                                            title="Lihat Detail">
                                            <i class="bi bi-eye text-sm"></i>
                                        </a>
                                        @if($transaction->transaction_status == 'pending')
                                        <button
                                            class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition-colors duration-200"
                                            title="Batalkan Transaksi">
                                            <i class="bi bi-x-circle text-sm"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-4 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="bi bi-inbox text-5xl text-gray-300 mb-3"></i>
                                        <p class="text-lg font-medium text-gray-500">Tidak ada data transaksi ditemukan</p>
                                        <p class="text-sm text-gray-400 mt-1">
                                            @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                                            Coba ubah filter pencarian Anda
                                            @else
                                            Belum ada transaksi yang dibuat
                                            @endif
                                        </p>
                                        <a href="{{ route('admin.transactions.index') }}" class="mt-4 inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                            <i class="bi bi-arrow-repeat mr-2"></i>
                                            Reset Filter
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination Section - Mobile Responsive --}}
            <div class="px-4 sm:px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $transactions->appends(request()->query())->links('pagination::tailwind') }}
            </div>
            @else
            <div class="text-center py-8 sm:py-12">
                <div class="text-gray-400 text-4xl sm:text-6xl mb-4 sm:mb-6">
                    <i class="bi bi-receipt"></i>
                </div>
                <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Tidak ada data transaksi yang ditemukan</h3>
                <p class="text-sm text-gray-500 mb-4 sm:mb-6">Transaksi akan muncul di sini setelah ada pembayaran</p>
            </div>
            @endif
        </div>

        {{-- Footer Info - Mobile Responsive --}}
        <div class="mt-6 lg:mt-8 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 rounded-xl lg:rounded-2xl p-4 sm:p-5">
            <div class="flex items-start">
                <div class="flex-shrink-0 mt-0.5">
                    <i class="bi bi-info-circle-fill text-blue-500 text-lg"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Informasi Status Transaksi</h3>
                    <div class="mt-2 text-sm grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="flex items-center bg-white p-2 rounded-lg shadow-sm">
                            <span class="w-4 h-4 rounded-full bg-green-500 mr-2 flex items-center justify-center">
                                <i class="bi bi-check text-white text-xs"></i>
                            </span>
                            <span class="text-gray-700"><span class="font-medium">Settlement/Capture:</span> <span class="hidden sm:inline">Pembayaran </span>berhasil</span>
                        </div>
                        <div class="flex items-center bg-white p-2 rounded-lg shadow-sm">
                            <span class="w-4 h-4 rounded-full bg-yellow-500 mr-2 flex items-center justify-center">
                                <i class="bi bi-hourglass-split text-white text-xs"></i>
                            </span>
                            <span class="text-gray-700"><span class="font-medium">Pending:</span> <span class="hidden sm:inline">Menunggu </span>pembayaran</span>
                        </div>
                        <div class="flex items-center bg-white p-2 rounded-lg shadow-sm">
                            <span class="w-4 h-4 rounded-full bg-red-500 mr-2 flex items-center justify-center">
                                <i class="bi bi-x text-white text-xs"></i>
                            </span>
                            <span class="text-gray-700"><span class="font-medium">Cancel/Deny/Expire:</span> <span class="hidden sm:inline">Pembayaran </span>gagal</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
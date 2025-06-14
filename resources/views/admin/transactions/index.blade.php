@extends('layouts.admin')

@section('content')
<div class="bg-gradient-to-br from-white to-gray-50 p-6 rounded-xl shadow-lg border border-gray-100">
    <!-- Debug Info (Hapus setelah testing) -->
    @if(config('app.debug'))
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <h4 class="font-semibold text-blue-800">Debug Information:</h4>
        <p class="text-sm text-blue-700">Total transaksi: {{ $transactions->total() }}</p>
        <p class="text-sm text-blue-700">Current page: {{ $transactions->currentPage() }}</p>
        <p class="text-sm text-blue-700">Per page: {{ $transactions->perPage() }}</p>
        <p class="text-sm text-blue-700">Settlement: {{ $totalSettlement ?? 'undefined' }}</p>
        <p class="text-sm text-blue-700">Pending: {{ $totalPending ?? 'undefined' }}</p>
        <p class="text-sm text-blue-700">Failed: {{ $totalFailed ?? 'undefined' }}</p>
    </div>
    @endif

    <!-- Header dan Filter -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-6">
        <!-- Header -->
        <div class="flex items-center">
            <div>
                <h2 class="text-2xl font-bold text-indigo-800">Daftar Transaksi</h2>
                <p class="text-sm text-gray-500 mt-1">Kelola dan pantau semua transaksi pembayaran (Terbaru ke Lama)</p>
            </div>
        </div>

        <!-- Statistik Transaksi -->
        <div class="flex flex-wrap gap-4 w-full lg:w-auto">
            <div class="flex items-center p-3 bg-white rounded-lg shadow-sm border border-gray-100">
                <div class="p-2 bg-green-100 text-green-600 rounded-lg mr-3">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Berhasil</p>
                    <p class="text-lg font-semibold text-green-600">{{ $totalSettlement ?? 0 }}</p>
                </div>
            </div>
            <div class="flex items-center p-3 bg-white rounded-lg shadow-sm border border-gray-100">
                <div class="p-2 bg-yellow-100 text-yellow-600 rounded-lg mr-3">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Pending</p>
                    <p class="text-lg font-semibold text-yellow-600">{{ $totalPending ?? 0 }}</p>
                </div>
            </div>
            <div class="flex items-center p-3 bg-white rounded-lg shadow-sm border border-gray-100">
                <div class="p-2 bg-red-100 text-red-600 rounded-lg mr-3">
                    <i class="bi bi-x-circle"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Gagal</p>
                    <p class="text-lg font-semibold text-red-600">{{ $totalFailed ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter dan Pencarian -->
    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 mb-6">
        <form action="{{ route('admin.transactions.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Pencarian -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="bi bi-search text-gray-400"></i>
                    </div>
                    <input type="text" id="search" name="search" placeholder="Order ID / Nama Pelanggan..."
                        class="pl-10 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                        value="{{ request('search') }}">
                </div>
            </div>

            <!-- Filter Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select id="status" name="status"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 bg-white">
                    <option value="">Semua Status</option>
                    <option value="settlement" {{ request('status') == 'settlement' ? 'selected' : '' }}>Settlement</option>
                    <option value="capture" {{ request('status') == 'capture' ? 'selected' : '' }}>Capture</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="cancel" {{ request('status') == 'cancel' ? 'selected' : '' }}>Cancel</option>
                    <option value="deny" {{ request('status') == 'deny' ? 'selected' : '' }}>Deny</option>
                    <option value="expire" {{ request('status') == 'expire' ? 'selected' : '' }}>Expire</option>
                </select>
            </div>

            <!-- Filter Tanggal -->
            <div>
                <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="bi bi-calendar3 text-gray-400"></i>
                    </div>
                    <input type="date" id="date_from" name="date_from"
                        class="pl-10 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                        value="{{ request('date_from') }}">
                </div>
            </div>
            <div>
                <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="bi bi-calendar3 text-gray-400"></i>
                    </div>
                    <input type="date" id="date_to" name="date_to"
                        class="pl-10 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                        value="{{ request('date_to') }}">
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="md:col-span-2 lg:col-span-4 flex flex-col sm:flex-row gap-3 mt-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg transition duration-150 ease-in-out flex items-center justify-center">
                    <i class="bi bi-filter mr-2"></i> Terapkan Filter
                </button>
                <a href="{{ route('admin.transactions.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2.5 px-4 rounded-lg transition duration-150 ease-in-out flex items-center justify-center">
                    <i class="bi bi-x-circle mr-2"></i> Reset
                </a>
                <button type="button" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2.5 px-4 rounded-lg transition duration-150 ease-in-out flex items-center justify-center ml-auto">
                    <i class="bi bi-download mr-2"></i> Export
                </button>
            </div>
        </form>
    </div>

    <!-- Tabel Transaksi -->
    <div class="overflow-hidden bg-white rounded-xl shadow-md border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-indigo-50">
                    <tr>
                        <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold text-indigo-800 uppercase tracking-wider">
                            ID
                            <i class="bi bi-arrow-down text-indigo-600 ml-1" title="Terbaru ke Lama"></i>
                        </th>
                        <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold text-indigo-800 uppercase tracking-wider">Order ID</th>
                        <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold text-indigo-800 uppercase tracking-wider">Pelanggan</th>
                        <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold text-indigo-800 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold text-indigo-800 uppercase tracking-wider">Pembayaran</th>
                        <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold text-indigo-800 uppercase tracking-wider">Jumlah</th>
                        <th scope="col" class="px-4 py-3.5 text-left text-xs font-semibold text-indigo-800 uppercase tracking-wider">
                            Waktu Transaksi
                            <i class="bi bi-arrow-down text-indigo-600 ml-1" title="Terbaru ke Lama"></i>
                        </th>
                        <th scope="col" class="px-4 py-3.5 text-center text-xs font-semibold text-indigo-800 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($transactions as $transaction)
                    <tr class="hover:bg-blue-50 transition duration-150 ease-in-out">
                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            <div class="flex items-center">
                                {{ $transaction->id }}
                                @if($loop->first)
                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                    <i class="bi bi-clock mr-1"></i>Terbaru
                                </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700 font-mono">{{ $transaction->order_id }}</td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            @if($transaction->booking)
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-gray-900">{{ $transaction->booking->customer_name }}</span>
                                <span class="text-xs text-gray-500">{{ $transaction->booking->customer_email }}</span>
                            </div>
                            @else
                            <span class="text-xs text-gray-400 italic">Data tidak tersedia</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
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
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">
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
                                <span class="capitalize">{{ $transaction->payment_type ? str_replace('_', ' ', $transaction->payment_type) : 'Belum ditentukan' }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-blue-600">Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}</td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">
                            @if($transaction->transaction_time)
                            <div class="flex flex-col">
                                <span class="font-medium">{{ $transaction->transaction_time->format('d/m/Y') }}</span>
                                <span class="text-xs text-gray-500">{{ $transaction->transaction_time->format('H:i:s') }}</span>
                                <span class="text-xs text-blue-600">{{ $transaction->transaction_time->diffForHumans() }}</span>
                            </div>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('admin.transactions.show', $transaction->id) }}"
                                    class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 rounded-full p-2 inline-flex transition duration-150 ease-in-out"
                                    title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($transaction->transaction_status == 'pending')
                                <button
                                    class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 rounded-full p-2 inline-flex transition duration-150 ease-in-out"
                                    title="Batalkan Transaksi">
                                    <i class="bi bi-x-circle"></i>
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
                                        Coba ubah filter pencarian Anda atau
                                    @else
                                        Belum ada transaksi yang dibuat. Silakan
                                    @endif
                                </p>
                                <a href="{{ route('admin.transactions.index') }}" class="mt-4 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
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

    <!-- Pagination -->
    <div class="mt-6">
        {{ $transactions->appends(request()->query())->links() }}
    </div>

    <!-- Footer Info -->
    <div class="mt-8 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 rounded-lg p-5">
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
                        <span class="text-gray-700"><span class="font-medium">Settlement/Capture:</span> Pembayaran berhasil</span>
                    </div>
                    <div class="flex items-center bg-white p-2 rounded-lg shadow-sm">
                        <span class="w-4 h-4 rounded-full bg-yellow-500 mr-2 flex items-center justify-center">
                            <i class="bi bi-hourglass-split text-white text-xs"></i>
                        </span>
                        <span class="text-gray-700"><span class="font-medium">Pending:</span> Menunggu pembayaran</span>
                    </div>
                    <div class="flex items-center bg-white p-2 rounded-lg shadow-sm">
                        <span class="w-4 h-4 rounded-full bg-red-500 mr-2 flex items-center justify-center">
                            <i class="bi bi-x text-white text-xs"></i>
                        </span>
                        <span class="text-gray-700"><span class="font-medium">Cancel/Deny/Expire:</span> Pembayaran gagal</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

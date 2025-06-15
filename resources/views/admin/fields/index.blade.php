@extends('layouts.admin')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 w-full">
        <div class="container mx-auto px-2 sm:px-4 lg:px-8 py-4 lg:py-8 max-w-7xl">
            {{-- Header Section - Mobile Responsive --}}
            <div class="bg-white rounded-xl lg:rounded-2xl shadow-lg lg:shadow-xl p-4 sm:p-6 lg:p-8 mb-6 lg:mb-8">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h2 class="text-xl sm:text-2xl lg:text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                            Daftar Lapangan
                        </h2>
                        <p class="text-sm sm:text-base text-gray-600 mt-1 lg:mt-2">Kelola semua lapangan dalam satu dashboard</p>
                    </div>
                    <a href="{{ route('admin.fields.create') }}" 
                       class="inline-flex items-center px-3 sm:px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs sm:text-sm text-white hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring ring-blue-300 transition ease-in-out duration-150 shadow-lg hover:shadow-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 mr-1 sm:mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        <span class="hidden sm:inline">Tambah Lapangan</span>
                        <span class="sm:hidden">Tambah</span>
                    </a>
                </div>
            </div>

            {{-- Search and Filter - Mobile Responsive --}}
            <div class="bg-white rounded-xl lg:rounded-2xl shadow-lg lg:shadow-xl p-4 sm:p-6 mb-6 lg:mb-8">
                <div class="flex flex-col gap-3 sm:flex-row sm:gap-4">
                    <div class="relative flex-grow">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" id="search" placeholder="Cari lapangan..." 
                               class="pl-8 sm:pl-10 pr-3 sm:pr-4 py-2 w-full rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base">
                    </div>
                    <div class="flex gap-2">
                        <select id="status-filter" 
                                class="rounded-lg border border-gray-300 text-gray-700 px-3 sm:px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base flex-1 sm:flex-none">
                            <option value="">Semua Status</option>
                            <option value="active">Aktif</option>
                            <option value="inactive">Nonaktif</option>
                        </select>
                        <button type="button" id="reset-filter" 
                                class="inline-flex items-center px-2 sm:px-3 py-2 border border-gray-300 shadow-sm text-xs sm:text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 sm:h-4 sm:w-4 mr-0 sm:mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                            </svg>
                            <span class="hidden sm:inline">Reset</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Stats Cards - Mobile Responsive --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 lg:gap-6 mb-6 lg:mb-8">
                <div class="bg-white rounded-xl lg:rounded-2xl shadow-lg lg:shadow-xl p-4 sm:p-6 border-l-4 border-blue-500 group hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 p-2 sm:p-3 rounded-lg lg:rounded-xl bg-blue-100 group-hover:bg-blue-200 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="ml-3 sm:ml-4">
                            <h3 class="text-xs sm:text-sm text-gray-500 font-medium">Total Lapangan</h3>
                            <p class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-800">{{ $fields->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl lg:rounded-2xl shadow-lg lg:shadow-xl p-4 sm:p-6 border-l-4 border-green-500 group hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 p-2 sm:p-3 rounded-lg lg:rounded-xl bg-green-100 group-hover:bg-green-200 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3 sm:ml-4">
                            <h3 class="text-xs sm:text-sm text-gray-500 font-medium">Lapangan Aktif</h3>
                            <p class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-800">{{ $fields->where('is_active', true)->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl lg:rounded-2xl shadow-lg lg:shadow-xl p-4 sm:p-6 border-l-4 border-red-500 group hover:shadow-xl transition-all duration-300 sm:col-span-3 lg:col-span-1">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 p-2 sm:p-3 rounded-lg lg:rounded-xl bg-red-100 group-hover:bg-red-200 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3 sm:ml-4">
                            <h3 class="text-xs sm:text-sm text-gray-500 font-medium">Lapangan Nonaktif</h3>
                            <p class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-800">{{ $fields->where('is_active', false)->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table - Mobile Responsive --}}
            <div class="bg-white rounded-xl lg:rounded-2xl shadow-lg lg:shadow-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-indigo-50 to-purple-50">
                            <tr>
                                <th scope="col" class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-indigo-800 uppercase tracking-wider">
                                    ID
                                </th>
                                <th scope="col" class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-indigo-800 uppercase tracking-wider">
                                    Lapangan
                                </th>
                                <th scope="col" class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-indigo-800 uppercase tracking-wider hidden md:table-cell">
                                    Harga per Jam
                                </th>
                                <th scope="col" class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-indigo-800 uppercase tracking-wider hidden lg:table-cell">
                                    Jam Operasional
                                </th>
                                <th scope="col" class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-indigo-800 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-3 sm:px-6 py-3 sm:py-4 text-right text-xs font-bold text-indigo-800 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($fields as $field)
                            <tr class="hover:bg-blue-50 transition-colors">
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm font-medium text-gray-900">
                                    #{{ $field->id }}
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8 sm:h-10 sm:w-10">
                                            @if($field->image_url)
                                            <img class="h-8 w-8 sm:h-10 sm:w-10 rounded-lg object-cover" src="{{ $field->image_url }}" alt="{{ $field->name }}">
                                            @else
                                            <div class="h-8 w-8 sm:h-10 sm:w-10 rounded-lg bg-gray-200 flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-6 sm:w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="ml-2 sm:ml-4">
                                            <div class="text-xs sm:text-sm font-medium text-gray-900">
                                                {{ $field->name }}
                                            </div>
                                            @if($field->description)
                                            <div class="text-xs text-gray-500 truncate max-w-xs hidden sm:block">
                                                {{ Str::limit($field->description, 50) }}
                                            </div>
                                            @endif
                                            {{-- Mobile: Show price and hours --}}
                                            <div class="md:hidden">
                                                <div class="text-xs text-gray-600 font-medium">Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}/jam</div>
                                                <div class="text-xs text-gray-500">{{ sprintf('%02d:00', $field->opening_hour) }} - {{ sprintf('%02d:00', $field->closing_hour) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap hidden md:table-cell">
                                    <div class="text-xs sm:text-sm text-gray-900 font-medium">Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}</div>
                                    <div class="text-xs text-gray-500">per jam</div>
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap hidden lg:table-cell">
                                    <div class="text-xs sm:text-sm text-gray-900">{{ sprintf('%02d:00', $field->opening_hour) }} - {{ sprintf('%02d:00', $field->closing_hour) }}</div>
                                    <div class="text-xs text-gray-500">{{ $field->closing_hour - $field->opening_hour }} jam</div>
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $field->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $field->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-right text-xs sm:text-sm font-medium">
                                    <div class="flex justify-end space-x-1 sm:space-x-2">
                                        <a href="{{ route('admin.fields.show', $field->id) }}" 
                                           class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 p-1.5 sm:p-2 rounded-lg transition-colors duration-200" 
                                           title="Lihat Detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('admin.fields.edit', $field->id) }}" 
                                           class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 p-1.5 sm:p-2 rounded-lg transition-colors duration-200" 
                                           title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.fields.destroy', $field->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus lapangan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-1.5 sm:p-2 rounded-lg transition-colors duration-200" 
                                                    title="Hapus">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($fields->isEmpty())
                <div class="text-center py-8 sm:py-12">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-10 w-10 sm:h-12 sm:w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada lapangan</h3>
                    <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan lapangan baru.</p>
                    <div class="mt-4 sm:mt-6">
                        <a href="{{ route('admin.fields.create') }}" 
                           class="inline-flex items-center px-3 sm:px-4 py-2 border border-transparent shadow-sm text-xs sm:text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                            <svg class="-ml-1 mr-1 sm:mr-2 h-4 w-4 sm:h-5 sm:w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Lapangan
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search');
            const statusFilter = document.getElementById('status-filter');
            const resetFilterButton = document.getElementById('reset-filter');
            const tableRows = document.querySelectorAll('tbody tr');

            function filterTable() {
                const searchValue = searchInput.value.toLowerCase();
                const statusValue = statusFilter.value.toLowerCase();

                tableRows.forEach(row => {
                    const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    const status = row.querySelector('td:nth-child(5)').textContent.toLowerCase().trim();

                    const matchesSearch = name.includes(searchValue);
                    const matchesStatus = statusValue === '' ||
                        (statusValue === 'active' && status === 'aktif') ||
                        (statusValue === 'inactive' && status === 'nonaktif');

                    row.style.display = matchesSearch && matchesStatus ? '' : 'none';
                });
            }

            searchInput.addEventListener('input', filterTable);
            statusFilter.addEventListener('change', filterTable);

            resetFilterButton.addEventListener('click', function() {
                searchInput.value = '';
                statusFilter.value = '';
                tableRows.forEach(row => {
                    row.style.display = '';
                });
            });
        });
    </script>
@endsection

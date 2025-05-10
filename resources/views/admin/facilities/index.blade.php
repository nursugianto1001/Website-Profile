@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="bg-gradient-to-br from-white to-gray-50 p-4 md:p-8 rounded-xl shadow-lg border border-gray-100 w-full">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-indigo-800">Manajemen Fasilitas</h2>
                    <p class="text-gray-500 mt-1">Mengatur dan Mengelola Kebutuhan Fasilitas</p>
                </div>
                <a href="{{ route('admin.facilities.create') }}"
                    class="w-full md:w-auto inline-flex justify-center items-center px-5 py-2.5 bg-blue-600 text-white font-medium rounded-lg shadow-md hover:bg-blue-700 transition-all duration-200 transform hover:-translate-y-0.5">
                    <i class="bi bi-plus-circle mr-2 text-white"></i>
                    Tambah Fasilitas Baru
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-4 md:p-5 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-700">Semua Fasilitas</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th
                                    class="px-3 md:px-6 py-3 md:py-4 text-indigo-800 font-semibold uppercase tracking-wider text-xs">
                                    Foto</th>
                                <th
                                    class="px-3 md:px-6 py-3 md:py-4 text-indigo-800 font-semibold uppercase tracking-wider text-xs">
                                    Nama</th>
                                <th
                                    class="hidden md:table-cell px-3 md:px-6 py-3 md:py-4 text-indigo-800 font-semibold uppercase tracking-wider text-xs">
                                    Deskripsi</th>
                                <th
                                    class="px-3 md:px-6 py-3 md:py-4 text-indigo-800 font-semibold uppercase tracking-wider text-xs text-right">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($facilities as $facility)
                                <tr class="hover:bg-blue-50 transition-colors duration-150">
                                    <td class="px-3 md:px-6 py-3 md:py-4">
                                        <div
                                            class="h-12 w-12 md:h-16 md:w-16 rounded-lg overflow-hidden shadow-sm border border-gray-200">
                                            <img src="{{ asset('storage/' . $facility->image_path) }}"
                                                alt="{{ $facility->name }}" class="h-full w-full object-cover">
                                        </div>
                                    </td>
                                    <td class="px-3 md:px-6 py-3 md:py-4">
                                        <p class="text-gray-800 font-semibold text-sm md:text-base">{{ $facility->name }}
                                        </p>
                                        <p class="text-gray-500 text-xs mt-1 md:hidden">
                                            {{ Str::limit($facility->description, 25) }}
                                        </p>
                                    </td>
                                    <td class="hidden md:table-cell px-3 md:px-6 py-3 md:py-4 text-gray-600">
                                        {{ Str::limit($facility->description, 50) }}
                                    </td>
                                    <td class="px-3 md:px-6 py-3 md:py-4">
                                        <div class="flex justify-end space-x-2 md:space-x-3">
                                            <a href="{{ route('admin.facilities.edit', $facility->id) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 p-2 rounded-md transition-colors duration-200" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('admin.facilities.destroy', $facility) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this facility?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-md transition-colors duration-200" title="Hapus">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-3 md:px-6 py-6 md:py-8 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="bi bi-building text-3xl md:text-4xl text-gray-300 mb-2 md:mb-3"></i>
                                            <p class="text-gray-500 font-medium text-sm md:text-base">Fasilitas Tidak Ditemukan
                                            </p>
                                            <p class="text-gray-400 text-xs md:text-sm mt-1">Tambah Fasilitas Baru Anda untuk Memulai</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

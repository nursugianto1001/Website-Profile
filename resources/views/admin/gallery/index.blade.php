@extends('layouts.admin')

@section('content')
    <div class="container p-4 md:p-8 rounded-lg shadow-md mx-2 md:ml-24">
        <div class="bg-gradient-to-br from-white to-gray-50 p-4 md:p-8 rounded-xl shadow-lg border border-gray-100 w-full">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-3xl font-bold text-indigo-800 flex items-center">
                        Kelola Galeri
                    </h2>
                    <p class="text-gray-500 mt-1">Kelola Foto Dokumentasi dan Poster Anda</p>
                </div>
                <div class="flex flex-col space-y-2 sm:flex-row sm:space-y-0 sm:space-x-3">
                    <a href="{{ route('admin.gallery.create', ['type' => 'poster']) }}"
                        class="px-3 py-1 md:px-4 md:py-2 bg-green-600 text-white text-center text-xs md:text-sm rounded-lg hover:bg-green-700 transition whitespace-nowrap">
                        <i class="bi bi-plus-circle mr-1 md:mr-2"></i> Tambah Poster
                    </a>
                    <a href="{{ route('admin.gallery.create', ['type' => 'documentation']) }}"
                        class="px-3 py-1 md:px-4 md:py-2 bg-purple-600 text-white text-center text-xs md:text-sm rounded-lg hover:bg-purple-700 transition whitespace-nowrap">
                        <i class="bi bi-plus-circle mr-1 md:mr-2"></i> Tambah Dokumentasi
                    </a>
                </div>
            </div>

            <!-- Messages -->
            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-8 rounded-r-lg shadow-sm flex items-start"
                    role="alert">
                    <span class="text-green-500 mr-3"><i class="bi bi-check-circle-fill"></i></span>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <!-- Tab Navigation -->
            <div class="mb-8 border-b border-gray-200">
                <ul class="flex w-full text-sm font-medium text-center" id="galleryTabs" role="tablist">
                    <li class="flex-1" role="presentation">
                        <button class="w-full inline-block p-4 border-b-2 rounded-t-lg active" id="posters-tab"
                            data-tabs-target="#posters" type="button" role="tab" aria-controls="posters"
                            aria-selected="true">
                            <div class="flex items-center justify-center">
                                <i class="bi bi-card-image mr-2"></i>Poster
                                <span
                                    class="bg-gray-100 text-gray-700 ml-2 px-2.5 py-0.5 rounded-full text-xs">{{ $posters->count() }}</span>
                            </div>
                        </button>
                    </li>
                    <li class="flex-1" role="presentation">
                        <button
                            class="w-full inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:border-gray-300"
                            id="documentation-tab" data-tabs-target="#documentation" type="button" role="tab"
                            aria-controls="documentation" aria-selected="false">
                            <div class="flex items-center justify-center">
                                <i class="bi bi-camera mr-2"></i>Dokumentasi
                                <span
                                    class="bg-gray-100 text-gray-700 ml-2 px-2.5 py-0.5 rounded-full text-xs">{{ $documentations->count() }}</span>
                            </div>
                        </button>
                    </li>
                </ul>
            </div>

            <!-- Tab Content -->
            <div id="galleryTabContent">
                <!-- Posters Section -->
                <div class="block" id="posters" role="tabpanel" aria-labelledby="posters-tab">
                    <div class="mb-4 flex justify-between items-center">
                        <h3 class="text-2xl font-semibold text-gray-700">Poster</h3>
                        <div class="flex items-center">
                            <span class="text-sm text-gray-500 mr-2">Urut Berdasarkan:</span>
                            <select id="posterSort"
                                class="text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                <option value="newest">Terbaru</option>
                                <option value="oldest">Terlama</option>
                                <option value="featured">Unggulan Pertama</option>
                            </select>
                        </div>
                    </div>

                    @if ($posters->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($posters as $poster)
                                <div
                                    class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition group">
                                    <div class="h-48 overflow-hidden relative">
                                        <img src="{{ asset('storage/' . $poster->image_path) }}" alt="{{ $poster->title }}"
                                            class="w-full h-full object-cover transition group-hover:scale-105">
                                        <div
                                            class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition flex items-end justify-center p-4">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('admin.gallery.edit', $poster) }}"
                                                    class="p-2 bg-white/90 rounded-full text-gray-700 hover:bg-white">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('admin.gallery.toggle-featured', $poster) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                        class="p-2 bg-white/90 rounded-full {{ $poster->is_featured ? 'text-yellow-500' : 'text-gray-700' }} hover:bg-white">
                                                        <i
                                                            class="bi {{ $poster->is_featured ? 'bi-star-fill' : 'bi-star' }}"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.gallery.destroy', $poster) }}" method="POST"
                                                    onsubmit="return confirm('Apakah kamu yakin untuk menghapus poster?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="p-2 bg-white/90 rounded-full text-red-500 hover:bg-white">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-5">
                                        <div class="flex justify-between items-start mb-3">
                                            <h4 class="font-medium text-gray-800 line-clamp-1">{{ $poster->title }}</h4>
                                            @if ($poster->is_featured)
                                                <span
                                                    class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full flex items-center">
                                                    <i class="bi bi-star-fill mr-1 text-yellow-500"></i> Unggulan
                                                </span>
                                            @endif
                                        </div>

                                        @if ($poster->description)
                                            <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ $poster->description }}
                                            </p>
                                        @endif

                                        <div class="flex justify-between pt-3 border-t border-gray-100">
                                            <div class="text-xs text-gray-500">Order: {{ $poster->display_order }}</div>
                                            <div class="flex items-center space-x-1">
                                                <a href="{{ route('admin.gallery.edit', $poster) }}"
                                                    class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                                                    <i class="bi bi-pencil-square mr-1"></i> Edit
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-gray-50 p-8 text-center rounded-xl border border-dashed border-gray-300">
                            <div class="text-gray-400 text-5xl mb-4">
                                <i class="bi bi-card-image"></i>
                            </div>
                            <p class="text-gray-500 mb-4"> Poster Tidak DItemukan.</p>
                            <a href="{{ route('admin.gallery.create', ['type' => 'poster']) }}"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                <i class="bi bi-plus-circle mr-2"></i> Tambah Poster Pertama Anda
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Documentation Photos Section -->
                <div class="hidden" id="documentation" role="tabpanel" aria-labelledby="documentation-tab">
                    <div class="mb-4 flex justify-between items-center">
                        <h3 class="text-2xl font-semibold text-gray-700">Foto Dokumentasi</h3>
                        <div class="flex items-center">
                            <span class="text-sm text-gray-500 mr-2">Lihat:</span>
                            <div class="flex border rounded-lg overflow-hidden">
                                <button id="gridViewBtn"
                                    class="px-3 py-1 bg-blue-50 text-blue-600 border-r border-gray-200">
                                    <i class="bi bi-grid"></i>
                                </button>
                                <button id="listViewBtn" class="px-3 py-1 bg-white text-gray-600">
                                    <i class="bi bi-list"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    @if ($documentations->count() > 0)
                        <div id="gridView" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                            @foreach ($documentations as $doc)
                                <div
                                    class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition group">
                                    <div class="relative h-40 overflow-hidden">
                                        <img src="{{ asset('storage/' . $doc->image_path) }}" alt="{{ $doc->title }}"
                                            class="w-full h-full object-cover transition group-hover:scale-105">
                                        <div
                                            class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition flex items-end justify-center p-3">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('admin.gallery.edit', $doc) }}"
                                                    class="p-1.5 bg-white/90 rounded-full text-gray-700 hover:bg-white text-xs">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('admin.gallery.toggle-featured', $doc) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                        class="p-1.5 bg-white/90 rounded-full {{ $doc->is_featured ? 'text-yellow-500' : 'text-gray-700' }} hover:bg-white text-xs">
                                                        <i
                                                            class="bi {{ $doc->is_featured ? 'bi-star-fill' : 'bi-star' }}"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.gallery.destroy', $doc) }}" method="POST"
                                                    onsubmit="return confirm('Apakah kamu yakin untuk menghapus dokumentasi ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="p-1.5 bg-white/90 rounded-full text-red-500 hover:bg-white text-xs">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        @if ($doc->is_featured)
                                            <span
                                                class="absolute top-2 right-2 px-1.5 py-0.5 text-xs bg-yellow-500 text-white rounded-full flex items-center">
                                                <i class="bi bi-star-fill mr-1 text-xs"></i> Unggulan
                                            </span>
                                        @endif
                                    </div>
                                    <div class="p-3">
                                        <div class="flex items-center justify-between">
                                            <div class="text-xs text-gray-500">Order: {{ $doc->display_order }}</div>
                                            <a href="{{ route('admin.gallery.edit', $doc) }}"
                                                class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                                                <i class="bi bi-pencil-square mr-1"></i> Edit
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div id="listView" class="hidden">
                            <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Gambar</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Judul</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Urutan</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($documentations as $doc)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="h-16 w-16 overflow-hidden rounded-lg">
                                                        <img src="{{ asset('storage/' . $doc->image_path) }}"
                                                            alt="{{ $doc->title }}" class="h-full w-full object-cover">
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ $doc->title }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500">{{ $doc->display_order }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if ($doc->is_featured)
                                                        <span
                                                            class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full flex items-center w-fit">
                                                            <i class="bi bi-star-fill mr-1 text-yellow-500"></i> Unggulan
                                                        </span>
                                                    @else
                                                        <span
                                                            class="px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded-full w-fit">Tidak
                                                            Unggulan</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                                    <div class="flex justify-end space-x-3">
                                                        <a href="{{ route('admin.gallery.edit', $doc->id) }}"
                                                            class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 p-2 rounded-md transition-colors duration-200"
                                                            title="Edit">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                            </svg>
                                                        </a>
                                                        <form action="{{ route('admin.gallery.toggle-featured', $doc) }}"
                                                            method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit"
                                                                class="{{ $doc->is_featured ? 'text-yellow-500 bg-yellow-50' : 'text-gray-500 bg-gray-50' }} hover:text-yellow-600 hover:bg-yellow-100 p-2 rounded-md transition-colors duration-200"
                                                                title="{{ $doc->is_featured ? 'Hapus dari Unggulan' : 'Tandai sebagai Unggulan' }}">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                                    fill="{{ $doc->is_featured ? 'currentColor' : 'none' }}"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                                                </svg>
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('admin.gallery.destroy', $doc) }}"
                                                            method="POST" class="inline"
                                                            onsubmit="return confirm('Apakah kamu yakin untuk menghapus dokumentasi ini?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-md transition-colors duration-200"
                                                                title="Hapus">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                                    fill="none" viewBox="0 0 24 24"
                                                                    stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
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
                        </div>
                    @else
                        <div class="bg-gray-50 p-8 text-center rounded-xl border border-dashed border-gray-300">
                            <div class="text-gray-400 text-5xl mb-4">
                                <i class="bi bi-camera"></i>
                            </div>
                            <p class="text-gray-500 mb-4"> Foto Dokumentasi tidak Ditemukan.</p>
                            <a href="{{ route('admin.gallery.create', ['type' => 'documentation']) }}"
                                class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                                <i class="bi bi-plus-circle mr-2"></i> Tambah Dokumentasi Pertama Anda
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script>
        // Tab functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('[data-tabs-target]');
            const tabContents = document.querySelectorAll('[role="tabpanel"]');

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    const target = document.querySelector(tab.dataset.tabsTarget);

                    tabContents.forEach(tc => {
                        tc.classList.add('hidden');
                    });
                    tabs.forEach(t => {
                        t.classList.remove('active', 'border-blue-600', 'text-blue-600');
                        t.classList.add('border-transparent');
                    });

                    target.classList.remove('hidden');
                    tab.classList.add('active', 'border-blue-600', 'text-blue-600');
                });
            });

            // Initialize the first tab as active
            tabs[0].classList.add('border-blue-600', 'text-blue-600');

            // View toggle functionality
            const gridViewBtn = document.getElementById('gridViewBtn');
            const listViewBtn = document.getElementById('listViewBtn');
            const gridView = document.getElementById('gridView');
            const listView = document.getElementById('listView');

            gridViewBtn.addEventListener('click', () => {
                gridView.classList.remove('hidden');
                listView.classList.add('hidden');
                gridViewBtn.classList.add('bg-blue-50', 'text-blue-600');
                gridViewBtn.classList.remove('bg-white', 'text-gray-600');
                listViewBtn.classList.add('bg-white', 'text-gray-600');
                listViewBtn.classList.remove('bg-blue-50', 'text-blue-600');
            });

            listViewBtn.addEventListener('click', () => {
                gridView.classList.add('hidden');
                listView.classList.remove('hidden');
                listViewBtn.classList.add('bg-blue-50', 'text-blue-600');
                listViewBtn.classList.remove('bg-white', 'text-gray-600');
                gridViewBtn.classList.add('bg-white', 'text-gray-600');
                gridViewBtn.classList.remove('bg-blue-50', 'text-blue-600');
            });
        });
    </script>
@endsection

@extends('layouts.admin')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 w-full">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8 max-w-7xl">
            {{-- Header Section --}}
            <div class="bg-white rounded-2xl shadow-xl p-6 lg:p-8 mb-8">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl lg:text-4xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                            Kelola Galeri
                        </h1>
                        <p class="text-gray-600 mt-2">Kelola Foto Dokumentasi dan Poster Anda</p>
                    </div>
                    <div class="flex flex-col space-y-2 sm:flex-row sm:space-y-0 sm:space-x-3">
                        <a href="{{ route('admin.gallery.create', ['type' => 'poster']) }}"
                            class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                            <i class="bi bi-plus-circle mr-2"></i> Tambah Poster
                        </a>
                        <a href="{{ route('admin.gallery.create', ['type' => 'documentation']) }}"
                            class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm rounded-lg hover:bg-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                            <i class="bi bi-plus-circle mr-2"></i> Tambah Dokumentasi
                        </a>
                    </div>
                </div>
            </div>

            {{-- Success Message --}}
            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-8 rounded-r-lg shadow-sm flex items-start"
                    role="alert">
                    <span class="text-green-500 mr-3"><i class="bi bi-check-circle-fill"></i></span>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            {{-- Main Content --}}
            <div class="bg-white rounded-2xl shadow-xl p-6 lg:p-8">
                {{-- Tab Navigation --}}
                <div class="mb-8 border-b border-gray-200">
                    <ul class="flex w-full text-sm font-medium text-center" id="galleryTabs" role="tablist">
                        <li class="flex-1" role="presentation">
                            <button class="w-full inline-block p-4 border-b-2 rounded-t-lg active" id="posters-tab"
                                data-tabs-target="#posters" type="button" role="tab" aria-controls="posters"
                                aria-selected="true">
                                <div class="flex items-center justify-center">
                                    <i class="bi bi-card-image mr-2 text-lg"></i>
                                    <span class="font-semibold">Poster</span>
                                    <span class="bg-blue-100 text-blue-800 ml-2 px-2.5 py-0.5 rounded-full text-xs font-medium">{{ $posters->count() }}</span>
                                </div>
                            </button>
                        </li>
                        <li class="flex-1" role="presentation">
                            <button class="w-full inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:border-gray-300"
                                id="documentation-tab" data-tabs-target="#documentation" type="button" role="tab"
                                aria-controls="documentation" aria-selected="false">
                                <div class="flex items-center justify-center">
                                    <i class="bi bi-camera mr-2 text-lg"></i>
                                    <span class="font-semibold">Dokumentasi</span>
                                    <span class="bg-gray-100 text-gray-700 ml-2 px-2.5 py-0.5 rounded-full text-xs font-medium">{{ $documentations->count() }}</span>
                                </div>
                            </button>
                        </li>
                    </ul>
                </div>

                {{-- Tab Content --}}
                <div id="galleryTabContent">
                    {{-- Posters Section --}}
                    <div class="block" id="posters" role="tabpanel" aria-labelledby="posters-tab">
                        <div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                            <h3 class="text-xl lg:text-2xl font-bold text-indigo-800">Poster Collection</h3>
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-gray-500">Urut Berdasarkan:</span>
                                <select id="posterSort" class="text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
                                    <option value="newest">Terbaru</option>
                                    <option value="oldest">Terlama</option>
                                    <option value="featured">Unggulan Pertama</option>
                                </select>
                            </div>
                        </div>

                        @if ($posters->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach ($posters as $poster)
                                    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 group">
                                        <div class="h-48 lg:h-56 overflow-hidden relative">
                                            <img src="{{ asset('storage/' . $poster->image_path) }}" alt="{{ $poster->title }}"
                                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center p-4">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('admin.gallery.edit', $poster) }}"
                                                        class="p-2 bg-white/90 backdrop-blur-sm rounded-full text-blue-600 hover:bg-white hover:text-blue-800 transition-colors shadow-lg">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form action="{{ route('admin.gallery.toggle-featured', $poster) }}" method="POST">
                                                        @csrf
                                                        <button type="submit"
                                                            class="p-2 bg-white/90 backdrop-blur-sm rounded-full {{ $poster->is_featured ? 'text-yellow-500' : 'text-gray-700' }} hover:bg-white transition-colors shadow-lg">
                                                            <i class="bi {{ $poster->is_featured ? 'bi-star-fill' : 'bi-star' }}"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('admin.gallery.destroy', $poster) }}" method="POST"
                                                        onsubmit="return confirm('Apakah kamu yakin untuk menghapus poster?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="p-2 bg-white/90 backdrop-blur-sm rounded-full text-red-500 hover:bg-white hover:text-red-700 transition-colors shadow-lg">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                            @if ($poster->is_featured)
                                                <div class="absolute top-4 right-4">
                                                    <span class="bg-yellow-500 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                                                        <i class="bi bi-star-fill mr-1"></i> Unggulan
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="p-6">
                                            <h4 class="font-bold text-gray-800 text-lg mb-3 line-clamp-1">{{ $poster->title }}</h4>
                                            @if ($poster->description)
                                                <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $poster->description }}</p>
                                            @endif
                                            <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                                                <div class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">Order: {{ $poster->display_order }}</div>
                                                <a href="{{ route('admin.gallery.edit', $poster) }}"
                                                    class="text-sm text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-lg font-medium transition-colors">
                                                    <i class="bi bi-pencil-square mr-1"></i> Edit
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-12 text-center rounded-2xl border-2 border-dashed border-gray-300">
                                <div class="text-gray-400 text-6xl mb-6">
                                    <i class="bi bi-card-image"></i>
                                </div>
                                <h4 class="text-gray-600 font-semibold text-lg mb-2">Poster Tidak Ditemukan</h4>
                                <p class="text-gray-500 mb-6">Belum ada poster yang ditambahkan ke galeri</p>
                                <a href="{{ route('admin.gallery.create', ['type' => 'poster']) }}"
                                    class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                                    <i class="bi bi-plus-circle mr-2"></i> Tambah Poster Pertama Anda
                                </a>
                            </div>
                        @endif
                    </div>

                    {{-- Documentation Photos Section --}}
                    <div class="hidden" id="documentation" role="tabpanel" aria-labelledby="documentation-tab">
                        <div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                            <h3 class="text-xl lg:text-2xl font-bold text-indigo-800">Foto Dokumentasi</h3>
                            <div class="flex items-center gap-4">
                                <span class="text-sm text-gray-500">Tampilan:</span>
                                <div class="flex border rounded-lg overflow-hidden shadow-sm">
                                    <button id="gridViewBtn"
                                        class="px-4 py-2 bg-blue-50 text-blue-600 border-r border-gray-200 transition-colors">
                                        <i class="bi bi-grid mr-1"></i> Grid
                                    </button>
                                    <button id="listViewBtn" class="px-4 py-2 bg-white text-gray-600 transition-colors">
                                        <i class="bi bi-list mr-1"></i> List
                                    </button>
                                </div>
                            </div>
                        </div>

                        @if ($documentations->count() > 0)
                            <div id="gridView" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 lg:gap-6">
                                @foreach ($documentations as $doc)
                                    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 group">
                                        <div class="relative h-40 lg:h-48 overflow-hidden">
                                            <img src="{{ asset('storage/' . $doc->image_path) }}" alt="{{ $doc->title }}"
                                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center p-3">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('admin.gallery.edit', $doc) }}"
                                                        class="p-2 bg-white/90 backdrop-blur-sm rounded-full text-blue-600 hover:bg-white transition-colors shadow-lg">
                                                        <i class="bi bi-pencil text-sm"></i>
                                                    </a>
                                                    <form action="{{ route('admin.gallery.toggle-featured', $doc) }}" method="POST">
                                                        @csrf
                                                        <button type="submit"
                                                            class="p-2 bg-white/90 backdrop-blur-sm rounded-full {{ $doc->is_featured ? 'text-yellow-500' : 'text-gray-700' }} hover:bg-white transition-colors shadow-lg">
                                                            <i class="bi {{ $doc->is_featured ? 'bi-star-fill' : 'bi-star' }} text-sm"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('admin.gallery.destroy', $doc) }}" method="POST"
                                                        onsubmit="return confirm('Apakah kamu yakin untuk menghapus dokumentasi ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="p-2 bg-white/90 backdrop-blur-sm rounded-full text-red-500 hover:bg-white transition-colors shadow-lg">
                                                            <i class="bi bi-trash text-sm"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                            @if ($doc->is_featured)
                                                <span class="absolute top-2 right-2 px-2 py-1 text-xs bg-yellow-500 text-white rounded-full flex items-center shadow-lg">
                                                    <i class="bi bi-star-fill mr-1"></i> Unggulan
                                                </span>
                                            @endif
                                        </div>
                                        <div class="p-4">
                                            <div class="flex items-center justify-between">
                                                <div class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">Order: {{ $doc->display_order }}</div>
                                                <a href="{{ route('admin.gallery.edit', $doc) }}"
                                                    class="text-sm text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-2 py-1 rounded font-medium transition-colors">
                                                    <i class="bi bi-pencil-square mr-1"></i> Edit
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div id="listView" class="hidden">
                                <div class="bg-white shadow-lg rounded-2xl overflow-hidden border border-gray-200">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                            <tr>
                                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Gambar</th>
                                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Judul</th>
                                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Urutan</th>
                                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                                                <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($documentations as $doc)
                                                <tr class="hover:bg-blue-50 transition-colors">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="h-16 w-16 overflow-hidden rounded-xl shadow-md">
                                                            <img src="{{ asset('storage/' . $doc->image_path) }}"
                                                                alt="{{ $doc->title }}" class="h-full w-full object-cover">
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-semibold text-gray-900">{{ $doc->title }}</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded w-fit">{{ $doc->display_order }}</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        @if ($doc->is_featured)
                                                            <span class="px-3 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full flex items-center w-fit font-medium">
                                                                <i class="bi bi-star-fill mr-1 text-yellow-500"></i> Unggulan
                                                            </span>
                                                        @else
                                                            <span class="px-3 py-1 text-xs bg-gray-100 text-gray-600 rounded-full w-fit">Tidak Unggulan</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                                        <div class="flex justify-end space-x-2">
                                                            <a href="{{ route('admin.gallery.edit', $doc->id) }}"
                                                                class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 p-2 rounded-lg transition-colors duration-200 shadow-sm"
                                                                title="Edit">
                                                                <i class="bi bi-pencil-square"></i>
                                                            </a>
                                                            <form action="{{ route('admin.gallery.toggle-featured', $doc) }}" method="POST" class="inline">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="{{ $doc->is_featured ? 'text-yellow-500 bg-yellow-50' : 'text-gray-500 bg-gray-50' }} hover:text-yellow-600 hover:bg-yellow-100 p-2 rounded-lg transition-colors duration-200 shadow-sm"
                                                                    title="{{ $doc->is_featured ? 'Hapus dari Unggulan' : 'Tandai sebagai Unggulan' }}">
                                                                    <i class="bi {{ $doc->is_featured ? 'bi-star-fill' : 'bi-star' }}"></i>
                                                                </button>
                                                            </form>
                                                            <form action="{{ route('admin.gallery.destroy', $doc) }}" method="POST" class="inline"
                                                                onsubmit="return confirm('Apakah kamu yakin untuk menghapus dokumentasi ini?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition-colors duration-200 shadow-sm"
                                                                    title="Hapus">
                                                                    <i class="bi bi-trash"></i>
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
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-12 text-center rounded-2xl border-2 border-dashed border-gray-300">
                                <div class="text-gray-400 text-6xl mb-6">
                                    <i class="bi bi-camera"></i>
                                </div>
                                <h4 class="text-gray-600 font-semibold text-lg mb-2">Foto Dokumentasi Tidak Ditemukan</h4>
                                <p class="text-gray-500 mb-6">Belum ada dokumentasi yang ditambahkan ke galeri</p>
                                <a href="{{ route('admin.gallery.create', ['type' => 'documentation']) }}"
                                    class="inline-flex items-center px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                                    <i class="bi bi-plus-circle mr-2"></i> Tambah Dokumentasi Pertama Anda
                                </a>
                            </div>
                        @endif
                    </div>
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

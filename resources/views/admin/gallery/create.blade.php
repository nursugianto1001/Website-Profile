@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="bg-gradient-to-br from-white to-gray-50 p-4 md:p-8 rounded-xl shadow-lg border border-gray-100 w-full">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800 flex items-center">
                        <span class="text-{{ request('type') == 'documentation' ? 'purple' : 'green' }}-600 mr-3">
                            <i class="bi {{ request('type') == 'documentation' ? 'bi-camera' : 'bi-card-image' }}"></i>
                        </span>
                        {{ request('type') == 'documentation' ? 'Tambah Foto Dokumentasi' : 'Tambah Poster' }}
                    </h2>
                    <p class="text-gray-500 mt-1">Tambah
                        {{ request('type') == 'documentation' ? 'Foto Dokumentasi' : 'Poster' }} Di Galeri Anda</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <!-- Form Header -->
                <div class="mb-6 pb-4 border-b border-gray-100">
                    <div class="flex items-center text-{{ request('type') == 'documentation' ? 'purple' : 'green' }}-600">
                        <i class="bi bi-plus-circle mr-2"></i>
                        <span class="font-medium">Masukkan {{ request('type') == 'documentation' ? 'Dokumentasi' : 'Poster' }}
                            Detail</span>
                    </div>
                </div>

                <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data"
                    class="max-w-full">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Left Column - Basic Info -->
                        <div class="md:col-span-2 space-y-6">
                            <!-- Type field (hidden but can be changed) -->
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Tipe</label>
                                <div class="flex space-x-4">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="type" value="poster"
                                            {{ request('type') == 'poster' ? 'checked' : '' }}
                                            class="rounded-full border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-gray-700">Poster</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="type" value="documentation"
                                            {{ request('type') == 'documentation' ? 'checked' : '' }}
                                            class="rounded-full border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-gray-700">Foto Dokumentasi</span>
                                    </label>
                                </div>
                                @error('type')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Title -->
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Judul</label>
                                <input type="text" name="title" id="title" required value="{{ old('title') }}"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                @error('title')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                    Deskripsi {{ request('type') == 'documentation' ? '(Opsional)' : '' }}
                                </label>
                                <textarea name="description" id="description" rows="4"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Featured and Order -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                    <label for="is_featured" class="flex items-center space-x-3">
                                        <div class="relative inline-block w-10 mr-2 align-middle select-none">
                                            <input type="checkbox" name="is_featured" id="is_featured" value="1"
                                                {{ old('is_featured') ? 'checked' : '' }}
                                                class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" />
                                            <label for="is_featured"
                                                class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                                        </div>
                                        <span class="text-gray-700">Unggulan di Beranda</span>
                                    </label>
                                    <p class="text-xs text-gray-500 mt-2 pl-12">Item unggulan akan ditampilkan secara menonjol di beranda situs web</p>
                                </div>
                                <div>
                                    <label for="display_order" class="block text-sm font-medium text-gray-700 mb-2">Urutab</label>
                                    <div class="relative">
                                        <input type="number" name="display_order" id="display_order"
                                            value="{{ old('display_order', 0) }}"
                                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <i class="bi bi-sort-numeric-down text-gray-400"></i>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Angka yang lebih rendah muncul lebih dulu</p>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Image Upload -->
                        <div class="md:col-span-1">
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 h-full">
                                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Gambar</label>
                                <div
                                    class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed border-gray-300 rounded-lg bg-white">
                                    <div class="space-y-3 text-center">
                                        <div id="upload-icon" class="mx-auto h-16 w-16 text-gray-400">
                                            <i class="bi bi-cloud-upload text-4xl"></i>
                                        </div>
                                        <div id="image-preview" class="hidden mt-2">
                                            <img id="preview-img" src="#" alt="Preview"
                                                class="mx-auto max-h-40 rounded-md">
                                        </div>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="image"
                                                class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                                <span>Unggah File</span>
                                                <input type="file" name="image" id="image" required
                                                    accept="image/*" class="sr-only">
                                            </label>
                                            <p class="pl-1">atau seret dan lepas</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                    </div>
                                </div>
                                @error('image')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end mt-8 pt-5 border-t border-gray-100">
                        <a href="{{ route('admin.gallery.index') }}"
                            class="px-5 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors mr-3">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium rounded-lg hover:from-blue-600 hover:to-blue-700 transition shadow-sm flex items-center">
                            Simpan {{ request('type') == 'documentation' ? 'Photo' : 'Poster' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>


        <style>
            .toggle-checkbox:checked {
                right: 0;
                border-color: #3B82F6;
            }

            .toggle-checkbox:checked+.toggle-label {
                background-color: #3B82F6;
            }
        </style>

        <script>
            // Image preview functionality
            document.getElementById('image').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('preview-img').src = e.target.result;
                        document.getElementById('image-preview').classList.remove('hidden');
                        document.getElementById('upload-icon').classList.add('hidden');
                    }
                    reader.readAsDataURL(file);
                }
            });

            // Drag and drop functionality
            const dropArea = document.querySelector('label[for="image"]').closest('div.border-dashed');

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                dropArea.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, unhighlight, false);
            });

            function highlight() {
                dropArea.classList.add('border-blue-500', 'bg-blue-50');
            }

            function unhighlight() {
                dropArea.classList.remove('border-blue-500', 'bg-blue-50');
            }

            dropArea.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                const fileInput = document.getElementById('image');

                if (files.length) {
                    fileInput.files = files;
                    // Trigger change event manually
                    const event = new Event('change', {
                        bubbles: true
                    });
                    fileInput.dispatchEvent(event);
                }
            }
        </script>
    @endsection

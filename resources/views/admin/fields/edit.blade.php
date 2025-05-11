@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-indigo-800">Edit Lapangan</h1>
            <p class="text-gray-600 mt-1">Perbarui informasi untuk lapangan "{{ $field->name }}"</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
                <h2 class="text-lg font-semibold text-gray-800">Detail Lapangan</h2>
            </div>
        </div>

        <form action="{{ route('admin.fields.update', $field->id) }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            <span class="flex items-center">
                                Nama Lapangan
                                <span class="text-red-500 ml-1">*</span>
                            </span>
                        </label>
                        <input type="text" name="name" id="name"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-shadow @error('name') border-red-500 @enderror"
                            value="{{ old('name', $field->name) }}" required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="price_per_hour" class="block text-sm font-medium text-gray-700 mb-1">
                            <span class="flex items-center">
                                Harga per Jam
                                <span class="text-red-500 ml-1">*</span>
                            </span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" name="price_per_hour" id="price_per_hour"
                                class="pl-12 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-shadow @error('price_per_hour') border-red-500 @enderror"
                                value="{{ old('price_per_hour', $field->price_per_hour) }}" required>
                        </div>
                        @error('price_per_hour')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="description" id="description" rows="5"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-shadow @error('description') border-red-500 @enderror"
                            placeholder="Masukkan deskripsi singkat tentang lapangan ini...">{{ old('description', $field->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="p-4 bg-blue-50 rounded-lg border border-blue-100">
                        <div class="flex items-center mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="font-medium text-blue-800">Informasi Status</span>
                        </div>
                        <div class="mt-2">
                            <div class="flex items-center">
                                <div class="relative inline-block w-10 mr-2 align-middle select-none">
                                    <input type="checkbox" name="is_active" id="is_active"
                                        class="absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-transform duration-200 ease-in-out checked:right-0 checked:border-blue-600 checked:bg-blue-600"
                                        value="1" {{ old('is_active', $field->is_active) ? 'checked' : '' }}>
                                    <label for="is_active" class="block h-6 overflow-hidden bg-gray-300 rounded-full cursor-pointer"></label>
                                </div>
                                <label for="is_active" class="text-sm font-medium text-gray-700 cursor-pointer">Lapangan Aktif</label>
                            </div>
                            <p class="mt-1 text-xs text-gray-600">Jika diaktifkan, lapangan dapat dipesan oleh pengguna</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <label for="opening_hour" class="block text-sm font-medium text-gray-700 mb-1">
                                <span class="flex items-center">
                                    Jam Buka
                                    <span class="text-red-500 ml-1">*</span>
                                </span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <select name="opening_hour" id="opening_hour"
                                    class="pl-10 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-shadow @error('opening_hour') border-red-500 @enderror">
                                    @for($i = 0; $i < 24; $i++)
                                        <option value="{{ $i }}" {{ old('opening_hour', $field->opening_hour) == $i ? 'selected' : '' }}>
                                            {{ sprintf('%02d:00', $i) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            @error('opening_hour')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="closing_hour" class="block text-sm font-medium text-gray-700 mb-1">
                                <span class="flex items-center">
                                    Jam Tutup
                                    <span class="text-red-500 ml-1">*</span>
                                </span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <select name="closing_hour" id="closing_hour"
                                    class="pl-10 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-shadow @error('closing_hour') border-red-500 @enderror">
                                    @for($i = 0; $i < 24; $i++)
                                        <option value="{{ $i }}" {{ old('closing_hour', $field->closing_hour) == $i ? 'selected' : '' }}>
                                            {{ sprintf('%02d:00', $i) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            @error('closing_hour')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="image_url" class="block text-sm font-medium text-gray-700 mb-2">Gambar Lapangan</label>

                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="space-y-2 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="image_url" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Unggah gambar</span>
                                        <input id="image_url" name="image_url" type="file" class="sr-only">
                                    </label>
                                    <p class="pl-1">atau seret dan lepas</p>
                                </div>
                                <p class="text-xs text-gray-500">
                                    Format: JPG, JPEG, PNG (Maks. 2MB)
                                </p>
                            </div>
                        </div>

                        @error('image_url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    @if($field->image_url)
                        <div class="mt-3">
                            <p class="text-sm font-medium text-gray-700 mb-2">Gambar Saat Ini:</p>
                            <div class="relative group rounded-lg overflow-hidden shadow-sm border border-gray-200">
                                <img src="{{ $field->image_url }}" alt="{{ $field->name }}" class="h-48 w-full object-cover">
                                <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="text-white text-sm font-medium px-3 py-1 bg-black bg-opacity-50 rounded-md">
                                        Akan diganti jika unggah gambar baru
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-10 pt-6 border-t border-gray-200 flex items-center justify-between">
                <div class="flex items-center text-sm text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>Kolom bertanda <span class="text-red-500">*</span> wajib diisi</span>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.fields.index') }}" class="inline-flex justify-center items-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex justify-center items-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

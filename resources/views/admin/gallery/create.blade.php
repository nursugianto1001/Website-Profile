@extends('layouts.admin')

@section('content')
<div class="bg-white p-8 rounded-lg shadow-md ml-24">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-semibold text-gray-800">
            {{ request('type') == 'documentation' ? 'Add Documentation Photo' : 'Add Poster' }}
        </h2>
        <a href="{{ route('admin.gallery.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
            <i class="bi bi-arrow-left mr-2"></i> Back to Gallery
        </a>
    </div>

    <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data" class="max-w-3xl">
        @csrf
        
        <!-- Type field (hidden but can be changed) -->
        <div class="mb-6">
            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type</label>
            <select name="type" id="type" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="poster" {{ request('type') == 'poster' ? 'selected' : '' }}>Poster</option>
                <option value="documentation" {{ request('type') == 'documentation' ? 'selected' : '' }}>Documentation Photo</option>
            </select>
            @error('type')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Title -->
        <div class="mb-6">
            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
            <input type="text" name="title" id="title" required value="{{ old('title') }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('title')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Description -->
        <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                Description {{ request('type') == 'documentation' ? '(Optional)' : '' }}
            </label>
            <textarea name="description" id="description" rows="3"
                     class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
            @error('description')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Image Upload -->
        <div class="mb-6">
            <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Image</label>
            <div class="flex items-center">
                <input type="file" name="image" id="image" required accept="image/*"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <p class="text-sm text-gray-500 mt-1">Accepted formats: JPEG, PNG, JPG, GIF. Max size: 2MB.</p>
            <div id="image-preview" class="mt-3 hidden">
                <img id="preview-img" src="#" alt="Preview" class="max-h-40 rounded-md">
            </div>
            @error('image')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Featured and Order -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="is_featured" class="flex items-center">
                    <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-600">Feature this item on homepage</span>
                </label>
            </div>
            <div>
                <label for="display_order" class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
                <input type="number" name="display_order" id="display_order" value="{{ old('display_order', 0) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-sm text-gray-500 mt-1">Lower numbers appear first</p>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                Save {{ request('type') == 'documentation' ? 'Photo' : 'Poster' }}
            </button>
        </div>
    </form>
</div>

<script>
    // Image preview functionality
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-img').src = e.target.result;
                document.getElementById('image-preview').classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
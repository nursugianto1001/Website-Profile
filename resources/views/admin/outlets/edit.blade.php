@extends('layouts.admin')

@section('content')
    <div class="bg-white p-8 rounded-lg shadow-md ml-24">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="mb-6">
                <h2 class="text-2xl font-semibold">Edit Outlet</h2>
            </div>

            <form action="{{ route('admin.outlets.update', $outlet) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $outlet->name) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                    @error('name')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <textarea name="address" id="address" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>{{ old('address', $outlet->address) }}</textarea>
                    @error('address')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="opening_hours" class="block text-sm font-medium text-gray-700 mb-1">Opening Hours</label>
                    <input type="text" name="opening_hours" id="opening_hours" value="{{ old('opening_hours', $outlet->opening_hours) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                    @error('opening_hours')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="contact" class="block text-sm font-medium text-gray-700 mb-1">Contact</label>
                    <input type="text" name="contact" id="contact" value="{{ old('contact', $outlet->contact) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                    @error('contact')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Image</label>
                    @if($outlet->image_path)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $outlet->image_path) }}" alt="{{ $outlet->name }}" class="h-32 w-32 object-cover rounded">
                        </div>
                    @endif
                    <input type="file" name="image" id="image" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <p class="text-sm text-gray-500 mt-1">Leave empty to keep the current image</p>
                    @error('image')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex items-center justify-end mt-6">
                    <a href="{{ route('admin.outlets.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 mr-2">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Update Outlet</button>
                </div>
            </form>
        </div>
    </div>
@endsection

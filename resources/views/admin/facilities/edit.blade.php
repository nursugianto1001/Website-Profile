@extends('layouts.admin')

@section('content')
    <div class="bg-gradient-to-br from-white to-gray-50 p-8 rounded-xl shadow-lg ml-24 border border-gray-100">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Edit Facility</h2>
                <p class="text-gray-500 mt-1">Update facility information and image</p>
            </div>
            <a href="{{ route('admin.facilities.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all duration-200">
                <i class="bi bi-arrow-left mr-2"></i>
                Back to List
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-5 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-700">Facility Details</h3>
            </div>
            
            <div class="p-6">
                <form action="{{ route('admin.facilities.update', $facility) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Facility Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $facility->name) }}" 
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-colors" required>
                                @error('name')
                                    <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <textarea name="description" id="description" rows="6" 
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-colors" required>{{ old('description', $facility->description) }}</textarea>
                                @error('description')
                                    <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="space-y-6">
                            <div>
                                <label for="image" class="block text-sm font-medium text-gray-700 mb-3">Facility Image</label>
                                @if($facility->image_path)
                                    <div class="mb-4 border border-gray-200 rounded-xl p-4 bg-gray-50">
                                        <p class="text-xs text-gray-500 mb-2">Current Image</p>
                                        <div class="relative">
                                            <img src="{{ asset('storage/' . $facility->image_path) }}" alt="{{ $facility->name }}" 
                                                class="h-48 w-full object-cover rounded-lg shadow-sm">
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition-colors">
                                    <input type="file" name="image" id="image" class="hidden" accept="image/*">
                                    <label for="image" class="cursor-pointer">
                                        <div class="space-y-2">
                                            <i class="bi bi-cloud-arrow-up text-3xl text-gray-400"></i>
                                            <p class="text-sm font-medium text-gray-700">Click to upload new image</p>
                                            <p class="text-xs text-gray-500">or drag and drop</p>
                                        </div>
                                    </label>
                                </div>
                                <p class="text-sm text-gray-500 mt-2">Leave empty to keep the current image</p>
                                @error('image')
                                    <span class="text-red-600 text-sm block mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8 pt-5 border-t border-gray-200">
                        <a href="{{ route('admin.facilities.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors mr-3">
                            Cancel
                        </a>
                        <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white font-medium rounded-lg shadow-md hover:bg-blue-700 transition-all duration-200 transform hover:-translate-y-0.5">
                            Update Facility
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
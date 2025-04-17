@extends('layouts.admin')

@section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="mb-6">
                <h2 class="text-2xl font-semibold">Edit Career Opportunity</h2>
            </div>

            <form action="{{ route('admin.careers.update', $career) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label for="position" class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                    <input type="text" name="position" id="position" value="{{ old('position', $career->position) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                    @error('position')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="description" rows="4" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>{{ old('description', $career->description) }}</textarea>
                    @error('description')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="requirements" class="block text-sm font-medium text-gray-700 mb-1">Requirements</label>
                    <textarea name="requirements" id="requirements" rows="4" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>{{ old('requirements', $career->requirements) }}</textarea>
                    @error('requirements')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex items-center justify-end mt-6">
                    <a href="{{ route('admin.careers.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 mr-2">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Update Career Opportunity</button>
                </div>
            </form>
        </div>
    </div>
@endsection
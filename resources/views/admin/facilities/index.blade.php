@extends('layouts.admin')

@section('content')
<div class="bg-gradient-to-br from-white to-gray-50 p-8 rounded-xl shadow-lg ml-24 border border-gray-100">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-bold text-indigo-800">Facilities Management</h2>
            <p class="text-gray-500 mt-1">Manage and organize your property facilities</p>
        </div>
        <a href="{{ route('admin.facilities.create') }}" class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white font-medium rounded-lg shadow-md hover:bg-blue-700 transition-all duration-200 transform hover:-translate-y-0.5">
            <i class="bi bi-plus-circle mr-2 text-white"></i>
            Add New Facility
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-5 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-700">All Facilities</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-indigo-800 font-semibold uppercase tracking-wider text-xs">Image</th>
                        <th class="px-6 py-4 text-indigo-800 font-semibold uppercase tracking-wider text-xs">Name</th>
                        <th class="px-6 py-4 text-indigo-800 font-semibold uppercase tracking-wider text-xs">Description</th>
                        <th class="px-6 py-4 text-indigo-800 font-semibold uppercase tracking-wider text-xs text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($facilities as $facility)
                        <tr class="hover:bg-blue-50 transition-colors duration-150">
                            <td class="px-6 py-4">
                                <div class="h-16 w-16 rounded-lg overflow-hidden shadow-sm border border-gray-200">
                                    <img src="{{ asset('storage/' . $facility->image_path) }}" alt="{{ $facility->name }}" class="h-full w-full object-cover">
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-gray-800 font-semibold">{{ $facility->name }}</p>
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ Str::limit($facility->description, 50) }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end space-x-3">
                                    <a href="{{ route('admin.facilities.edit', $facility) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-600 rounded-md hover:bg-blue-100 transition-colors border border-blue-200">
                                        <i class="bi bi-pencil-square mr-1.5"></i>
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.facilities.destroy', $facility) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this facility?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-600 rounded-md hover:bg-red-100 transition-colors border border-red-200">
                                            <i class="bi bi-trash mr-1.5"></i>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="bi bi-building text-4xl text-gray-300 mb-3"></i>
                                    <p class="text-gray-500 font-medium">No facilities found</p>
                                    <p class="text-gray-400 text-sm mt-1">Add your first facility to get started</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
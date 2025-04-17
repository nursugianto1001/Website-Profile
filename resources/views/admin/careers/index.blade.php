@extends('layouts.admin')

@section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold">Career Opportunities</h2>
                <a href="{{ route('admin.careers.create') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Add New Career</a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                            <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requirements</th>
                            <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($careers as $career)
                            <tr>
                                <td class="py-4 px-4 border-b border-gray-200">{{ $career->position }}</td>
                                <td class="py-4 px-4 border-b border-gray-200">{{ Str::limit($career->description, 50) }}</td>
                                <td class="py-4 px-4 border-b border-gray-200">{{ Str::limit($career->requirements, 50) }}</td>
                                <td class="py-4 px-4 border-b border-gray-200">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.careers.edit', $career) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                        <form action="{{ route('admin.careers.destroy', $career) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this career opportunity?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-4 px-4 border-b border-gray-200 text-center">No career opportunities found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
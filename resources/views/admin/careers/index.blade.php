@extends('layouts.admin')

@section('content')
<div class="bg-white p-8 rounded-lg shadow-md ml-24">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-semibold text-gray-800">Career Opportunities</h2>
        <a href="{{ route('admin.careers.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg shadow hover:bg-blue-700 transition">
            <i class="bi bi-plus-circle mr-2 text-white"></i>
            Add New Career
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left border rounded-lg overflow-hidden">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-3 text-gray-600 font-medium uppercase tracking-wider">Position</th>
                    <th class="px-6 py-3 text-gray-600 font-medium uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3 text-gray-600 font-medium uppercase tracking-wider">Requirements</th>
                    <th class="px-6 py-3 text-gray-600 font-medium uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($careers as $career)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-gray-800 font-medium">{{ $career->position }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ Str::limit($career->description, 50) }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ Str::limit($career->requirements, 50) }}</td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-4">
                                <a href="{{ route('admin.careers.edit', $career) }}" class="text-blue-600 hover:text-blue-800 font-medium">Edit</a>
                                <form action="{{ route('admin.careers.destroy', $career) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this career opportunity?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">No career opportunities found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

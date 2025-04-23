@extends('layouts.admin')

@section('content')
<div class="bg-white p-8 rounded-lg shadow-md ml-24">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-semibold text-gray-800">Outlets</h2>
        <a href="{{ route('admin.outlets.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg shadow hover:bg-blue-700 transition">
            <i class="bi bi-plus-circle mr-2 text-white"></i>
            Add New Outlet
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-left text-sm border rounded-lg overflow-hidden">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-3 text-gray-600 font-medium uppercase tracking-wider">Image</th>
                    <th class="px-6 py-3 text-gray-600 font-medium uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-gray-600 font-medium uppercase tracking-wider">Address</th>
                    <th class="px-6 py-3 text-gray-600 font-medium uppercase tracking-wider">Contact</th>
                    <th class="px-6 py-3 text-gray-600 font-medium uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($outlets as $outlet)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <img src="{{ asset('storage/' . $outlet->image_path) }}" alt="{{ $outlet->name }}" class="h-16 w-16 object-cover rounded-lg shadow-sm">
                        </td>
                        <td class="px-6 py-4 text-gray-800 font-medium">{{ $outlet->name }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $outlet->address }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $outlet->contact }}</td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-4">
                                <a href="{{ route('admin.outlets.edit', $outlet) }}" class="text-blue-600 hover:text-blue-800 font-medium">Edit</a>
                                <form action="{{ route('admin.outlets.destroy', $outlet) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this outlet?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No outlets found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('content')
<div class="bg-white p-8 rounded-lg shadow-md ml-24">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-semibold text-gray-800">Menu Items</h2>
        <a href="{{ route('admin.menus.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg shadow hover:bg-blue-700 transition">
            <i class="bi bi-plus-circle mr-2 text-white"></i>
            Add New Menu Item
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-left text-sm border rounded-lg overflow-hidden">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-3 text-gray-600 font-medium uppercase tracking-wider">Image</th>
                    <th class="px-6 py-3 text-gray-600 font-medium uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-gray-600 font-medium uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-gray-600 font-medium uppercase tracking-wider">Price</th>
                    <th class="px-6 py-3 text-gray-600 font-medium uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($menus as $menu)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <img src="{{ asset('storage/' . $menu->image_path) }}" alt="{{ $menu->name }}" class="h-16 w-16 object-cover rounded-lg shadow-sm">
                        </td>
                        <td class="px-6 py-4 text-gray-800 font-medium">{{ $menu->name }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $menu->category->name }}</td>
                        <td class="px-6 py-4 text-gray-800">Rp{{ number_format($menu->price, 2) }}</td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-4">
                                <a href="{{ route('admin.menus.edit', $menu) }}" class="text-blue-600 hover:text-blue-800 font-medium">Edit</a>
                                <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this menu item?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No menu items found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

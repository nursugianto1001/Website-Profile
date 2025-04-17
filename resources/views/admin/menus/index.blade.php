@extends('layouts.admin')

@section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold">Menu Items</h2>
                <a href="{{ route('admin.menus.create') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Add New Menu Item</a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                            <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($menus as $menu)
                            <tr>
                                <td class="py-4 px-4 border-b border-gray-200">
                                    <img src="{{ asset('storage/' . $menu->image_path) }}" alt="{{ $menu->name }}" class="h-16 w-16 object-cover rounded">
                                </td>
                                <td class="py-4 px-4 border-b border-gray-200">{{ $menu->name }}</td>
                                <td class="py-4 px-4 border-b border-gray-200">{{ $menu->category->name }}</td>
                                <td class="py-4 px-4 border-b border-gray-200">${{ number_format($menu->price, 2) }}</td>
                                <td class="py-4 px-4 border-b border-gray-200">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.menus.edit', $menu) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                        <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this menu item?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-4 px-4 border-b border-gray-200 text-center">No menu items found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@extends('layouts.admin')

@section('content')
<div class="bg-white p-8 rounded-lg shadow-md ml-24">
    <h2 class="text-3xl font-semibold text-gray-800 mb-8">Admin Dashboard</h2>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 hover:shadow-md transition">
            <div class="flex items-center space-x-4">
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="bi bi-menu-button text-blue-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-blue-600">Menu Items</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ \App\Models\Menu::count() }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 hover:shadow-md transition">
            <div class="flex items-center space-x-4">
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="bi bi-shop text-green-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-green-600">Outlets</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ \App\Models\Outlet::count() }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 hover:shadow-md transition">
            <div class="flex items-center space-x-4">
                <div class="bg-purple-100 p-3 rounded-full">
                    <i class="bi bi-briefcase text-purple-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-purple-600">Career Openings</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ \App\Models\Career::count() }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
        <h3 class="text-xl font-semibold text-gray-700 mb-6">Quick Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('admin.categories.create') }}" class="flex items-center p-4 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                <i class="bi bi-plus-circle text-gray-600 text-xl mr-3"></i>
                <span class="text-gray-700 font-medium">Add Category</span>
            </a>
            <a href="{{ route('admin.menus.create') }}" class="flex items-center p-4 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                <i class="bi bi-plus-circle text-gray-600 text-xl mr-3"></i>
                <span class="text-gray-700 font-medium">Add Menu Item</span>
            </a>
            <a href="{{ route('admin.outlets.create') }}" class="flex items-center p-4 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                <i class="bi bi-plus-circle text-gray-600 text-xl mr-3"></i>
                <span class="text-gray-700 font-medium">Add Outlet</span>
            </a>
            <a href="{{ route('admin.careers.create') }}" class="flex items-center p-4 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                <i class="bi bi-plus-circle text-gray-600 text-xl mr-3"></i>
                <span class="text-gray-700 font-medium">Add Career</span>
            </a>
        </div>
    </div>
</div>
@endsection

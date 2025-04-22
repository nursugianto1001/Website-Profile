@extends('layouts.admin')

@section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <h2 class="text-2xl font-semibold mb-6">Dashboard</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="bg-blue-100 rounded-full p-3 mr-4">
                            <i class="bi bi-menu-button text-blue-500"></i>
                        </div>
                        <div>
                            <p class="text-sm text-blue-500 font-medium">Menu Items</p>
                            <h3 class="text-2xl font-bold text-gray-800">{{ \App\Models\Menu::count() }}</h3>
                        </div>
                    </div>
                </div>
                
                <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="bg-green-100 rounded-full p-3 mr-4">
                            <i class="bi bi-shop text-green-500"></i>
                        </div>
                        <div>
                            <p class="text-sm text-green-500 font-medium">Outlets</p>
                            <h3 class="text-2xl font-bold text-gray-800">{{ \App\Models\Outlet::count() }}</h3>
                        </div>
                    </div>
                </div>
                
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="bg-purple-100 rounded-full p-3 mr-4">
                            <i class="bi bi-briefcase text-purple-500"></i>
                        </div>
                        <div>
                            <p class="text-sm text-purple-500 font-medium">Career Openings</p>
                            <h3 class="text-2xl font-bold text-gray-800">{{ \App\Models\Career::count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="{{ route('admin.categories.create') }}" class="bg-white border border-gray-300 rounded-lg p-4 hover:bg-gray-50 flex items-center">
                        <i class="bi bi-plus-circle mr-2 text-gray-600"></i>
                        Add Category
                    </a>
                    <a href="{{ route('admin.menus.create') }}" class="bg-white border border-gray-300 rounded-lg p-4 hover:bg-gray-50 flex items-center">
                        <i class="bi bi-plus-circle mr-2 text-gray-600"></i>
                        Add Menu Item
                    </a>
                    <a href="{{ route('admin.outlets.create') }}" class="bg-white border border-gray-300 rounded-lg p-4 hover:bg-gray-50 flex items-center">
                        <i class="bi bi-plus-circle mr-2 text-gray-600"></i>
                        Add Outlet
                    </a>
                    <a href="{{ route('admin.careers.create') }}" class="bg-white border border-gray-300 rounded-lg p-4 hover:bg-gray-50 flex items-center">
                        <i class="bi bi-plus-circle mr-2 text-gray-600"></i>
                        Add Career
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
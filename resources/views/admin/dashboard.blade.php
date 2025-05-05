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
                    <p class="text-sm font-medium text-blue-600">Facility</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ \App\Models\Facility::count() }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 hover:shadow-md transition">
            <div class="flex items-center space-x-4">
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="bi bi-image text-green-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-green-600">Posters</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ \App\Models\Gallery::where('type', 'poster')->count() }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 hover:shadow-md transition">
            <div class="flex items-center space-x-4">
                <div class="bg-purple-100 p-3 rounded-full">
                    <i class="bi bi-camera text-purple-600 text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-purple-600">Documentation Photos</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ \App\Models\Gallery::where('type', 'documentation')->count() }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Featured Content Preview --}}
    <div class="mb-10">
        <h3 class="text-xl font-semibold text-gray-700 mb-6">Featured Content</h3>
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach(\App\Models\Gallery::where('is_featured', true)->take(3)->get() as $item)
                <div class="bg-white rounded-lg overflow-hidden shadow-sm">
                    <div class="h-40 overflow-hidden">
                        <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                    </div>
                    <div class="p-4">
                        <p class="text-xs font-medium text-gray-500 uppercase">{{ $item->type }}</p>
                        <h4 class="font-medium text-gray-800 truncate">{{ $item->title }}</h4>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
        <h3 class="text-xl font-semibold text-gray-700 mb-6">Quick Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('admin.facilities.create') }}" class="flex items-center p-4 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                <i class="bi bi-plus-circle text-gray-600 text-xl mr-3"></i>
                <span class="text-gray-700 font-medium">Add Facility</span>
            </a>
            <a href="{{ route('admin.gallery.create') }}?type=poster" class="flex items-center p-4 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                <i class="bi bi-image-fill text-gray-600 text-xl mr-3"></i>
                <span class="text-gray-700 font-medium">Add Poster</span>
            </a>
            <a href="{{ route('admin.gallery.create') }}?type=documentation" class="flex items-center p-4 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                <i class="bi bi-camera-fill text-gray-600 text-xl mr-3"></i>
                <span class="text-gray-700 font-medium">Add Documentation</span>
            </a>
            <a href="{{ route('admin.gallery.index') }}" class="flex items-center p-4 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                <i class="bi bi-images text-gray-600 text-xl mr-3"></i>
                <span class="text-gray-700 font-medium">Manage Gallery</span>
            </a>
        </div>
    </div>
</div>
@endsection
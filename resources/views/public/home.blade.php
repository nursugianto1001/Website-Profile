@extends('layouts.public')

@section('title', 'Home')

@section('content')
    <!-- Hero Section -->
    <div class="bg-cover bg-center h-96" style="background-image: url('https://placehold.co/1200x500');">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center">
            <div class="max-w-lg">
                <h1 class="text-4xl font-bold text-white drop-shadow-lg mb-4">
                    Welcome to Our Cafe
                </h1>
                <p class="text-xl text-white drop-shadow-lg mb-8">
                    Experience the best coffee and delicious food in a cozy atmosphere.
                </p>
                <a href="{{ route('menu') }}" class="bg-white text-gray-900 hover:bg-gray-100 px-6 py-3 rounded-md font-medium">
                    View Our Menu
                </a>
            </div>
        </div>
    </div>

    <!-- Featured Menu Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h2 class="text-3xl font-bold text-center mb-12">Featured Menu Items</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($featuredMenus as $menu)
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <img src="{{ asset('storage/' . $menu->image_path) }}" alt="{{ $menu->name }}" class="w-full h-64 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">{{ $menu->name }}</h3>
                        <p class="text-gray-600 mb-4">{{ Str::limit($menu->description, 100) }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-gray-900">${{ number_format($menu->price, 2) }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="text-center mt-10">
            <a href="{{ route('menu') }}" class="bg-gray-800 text-white hover:bg-gray-700 px-6 py-3 rounded-md font-medium">
                View Full Menu
            </a>
        </div>
    </div>

    <!-- Outlets Section -->
    <div class="bg-gray-100 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12">Our Locations</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($outlets as $outlet)
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                        <img src="{{ asset('storage/' . $outlet->image_path) }}" alt="{{ $outlet->name }}" class="w-full h-48 object-cover">
                        <div class="p-6">
                            <h3 class="text-xl font-bold mb-2">{{ $outlet->name }}</h3>
                            <p class="text-gray-600 mb-2">{{ $outlet->address }}</p>
                            <p class="text-gray-600 mb-2"><strong>Hours:</strong> {{ $outlet->opening_hours }}</p>
                            <p class="text-gray-600"><strong>Contact:</strong> {{ $outlet->contact }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="text-center mt-10">
                <a href="{{ route('outlets') }}" class="bg-gray-800 text-white hover:bg-gray-700 px-6 py-3 rounded-md font-medium">
                    View All Locations
                </a>
            </div>
        </div>
    </div>
@endsection
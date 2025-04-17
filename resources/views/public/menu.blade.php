@extends('layouts.public')

@section('title', 'Menu')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h1 class="text-4xl font-bold mb-8 text-center">Our Menu</h1>
        
        <div class="mb-8">
            <p class="text-center text-gray-700 max-w-3xl mx-auto">
                Explore our extensive menu featuring handcrafted coffee beverages, delicious pastries, and savory meals.
                All of our items are made with quality ingredients and prepared with care.
            </p>
        </div>
        
        @foreach($categories as $category)
            @if($category->menus->count() > 0)
                <div class="mb-16">
                    <h2 class="text-2xl font-bold mb-6 pb-2 border-b border-gray-200">{{ $category->name }}</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($category->menus as $menu)
                            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                                <img src="{{ asset('storage/' . $menu->image_path) }}" alt="{{ $menu->name }}" class="w-full h-64 object-cover">
                                <div class="p-6">
                                    <h3 class="text-xl font-bold mb-2">{{ $menu->name }}</h3>
                                    <p class="text-gray-600 mb-4">{{ $menu->description }}</p>
                                    <div class="flex justify-between items-center">
                                        <span class="text-lg font-bold text-gray-900">${{ number_format($menu->price, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@endsection
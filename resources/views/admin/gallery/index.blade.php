@extends('layouts.admin')

@section('content')
<div class="bg-white p-8 rounded-lg shadow-md ml-24">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-semibold text-gray-800">Manage Gallery</h2>
        <div class="flex space-x-3">
            <a href="{{ route('admin.gallery.create', ['type' => 'poster']) }}" 
               class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                <i class="bi bi-plus-circle mr-2"></i> Add Poster
            </a>
            <a href="{{ route('admin.gallery.create', ['type' => 'documentation']) }}" 
               class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                <i class="bi bi-plus-circle mr-2"></i> Add Documentation
            </a>
        </div>
    </div>

    <!-- Messages -->
    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    <!-- Posters Section -->
    <div class="mb-12">
        <h3 class="text-2xl font-medium text-gray-700 mb-6">Posters</h3>
        
        @if($posters->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($posters as $poster)
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
                <div class="h-48 overflow-hidden">
                    <img src="{{ asset('storage/' . $poster->image_path) }}" 
                         alt="{{ $poster->title }}" 
                         class="w-full h-full object-cover">
                </div>
                <div class="p-4">
                    <div class="flex justify-between items-start mb-3">
                        <h4 class="font-medium text-gray-800">{{ $poster->title }}</h4>
                        <span class="px-2 py-1 text-xs {{ $poster->is_featured ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-600' }} rounded-full">
                            {{ $poster->is_featured ? 'Featured' : 'Not Featured' }}
                        </span>
                    </div>
                    
                    @if($poster->description)
                    <p class="text-sm text-gray-500 mb-4">{{ Str::limit($poster->description, 100) }}</p>
                    @endif

                    <div class="flex justify-between pt-3 border-t border-gray-200">
                        <form action="{{ route('admin.gallery.toggle-featured', $poster) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-sm text-blue-600 hover:text-blue-800">
                                {{ $poster->is_featured ? 'Remove from Featured' : 'Add to Featured' }}
                            </button>
                        </form>
                        <div class="flex space-x-3">
                            <a href="{{ route('admin.gallery.edit', $poster) }}" 
                               class="text-sm text-gray-600 hover:text-gray-800">
                                Edit
                            </a>
                            <form action="{{ route('admin.gallery.destroy', $poster) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this poster?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm text-red-600 hover:text-red-800">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-gray-50 p-6 text-center rounded-lg">
            <p class="text-gray-500">No posters found. Add some using the button above.</p>
        </div>
        @endif
    </div>

    <!-- Documentation Photos Section -->
    <div>
        <h3 class="text-2xl font-medium text-gray-700 mb-6">Documentation Photos</h3>
        
        @if($documentations->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($documentations as $doc)
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
                <div class="relative h-40 overflow-hidden">
                    <img src="{{ asset('storage/' . $doc->image_path) }}" 
                         alt="{{ $doc->title }}" 
                         class="w-full h-full object-cover">
                    @if($doc->is_featured)
                    <span class="absolute top-2 right-2 px-2 py-1 text-xs bg-yellow-500 text-white rounded-full">Featured</span>
                    @endif
                </div>
                <div class="p-3">
                    <h4 class="font-medium text-gray-800 truncate mb-2">{{ $doc->title }}</h4>
                    
                    <div class="flex justify-between pt-2 border-t border-gray-200">
                        <form action="{{ route('admin.gallery.toggle-featured', $doc) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs text-blue-600 hover:text-blue-800">
                                {{ $doc->is_featured ? 'Unfeature' : 'Feature' }}
                            </button>
                        </form>
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.gallery.edit', $doc) }}" 
                               class="text-xs text-gray-600 hover:text-gray-800">
                                Edit
                            </a>
                            <form action="{{ route('admin.gallery.destroy', $doc) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this photo?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-red-600 hover:text-red-800">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-gray-50 p-6 text-center rounded-lg">
            <p class="text-gray-500">No documentation photos found. Add some using the button above.</p>
        </div>
        @endif
    </div>
</div>
@endsection
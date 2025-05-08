@extends('layouts.booking-app')
@section('title', 'Available Fields')
@section('content')
<div class="bg-gray-100 py-6">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold mb-6">Available Fields</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($fields as $field)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                @if ($field->image)
                <img src="{{ asset('storage/' . $field->image) }}" alt="{{ $field->name }}" class="w-full h-48 object-cover">
                @else
                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                    <span class="text-gray-400">No image available</span>
                </div>
                @endif
                <div class="p-4">
                    <h3 class="text-xl font-semibold mb-2">{{ $field->name }}</h3>
                    <p class="text-gray-600 mb-3">{{ Str::limit($field->description, 100) }}</p>
                    <div class="mb-3">
                        <div class="flex items-center text-sm text-gray-500 mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $field->location }}
                        </div>
                        <div class="flex items-center text-sm text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $field->opening_hour }} - {{ $field->closing_hour }}
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-primary font-bold">{{ number_format($field->price_per_hour) }} / hour</span>
                        <a href="{{ route('booking.field-detail', $field) }}" class="bg-primary text-blue-900 py-2 px-4 rounded hover:bg-primary-dark transition">View Details</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-8">
                <p class="text-gray-500">No fields available at the moment.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
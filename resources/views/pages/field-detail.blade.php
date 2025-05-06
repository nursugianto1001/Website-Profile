@extends('layouts.booking-app')

@section('title', $field->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('booking.fields') }}" class="flex items-center text-primary hover:underline">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Fields
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-2">
            <div class="p-6">
                @if ($field->image)
                <img src="{{ asset('storage/' . $field->image) }}" alt="{{ $field->name }}" class="w-full h-64 object-cover rounded-lg mb-4">
                @else
                <div class="w-full h-64 bg-gray-200 flex items-center justify-center rounded-lg mb-4">
                    <span class="text-gray-400">No image available</span>
                </div>
                @endif

                <h1 class="text-3xl font-bold mb-4">{{ $field->name }}</h1>

                <div class="mb-4">
                    <div class="flex items-center text-gray-600 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        {{ $field->location }}
                    </div>

                    <div class="flex items-center text-gray-600 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $field->opening_hour }} - {{ $field->closing_hour }}
                    </div>

                    <div class="flex items-center text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        <span class="font-semibold text-primary">{{ number_format($field->price_per_hour) }} / hour</span>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-gray-50">
                <h2 class="text-2xl font-semibold mb-4">Field Description</h2>
                <p class="text-gray-700 mb-6">{{ $field->description }}</p>

                <h2 class="text-2xl font-semibold mb-4">Facilities</h2>
                <ul class="list-disc list-inside text-gray-700 mb-6">
                    @if(!empty($field->facilities))
                    @foreach(json_decode($field->facilities) as $facility)
                    <li>{{ $facility }}</li>
                    @endforeach
                    @else
                    <li>No facilities information available</li>
                    @endif
                </ul>

                <div class="mt-6">
                    <a href="{{ route('booking.form', $field) }}" class="block w-full bg-primary text-white text-center py-3 px-4 rounded-lg font-semibold hover:bg-primary-dark transition">Book Now</a>
                </div>
            </div>
        </div>
    </div>

    @if($field->gallery && count($field->gallery) > 0)
    <div class="mt-8">
        <h2 class="text-2xl font-semibold mb-4">Gallery</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($field->gallery as $image)
            <div class="aspect-square overflow-hidden rounded-lg">
                <img src="{{ asset('storage/' . $image) }}" alt="Field Gallery" class="w-full h-full object-cover hover:scale-105 transition">
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
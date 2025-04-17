@extends('layouts.public')

@section('title', 'Facilities')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h1 class="text-4xl font-bold mb-8 text-center">Our Facilities</h1>
        
        <div class="mb-12">
            <p class="text-center text-gray-700 max-w-3xl mx-auto">
                We offer a variety of amenities to enhance your experience at our cafes.
                Whether you're looking for a quiet space to work, a cozy corner to read, or a venue for your next event,
                we have something for everyone.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            @foreach($facilities as $facility)
                <div class="bg-white shadow-lg rounded-lg overflow-hidden flex flex-col md:flex-row">
                    <div class="md:w-2/5">
                        <img src="{{ asset('storage/' . $facility->image_path) }}" alt="{{ $facility->name }}" class="h-full w-full object-cover">
                    </div>
                    <div class="p-6 md:w-3/5">
                        <h3 class="text-2xl font-bold mb-4">{{ $facility->name }}</h3>
                        <p class="text-gray-700">
                            {{ $facility->description }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
@extends('layouts.public')

@section('title', 'Careers')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h1 class="text-4xl font-bold mb-8 text-center">Join Our Team</h1>
        
        <div class="mb-12">
            <p class="text-center text-gray-700 max-w-3xl mx-auto">
                We're always looking for passionate individuals to join our team. Check out our current openings
                and become part of a company that values quality, community, and sustainability.
            </p>
        </div>
        
        @if($careers->count() > 0)
            <div class="space-y-8">
                @foreach($careers as $career)
                    <div class="bg-white shadow-md rounded-lg p-6">
                        <h2 class="text-2xl font-bold mb-4">{{ $career->position }}</h2>
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-2">Job Description</h3>
                            <div class="text-gray-700">
                                {!! nl2br($career->description) !!}
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-2">Requirements</h3>
                            <div class="text-gray-700">
                                {!! nl2br($career->requirements) !!}
                            </div>
                        </div>
                        
                        <button class="bg-gray-800 text-white px-6 py-2 rounded hover:bg-gray-700 font-medium">
                            Apply Now
                        </button>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center bg-gray-100 p-10 rounded-lg">
                <h3 class="text-xl font-bold mb-4">No Current Openings</h3>
                <p class="text-gray-700">
                    We don't have any open positions at the moment, but we're always interested in hearing from talented individuals.
                    Please check back later or send your resume to careers@example.com.
                </p>
            </div>
        @endif
    </div>
@endsection
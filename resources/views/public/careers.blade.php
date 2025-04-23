@extends('layouts.public')

@section('title', 'Careers')

@section('content')
    <!-- Full-screen Parallax Background -->
    <div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('https://placehold.co/1920x1080');">
        <!-- Semi-transparent overlay for better text readability -->
        <div class="absolute inset-0 bg-black opacity-40"></div>
    </div>

    <!-- Hero Section with transparent overlay -->
    <div class="relative h-screen flex items-center z-10 pt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-lg" data-aos="fade-right" data-aos-duration="1000">
                <h1 class="text-5xl font-bold text-white drop-shadow-lg mb-6">
                    Join Our Team
                </h1>
                <p class="text-xl text-white drop-shadow-lg mb-8 max-w-md">
                    We're always looking for passionate individuals to join our team. Check out our current openings
                    and become part of a company that values quality, community, and sustainability.
                </p>
            </div>
        </div>
    </div>

    <!-- Careers Overview Section - Semi-transparent Layer -->
    <div class="relative z-10">
        <div class="bg-white bg-opacity-80 backdrop-blur-md py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12" data-aos="fade-up" data-aos-duration="800">
                    <h2 class="text-3xl font-bold">Current Openings</h2>
                    <div class="w-20 h-1 bg-gray-800 mx-auto mt-4 mb-2"></div>
                    <p class="text-gray-600 max-w-3xl mx-auto">
                        Join our growing team and help us create exceptional experiences for our customers.
                        We offer competitive benefits and opportunities for growth.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Job Listings Section - Semi-transparent Layer -->
    <div class="relative z-10">
        <div class="bg-black bg-opacity-70 backdrop-blur-md py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @if($careers->count() > 0)
                    <div class="space-y-8">
                        @foreach($careers as $index => $career)
                            <div class="bg-white bg-opacity-90 shadow-lg rounded-lg p-6 transition transform hover:scale-105"
                                 data-aos="fade-up" 
                                 data-aos-duration="800" 
                                 data-aos-delay="{{ $index * 100 }}">
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
                                
                                <button class="bg-gray-800 text-white px-6 py-2 rounded hover:bg-gray-700 font-medium transition duration-300">
                                    Apply Now
                                </button>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center bg-white bg-opacity-90 p-10 rounded-lg shadow-lg" data-aos="fade-up" data-aos-duration="800">
                        <h3 class="text-xl font-bold mb-4">No Current Openings</h3>
                        <p class="text-gray-700">
                            We don't have any open positions at the moment, but we're always interested in hearing from talented individuals.
                            Please check back later or send your resume to careers@example.com.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Why Join Us Section - Semi-transparent Layer -->
    <div class="relative z-10">
        <div class="bg-white bg-opacity-80 backdrop-blur-md py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                    <div data-aos="fade-right" data-aos-duration="1000">
                        <h2 class="text-3xl font-bold mb-6">Why Join Us</h2>
                        <div class="w-20 h-1 bg-gray-800 mb-6"></div>
                        <p class="text-gray-700 mb-4">
                            We believe in creating a positive work environment where everyone can thrive. Our team members
                            enjoy competitive pay, flexible schedules, and opportunities for advancement.
                        </p>
                        <p class="text-gray-700 mb-6">
                            We're committed to diversity, equity, and inclusion, and we value the unique perspectives
                            that each team member brings to our company.
                        </p>
                    </div>
                    <div class="rounded-lg overflow-hidden shadow-xl" data-aos="fade-left" data-aos-duration="1000">
                        <img src="https://placehold.co/600x400" alt="Team Working" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- AOS Library Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                once: true,
                offset: 120,
                duration: 800
            });
        });
    </script>
@endsection
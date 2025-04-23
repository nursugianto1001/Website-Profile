@extends('layouts.public')

@section('title', 'About Us')

@section('content')
    <!-- Full-screen Parallax Background -->
    <div class="fixed inset-0 bg-cover bg-center z-0" style="background-image: url('https://placehold.co/1920x1080');">
        <!-- Semi-transparent overlay for better text readability -->
        <div class="absolute inset-0 bg-black opacity-40"></div>
    </div>
    
    <!-- Main Content - With proper spacing for the fixed header -->
    <div class="relative z-10 pt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <h1 class="text-4xl font-bold mb-8 text-center text-white">About Our Cafe</h1>
            
            <!-- Our Story Section -->
            <div class="bg-white bg-opacity-80 backdrop-blur-md p-8 rounded-lg shadow-lg mb-16" data-aos="fade-up" data-aos-duration="1000">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                    <div>
                        <h2 class="text-2xl font-bold mb-4">Our Story</h2>
                        <div class="w-20 h-1 bg-gray-800 mb-6"></div>
                        <p class="text-gray-700 mb-4">
                            Founded in 2010, our cafe started as a small passion project by a group of friends who shared a love for quality coffee and food. What began as a tiny corner shop has now grown into multiple locations across the city, all while maintaining our commitment to excellence.
                        </p>
                        <p class="text-gray-700">
                            We source our coffee beans directly from farmers, ensuring fair trade practices and the highest quality. Our food menu is crafted using locally sourced ingredients whenever possible, supporting local businesses and reducing our environmental footprint.
                        </p>
                    </div>
                    <div>
                        <img src="https://placehold.co/600x400" alt="Our Cafe Story" class="rounded-lg shadow-lg w-full">
                    </div>
                </div>
            </div>
            
            <!-- Our Mission Section -->
            <div class="bg-black bg-opacity-70 backdrop-blur-md p-8 rounded-lg shadow-lg mb-16 text-white" data-aos="fade-up" data-aos-duration="1000">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                    <div class="order-2 md:order-1">
                        <img src="https://placehold.co/600x400" alt="Our Mission" class="rounded-lg shadow-lg w-full">
                    </div>
                    <div class="order-1 md:order-2">
                        <h2 class="text-2xl font-bold mb-4">Our Mission</h2>
                        <div class="w-20 h-1 bg-white mb-6"></div>
                        <p class="text-gray-200 mb-4">
                            Our mission is to create a warm and welcoming environment where people can enjoy exceptional coffee, delicious food, and meaningful connections. We believe that a cafe should be more than just a place to grab a quick bite – it should be a community hub.
                        </p>
                        <p class="text-gray-200">
                            We are committed to sustainability, community involvement, and creating memorable experiences for every customer who walks through our doors.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Our Values Section -->
            <div class="bg-white bg-opacity-80 backdrop-blur-md p-8 rounded-lg shadow-lg" data-aos="fade-up" data-aos-duration="1000">
                <h2 class="text-2xl font-bold mb-6 text-center">Our Values</h2>
                <div class="w-20 h-1 bg-gray-800 mx-auto mb-8"></div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center p-6 bg-white bg-opacity-90 rounded-lg shadow-md">
                        <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 shadow-md bg-gray-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Quality</h3>
                        <p class="text-gray-700">
                            We never compromise on the quality of our products, from bean to cup and from kitchen to table.
                        </p>
                    </div>
                    
                    <div class="text-center p-6 bg-white bg-opacity-90 rounded-lg shadow-md">
                        <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 shadow-md bg-gray-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Community</h3>
                        <p class="text-gray-700">
                            We strive to create spaces where people feel welcome and connections flourish.
                        </p>
                    </div>
                    
                    <div class="text-center p-6 bg-white bg-opacity-90 rounded-lg shadow-md">
                        <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 shadow-md bg-gray-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Sustainability</h3>
                        <p class="text-gray-700">
                            We are committed to environmental responsibility and ethical business practices.
                        </p>
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
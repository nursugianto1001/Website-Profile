<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Badminton Admin') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
    <!-- Background elements -->
    <div class="badminton-bg">
        <div class="court-overlay"></div>
        <div class="shuttlecock shuttlecock-1"></div>
        <div class="shuttlecock shuttlecock-2"></div>
        <div class="shuttlecock shuttlecock-3"></div>
        <div class="racket racket-1"></div>
    </div>

    <!-- Content -->
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-badminton overflow-hidden sm:rounded-lg">
            <div class="flex justify-center mb-4">
                <div class="badminton-icon">
                    <img src="{{ Vite::asset(asset: 'resources/images/Karvin.png') }}" alt="Karvin Logo"
                        class="h-12 w-auto">
                </div>
            </div>
            <h1 class="text-center text-3xl font-bold text-badminton-blue mb-1">Admin Login</h1>
            <div class="title-underline"></div>
            <p class="text-center text-gray-600 mt-4 mb-1">Selamat Datang di halaman login Admin Panel <br>Karvin
                Badminton</p>
            <p class="text-center text-sm mb-6"><a href="{{ route('password.request') }}"
                    class="text-badminton-green hover:underline">Lupa Password ?</a></p>
            {{ $slot }}
        </div>
    </div>
</body>

</html>

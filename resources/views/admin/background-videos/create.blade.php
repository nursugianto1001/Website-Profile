<!-- resources/views/admin/background-videos/create.blade.php -->
@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold">Upload New Background Video</h1>
        <a href="{{ route('admin.background-videos.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
            <i class="bi bi-arrow-left mr-2"></i>Back to List
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.background-videos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" class="border-gray-300 rounded-md shadow-sm w-full" required>
            </div>

            <div class="mb-6">
                <label for="video" class="block text-sm font-medium text-gray-700 mb-1">Video File</label>
                <input type="file" name="video" id="video" accept="video/mp4,video/webm,video/ogg" class="border-gray-300 rounded-md shadow-sm w-full" required>
                <p class="mt-1 text-sm text-gray-500">Accepted formats: MP4, WebM, Ogg. Max size: 20MB</p>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Upload Video
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

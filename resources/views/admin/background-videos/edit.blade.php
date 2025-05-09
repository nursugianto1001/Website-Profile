<!-- resources/views/admin/background-videos/edit.blade.php -->
@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 py-6">
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
            <div class="mb-6">
                <h2 class="text-lg font-medium mb-2">Current Video</h2>
                <video width="320" height="240" controls class="rounded">
                    <source src="{{ Storage::url($backgroundVideo->path) }}" type="{{ $backgroundVideo->mime_type }}">
                    Your browser does not support the video tag.
                </video>
            </div>

            <form action="{{ route('admin.background-videos.update', $backgroundVideo) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $backgroundVideo->title) }}"
                        class="border-gray-300 rounded-md shadow-sm w-full" required>
                </div>

                <div class="mb-6">
                    <label for="video" class="block text-sm font-medium text-gray-700 mb-1">Video File (Optional)</label>
                    <input type="file" name="video" id="video" accept="video/mp4,video/webm,video/ogg"
                        class="border-gray-300 rounded-md shadow-sm w-full">
                    <p class="mt-1 text-sm text-gray-500">Leave empty to keep current video. Accepted formats: MP4, WebM,
                        Ogg. Max size: 20MB</p>
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('admin.background-videos.index') }}"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 mr-2">Cancel</a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                        Update Video
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

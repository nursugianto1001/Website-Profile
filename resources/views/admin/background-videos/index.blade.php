<!-- resources/views/admin/background-videos/index.blade.php -->
@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold">Background Videos</h1>
        <a href="{{ route('admin.background-videos.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
            Upload New Video
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full">
            <thead>
                <tr class="bg-gray-50 border-b">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Preview</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($videos as $video)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $video->title }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <video width="120" height="80" class="rounded">
                                <source src="{{ Storage::url($video->path) }}" type="{{ $video->mime_type }}">
                                Your browser does not support the video tag.
                            </video>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($video->is_active)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                @if(!$video->is_active)
                                    <form action="{{ route('admin.background-videos.set-active', $video) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900">Activate</button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.background-videos.edit', $video) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                <form action="{{ route('admin.background-videos.destroy', $video) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this video?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No background videos available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
{{-- resources/views/components/sidebar-link.blade.php --}}
@props(['route', 'icon', 'label', 'match' => null])

@php
    $active = request()->routeIs($match ?? $route);
@endphp

<a href="{{ route($route) }}" class="flex items-center p-3 rounded-md text-sm font-medium {{ $active ? 'bg-blue-600' : 'hover:bg-gray-700' }} transition">
    <i class="bi {{ $icon }} text-lg"></i>
    <span class="ml-3">{{ $label }}</span>
</a>

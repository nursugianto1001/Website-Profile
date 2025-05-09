{{-- resources/views/components/sidebar-link.blade.php --}}
@props(['route', 'icon', 'label', 'match' => null])

@php
    $active = request()->routeIs($match ?? $route);
@endphp

<a href="{{ route($route) }}" {{ $attributes->merge(['class' => 'flex items-center p-3 rounded-md text-sm font-medium ' . ($active ? 'bg-blue-600/20 text-blue-300 border-l-2 border-blue-500 pl-2' : 'text-gray-300 hover:bg-gray-700/50 hover:text-blue-300')]) }}>
    <i class="bi {{ $icon }} text-lg mr-3 {{ $active ? 'text-blue-400' : 'text-gray-400 group-hover:text-blue-400 transition-colors' }}"></i>
    <span class="{{ !$active ? 'group-hover:text-blue-300 transition-colors' : '' }}">{{ $label }}</span>
</a>

{{-- resources/views/components/sidebar-link.blade.php --}}
@props(['route', 'icon', 'label', 'match' => null])

@php
    $active = request()->routeIs($match ?? $route);
@endphp

<a href="{{ route($route) }}" 
   {{ $attributes->merge([
       'class' => 'flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 ' . 
       ($active 
           ? 'bg-blue-600 text-white shadow-lg' 
           : 'text-gray-300 hover:bg-gray-700 hover:text-white')
   ]) }}
   onclick="closeMobileSidebar()">
    <i class="bi {{ $icon }} mr-3 text-lg {{ $active ? 'text-white' : 'text-gray-400' }}"></i>
    <span class="truncate">{{ $label }}</span>
</a>

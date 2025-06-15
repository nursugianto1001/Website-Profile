{{-- resources/views/components/sidebar-link.blade.php --}}
@props(['route', 'icon', 'label', 'match' => null])

@php
    $active = request()->routeIs($match ?? $route);
@endphp

<a href="{{ route($route) }}" 
   {{ $attributes->merge([
       'class' => 'group flex items-center px-3 py-3 rounded-lg text-sm font-medium transition-all duration-200 relative w-full ' . 
       ($active 
           ? 'bg-gradient-to-r from-blue-600/20 to-blue-500/10 text-blue-300 border-l-4 border-blue-500 shadow-lg shadow-blue-500/10' 
           : 'text-gray-300 hover:bg-gradient-to-r hover:from-gray-700/50 hover:to-gray-600/30 hover:text-blue-300 hover:translate-x-1 hover:shadow-md')
   ]) }}>
    
    {{-- Icon Container - Fixed Width --}}
    <div class="flex items-center justify-center w-6 h-6 mr-3 flex-shrink-0 {{ $active ? 'text-blue-400' : 'text-gray-400 group-hover:text-blue-400' }} transition-colors duration-200">
        <i class="bi {{ $icon }} text-lg"></i>
    </div>
    
    {{-- Label - Dengan Truncate untuk Text Panjang --}}
    <span class="flex-1 truncate {{ $active ? 'text-blue-200 font-semibold' : 'group-hover:text-blue-300' }} transition-colors duration-200">
        {{ $label }}
    </span>
    
    {{-- Active Indicator --}}
    @if($active)
        <div class="absolute right-3 w-2 h-2 bg-blue-400 rounded-full animate-pulse flex-shrink-0"></div>
    @endif
    
    {{-- Hover Effect Line --}}
    <div class="absolute left-0 top-0 bottom-0 w-1 bg-blue-500 rounded-r-full transform scale-y-0 {{ $active ? 'scale-y-100' : 'group-hover:scale-y-100' }} transition-transform duration-200 origin-center"></div>
</a>

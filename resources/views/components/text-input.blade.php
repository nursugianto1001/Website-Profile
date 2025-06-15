@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 focus:border-[#A66E38] focus:ring focus:ring-[#A66E38] focus:ring-opacity-50 rounded-lg shadow-sm']) !!}>

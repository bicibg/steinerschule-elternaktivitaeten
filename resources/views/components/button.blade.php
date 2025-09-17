@props([
    'variant' => 'primary',
    'size' => 'base',
    'type' => 'button',
    'block' => false
])

@php
    $baseClasses = 'px-4 py-2 rounded-md transition-colors duration-200 disabled:opacity-50 inline-flex items-center justify-center';

    if ($size === 'sm') {
        $baseClasses = 'px-3 py-1 text-sm rounded-md transition-colors duration-200 disabled:opacity-50 inline-flex items-center justify-center';
    }

    if ($block) {
        $baseClasses .= ' w-full';
    }

    $variantClasses = match($variant) {
        'primary' => 'bg-steiner-blue text-white hover:bg-steiner-dark',
        'secondary' => 'bg-gray-200 text-gray-700 hover:bg-gray-300',
        'danger' => 'bg-red-600 text-white hover:bg-red-700',
        default => 'bg-steiner-blue text-white hover:bg-steiner-dark'
    };
@endphp

<button
    type="{{ $type }}"
    {{ $attributes->merge(['class' => $baseClasses . ' ' . $variantClasses]) }}
>
    {{ $slot }}
</button>
@props([
    'variant' => 'primary',
    'size' => 'base',
    'type' => 'button',
    'block' => false
])

@php
    $baseClasses = 'px-4 py-2 rounded-md transition-colors duration-200 disabled:opacity-50 inline-flex items-center justify-center focus:outline-none';

    if ($size === 'sm') {
        $baseClasses = 'px-3 py-1 text-sm rounded-md transition-colors duration-200 disabled:opacity-50 inline-flex items-center justify-center focus:outline-none';
    }

    if ($block) {
        $baseClasses .= ' w-full';
    }

    $variantClasses = match($variant) {
        'primary' => 'bg-steiner-blue text-white hover:bg-steiner-dark hover:text-white focus:ring-2 focus:ring-steiner-blue focus:ring-offset-2',
        'secondary' => 'bg-steiner-lighter text-steiner-dark hover:bg-steiner-light hover:text-steiner-dark focus:ring-2 focus:ring-steiner-blue focus:ring-offset-2',
        'success' => 'bg-green-600 text-white hover:bg-green-700 hover:text-white focus:ring-2 focus:ring-green-600 focus:ring-offset-2',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 hover:text-white focus:ring-2 focus:ring-red-600 focus:ring-offset-2',
        'outline' => 'border border-steiner-blue text-steiner-blue hover:bg-steiner-lighter hover:border-steiner-dark hover:text-steiner-dark focus:ring-2 focus:ring-steiner-blue focus:ring-offset-2',
        default => 'bg-steiner-blue text-white hover:bg-steiner-dark hover:text-white focus:ring-2 focus:ring-steiner-blue focus:ring-offset-2'
    };
@endphp

<button
    type="{{ $type }}"
    {{ $attributes->merge(['class' => $baseClasses . ' ' . $variantClasses]) }}
>
    {{ $slot }}
</button>
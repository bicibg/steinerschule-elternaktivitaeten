@props([
    'type' => 'info'
])

@php
    $typeClasses = match($type) {
        'success' => 'bg-green-100 text-green-800',
        'warning' => 'bg-yellow-100 text-yellow-800',
        'danger' => 'bg-red-100 text-red-800',
        'urgent' => 'bg-red-100 text-red-800',
        'featured' => 'bg-blue-100 text-blue-800',
        default => 'bg-blue-100 text-blue-800'
    };
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ' . $typeClasses]) }}>
    {{ $slot }}
</span>
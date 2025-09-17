@props([
    'type' => 'info',
    'icon' => null
])

@php
    $classes = match($type) {
        'info' => 'bg-blue-50 border-blue-200 text-blue-800',
        'help' => 'bg-steiner-lighter border-steiner-light text-steiner-dark',
        'tip' => 'bg-green-50 border-green-200 text-green-800',
        default => 'bg-gray-50 border-gray-200 text-gray-800'
    };

    $defaultIcon = match($type) {
        'info' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        'help' => 'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        'tip' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        default => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
    };

    $iconPath = $icon ?? $defaultIcon;
@endphp

<div {{ $attributes->merge(['class' => 'rounded-lg border p-4 mb-6 ' . $classes]) }}>
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}"></path>
            </svg>
        </div>
        <div class="ml-3 text-sm">
            {{ $slot }}
        </div>
    </div>
</div>
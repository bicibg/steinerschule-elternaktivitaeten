@props([
    'date',
    'route',
    'title' => null
])

<div class="flex items-center justify-between p-4 border-b border-gray-200">
    <a href="{{ route($route, ['month' => $date->copy()->subMonth()->month, 'year' => $date->copy()->subMonth()->year]) }}"
       class="p-2 hover:bg-gray-100 rounded-lg transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
    </a>

    <h2 class="text-xl font-semibold text-gray-800">
        {{ $title ?? $date->locale('de')->monthName . ' ' . $date->year }}
    </h2>

    <a href="{{ route($route, ['month' => $date->copy()->addMonth()->month, 'year' => $date->copy()->addMonth()->year]) }}"
       class="p-2 hover:bg-gray-100 rounded-lg transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    </a>
</div>
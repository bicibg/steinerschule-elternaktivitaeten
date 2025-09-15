@props([
    'date',
    'routeName',
    'title' => null
])

@if($title)
    <h1 class="text-2xl font-bold text-gray-800 mb-6">{{ $title }}</h1>
@endif

<!-- Calendar Container -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <!-- Month Navigation -->
    <div class="flex items-center justify-between p-4 border-b border-gray-200">
        <a href="{{ route($routeName, ['month' => $date->copy()->subMonth()->month, 'year' => $date->copy()->subMonth()->year]) }}"
           class="p-2 hover:bg-gray-100 rounded-lg transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>

        <h2 class="text-xl font-semibold text-gray-800">
            {{ $date->locale('de')->monthName }} {{ $date->year }}
        </h2>

        <a href="{{ route($routeName, ['month' => $date->copy()->addMonth()->month, 'year' => $date->copy()->addMonth()->year]) }}"
           class="p-2 hover:bg-gray-100 rounded-lg transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
    </div>

    <!-- Calendar Grid -->
    <div class="p-4">
        <!-- Weekday Headers -->
        <div class="grid grid-cols-7 gap-px bg-gray-200 mb-px">
            @foreach(['Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So'] as $day)
                <div class="bg-gray-50 py-1 px-2 text-center text-sm font-medium text-gray-700">
                    {{ $day }}
                </div>
            @endforeach
        </div>

        <!-- Calendar Days -->
        <div class="grid grid-cols-7 gap-px bg-gray-200" style="grid-auto-rows: minmax(120px, 1fr);">
            {{ $slot }}
        </div>
    </div>
</div>
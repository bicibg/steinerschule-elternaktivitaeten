@extends('layouts.app')

@section('title', 'Schichtkalender')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Schichtkalender - Helfer gesucht</h1>

    @php
        // Collect all unique items for legend
        $legendItems = [];
        foreach ($itemsByDate as $items) {
            foreach ($items as $item) {
                $key = $item['activity']->id . '::' . ($item['type'] === 'shift' ? $item['title'] : $item['type']);
                if (!isset($legendItems[$key])) {
                    $legendItems[$key] = [
                        'activity' => $item['activity']->title,
                        'title' => $item['title'],
                        'type' => $item['type'],
                        'color' => $item['color'],
                        'note' => $item['note'] ?? null,
                    ];
                }
            }
        }
    @endphp

    <!-- Calendar Container -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <!-- Month Navigation -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200">
            <a href="{{ route('calendar.index', ['month' => $date->copy()->subMonth()->month, 'year' => $date->copy()->subMonth()->year]) }}"
               class="p-2 hover:bg-gray-100 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>

            <h2 class="text-xl font-semibold text-gray-800">
                {{ $date->locale('de')->monthName }} {{ $date->year }}
            </h2>

            <a href="{{ route('calendar.index', ['month' => $date->copy()->addMonth()->month, 'year' => $date->copy()->addMonth()->year]) }}"
               class="p-2 hover:bg-gray-100 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>

        <!-- Calendar Grid -->
        <div class="p-4">
            <div class="grid grid-cols-7 gap-px bg-gray-200">
                <!-- Weekday Headers -->
                @foreach(['Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So'] as $day)
                    <div class="bg-gray-50 p-2 text-center text-sm font-medium text-gray-700">
                        {{ $day }}
                    </div>
                @endforeach

                <!-- Calendar Days -->
                @php
                    $startOfMonth = $date->copy()->startOfMonth();
                    $endOfMonth = $date->copy()->endOfMonth();
                    $startDate = $startOfMonth->copy()->startOfWeek();
                    $endDate = $endOfMonth->copy()->endOfWeek();
                    $currentDate = $startDate->copy();
                @endphp

                @while($currentDate <= $endDate)
                    @php
                        $isCurrentMonth = $currentDate->month === $date->month;
                        $isToday = $currentDate->isToday();
                        $dateKey = $currentDate->format('Y-m-d');
                        $dayItems = $itemsByDate->get($dateKey, collect());
                    @endphp

                    <div class="bg-white h-[140px] {{ !$isCurrentMonth ? 'bg-gray-50' : '' }} {{ $isToday ? 'bg-blue-50' : '' }} relative overflow-hidden">
                        <div class="p-1 h-full flex flex-col">
                            <div class="font-medium text-sm mb-1 {{ $isToday ? 'text-blue-600' : '' }} {{ !$isCurrentMonth ? 'text-gray-400' : 'text-gray-700' }}">
                                {{ $currentDate->day }}
                            </div>

                            <div class="space-y-1 flex-1 overflow-y-auto">
                                @foreach($dayItems as $item)
                                    <a href="{{ route('activities.show', $item['activity']->slug) }}"
                                       class="block text-xs px-1 py-0.5 rounded {{ $item['color'] }} text-white truncate hover:opacity-75 transition-opacity"
                                       title="{{ $item['activity']->title }}: {{ $item['title'] }}{{ isset($item['shift']) ? ' (' . $item['shift']->filled . '/' . ($item['shift']->needed ?? '∞') . ' Helfer)' : '' }}">
                                        @if($item['type'] === 'shift')
                                            {{ $item['title'] }}
                                        @elseif($item['type'] === 'production')
                                            🏭 {{ Str::limit($item['activity']->title, 20) }}
                                        @elseif($item['type'] === 'meeting')
                                            📅 {{ Str::limit($item['activity']->title, 20) }}
                                        @elseif($item['type'] === 'flexible')
                                            🤝 {{ Str::limit($item['activity']->title, 20) }}
                                        @else
                                            {{ Str::limit($item['title'], 25) }}
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    @php $currentDate->addDay(); @endphp
                @endwhile
            </div>
        </div>
    </div>

    <!-- Month's Activities List -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mt-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Alle Aktivitäten im {{ $date->locale('de')->monthName }}</h3>

        @if($itemsByDate->isEmpty())
            <p class="text-gray-500">Keine Aktivitäten in diesem Monat.</p>
        @else
            <div class="space-y-4">
                @foreach($itemsByDate->sortKeys() as $dateKey => $items)
                    @php
                        $itemDate = \Carbon\Carbon::parse($dateKey);
                    @endphp
                    <div class="pb-4 border-b border-gray-100 last:border-0">
                        <div class="font-medium text-gray-800 mb-3">
                            {{ $itemDate->locale('de')->dayName }}, {{ $itemDate->format('d.m.Y') }}
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($items as $item)
                                <div class="flex items-start space-x-3">
                                    <div class="w-3 h-3 rounded-full {{ $item['color'] }} mt-1 flex-shrink-0"></div>
                                    <div class="flex-1 min-w-0">
                                        <a href="{{ route('activities.show', $item['activity']->slug) }}"
                                           class="font-medium text-steiner-blue hover:text-steiner-dark transition-colors block">
                                            {{ $item['activity']->title }}
                                        </a>
                                        <div class="text-sm text-gray-600">
                                            @if($item['type'] === 'shift')
                                                <strong>{{ $item['title'] }}</strong>
                                                @if(isset($item['shift']->time))
                                                    - {{ $item['shift']->time }}
                                                @endif
                                                @if(isset($item['shift']->needed))
                                                    <span class="text-xs">
                                                        ({{ $item['shift']->filled }}/{{ $item['shift']->needed }} Helfer)
                                                    </span>
                                                @elseif(isset($item['shift']->flexible_capacity) && $item['shift']->flexible_capacity)
                                                    <span class="text-xs text-green-600">Flexible Teilnahme</span>
                                                @endif
                                            @elseif($item['type'] === 'production')
                                                <span class="text-yellow-700">Produktion</span>
                                                @if($item['note'])
                                                    - {{ $item['note'] }}
                                                @endif
                                            @elseif($item['type'] === 'meeting')
                                                <span class="text-blue-700">Treffen</span>
                                                @if($item['note'])
                                                    - {{ $item['note'] }}
                                                @endif
                                            @elseif($item['type'] === 'flexible')
                                                <span class="text-green-700">Flexible Hilfe</span>
                                                @if($item['note'])
                                                    - {{ $item['note'] }}
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Legend -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mt-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Legende</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($legendItems as $item)
                <div class="flex items-start space-x-2">
                    <div class="w-4 h-4 rounded {{ $item['color'] }} mt-0.5 flex-shrink-0"></div>
                    <div class="text-sm">
                        <span class="font-medium">{{ $item['activity'] }}</span><br>
                        <span class="text-gray-600">
                            @if($item['type'] === 'shift')
                                {{ $item['title'] }}
                            @elseif($item['type'] === 'production')
                                Produktion{{ $item['note'] ? ': ' . $item['note'] : '' }}
                            @elseif($item['type'] === 'meeting')
                                Treffen{{ $item['note'] ? ': ' . $item['note'] : '' }}
                            @elseif($item['type'] === 'flexible')
                                Flexible Hilfe
                            @endif
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4 pt-4 border-t border-gray-100">
            <div class="text-sm text-gray-600 space-y-1">
                <div class="flex items-center">
                    <span class="mr-2">🏭</span> Produktion - Laufende Arbeiten über mehrere Tage
                </div>
                <div class="flex items-center">
                    <span class="mr-2">📅</span> Regelmässige Treffen
                </div>
                <div class="flex items-center">
                    <span class="mr-2">🤝</span> Flexible Hilfe - Jede Unterstützung willkommen
                </div>
            </div>
        </div>
    </div>
@endsection
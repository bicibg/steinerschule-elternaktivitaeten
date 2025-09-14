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

                    <div class="bg-white min-h-[120px] {{ !$isCurrentMonth ? 'bg-gray-50' : '' }} {{ $isToday ? 'bg-blue-50' : '' }} relative overflow-hidden">
                        <div class="p-1 h-full flex flex-col">
                            <div class="font-medium text-sm mb-1 {{ $isToday ? 'text-blue-600' : '' }} {{ !$isCurrentMonth ? 'text-gray-400' : 'text-gray-700' }}">
                                {{ $currentDate->day }}
                            </div>

                            <div class="space-y-1 flex-1 overflow-y-auto">
                                @foreach($dayItems as $item)
                                    @php
                                        $isSpanning = ($item['type'] === 'production' || $item['type'] === 'flexible') && (isset($item['is_start']) || isset($item['is_middle']) || isset($item['is_end']));
                                        $roundedClass = '';
                                        if ($isSpanning) {
                                            if (isset($item['is_start']) && isset($item['is_end'])) {
                                                $roundedClass = 'rounded';
                                            } elseif (isset($item['is_start'])) {
                                                $roundedClass = 'rounded-l';
                                            } elseif (isset($item['is_end'])) {
                                                $roundedClass = 'rounded-r';
                                            }
                                        } else {
                                            $roundedClass = 'rounded';
                                        }
                                    @endphp
                                    <a href="{{ route('activities.show', $item['activity']->slug) }}"
                                       class="block text-xs px-1 py-0.5 {{ $roundedClass }} {{ $item['color'] }} text-white hover:opacity-75 transition-opacity relative"
                                       style="{{ $isSpanning && !isset($item['is_start']) ? 'text-indent: -9999px;' : '' }}"
                                       title="{{ $item['activity']->title }}{{ isset($item['shift']) ? ': ' . $item['title'] . ' (' . $item['shift']->filled . '/' . ($item['shift']->needed ?? '∞') . ' Helfer)' : '' }}{{ isset($item['date_range']) ? ' (' . $item['date_range'] . ')' : '' }}">
                                        @if($item['type'] === 'shift')
                                            {{ $item['title'] }}
                                        @elseif($isSpanning)
                                            @if(isset($item['is_start']))
                                                {{ $item['activity']->title }}
                                            @else
                                                &nbsp;
                                            @endif
                                        @elseif($item['type'] === 'meeting')
                                            {{ Str::limit($item['activity']->title, 20) }}
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

        @if($itemsByDate->isEmpty() && (!isset($productionActivities) || $productionActivities->isEmpty()))
            <p class="text-gray-500">Keine Aktivitäten in diesem Monat.</p>
        @else
            <div class="space-y-4">
                @php
                    // Group spanning activities (production and flexible) separately
                    $displayedSpanning = collect();
                    $spanningActivities = collect();
                    $seenActivities = collect();

                    // Group production and flexible activities by their activity ID
                    foreach($itemsByDate as $dateKey => $items) {
                        foreach($items as $item) {
                            if (($item['type'] === 'production' || $item['type'] === 'flexible')) {
                                // Only add once per activity
                                if (!$seenActivities->contains($item['activity']->id)) {
                                    $seenActivities->push($item['activity']->id);

                                    // Find the first and last date for this activity in the month
                                    $activityDates = collect();
                                    foreach($itemsByDate as $dk => $dayItems) {
                                        foreach($dayItems as $di) {
                                            if ($di['activity']->id === $item['activity']->id) {
                                                $activityDates->push(\Carbon\Carbon::parse($dk));
                                            }
                                        }
                                    }

                                    if ($activityDates->isNotEmpty()) {
                                        $startDate = $activityDates->min();
                                        $endDate = $activityDates->max();

                                        $spanningActivities->push([
                                            'activity' => $item['activity'],
                                            'type' => $item['type'],
                                            'color' => $item['color'],
                                            'date_range' => $startDate->format('d.m') . '-' . $endDate->format('d.m'),
                                            'note' => $item['note'] ?? null,
                                        ]);
                                        $displayedSpanning->push($item['activity']->id);
                                    }
                                }
                            }
                        }
                    }
                @endphp

                @foreach($spanningActivities as $spanning)
                    <div class="pb-4 border-b border-gray-100 last:border-0">
                        <div class="flex items-start space-x-3">
                            <div class="w-3 h-3 rounded-full {{ $spanning['color'] }} mt-1 flex-shrink-0"></div>
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('activities.show', $spanning['activity']->slug) }}"
                                   class="font-medium text-steiner-blue hover:text-steiner-dark transition-colors block">
                                    {{ $spanning['activity']->title }}
                                </a>
                                <div class="text-sm text-gray-600">
                                    @if($spanning['type'] === 'production')
                                        <span class="text-yellow-700 font-medium">Produktion</span>
                                    @elseif($spanning['type'] === 'flexible')
                                        <span class="text-green-700 font-medium">Flexible Hilfe</span>
                                    @endif
                                    @if($spanning['date_range'])
                                        - {{ $spanning['date_range'] }}
                                    @endif
                                    @if($spanning['activity']->participation_note)
                                        <br>{{ $spanning['activity']->participation_note }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                @foreach($itemsByDate->sortKeys() as $dateKey => $items)
                    @php
                        $itemDate = \Carbon\Carbon::parse($dateKey);
                        // Filter out spanning activities as they're shown separately
                        $filteredItems = $items->filter(function($item) use ($displayedSpanning) {
                            if (($item['type'] === 'production' || $item['type'] === 'flexible')) {
                                return !$displayedSpanning->contains($item['activity']->id);
                            }
                            return true;
                        });
                    @endphp
                    @if($filteredItems->isNotEmpty())
                        <div class="pb-4 border-b border-gray-100 last:border-0">
                            <div class="font-medium text-gray-800 mb-3">
                                {{ $itemDate->locale('de')->dayName }}, {{ $itemDate->format('d.m.Y') }}
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($filteredItems as $item)
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
                                                @elseif($item['type'] === 'meeting')
                                                    <span class="text-blue-700 font-medium">Regelmässiges Treffen</span>
                                                    @if($item['note'])
                                                        - {{ $item['note'] }}
                                                    @endif
                                                @elseif($item['type'] === 'flexible')
                                                    <span class="text-green-700 font-medium">Flexible Hilfe</span>
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
                    @endif
                @endforeach
            </div>
        @endif
    </div>
@endsection
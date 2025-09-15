@extends('layouts.app')

@section('title', 'Schichtkalender')

@section('content')
    <x-calendar-layout :date="$date" route-name="calendar.index" title="Schichtkalender - Helfer gesucht">
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

            <x-calendar-day :date="$currentDate" :is-current-month="$isCurrentMonth" :is-today="$isToday">
                @foreach($dayItems as $item)
                    @php
                        $isSpanning = ($item['type'] === 'production' || $item['type'] === 'flexible') &&
                                     (isset($item['is_start']) || isset($item['is_middle']) || isset($item['is_end']));
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
                       class="block text-xs px-0.5 mb-px {{ $roundedClass }} {{ $item['color'] }} text-white hover:opacity-75 transition-opacity truncate"
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
                            {{ $item['activity']->title }}
                        @else
                            {{ $item['title'] }}
                        @endif
                    </a>
                @endforeach
            </x-calendar-day>

            @php $currentDate->addDay(); @endphp
        @endwhile
    </x-calendar-layout>

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
                                    $spanningActivities->push($item);
                                }
                            }
                        }
                    }

                    // Collect regular shifts (non-spanning)
                    $regularShifts = collect();
                    foreach($itemsByDate as $dateKey => $items) {
                        foreach($items as $item) {
                            if ($item['type'] === 'shift') {
                                $regularShifts->push($item);
                            }
                        }
                    }

                    // Combine and sort all items
                    $allItems = $spanningActivities->concat($regularShifts)->sortBy(function($item) {
                        if ($item['type'] === 'production' || $item['type'] === 'flexible') {
                            return $item['activity']->production_date ?? $item['activity']->flexible_start;
                        }
                        return $item['shift']->date ?? '9999-12-31';
                    });
                @endphp

                @foreach($allItems as $item)
                    <div class="flex items-start space-x-3 pb-3 border-b border-gray-100 last:border-0">
                        <div class="w-3 h-3 rounded-full {{ $item['color'] }} mt-1 flex-shrink-0"></div>
                        <div class="flex-1">
                            <a href="{{ route('activities.show', $item['activity']->slug) }}"
                               class="font-medium text-gray-900 hover:text-steiner-blue transition-colors">
                                {{ $item['activity']->title }}
                            </a>
                            @if($item['type'] === 'shift')
                                <p class="text-sm text-gray-600 mt-1">
                                    <span class="font-medium">{{ $item['title'] }}</span> -
                                    {{ \Carbon\Carbon::parse($item['shift']->date)->format('d.m.Y') }}
                                    @if($item['shift']->start_time)
                                        , {{ $item['shift']->start_time }}
                                        @if($item['shift']->end_time)
                                            - {{ $item['shift']->end_time }}
                                        @endif
                                        Uhr
                                    @endif
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $item['shift']->filled }}/{{ $item['shift']->needed ?? '∞' }} Helfer
                                </p>
                            @elseif($item['type'] === 'production')
                                <p class="text-sm text-gray-600 mt-1">
                                    Produktionstag: {{ \Carbon\Carbon::parse($item['activity']->production_date)->format('d.m.Y') }}
                                    @if($item['activity']->production_time)
                                        , {{ $item['activity']->production_time }}
                                    @endif
                                </p>
                            @elseif($item['type'] === 'flexible')
                                <p class="text-sm text-gray-600 mt-1">
                                    Flexibler Zeitraum: {{ \Carbon\Carbon::parse($item['activity']->flexible_start)->format('d.m.Y') }}
                                    - {{ \Carbon\Carbon::parse($item['activity']->flexible_end)->format('d.m.Y') }}
                                </p>
                            @endif
                            @if(isset($item['note']) && $item['note'])
                                <p class="text-sm text-gray-500 mt-1">{{ $item['note'] }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
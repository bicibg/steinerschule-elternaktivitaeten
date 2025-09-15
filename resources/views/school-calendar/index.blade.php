@extends('layouts.app')

@section('title', 'Schulkalender')

@section('content')
    <x-calendar-layout :date="$date" route-name="school-calendar.index" title="Schulkalender">
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
                $dayEvents = $eventsByDate->get($dateKey, collect());
            @endphp

            <x-calendar-day :date="$currentDate" :is-current-month="$isCurrentMonth" :is-today="$isToday">
                @foreach($dayEvents as $eventData)
                    @php
                        $event = $eventData['event'];
                        $isSpanning = $event->end_date && !$event->start_date->isSameDay($event->end_date);
                        $roundedClass = '';
                        if ($isSpanning) {
                            if ($eventData['is_start'] && $eventData['is_end']) {
                                $roundedClass = 'rounded';
                            } elseif ($eventData['is_start']) {
                                $roundedClass = 'rounded-l';
                            } elseif ($eventData['is_end']) {
                                $roundedClass = 'rounded-r';
                            }
                        } else {
                            $roundedClass = 'rounded';
                        }

                        $colorClass = match($event->event_type) {
                            'festival' => 'bg-red-500',
                            'meeting' => 'bg-blue-500',
                            'performance' => 'bg-purple-500',
                            'holiday' => 'bg-gray-500',
                            'sports' => 'bg-green-500',
                            'excursion' => 'bg-yellow-500',
                            default => 'bg-steiner-blue'
                        };
                    @endphp

                    <div class="block text-xs px-0.5 mb-px {{ $roundedClass }} {{ $colorClass }} text-white hover:opacity-75 transition-opacity truncate"
                         title="{{ $event->title }}{{ $event->location ? ' - ' . $event->location : '' }}">
                        @if(!$isSpanning || $eventData['is_start'])
                            {{ $event->title }}
                        @else
                            &nbsp;
                        @endif
                    </div>
                @endforeach
            </x-calendar-day>

            @php $currentDate->addDay(); @endphp
        @endwhile
    </x-calendar-layout>

    <!-- Events List for the Month -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mt-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Alle Veranstaltungen im {{ $date->locale('de')->monthName }}</h3>

        @if($events->isEmpty())
            <p class="text-gray-500">Keine Veranstaltungen in diesem Monat.</p>
        @else
            <div class="space-y-4">
                @foreach($events as $event)
                    @php
                        $colorClass = match($event->event_type) {
                            'festival' => 'bg-red-500',
                            'meeting' => 'bg-blue-500',
                            'performance' => 'bg-purple-500',
                            'holiday' => 'bg-gray-500',
                            'sports' => 'bg-green-500',
                            'excursion' => 'bg-yellow-500',
                            default => 'bg-steiner-blue'
                        };
                    @endphp
                    <div class="flex items-start space-x-3 pb-3 border-b border-gray-100 last:border-0">
                        <div class="w-3 h-3 rounded-full {{ $colorClass }} mt-1 flex-shrink-0"></div>
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">{{ $event->title }}</h4>
                            <p class="text-sm text-gray-600 mt-1">
                                {{ $event->start_date->format('d.m.Y') }}
                                @if($event->end_date && !$event->start_date->isSameDay($event->end_date))
                                    - {{ $event->end_date->format('d.m.Y') }}
                                @endif
                                @if($event->event_time)
                                    , {{ $event->event_time }}
                                @elseif(!$event->all_day)
                                    , {{ $event->start_date->format('H:i') }} Uhr
                                @endif
                            </p>
                            @if($event->location)
                                <p class="text-sm text-gray-500">📍 {{ $event->location }}</p>
                            @endif
                            @if($event->description)
                                <p class="text-sm text-gray-600 mt-2">{{ $event->description }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
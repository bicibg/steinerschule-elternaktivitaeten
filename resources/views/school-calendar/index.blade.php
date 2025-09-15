@extends('layouts.app')

@section('title', 'Schulkalender')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Schulkalender</h1>

    <!-- Calendar Container -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <!-- Month Navigation -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200">
            <a href="{{ route('school-calendar.index', ['month' => $date->copy()->subMonth()->month, 'year' => $date->copy()->subMonth()->year]) }}"
               class="p-2 hover:bg-gray-100 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>

            <h2 class="text-xl font-semibold text-gray-800">
                {{ $date->locale('de')->monthName }} {{ $date->year }}
            </h2>

            <a href="{{ route('school-calendar.index', ['month' => $date->copy()->addMonth()->month, 'year' => $date->copy()->addMonth()->year]) }}"
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

                    <div class="bg-white h-[120px] {{ !$isCurrentMonth ? 'bg-gray-50' : '' }} {{ $isToday ? 'bg-blue-50' : '' }} relative overflow-hidden">
                        <div class="h-full flex flex-col">
                            <div class="font-medium text-sm px-1 pt-0.5 {{ $isToday ? 'text-blue-600' : '' }} {{ !$isCurrentMonth ? 'text-gray-400' : 'text-gray-700' }}">
                                {{ $currentDate->day }}
                            </div>

                            <div class="flex-1 overflow-hidden">
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

                                        // Generate color based on event type
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
                    <div class="pb-4 border-b border-gray-100 last:border-0">
                        <div class="flex items-start space-x-3">
                            <div class="w-3 h-3 rounded-full {{ $colorClass }} mt-1 flex-shrink-0"></div>
                            <div class="flex-1 min-w-0">
                                <span class="font-medium text-steiner-blue hover:text-steiner-dark transition-colors block">
                                    {{ $event->title }}
                                </span>
                                <div class="text-sm text-gray-600">
                                    @php
                                        $typeLabel = match($event->event_type) {
                                            'festival' => 'Fest',
                                            'meeting' => 'Treffen',
                                            'performance' => 'Aufführung',
                                            'holiday' => 'Ferien',
                                            'sports' => 'Sport',
                                            'excursion' => 'Ausflug',
                                            default => 'Veranstaltung'
                                        };
                                    @endphp
                                    <span class="text-gray-700 font-medium">{{ $typeLabel }}</span>
                                    - {{ $event->start_date->format('d.m') }}@if($event->end_date && !$event->start_date->isSameDay($event->end_date))-{{ $event->end_date->format('d.m') }}@endif
                                    @if($event->description)
                                        <br>{{ $event->description }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
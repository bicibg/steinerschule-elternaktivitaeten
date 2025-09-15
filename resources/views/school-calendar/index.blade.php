@extends('layouts.app')

@section('title', 'Schulkalender')

@section('content')
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <!-- Calendar Header -->
        <div class="flex justify-between items-center p-4 border-b">
            <a href="{{ route('school-calendar.index', ['month' => $date->copy()->subMonth()->month, 'year' => $date->copy()->subMonth()->year]) }}"
               class="p-2 hover:bg-gray-100 rounded transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>

            <h2 class="text-lg font-semibold text-gray-800">
                {{ $date->locale('de')->monthName }} {{ $date->year }}
            </h2>

            <a href="{{ route('school-calendar.index', ['month' => $date->copy()->addMonth()->month, 'year' => $date->copy()->addMonth()->year]) }}"
               class="p-2 hover:bg-gray-100 rounded transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        <!-- Admin Actions -->
        @if(auth()->check() && auth()->user()->is_super_admin)
            <div class="px-4 py-2 border-b bg-gray-50">
                <a href="{{ route('school-calendar.create') }}"
                   class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-steiner-blue hover:bg-steiner-dark transition-colors">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Neue Veranstaltung
                </a>
            </div>
        @endif

        <!-- Calendar Grid -->
        <div class="p-4">
            <!-- Weekday Headers -->
            <div class="grid grid-cols-7 gap-px bg-gray-200 mb-px">
                @foreach(['Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So'] as $day)
                    <div class="bg-gray-50 px-2 py-1 text-center text-xs sm:text-sm font-medium text-gray-700">
                        {{ $day }}
                    </div>
                @endforeach
            </div>

            <!-- Calendar Days -->
            <div class="grid grid-cols-7 gap-px bg-gray-200" style="grid-auto-rows: minmax(100px, 1fr);">
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

                    <div class="bg-white h-[100px] {{ !$isCurrentMonth ? 'bg-gray-50' : '' }} {{ $isToday ? 'bg-blue-50' : '' }} relative overflow-hidden">
                        <div class="h-full flex flex-col">
                            <div class="font-medium text-sm px-1 pt-0.5 {{ $isToday ? 'text-blue-600' : '' }} {{ !$isCurrentMonth ? 'text-gray-400' : 'text-gray-700' }}">
                                {{ $currentDate->day }}
                            </div>

                            <div class="flex-1 overflow-hidden px-0.5">
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

                                    <div class="mb-0.5">
                                        @if(auth()->check() && auth()->user()->is_super_admin)
                                            <a href="{{ route('school-calendar.edit', $event) }}"
                                               class="block text-xs px-0.5 {{ $roundedClass }} {{ $colorClass }} text-white hover:opacity-75 transition-opacity truncate"
                                               title="{{ $event->title }}{{ $event->location ? ' - ' . $event->location : '' }}">
                                                @if(!$isSpanning || $eventData['is_start'])
                                                    {{ $event->title }}
                                                @else
                                                    &nbsp;
                                                @endif
                                            </a>
                                        @else
                                            <div class="block text-xs px-0.5 {{ $roundedClass }} {{ $colorClass }} text-white truncate"
                                                 title="{{ $event->title }}{{ $event->location ? ' - ' . $event->location : '' }}">
                                                @if(!$isSpanning || $eventData['is_start'])
                                                    {{ $event->title }}
                                                @else
                                                    &nbsp;
                                                @endif
                                            </div>
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
                            <div class="flex items-start justify-between">
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $event->title }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ $event->start_date->format('d.m.Y') }}
                                        @if($event->end_date && !$event->start_date->isSameDay($event->end_date))
                                            - {{ $event->end_date->format('d.m.Y') }}
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

                                @if(auth()->check() && auth()->user()->is_super_admin)
                                    <div class="flex items-center space-x-2 ml-4">
                                        <a href="{{ route('school-calendar.edit', $event) }}"
                                           class="text-gray-400 hover:text-gray-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('school-calendar.destroy', $event) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Möchten Sie diese Veranstaltung wirklich löschen?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
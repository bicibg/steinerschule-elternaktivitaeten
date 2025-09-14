@extends('layouts.app')

@section('title', 'Schulkalender')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Schulkalender</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Calendar Grid -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <!-- Month Navigation -->
                <div class="flex items-center justify-between mb-4">
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
                            $dayEvents = $events->filter(function($event) use ($currentDate) {
                                return $event->date->isSameDay($currentDate);
                            });
                        @endphp

                        <div class="bg-white min-h-[80px] p-2 {{ !$isCurrentMonth ? 'text-gray-400' : '' }} {{ $isToday ? 'bg-blue-50' : '' }}">
                            <div class="font-medium text-sm mb-1 {{ $isToday ? 'text-blue-600' : '' }}">
                                {{ $currentDate->day }}
                            </div>

                            @foreach($dayEvents->take(2) as $event)
                                <div class="text-xs mb-1 truncate">
                                    <span class="inline-block w-2 h-2 rounded-full bg-{{ $event->type_color }}-500 mr-1"></span>
                                    <span class="{{ !$isCurrentMonth ? 'text-gray-400' : 'text-gray-700' }}">
                                        {{ $event->title }}
                                    </span>
                                </div>
                            @endforeach

                            @if($dayEvents->count() > 2)
                                <div class="text-xs text-gray-500">
                                    +{{ $dayEvents->count() - 2 }} weitere
                                </div>
                            @endif
                        </div>

                        @php $currentDate->addDay(); @endphp
                    @endwhile
                </div>
            </div>

            <!-- Month's Events List -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Termine im {{ $date->locale('de')->monthName }}</h3>

                @if($events->isEmpty())
                    <p class="text-gray-500">Keine Termine in diesem Monat.</p>
                @else
                    <div class="space-y-3">
                        @foreach($events as $event)
                            <div class="flex items-start space-x-3 pb-3 border-b border-gray-100 last:border-0">
                                <div class="flex-shrink-0 text-center">
                                    <div class="text-sm font-medium text-gray-500">
                                        {{ $event->date->locale('de')->dayName }}
                                    </div>
                                    <div class="text-2xl font-bold text-gray-800">
                                        {{ $event->date->day }}
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <h4 class="font-medium text-gray-800">{{ $event->title }}</h4>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                            {{ $event->type === 'holiday' ? 'bg-gray-100 text-gray-800' : '' }}
                                            {{ $event->type === 'concert' ? 'bg-purple-100 text-purple-800' : '' }}
                                            {{ $event->type === 'parent_evening' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $event->type === 'festival' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $event->type === 'other' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                            {{ $event->type_label }}
                                        </span>
                                    </div>
                                    @if($event->formatted_time)
                                        <div class="text-sm text-gray-600 mt-1">
                                            <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $event->formatted_time }}
                                        </div>
                                    @endif
                                    @if($event->location)
                                        <div class="text-sm text-gray-600">
                                            <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            {{ $event->location }}
                                        </div>
                                    @endif
                                    @if($event->description)
                                        <p class="text-sm text-gray-700 mt-2">{{ $event->description }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Upcoming Events Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Nächste Termine</h3>

                @if($upcomingEvents->isEmpty())
                    <p class="text-gray-500">Keine anstehenden Termine.</p>
                @else
                    <div class="space-y-4">
                        @foreach($upcomingEvents as $event)
                            <div class="pb-4 border-b border-gray-100 last:border-0">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h4 class="font-medium text-gray-800">{{ $event->title }}</h4>
                                        <div class="text-sm text-gray-600 mt-1">
                                            {{ $event->date->format('d.m.Y') }}
                                            @if($event->formatted_time)
                                                <br>{{ $event->formatted_time }}
                                            @endif
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                        {{ $event->type === 'holiday' ? 'bg-gray-100 text-gray-800' : '' }}
                                        {{ $event->type === 'concert' ? 'bg-purple-100 text-purple-800' : '' }}
                                        {{ $event->type === 'parent_evening' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $event->type === 'festival' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $event->type === 'other' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                        {{ $event->type_label }}
                                    </span>
                                </div>
                                @if($event->location)
                                    <div class="text-sm text-gray-600 mt-2">
                                        <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        {{ $event->location }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Legend -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Legende</h3>
                <div class="space-y-2">
                    @foreach(App\Models\CalendarEvent::getTypeLabels() as $type => $label)
                        <div class="flex items-center">
                            <span class="inline-block w-3 h-3 rounded-full mr-2
                                {{ $type === 'holiday' ? 'bg-gray-500' : '' }}
                                {{ $type === 'concert' ? 'bg-purple-500' : '' }}
                                {{ $type === 'parent_evening' ? 'bg-blue-500' : '' }}
                                {{ $type === 'festival' ? 'bg-green-500' : '' }}
                                {{ $type === 'other' ? 'bg-yellow-500' : '' }}">
                            </span>
                            <span class="text-sm text-gray-700">{{ $label }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
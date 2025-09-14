@extends('layouts.app')

@section('title', 'Schichtkalender')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Schichtkalender - Helfer gesucht</h1>

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
                            $dateKey = $currentDate->format('Y-m-d');
                            $dayShifts = $shiftsByDate->get($dateKey, collect());
                        @endphp

                        <div class="bg-white min-h-[100px] p-2 {{ !$isCurrentMonth ? 'text-gray-400' : '' }} {{ $isToday ? 'bg-blue-50' : '' }}">
                            <div class="font-medium text-sm mb-1 {{ $isToday ? 'text-blue-600' : '' }}">
                                {{ $currentDate->day }}
                            </div>

                            @foreach($dayShifts->take(2) as $shift)
                                <a href="{{ route('activities.show', $shift->activity->slug) }}"
                                   class="block text-xs mb-1 truncate hover:text-steiner-blue transition-colors">
                                    <span class="inline-block w-2 h-2 rounded-full mr-1
                                        {{ $shift->filled >= $shift->needed ? 'bg-green-500' : 'bg-orange-500' }}">
                                    </span>
                                    <span class="{{ !$isCurrentMonth ? 'text-gray-400' : 'text-gray-700' }}" title="{{ $shift->activity->title }}: {{ $shift->role }}">
                                        {{ Str::limit($shift->activity->title, 15) }}
                                    </span>
                                </a>
                            @endforeach

                            @if($dayShifts->count() > 2)
                                <div class="text-xs text-gray-500">
                                    +{{ $dayShifts->count() - 2 }} weitere
                                </div>
                            @endif
                        </div>

                        @php $currentDate->addDay(); @endphp
                    @endwhile
                </div>
            </div>

            <!-- Month's Shifts List -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Schichten im {{ $date->locale('de')->monthName }}</h3>

                @if($shiftsByDate->isEmpty())
                    <p class="text-gray-500">Keine Schichten in diesem Monat.</p>
                @else
                    <div class="space-y-3">
                        @foreach($shiftsByDate->sortKeys() as $dateKey => $shifts)
                            @php
                                $shiftDate = \Carbon\Carbon::parse($dateKey);
                            @endphp
                            <div class="pb-3 border-b border-gray-100 last:border-0">
                                <div class="font-medium text-gray-800 mb-2">
                                    {{ $shiftDate->locale('de')->dayName }}, {{ $shiftDate->format('d.m.Y') }}
                                </div>
                                @foreach($shifts as $shift)
                                    <div class="flex items-start space-x-3 ml-4 mb-2">
                                        <div class="flex-1">
                                            <a href="{{ route('activities.show', $shift->activity->slug) }}"
                                               class="font-medium text-steiner-blue hover:text-steiner-dark transition-colors">
                                                {{ $shift->activity->title }}
                                            </a>
                                            <div class="text-sm text-gray-600">
                                                <strong>{{ $shift->role }}</strong> - {{ $shift->time }}
                                            </div>
                                            <div class="text-sm mt-1">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                    {{ $shift->filled >= $shift->needed ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                                                    {{ $shift->filled }}/{{ $shift->needed }} Helfer
                                                </span>
                                                @if($shift->filled < $shift->needed)
                                                    <span class="text-orange-600 text-xs ml-2">
                                                        Noch {{ $shift->needed - $shift->filled }} {{ ($shift->needed - $shift->filled) == 1 ? 'Helfer' : 'Helfer' }} gesucht!
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Upcoming Shifts -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Dringend Helfer gesucht</h3>

                @if($upcomingShifts->isEmpty())
                    <p class="text-gray-500">Keine offenen Schichten.</p>
                @else
                    <div class="space-y-4">
                        @foreach($upcomingShifts as $shift)
                            <div class="pb-4 border-b border-gray-100 last:border-0">
                                <a href="{{ route('activities.show', $shift->activity->slug) }}"
                                   class="font-medium text-steiner-blue hover:text-steiner-dark transition-colors">
                                    {{ $shift->activity->title }}
                                </a>
                                <div class="text-sm text-gray-600 mt-1">
                                    <strong>{{ $shift->role }}</strong>
                                </div>
                                <div class="text-sm text-gray-600">
                                    {{ $shift->parsed_date->format('d.m.Y') }}
                                </div>
                                <div class="mt-2">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800">
                                        {{ $shift->needed - $shift->filled }} {{ ($shift->needed - $shift->filled) == 1 ? 'Platz frei' : 'Plätze frei' }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Activities Legend -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Aktivitäten mit Schichten</h3>
                <div class="space-y-2">
                    @foreach($activities->where('shifts', '>', 0) as $activity)
                        <div class="flex items-start">
                            <span class="inline-block w-3 h-3 rounded-full mr-2 mt-0.5
                                {{ $activity->shifts->where('filled', '<', 'needed')->count() > 0 ? 'bg-orange-500' : 'bg-green-500' }}">
                            </span>
                            <div class="flex-1">
                                <a href="{{ route('activities.show', $activity->slug) }}"
                                   class="text-sm text-steiner-blue hover:text-steiner-dark transition-colors">
                                    {{ $activity->title }}
                                </a>
                                <div class="text-xs text-gray-500">
                                    {{ $activity->shifts->count() }} {{ $activity->shifts->count() == 1 ? 'Schicht' : 'Schichten' }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Status Legend -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Legende</h3>
                <div class="space-y-2">
                    <div class="flex items-center">
                        <span class="inline-block w-3 h-3 rounded-full bg-orange-500 mr-2"></span>
                        <span class="text-sm text-gray-700">Helfer gesucht</span>
                    </div>
                    <div class="flex items-center">
                        <span class="inline-block w-3 h-3 rounded-full bg-green-500 mr-2"></span>
                        <span class="text-sm text-gray-700">Schicht besetzt</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
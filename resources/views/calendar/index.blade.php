@extends('layouts.app')

@section('title', 'Schichtkalender')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Schichtkalender - Helfer gesucht</h1>

    @php
        // Define colors for each activity-shift combination
        $shiftColors = [
            'Helfer für Märit - Aufbau und Standbetreuung' => [
                'Aufbau Freitag' => 'bg-blue-500',
                'Blumenstand Vormittag' => 'bg-green-500',
                'Cafeteria-Team' => 'bg-yellow-500',
                'Kinderbetreuung' => 'bg-purple-500',
                'Abbau-Team' => 'bg-red-500',
            ],
            'Helferteam für Kerzenziehen gesucht' => [
                'Wachsvorbereitung' => 'bg-indigo-500',
                'Betreuung Kerzenzieh-Station' => 'bg-pink-500',
                'Verkaufsstand' => 'bg-teal-500',
                'Aufräumen und Reinigung' => 'bg-orange-500',
            ],
            'Helfer für Adventskranzbinden' => [
                'Material vorbereiten' => 'bg-cyan-500',
                'Kranzbinden Donnerstag' => 'bg-lime-500',
            ],
            'Team für Elternkafi am Schulsamstag' => [
                'Kafi-Aufbau' => 'bg-amber-500',
                'Kafi-Betreuung Vormittag' => 'bg-rose-500',
            ],
        ];

        // Collect all unique shifts for legend
        $legendItems = [];
        foreach ($shiftsByDate as $shifts) {
            foreach ($shifts as $shift) {
                $key = $shift->activity->title . '::' . $shift->role;
                if (!isset($legendItems[$key])) {
                    $legendItems[$key] = [
                        'activity' => $shift->activity->title,
                        'role' => $shift->role,
                        'color' => $shiftColors[$shift->activity->title][$shift->role] ?? 'bg-gray-500',
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
                        $dayShifts = $shiftsByDate->get($dateKey, collect());
                    @endphp

                    <div class="bg-white h-[120px] {{ !$isCurrentMonth ? 'bg-gray-50' : '' }} {{ $isToday ? 'bg-blue-50' : '' }} relative overflow-hidden">
                        <div class="p-1 h-full flex flex-col">
                            <div class="font-medium text-sm mb-1 {{ $isToday ? 'text-blue-600' : '' }} {{ !$isCurrentMonth ? 'text-gray-400' : 'text-gray-700' }}">
                                {{ $currentDate->day }}
                            </div>

                            <div class="space-y-1 flex-1 overflow-y-auto">
                                @foreach($dayShifts as $shift)
                                    @php
                                        $color = $shiftColors[$shift->activity->title][$shift->role] ?? 'bg-gray-500';
                                        $opacity = $shift->filled >= $shift->needed ? 'opacity-50' : '';
                                    @endphp
                                    <a href="{{ route('activities.show', $shift->activity->slug) }}"
                                       class="block text-xs px-1 py-0.5 rounded {{ $color }} {{ $opacity }} text-white truncate hover:opacity-75 transition-opacity"
                                       title="{{ $shift->activity->title }}: {{ $shift->role }} ({{ $shift->filled }}/{{ $shift->needed }} Helfer)">
                                        {{ $shift->role }}
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

    <!-- Combined Shifts List with Legend -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mt-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Alle Schichten im {{ $date->locale('de')->monthName }}</h3>

        @if($shiftsByDate->isEmpty())
            <p class="text-gray-500">Keine Schichten in diesem Monat.</p>
        @else
            <div class="space-y-4">
                @foreach($shiftsByDate->sortKeys() as $dateKey => $shifts)
                    @php
                        $shiftDate = \Carbon\Carbon::parse($dateKey);
                    @endphp
                    <div class="pb-4 border-b border-gray-100 last:border-0">
                        <div class="font-medium text-gray-800 mb-3">
                            {{ $shiftDate->locale('de')->dayName }}, {{ $shiftDate->format('d.m.Y') }}
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($shifts as $shift)
                                @php
                                    $color = $shiftColors[$shift->activity->title][$shift->role] ?? 'bg-gray-500';
                                @endphp
                                <div class="flex items-start space-x-3">
                                    <div class="w-3 h-3 rounded-full {{ $color }} mt-1 flex-shrink-0"></div>
                                    <div class="flex-1 min-w-0">
                                        <a href="{{ route('activities.show', $shift->activity->slug) }}"
                                           class="font-medium text-steiner-blue hover:text-steiner-dark transition-colors block">
                                            {{ $shift->activity->title }}
                                        </a>
                                        <div class="text-sm text-gray-600">
                                            <strong>{{ $shift->role }}</strong> - {{ $shift->time }}
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
@endsection
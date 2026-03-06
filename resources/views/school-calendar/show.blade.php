@extends('layouts.app')

@section('title', $schoolEvent->title)

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            @php
                $referrer = request()->headers->get('referer');
                $backRoute = route('school-calendar.index');
                $backText = 'Zurück zum Schulkalender';

                if ($referrer && str_contains($referrer, '/kalender') && !str_contains($referrer, '/schulkalender')) {
                    $backRoute = route('calendar.index');
                    $backText = 'Zurück zum Kalender';
                }
            @endphp
            <a href="{{ $backRoute }}" class="inline-flex items-center text-steiner-blue hover:text-steiner-dark">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                {{ $backText }}
            </a>
        </div>

        <!-- Event Details -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="mb-6">
                <div class="flex items-start justify-between">
                    <h1 class="text-3xl font-bold text-gray-800">{{ $schoolEvent->title }}</h1>
                    @if($schoolEvent->event_type)
                        @php
                            $colorClass = match($schoolEvent->event_type) {
                                'holiday', 'ferien' => 'bg-green-100 text-green-800',
                                'festival' => 'bg-yellow-100 text-yellow-800',
                                'meeting', 'konferenz' => 'bg-purple-100 text-purple-800',
                                'performance' => 'bg-pink-100 text-pink-800',
                                'sports' => 'bg-orange-100 text-orange-800',
                                'excursion' => 'bg-teal-100 text-teal-800',
                                'feiertag' => 'bg-red-100 text-red-800',
                                'veranstaltung' => 'bg-blue-100 text-blue-800',
                                'andere' => 'bg-gray-100 text-gray-800',
                                default => 'bg-gray-100 text-gray-800'
                            };
                        @endphp
                        <span class="ml-2 inline-flex items-center px-3 py-1 rounded text-sm font-medium {{ $colorClass }}">
                            @php
                                $typeLabel = match($schoolEvent->event_type) {
                                    'holiday', 'ferien' => 'Ferien',
                                    'festival' => 'Fest',
                                    'meeting' => 'Treffen',
                                    'performance' => 'Aufführung',
                                    'sports' => 'Sport',
                                    'excursion' => 'Ausflug',
                                    'feiertag' => 'Feiertag',
                                    'veranstaltung' => 'Veranstaltung',
                                    'konferenz' => 'Konferenz',
                                    'andere' => 'Andere',
                                    default => 'Veranstaltung'
                                };
                            @endphp
                            {{ $typeLabel }}
                        </span>
                    @endif
                </div>
            </div>

            <!-- Date and Time -->
            <div class="mb-6 bg-gray-50 rounded-lg p-4">
                <div class="flex items-center text-gray-700">
                    <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <div>
                        @if($schoolEvent->end_date && !$schoolEvent->start_date->isSameDay($schoolEvent->end_date))
                            <div class="font-medium">
                                {{ $schoolEvent->start_date->format('d.m.Y') }} - {{ $schoolEvent->end_date->format('d.m.Y') }}
                            </div>
                            <div class="text-sm text-gray-600">
                                {{ $schoolEvent->start_date->diffInDays($schoolEvent->end_date) + 1 }} Tage
                            </div>
                        @else
                            <div class="font-medium">
                                {{ $schoolEvent->start_date->locale('de')->dayName }}, {{ $schoolEvent->start_date->format('d.m.Y') }}
                            </div>
                            @if($schoolEvent->event_time && !$schoolEvent->all_day)
                                <div class="text-sm text-gray-600">
                                    {{ $schoolEvent->event_time }}
                                </div>
                            @elseif($schoolEvent->all_day)
                                <div class="text-sm text-gray-600">
                                    Ganztägig
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <!-- Location -->
            @if($schoolEvent->location)
                <div class="mb-6 bg-blue-50 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 mr-3 text-gray-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <div>
                            <div class="font-medium text-gray-700">Ort</div>
                            <div class="text-gray-600">{{ $schoolEvent->location }}</div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Description -->
            @if($schoolEvent->description)
                <div class="prose max-w-none">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Beschreibung</h3>
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $schoolEvent->description }}</p>
                </div>
            @endif

            <!-- Event Type Specific Information -->
            @if($schoolEvent->event_type === 'ferien')
                <div class="mt-6 p-4 bg-green-50 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-green-800">Während dieser Zeit findet kein regulärer Unterricht statt.</span>
                    </div>
                </div>
            @elseif($schoolEvent->event_type === 'konferenz')
                <div class="mt-6 p-4 bg-purple-50 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-purple-800">Diese Veranstaltung ist für Lehrpersonen und Mitarbeitende.</span>
                    </div>
                </div>
            @endif

        </div>
    </div>
@endsection
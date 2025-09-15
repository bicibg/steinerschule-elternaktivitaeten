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
                        <span class="ml-2 inline-flex items-center px-3 py-1 rounded text-sm font-medium
                            {{ $schoolEvent->event_type === 'ferien' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $schoolEvent->event_type === 'feiertag' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $schoolEvent->event_type === 'veranstaltung' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $schoolEvent->event_type === 'konferenz' ? 'bg-purple-100 text-purple-800' : '' }}
                            {{ $schoolEvent->event_type === 'andere' ? 'bg-gray-100 text-gray-800' : '' }}">
                            {{ ucfirst($schoolEvent->event_type) }}
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

            <!-- Admin Actions -->
            @if(auth()->user() && auth()->user()->is_super_admin)
                <div class="mt-8 pt-6 border-t border-gray-200 flex gap-3">
                    <a href="{{ route('school-calendar.edit', $schoolEvent) }}"
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Bearbeiten
                    </a>
                    <form action="{{ route('school-calendar.destroy', $schoolEvent) }}" method="POST" class="inline"
                          onsubmit="return confirm('Sind Sie sicher, dass Sie diese Veranstaltung löschen möchten?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-red-300 rounded-md text-sm font-medium text-red-700 bg-white hover:bg-red-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Löschen
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection
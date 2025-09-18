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
                    <div x-data="{ showDeleteModal: false }" class="inline">
                        <button type="button"
                                @click="showDeleteModal = true"
                                class="inline-flex items-center px-4 py-2 border border-red-300 rounded-md text-sm font-medium text-red-700 bg-white hover:bg-red-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Löschen
                        </button>

                        <!-- Delete Confirmation Modal -->
                        <template x-teleport="body">
                            <div x-show="showDeleteModal"
                                 x-cloak
                                 @click.away="showDeleteModal = false"
                                 @keydown.escape.window="showDeleteModal = false"
                                 class="fixed inset-0 z-50 flex items-center justify-center">
                                <!-- Background overlay -->
                                <div x-show="showDeleteModal"
                                     x-transition:enter="ease-out duration-300"
                                     x-transition:enter-start="opacity-0"
                                     x-transition:enter-end="opacity-100"
                                     x-transition:leave="ease-in duration-200"
                                     x-transition:leave-start="opacity-100"
                                     x-transition:leave-end="opacity-0"
                                     class="fixed inset-0 bg-gray-500 bg-opacity-75"
                                     @click="showDeleteModal = false"></div>

                                <!-- Modal content -->
                                <div x-show="showDeleteModal"
                                     x-transition:enter="ease-out duration-300"
                                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                     x-transition:leave="ease-in duration-200"
                                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                     class="relative bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full sm:p-6">
                                    <div class="sm:flex sm:items-start">
                                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                        </div>
                                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                                Veranstaltung löschen
                                            </h3>
                                            <div class="mt-2">
                                                <p class="text-sm text-gray-500">
                                                    Sind Sie sicher, dass Sie diese Veranstaltung löschen möchten? Diese Aktion kann nicht rückgängig gemacht werden.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                                        <form action="{{ route('school-calendar.destroy', $schoolEvent) }}" method="POST" class="sm:ml-3">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:w-auto sm:text-sm">
                                                Löschen
                                            </button>
                                        </form>
                                        <button type="button"
                                                @click="showDeleteModal = false"
                                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-steiner-blue sm:mt-0 sm:w-auto sm:text-sm">
                                            Abbrechen
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
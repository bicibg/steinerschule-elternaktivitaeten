@extends('layouts.app')

@section('title', 'Meine Schichten')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('profile.edit') }}" class="inline-flex items-center text-steiner-blue hover:text-steiner-dark">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Zurück zum Profil
            </a>
        </div>

        <h1 class="text-2xl font-bold text-gray-800 mb-6">Meine Schichten</h1>

        @if($volunteers->isEmpty())
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-gray-500 mb-4">Sie haben sich noch für keine Schichten angemeldet.</p>
                <a href="{{ route('bulletin.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-steiner-blue text-white rounded-md hover:bg-steiner-dark transition-colors">
                    Aktivitäten ansehen
                </a>
            </div>
        @else
            @php
                $upcomingShifts = $volunteers->filter(fn($v) =>
                    $v->shift && $v->shift->bulletinPost &&
                    (!$v->shift->bulletinPost->end_at || $v->shift->bulletinPost->end_at->isFuture())
                );
                $pastShifts = $volunteers->filter(fn($v) =>
                    $v->shift && $v->shift->bulletinPost &&
                    $v->shift->bulletinPost->end_at && $v->shift->bulletinPost->end_at->isPast()
                );
            @endphp

            <!-- Upcoming Shifts -->
            @if($upcomingShifts->isNotEmpty())
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Kommende Schichten</h2>
                    <div class="space-y-4">
                        @foreach($upcomingShifts as $volunteer)
                            @if($volunteer->shift && $volunteer->shift->bulletinPost)
                                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <a href="{{ route('bulletin.show', $volunteer->shift->bulletinPost->slug) }}"
                                               class="text-lg font-medium text-steiner-blue hover:text-steiner-dark">
                                                {{ $volunteer->shift->bulletinPost->title }}
                                            </a>
                                            <div class="mt-2 space-y-1">
                                                <div class="flex items-center text-sm text-gray-600">
                                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                    </svg>
                                                    <span class="font-medium">{{ $volunteer->shift->role }}</span>
                                                </div>
                                                @if($volunteer->shift->time)
                                                    <div class="flex items-center text-sm text-gray-600">
                                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        {{ $volunteer->shift->time }}
                                                    </div>
                                                @endif
                                                @if($volunteer->shift->bulletinPost->location)
                                                    <div class="flex items-center text-sm text-gray-600">
                                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        </svg>
                                                        {{ $volunteer->shift->bulletinPost->location }}
                                                    </div>
                                                @endif
                                                <div class="flex items-center text-sm text-gray-500 mt-2">
                                                    <span>Angemeldet am: {{ $volunteer->created_at->format('d.m.Y') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div x-data="{ showModal: false }">
                                            <button type="button"
                                                    @click="showModal = true"
                                                    class="px-3 py-1.5 text-sm text-red-600 border border-red-300 rounded hover:bg-red-50 transition-colors">
                                                Abmelden
                                            </button>

                                            <!-- Confirmation Modal -->
                                            <template x-teleport="body">
                                                <div x-show="showModal"
                                                     x-cloak
                                                     @click.away="showModal = false"
                                                     @keydown.escape.window="showModal = false"
                                                     class="fixed inset-0 z-50 flex items-center justify-center">
                                                    <!-- Background overlay -->
                                                    <div x-show="showModal"
                                                         x-transition:enter="ease-out duration-300"
                                                         x-transition:enter-start="opacity-0"
                                                         x-transition:enter-end="opacity-100"
                                                         x-transition:leave="ease-in duration-200"
                                                         x-transition:leave-start="opacity-100"
                                                         x-transition:leave-end="opacity-0"
                                                         class="fixed inset-0 bg-gray-500 bg-opacity-75"
                                                         @click="showModal = false"></div>

                                                    <!-- Modal content -->
                                                    <div x-show="showModal"
                                                         x-transition:enter="ease-out duration-300"
                                                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                                         x-transition:leave="ease-in duration-200"
                                                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                                         class="relative bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full sm:p-6">
                                                        <div class="sm:flex sm:items-start">
                                                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-amber-100 sm:mx-0 sm:h-10 sm:w-10">
                                                                <svg class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                                </svg>
                                                            </div>
                                                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                                                    Von Schicht abmelden
                                                                </h3>
                                                                <div class="mt-2">
                                                                    <p class="text-sm text-gray-500">
                                                                        Möchten Sie sich wirklich von dieser Schicht abmelden?
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                                                            <form action="{{ route('shifts.withdraw', $volunteer->shift) }}" method="POST" class="sm:ml-3">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:w-auto sm:text-sm">
                                                                    Abmelden
                                                                </button>
                                                            </form>
                                                            <button type="button"
                                                                    @click="showModal = false"
                                                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-steiner-blue sm:mt-0 sm:w-auto sm:text-sm">
                                                                Abbrechen
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Past Shifts -->
            @if($pastShifts->isNotEmpty())
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Vergangene Schichten</h2>
                    <div class="space-y-4">
                        @foreach($pastShifts as $volunteer)
                            @if($volunteer->shift && $volunteer->shift->bulletinPost)
                                <div class="bg-gray-50 rounded-lg border border-gray-200 p-4 opacity-75">
                                    <div class="flex-1">
                                        <a href="{{ route('bulletin.show', $volunteer->shift->bulletinPost->slug) }}"
                                           class="text-lg font-medium text-gray-600 hover:text-gray-800">
                                            {{ $volunteer->shift->bulletinPost->title }}
                                        </a>
                                        <div class="mt-2 space-y-1">
                                            <div class="flex items-center text-sm text-gray-500">
                                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                </svg>
                                                <span>{{ $volunteer->shift->role }}</span>
                                            </div>
                                            @if($volunteer->shift->time)
                                                <div class="flex items-center text-sm text-gray-500">
                                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    {{ $volunteer->shift->time }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        @endif
    </div>
@endsection
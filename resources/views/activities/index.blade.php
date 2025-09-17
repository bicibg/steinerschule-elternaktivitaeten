@extends('layouts.app')

@section('title', 'Elternaktivitäten')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Elternaktivitäten</h1>
        <p class="text-gray-600">
            Hier finden Sie <strong>dauerhafte Arbeitsgruppen und regelmässige Aktivitäten</strong>, die von Eltern organisiert werden.
            Diese Gruppen treffen sich kontinuierlich und freuen sich über neue Mitglieder.
        </p>
    </div>

    <x-info-box type="info">
        <strong>Möchten Sie sich engagieren?</strong> Diese Arbeitsgruppen und Aktivitäten finden regelmässig statt und suchen Mitglieder für langfristige Mitarbeit.
        <br>
        <span class="text-xs">Für einmalige Helfergesuche siehe <a href="{{ route('bulletin.index') }}" class="underline text-steiner-blue hover:text-steiner-dark">Pinnwand</a></span>
    </x-info-box>

    <!-- Category Filter -->
    <div class="mb-6 flex flex-wrap gap-2">
        <a href="{{ route('activities.index', ['category' => 'all']) }}"
           class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-colors
                  {{ $selectedCategory === 'all' ? 'bg-steiner-blue text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            Alle Kategorien
            @if($totalCount > 0)
                <span class="ml-2 px-2 py-0.5 rounded-full text-xs {{ $selectedCategory === 'all' ? 'bg-white/20' : 'bg-gray-200' }}">
                    {{ $totalCount }}
                </span>
            @endif
        </a>
        @foreach($categories as $key => $label)
            <a href="{{ route('activities.index', ['category' => $key]) }}"
               class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-colors
                      {{ $selectedCategory === $key ?
                         match($key) {
                             'anlass' => 'bg-blue-500 text-white',
                             'haus_umgebung_taskforces' => 'bg-green-500 text-white',
                             'produktion' => 'bg-yellow-500 text-white',
                             'organisation' => 'bg-purple-500 text-white',
                             'verkauf' => 'bg-pink-500 text-white',
                             'paedagogik' => 'bg-indigo-500 text-white',
                             'kommunikation' => 'bg-teal-500 text-white',
                             default => 'bg-gray-500 text-white'
                         } :
                         match($key) {
                             'anlass' => 'bg-blue-100 text-blue-800 hover:bg-blue-200',
                             'haus_umgebung_taskforces' => 'bg-green-100 text-green-800 hover:bg-green-200',
                             'produktion' => 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200',
                             'organisation' => 'bg-purple-100 text-purple-800 hover:bg-purple-200',
                             'verkauf' => 'bg-pink-100 text-pink-800 hover:bg-pink-200',
                             'paedagogik' => 'bg-indigo-100 text-indigo-800 hover:bg-indigo-200',
                             'kommunikation' => 'bg-teal-100 text-teal-800 hover:bg-teal-200',
                             default => 'bg-gray-100 text-gray-800 hover:bg-gray-200'
                         }
                      }}">
                {{ $label }}
                @if(isset($categoryCounts[$key]) && $categoryCounts[$key] > 0)
                    <span class="ml-2 px-2 py-0.5 rounded-full text-xs {{ $selectedCategory === $key ? 'bg-white/20' : 'bg-white' }}">
                        {{ $categoryCounts[$key] }}
                    </span>
                @endif
            </a>
        @endforeach
    </div>

    @if($activities->isEmpty())
        <x-card>
            <div class="text-center py-8">
                <p class="text-gray-500">
                    @if($selectedCategory !== 'all' && isset($categories[$selectedCategory]))
                        Keine Aktivitäten in der Kategorie "{{ $categories[$selectedCategory] }}" gefunden.
                    @else
                        Zurzeit sind keine Aktivitäten vorhanden.
                    @endif
                </p>
                @if($selectedCategory !== 'all')
                    <a href="{{ route('activities.index') }}" class="mt-4 inline-flex items-center text-steiner-blue hover:text-steiner-dark">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Alle Aktivitäten anzeigen
                    </a>
                @endif
            </div>
        </x-card>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($activities as $activity)
                <x-card hover="true" class="h-full">
                    <a href="{{ route('activities.show', $activity->slug) }}" class="block h-full">
                    <div class="flex flex-col h-full">
                        <div class="flex items-start justify-between mb-3">
                            <h2 class="text-xl font-semibold text-gray-800">{{ $activity->title }}</h2>
                            @if($activity->category_text)
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium
                                    {{ $activity->category === 'anlass' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $activity->category === 'haus_umgebung_taskforces' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $activity->category === 'produktion' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $activity->category === 'organisation' ? 'bg-purple-100 text-purple-800' : '' }}
                                    {{ $activity->category === 'verkauf' ? 'bg-pink-100 text-pink-800' : '' }}
                                    {{ $activity->category === 'paedagogik' ? 'bg-indigo-100 text-indigo-800' : '' }}
                                    {{ $activity->category === 'kommunikation' ? 'bg-teal-100 text-teal-800' : '' }}">
                                    {{ $activity->category_text }}
                                </span>
                            @endif
                        </div>

                        <p class="text-gray-700 mb-4 flex-grow line-clamp-3">{{ Str::limit($activity->description, 200) }}</p>

                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span class="font-medium">{{ $activity->contact_name }}</span>
                            </div>

                            @if($activity->meeting_time || $activity->meeting_location)
                                <div class="flex items-start">
                                    <svg class="w-4 h-4 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <div>
                                        @if($activity->meeting_time)
                                            <div>{{ $activity->meeting_time }}</div>
                                        @endif
                                        @if($activity->meeting_location)
                                            <div>{{ $activity->meeting_location }}</div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if($activity->has_forum && $activity->posts->count() > 0)
                            <div class="mt-3 pt-3 border-t border-gray-100">
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                    {{ $activity->posts->count() }} {{ $activity->posts->count() === 1 ? 'Beitrag' : 'Beiträge' }}
                                </div>
                            </div>
                        @endif
                    </div>
                    </a>
                </x-card>
            @endforeach
        </div>
    @endif
@endsection
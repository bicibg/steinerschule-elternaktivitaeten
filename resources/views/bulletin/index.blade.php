@extends('layouts.app')

@section('title', 'Pinnwand')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Pinnwand <span class="text-lg font-normal text-gray-600">– Helfergesuche</span></h1>
        <p class="text-gray-600">
            Hier finden Sie <strong>aktuelle Helfergesuche</strong> von den Organisatoren der verschiedenen Elternaktivitäten.
            Wenn eine Arbeitsgruppe oder Aktivität Unterstützung braucht, wird hier ein Aufruf veröffentlicht.
        </p>
    </div>

    <x-info-box type="help">
        <strong>Möchten Sie helfen?</strong> Die Organisatoren der <a href="{{ route('activities.index') }}" class="underline text-steiner-blue hover:text-steiner-dark">Elternaktivitäten</a> suchen hier nach Unterstützung für ihre Veranstaltungen und Projekte.
        <br>
        <span class="text-xs">Schauen Sie sich die Aufrufe an und melden Sie sich direkt bei den Organisatoren.</span>
    </x-info-box>

    <!-- Category Filter -->
    <div class="mb-6 flex flex-wrap gap-2">
        <a href="{{ route('bulletin.index', ['category' => 'all']) }}"
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
            <a href="{{ route('bulletin.index', ['category' => $key]) }}"
               class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-colors
                      {{ $selectedCategory === $key ?
                         match($key) {
                             'anlass' => 'bg-blue-500 text-white',
                             'haus_umgebung_taskforces' => 'bg-green-500 text-white',
                             'produktion' => 'bg-yellow-500 text-white',
                             'organisation' => 'bg-purple-500 text-white',
                             'verkauf' => 'bg-pink-500 text-white',
                             default => 'bg-gray-500 text-white'
                         } :
                         match($key) {
                             'anlass' => 'bg-blue-100 text-blue-800 hover:bg-blue-200',
                             'haus_umgebung_taskforces' => 'bg-green-100 text-green-800 hover:bg-green-200',
                             'produktion' => 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200',
                             'organisation' => 'bg-purple-100 text-purple-800 hover:bg-purple-200',
                             'verkauf' => 'bg-pink-100 text-pink-800 hover:bg-pink-200',
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

    @if($bulletinPosts->isEmpty())
        <x-card>
            <div class="text-center py-8">
                <p class="text-gray-500">
                    @if($selectedCategory !== 'all' && isset($categories[$selectedCategory]))
                        Keine Einträge in der Kategorie "{{ $categories[$selectedCategory] }}" gefunden.
                    @else
                        Zurzeit sind keine Einträge vorhanden.
                    @endif
                </p>
                @if($selectedCategory !== 'all')
                    <a href="{{ route('bulletin.index') }}" class="mt-4 inline-flex items-center text-steiner-blue hover:text-steiner-dark">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7 7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Alle Einträge anzeigen
                    </a>
                @endif
            </div>
        </x-card>
    @else
        <div class="space-y-4">
            @foreach($bulletinPosts as $bulletinPost)
                <a href="{{ route('bulletin.show', $bulletinPost->slug) }}" class="block bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <h2 class="text-xl font-semibold text-gray-800">{{ $bulletinPost->title }}</h2>
                                @if($bulletinPost->category_text)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium
                                        {{ $bulletinPost->category === 'anlass' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $bulletinPost->category === 'haus_umgebung_taskforces' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $bulletinPost->category === 'produktion' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $bulletinPost->category === 'organisation' ? 'bg-purple-100 text-purple-800' : '' }}
                                        {{ $bulletinPost->category === 'verkauf' ? 'bg-pink-100 text-pink-800' : '' }}">
                                        {{ $bulletinPost->category_text }}
                                    </span>
                                @endif
                                @if($bulletinPost->label_text)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $bulletinPost->label === 'urgent' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $bulletinPost->label === 'important' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $bulletinPost->label === 'featured' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $bulletinPost->label === 'last_minute' ? 'bg-orange-100 text-orange-800' : '' }}">
                                        {{ $bulletinPost->label_text }}
                                    </span>
                                @endif
                            </div>

                            <div class="space-y-1 text-sm text-gray-600 mb-3">
                                @if($bulletinPost->start_at || $bulletinPost->end_at)
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        @if($bulletinPost->start_at && $bulletinPost->end_at)
                                            {{ $bulletinPost->start_at->format('d.m.Y') }} - {{ $bulletinPost->end_at->format('d.m.Y') }}
                                        @elseif($bulletinPost->start_at)
                                            Ab {{ $bulletinPost->start_at->format('d.m.Y H:i') }}
                                        @else
                                            Bis {{ $bulletinPost->end_at->format('d.m.Y H:i') }}
                                        @endif
                                    </div>
                                @endif
                                @if($bulletinPost->location)
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        {{ $bulletinPost->location }}
                                    </div>
                                @endif
                            </div>

                            <p class="text-gray-700 line-clamp-2">{{ Str::limit($bulletinPost->description, 200) }}</p>

                            <div class="mt-3 flex items-center justify-between">
                                <span class="text-sm text-gray-500">
                                    von <span class="font-medium">{{ $bulletinPost->organizer_name }}</span>
                                </span>
                                <div class="flex items-center gap-3">
                                    @if($bulletinPost->has_forum && $bulletinPost->posts->count() > 0)
                                        <div class="flex items-center text-sm text-gray-500">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                            </svg>
                                            {{ $bulletinPost->posts->count() }} {{ $bulletinPost->posts->count() === 1 ? 'Beitrag' : 'Beiträge' }}
                                        </div>
                                    @endif
                                    @if($bulletinPost->has_shifts && $bulletinPost->shifts->count() > 0)
                                        @php
                                            $totalNeeded = $bulletinPost->shifts->sum('needed');
                                            $totalFilled = $bulletinPost->shifts->sum('filled');
                                        @endphp
                                        <div class="flex items-center text-sm {{ $totalFilled >= $totalNeeded ? 'text-green-600' : 'text-orange-600' }}">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                            </svg>
                                            {{ $totalFilled }}/{{ $totalNeeded }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @php
                            $referenceDate = $bulletinPost->start_at ?? $bulletinPost->end_at;
                        @endphp
                        @if($referenceDate)
                            <div class="flex-shrink-0">
                                <span class="inline-block px-3 py-1 text-sm font-medium rounded-full
                                    @if($referenceDate->isFuture())
                                        bg-blue-100 text-blue-800
                                    @elseif($referenceDate->isToday())
                                        bg-green-100 text-green-800
                                    @else
                                        bg-gray-100 text-gray-800
                                    @endif">
                                    @if($referenceDate->isFuture())
                                        Bevorstehend
                                    @elseif($referenceDate->isToday())
                                        Heute
                                    @else
                                        Vergangen
                                    @endif
                                </span>
                            </div>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    @endif
@endsection
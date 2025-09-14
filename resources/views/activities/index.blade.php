@extends('layouts.app')

@section('title', 'Aktuelle Aktivitäten')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Aktuelle Aktivitäten</h1>

    @if($activities->isEmpty())
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center py-12">
            <p class="text-gray-500">Zurzeit sind keine Aktivitäten geplant.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($activities as $activity)
                <a href="{{ route('activities.show', $activity->slug) }}" class="block bg-white rounded-lg shadow-sm border {{ $activity->label ? 'border-' . $activity->label_color . '-400 border-2' : 'border-gray-200' }} p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <h2 class="text-xl font-semibold text-gray-800">{{ $activity->title }}</h2>
                                @if($activity->label_text)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $activity->label === 'urgent' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $activity->label === 'important' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $activity->label === 'featured' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $activity->label === 'last_minute' ? 'bg-orange-100 text-orange-800' : '' }}
                                        {{ $activity->label === 'help_needed' ? 'bg-purple-100 text-purple-800' : '' }}">
                                        {{ $activity->label_text }}
                                    </span>
                                @endif
                            </div>

                            <div class="space-y-1 text-sm text-gray-600 mb-3">
                                @if($activity->start_at || $activity->end_at)
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        @if($activity->start_at && $activity->end_at)
                                            {{ $activity->start_at->format('d.m.Y') }} - {{ $activity->end_at->format('d.m.Y') }}
                                        @elseif($activity->start_at)
                                            Ab {{ $activity->start_at->format('d.m.Y H:i') }}
                                        @else
                                            Bis {{ $activity->end_at->format('d.m.Y H:i') }}
                                        @endif
                                    </div>
                                @endif
                                @if($activity->location)
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        {{ $activity->location }}
                                    </div>
                                @endif
                            </div>

                            <p class="text-gray-700 line-clamp-2">{{ Str::limit($activity->description, 200) }}</p>

                            <div class="mt-3 flex items-center justify-between">
                                <span class="text-sm text-gray-500">
                                    von <span class="font-medium">{{ $activity->organizer_name }}</span>
                                </span>
                                <div class="flex items-center gap-3">
                                    @if($activity->has_forum && $activity->posts->count() > 0)
                                        <div class="flex items-center text-sm text-gray-500">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                            </svg>
                                            {{ $activity->posts->count() }} {{ $activity->posts->count() === 1 ? 'Beitrag' : 'Beiträge' }}
                                        </div>
                                    @endif
                                    @if($activity->has_shifts && $activity->shifts->count() > 0)
                                        @php
                                            $totalNeeded = $activity->shifts->sum('needed');
                                            $totalFilled = $activity->shifts->sum('filled');
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
                            $referenceDate = $activity->start_at ?? $activity->end_at;
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
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
                <a href="{{ route('activities.show', $activity->slug) }}" class="block bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
                        <div class="flex-1">
                            <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ $activity->title }}</h2>

                            <div class="space-y-1 text-sm text-gray-600 mb-3">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $activity->start_at->format('d.m.Y') }}
                                    @if($activity->end_at)
                                        - {{ $activity->end_at->format('d.m.Y') }}
                                    @endif
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $activity->start_at->format('H:i') }} Uhr
                                    @if($activity->end_at)
                                        - {{ $activity->end_at->format('H:i') }} Uhr
                                    @endif
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ $activity->location }}
                                </div>
                            </div>

                            <p class="text-gray-700 line-clamp-2">{{ Str::limit($activity->description, 200) }}</p>

                            <div class="mt-3 flex items-center justify-between">
                                <span class="text-sm text-gray-500">
                                    von <span class="font-medium">{{ $activity->organizer_name }}</span>
                                </span>
                                @if($activity->posts->count() > 0)
                                    <div class="flex items-center text-sm text-gray-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                        </svg>
                                        {{ $activity->posts->count() }} {{ $activity->posts->count() === 1 ? 'Beitrag' : 'Beiträge' }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="flex-shrink-0">
                            <span class="inline-block px-3 py-1 text-sm font-medium rounded-full
                                @if($activity->start_at->isFuture())
                                    bg-blue-100 text-blue-800
                                @elseif($activity->start_at->isToday())
                                    bg-green-100 text-green-800
                                @else
                                    bg-gray-100 text-gray-800
                                @endif">
                                @if($activity->start_at->isFuture())
                                    Bevorstehend
                                @elseif($activity->start_at->isToday())
                                    Heute
                                @else
                                    Vergangen
                                @endif
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
@endsection
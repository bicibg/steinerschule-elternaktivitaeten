@extends('layouts.app')

@section('title', $activity->title)

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Back Button -->
        <a href="{{ route('activities.index') }}" class="inline-flex items-center text-steiner-blue hover:text-steiner-dark mb-4">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Zurück zur Übersicht
        </a>

        <!-- Activity Details -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="mb-4">
                <div class="flex items-start justify-between">
                    <h1 class="text-2xl font-bold text-gray-800">{{ $activity->title }}</h1>
                    @if($activity->category_text)
                        <span class="ml-2 inline-flex items-center px-3 py-1 rounded text-sm font-medium
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
            </div>

            <div class="prose max-w-none mb-6">
                <p class="text-gray-700 whitespace-pre-wrap">{{ $activity->description }}</p>
            </div>

            <!-- Contact Information -->
            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                <h3 class="font-semibold text-gray-800 mb-3">Kontaktinformationen</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="font-medium">{{ $activity->contact_name }}</span>
                    </div>
                    @if($activity->contact_email)
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <a href="mailto:{{ $activity->contact_email }}" class="text-steiner-blue hover:text-steiner-dark">
                                {{ $activity->contact_email }}
                            </a>
                        </div>
                    @endif
                    @if($activity->contact_phone)
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <a href="tel:{{ $activity->contact_phone }}" class="text-steiner-blue hover:text-steiner-dark">
                                {{ $activity->contact_phone }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            @if($activity->meeting_time || $activity->meeting_location)
                <div class="bg-blue-50 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-800 mb-3">Treffen & Termine</h3>
                    <div class="space-y-2 text-sm">
                        @if($activity->meeting_time)
                            <div class="flex items-start">
                                <svg class="w-4 h-4 mr-3 text-gray-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>{{ $activity->meeting_time }}</span>
                            </div>
                        @endif
                        @if($activity->meeting_location)
                            <div class="flex items-start">
                                <svg class="w-4 h-4 mr-3 text-gray-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>{{ $activity->meeting_location }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Forum Section -->
        @if($activity->has_forum)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Diskussionsforum</h2>

                <!-- New Post Form -->
                @if(Auth::check())
                    <form action="{{ route('activity-posts.store', $activity->slug) }}" method="POST" class="mb-6">
                        @csrf
                        <div class="space-y-3">
                            <input type="text" name="author_name" value="{{ Auth::user()->name }}" readonly
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
                            <textarea name="body" rows="4" required
                                      placeholder="Schreiben Sie hier Ihren Beitrag..."
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-steiner-blue"></textarea>
                            <button type="submit"
                                    class="px-4 py-2 bg-steiner-blue text-white rounded-md hover:bg-steiner-dark transition-colors">
                                Beitrag veröffentlichen
                            </button>
                        </div>
                    </form>
                @else
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <p class="text-gray-600">
                            <a href="{{ route('login') }}" class="text-steiner-blue hover:text-steiner-dark font-medium">Melden Sie sich an</a>,
                            um einen Beitrag zu schreiben.
                        </p>
                    </div>
                @endif

                <!-- Posts List -->
                <div class="space-y-4">
                    @forelse($activity->posts as $post)
                        <div id="post-{{ $post->id }}" class="bg-gray-50 rounded-lg p-4">
                            <div class="flex justify-between items-start mb-2">
                                <span class="font-medium text-gray-800">{{ $post->author_name }}</span>
                                <span class="text-sm text-gray-500">{{ $post->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-gray-700 mb-3">{{ $post->body }}</p>

                            <!-- Comments -->
                            @if($post->comments->count() > 0)
                                <div class="ml-6 mt-3 space-y-3">
                                    @foreach($post->comments as $comment)
                                        <div id="comment-{{ $comment->id }}" class="bg-white rounded-lg p-3">
                                            <div class="flex justify-between items-start mb-1">
                                                <span class="font-medium text-gray-700 text-sm">{{ $comment->author_name }}</span>
                                                <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-gray-600 text-sm">{{ $comment->body }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Comment Form -->
                            @if(Auth::check())
                                <div class="mt-3" x-data="{ showCommentForm: false }">
                                    <button @click="showCommentForm = !showCommentForm"
                                            class="text-sm text-steiner-blue hover:text-steiner-dark">
                                        Antworten
                                    </button>
                                    <form x-show="showCommentForm" x-cloak
                                          action="{{ route('activity-comments.store', $post) }}" method="POST"
                                          class="mt-3">
                                        @csrf
                                        <div class="space-y-2">
                                            <input type="text" name="author_name" value="{{ Auth::user()->name }}" readonly
                                                   class="w-full px-2 py-1 text-sm border border-gray-300 rounded bg-gray-50">
                                            <textarea name="body" rows="2" required
                                                      placeholder="Ihr Kommentar..."
                                                      class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-steiner-blue"></textarea>
                                            <button type="submit"
                                                    class="px-3 py-1 text-sm bg-steiner-blue text-white rounded hover:bg-steiner-dark transition-colors">
                                                Kommentar veröffentlichen
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-8">
                            Noch keine Beiträge vorhanden. Seien Sie der Erste!
                        </p>
                    @endforelse
                </div>
            </div>
        @endif
    </div>
@endsection
@extends('layouts.app')

@section('title', $activity->title)

@section('content')
    <div class="mb-6">
        <a href="{{ route('activities.index') }}" class="inline-flex items-center text-steiner-blue hover:text-steiner-dark">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Zurück zur Übersicht
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $activity->title }}</h1>

        <div class="space-y-2 text-gray-600 mb-6">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                {{ $activity->start_at->format('d.m.Y') }}
                @if($activity->end_at)
                    - {{ $activity->end_at->format('d.m.Y') }}
                @endif
            </div>
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ $activity->start_at->format('H:i') }} Uhr
                @if($activity->end_at)
                    - {{ $activity->end_at->format('H:i') }} Uhr
                @endif
            </div>
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                {{ $activity->location }}
            </div>
        </div>

        <div class="prose max-w-none mb-6">
            {!! nl2br(e($activity->description)) !!}
        </div>

        <div class="border-t pt-4">
            <h3 class="font-semibold text-gray-700 mb-2">Organisator</h3>
            <div class="text-gray-600">
                <p class="font-medium">{{ $activity->organizer_name }}</p>
                @if($activity->organizer_phone)
                    <p class="mt-1">
                        <a href="tel:{{ $activity->organizer_phone }}" class="text-steiner-blue hover:text-steiner-dark">
                            {{ $activity->organizer_phone }}
                        </a>
                    </p>
                @endif
                @if($activity->organizer_email)
                    <p class="mt-1" x-data="{ revealed: false }">
                        <button @click="revealed = true" x-show="!revealed" class="text-steiner-blue hover:text-steiner-dark underline">
                            E-Mail anzeigen
                        </button>
                        <a x-show="revealed" x-cloak href="mailto:{{ $activity->organizer_email }}" class="text-steiner-blue hover:text-steiner-dark">
                            {{ $activity->organizer_email }}
                        </a>
                    </p>
                @endif
            </div>
        </div>
    </div>

    @if($activity->has_forum || $activity->has_shifts)
    <!-- Tabs for Forum and Shifts -->
    <div x-data="{ activeTab: '{{ $activity->has_forum ? 'forum' : 'shifts' }}' }" class="bg-white rounded-lg shadow-sm border border-gray-200">
        @if($activity->has_forum && $activity->has_shifts)
        <!-- Tab Navigation - Show both tabs -->
        <div class="flex border-b border-gray-200">
            <button @click="activeTab = 'forum'"
                    :class="activeTab === 'forum' ? 'border-b-2 border-steiner-blue text-steiner-blue' : 'text-gray-600 hover:text-gray-800'"
                    class="px-6 py-3 font-medium focus:outline-none transition-colors">
                Diskussion
            </button>
            <button @click="activeTab = 'shifts'"
                    :class="activeTab === 'shifts' ? 'border-b-2 border-steiner-blue text-steiner-blue' : 'text-gray-600 hover:text-gray-800'"
                    class="px-6 py-3 font-medium focus:outline-none transition-colors">
                Schichten
            </button>
        </div>
        @endif

        @if($activity->has_forum)
        <!-- Forum Tab Content -->
        <div x-show="{{ $activity->has_shifts ? "activeTab === 'forum'" : 'true' }}" class="p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Diskussion</h2>

        @php
            $num1 = rand(1, 10);
            $num2 = rand(1, 10);
            $captchaAnswer = $num1 + $num2;
            session(['captcha_answer' => $captchaAnswer]);
        @endphp

        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <h3 class="font-semibold text-gray-700 mb-3">Neuen Beitrag verfassen</h3>
            <form action="{{ route('posts.store', $activity->slug) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="author_name" class="block text-sm font-medium text-gray-700 mb-1">Ihr Name</label>
                    <input type="text" id="author_name" name="author_name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="z.B. Anna (2a)"
                           value="{{ old('author_name') }}"
                           maxlength="100">
                </div>
                <div class="mb-3">
                    <label for="body" class="block text-sm font-medium text-gray-700 mb-1">Ihre Nachricht</label>
                    <textarea id="body" name="body" rows="3" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Ihre Nachricht..."
                              maxlength="2000">{{ old('body') }}</textarea>
                </div>

                <div style="position: absolute; left: -9999px;">
                    <label for="website">Website</label>
                    <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                </div>

                <div class="mb-3">
                    <label for="captcha" class="block text-sm font-medium text-gray-700 mb-1">
                        Sicherheitsfrage: Was ist {{ $num1 }} + {{ $num2 }}?
                    </label>
                    <input type="number" id="captcha" name="captcha" required
                           class="w-32 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200">
                    Beitrag veröffentlichen
                </button>
            </form>
        </div>

        @if($activity->posts->count() > 0)
            <div class="space-y-4">
                @foreach($activity->posts as $post)
                    <div class="border border-gray-200 rounded-lg p-4" x-data="{ showCommentForm: false }">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-semibold text-gray-800">{{ $post->author_name }}</h4>
                            <span class="text-sm text-gray-500">{{ $post->created_at->format('d.m.Y H:i') }}</span>
                        </div>
                        <div class="text-gray-700 mb-3">
                            {!! nl2br(e($post->body)) !!}
                        </div>

                        <button @click="showCommentForm = !showCommentForm"
                                class="text-sm text-steiner-blue hover:text-steiner-dark">
                            <span x-show="!showCommentForm">Kommentieren</span>
                            <span x-show="showCommentForm" x-cloak>Kommentar abbrechen</span>
                        </button>

                        @if($post->comments->count() > 0)
                            <div class="mt-4 ml-6 space-y-3">
                                @foreach($post->comments as $comment)
                                    <div class="bg-gray-50 rounded-lg p-3">
                                        <div class="flex justify-between items-start mb-1">
                                            <h5 class="font-medium text-gray-700 text-sm">{{ $comment->author_name }}</h5>
                                            <span class="text-xs text-gray-500">{{ $comment->created_at->format('d.m.Y H:i') }}</span>
                                        </div>
                                        <div class="text-gray-600 text-sm">
                                            {!! nl2br(e($comment->body)) !!}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div x-show="showCommentForm" x-cloak class="mt-4 ml-6">
                            @php
                                $commentNum1 = rand(1, 10);
                                $commentNum2 = rand(1, 10);
                                $commentCaptchaAnswer = $commentNum1 + $commentNum2;
                                session(['captcha_answer_comment_' . $post->id => $commentCaptchaAnswer]);
                            @endphp

                            <form action="{{ route('comments.store', $post) }}" method="POST" class="bg-gray-50 rounded-lg p-3">
                                @csrf
                                <div class="mb-2">
                                    <input type="text" name="author_name" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                           placeholder="Ihr Name"
                                           maxlength="100">
                                </div>
                                <div class="mb-2">
                                    <textarea name="body" rows="2" required
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                              placeholder="Ihr Kommentar..."
                                              maxlength="800"></textarea>
                                </div>

                                <div style="position: absolute; left: -9999px;">
                                    <label for="website_comment_{{ $post->id }}">Website</label>
                                    <input type="text" id="website_comment_{{ $post->id }}" name="website" tabindex="-1" autocomplete="off">
                                </div>

                                <div class="mb-2">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">
                                        Was ist {{ $commentNum1 }} + {{ $commentNum2 }}?
                                    </label>
                                    <input type="number" name="captcha" required
                                           class="w-24 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                </div>

                                <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200 text-sm">
                                    Kommentar veröffentlichen
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-center py-8">
                Noch keine Beiträge vorhanden. Seien Sie der Erste!
            </p>
        @endif
        </div>
        @endif

        @if($activity->has_shifts)
        <!-- Shifts Tab Content -->
        <div x-show="{{ $activity->has_forum ? "activeTab === 'shifts'" : 'true' }}" x-cloak class="p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Schichten & Helfer</h2>

            @if($activity->shifts->count() > 0)
                <div class="space-y-4">
                    @foreach($activity->shifts as $shift)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-3">
                                <div>
                                    <h3 class="font-semibold text-gray-800">{{ $shift->role }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">{{ $shift->time }}</p>
                                </div>
                                <div class="mt-2 sm:mt-0">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        {{ $shift->filled >= $shift->needed ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $shift->filled }} / {{ $shift->needed }} besetzt
                                    </span>
                                </div>
                            </div>

                            @if($shift->volunteers->count() > 0)
                                <div class="mb-3">
                                    <p class="text-sm font-medium text-gray-700 mb-2">Angemeldete Helfer:</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($shift->volunteers as $volunteer)
                                            <span class="inline-flex items-center px-2 py-1 rounded-md bg-gray-100 text-sm text-gray-700">
                                                {{ $volunteer->name }}
                                                @auth
                                                    @if($volunteer->user_id === auth()->id())
                                                        <form action="{{ route('shifts.withdraw', $shift) }}" method="POST" class="inline ml-2">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-800">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endauth
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @auth
                                @if($shift->filled < $shift->needed && !$shift->volunteers->where('user_id', auth()->id())->count())
                                    <form action="{{ route('shifts.signup', $shift) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 bg-steiner-blue text-white rounded-md hover:bg-steiner-dark transition-colors text-sm">
                                            Für diese Schicht anmelden
                                        </button>
                                    </form>
                                @endif
                            @else
                                <p class="text-sm text-gray-600">
                                    <a href="{{ route('login') }}" class="text-steiner-blue hover:text-steiner-dark underline">Melden Sie sich an</a>,
                                    um sich für Schichten anzumelden.
                                </p>
                            @endauth
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-8">
                    Für diese Aktivität wurden keine Schichten geplant.
                </p>
            @endif
        </div>
        @endif
    </div>
    @endif
@endsection
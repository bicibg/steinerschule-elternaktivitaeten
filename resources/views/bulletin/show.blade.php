@extends('layouts.app')

@section('title', $bulletinPost->title)

@section('content')
    <div class="mb-6">
        @php
            $referrer = request()->headers->get('referer');
            $backRoute = route('bulletin.index');
            $backText = 'Zurück zur Pinnwand';

            if ($referrer && str_contains($referrer, '/kalender')) {
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

    <div class="bg-white rounded-lg shadow-sm border {{ $bulletinPost->label ? 'border-' . $bulletinPost->label_color . '-400 border-2' : 'border-gray-200' }} p-6 mb-6">
        <div class="mb-4">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $bulletinPost->title }}</h1>
            <div class="flex flex-wrap gap-2">
                @if($bulletinPost->category_text)
                    <span class="inline-flex items-center px-3 py-1 rounded text-sm font-medium
                        {{ $bulletinPost->category === 'anlass' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $bulletinPost->category === 'haus_umgebung_taskforces' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $bulletinPost->category === 'produktion' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $bulletinPost->category === 'organisation' ? 'bg-purple-100 text-purple-800' : '' }}
                        {{ $bulletinPost->category === 'verkauf' ? 'bg-pink-100 text-pink-800' : '' }}">
                        {{ $bulletinPost->category_text }}
                    </span>
                @endif
                @if($bulletinPost->label_text)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        {{ $bulletinPost->label === 'urgent' ? 'bg-red-100 text-red-800' : '' }}
                        {{ $bulletinPost->label === 'important' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $bulletinPost->label === 'featured' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $bulletinPost->label === 'last_minute' ? 'bg-orange-100 text-orange-800' : '' }}">
                        {{ $bulletinPost->label_text }}
                    </span>
                @endif
            </div>
        </div>

        <div class="space-y-2 text-gray-600 mb-6">
            @if($bulletinPost->start_at || $bulletinPost->end_at)
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    @if($bulletinPost->start_at && $bulletinPost->end_at)
                        {{ $bulletinPost->start_at->format('d.m.Y H:i') }} - {{ $bulletinPost->end_at->format('d.m.Y H:i') }} Uhr
                    @elseif($bulletinPost->start_at)
                        Ab {{ $bulletinPost->start_at->format('d.m.Y H:i') }} Uhr
                    @else
                        Bis {{ $bulletinPost->end_at->format('d.m.Y H:i') }} Uhr
                    @endif
                </div>
            @endif
            @if($bulletinPost->location)
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    {{ $bulletinPost->location }}
                </div>
            @endif
        </div>

        <div class="prose max-w-none mb-6">
            {!! nl2br(e($bulletinPost->description)) !!}
        </div>

        <div class="border-t pt-4">
            <h3 class="font-semibold text-gray-700 mb-2">Organisator</h3>
            <div class="text-gray-600">
                <p class="font-medium">{{ $bulletinPost->organizer_name }}</p>
                @if($bulletinPost->organizer_phone)
                    <p class="mt-1">
                        <a href="tel:{{ $bulletinPost->organizer_phone }}" class="text-steiner-blue hover:text-steiner-dark">
                            {{ $bulletinPost->organizer_phone }}
                        </a>
                    </p>
                @endif
                @if($bulletinPost->organizer_email)
                    <p class="mt-1" x-data="{ revealed: false }">
                        <button @click="revealed = true" x-show="!revealed" class="text-steiner-blue hover:text-steiner-dark underline">
                            E-Mail anzeigen
                        </button>
                        <a x-show="revealed" x-cloak href="mailto:{{ $bulletinPost->organizer_email }}" class="text-steiner-blue hover:text-steiner-dark">
                            {{ $bulletinPost->organizer_email }}
                        </a>
                    </p>
                @endif
            </div>
        </div>
    </div>

    @if($bulletinPost->has_forum || $bulletinPost->has_shifts)
    <!-- Tabs for Forum and Shifts -->
    <div x-data="{ activeTab: '{{ $bulletinPost->has_forum ? 'forum' : 'shifts' }}' }" class="bg-white rounded-lg shadow-sm border border-gray-200">
        @if($bulletinPost->has_forum && $bulletinPost->has_shifts)
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

        @if($bulletinPost->has_forum)
        <!-- Forum Tab Content -->
        <div x-show="{{ $bulletinPost->has_shifts ? "activeTab === 'forum'" : 'true' }}" class="p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Diskussion</h2>
            @auth
            <div class="bg-gray-50 rounded-lg p-4 mb-6" x-data="{
                body: '',
                loading: false,
                async submitPost() {
                    if (!this.body.trim() || this.loading) return;
                    this.loading = true;

                    try {
                        const response = await fetch('/api/pinnwand/{{ $bulletinPost->slug }}/posts', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                            },
                            body: JSON.stringify({ body: this.body })
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.body = '';
                            location.reload();
                        }
                    } catch (error) {
                        console.error('Fehler:', error);
                    }

                    this.loading = false;
                }
            }">
                <h3 class="font-semibold text-gray-700 mb-3">Neuen Beitrag verfassen</h3>
                <div>
                    <div class="mb-3">
                        <textarea x-model="body" rows="3" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-steiner-blue focus:border-transparent"
                                  placeholder="Ihre Nachricht..."
                                  maxlength="2000"></textarea>
                    </div>

                    <button @click="submitPost()" :disabled="loading"
                            class="px-4 py-2 bg-steiner-blue text-white rounded-md hover:bg-steiner-dark transition-colors duration-200 disabled:opacity-50">
                        <span x-show="!loading">Beitrag veröffentlichen</span>
                        <span x-show="loading">Wird verarbeitet...</span>
                    </button>
                </div>
            </div>

            @if($bulletinPost->posts->count() > 0)
                <div class="space-y-4">
                    @foreach($bulletinPost->posts as $post)
                        <div class="border border-gray-200 rounded-lg p-4" x-data="{ showCommentForm: false }">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-semibold">
                                    <a href="{{ route('profile.show', $post->user_id) }}" class="text-steiner-blue hover:text-steiner-dark hover:underline">
                                        {{ $post->user->name }}
                                    </a>
                                </h4>
                                <span class="text-sm text-gray-500">{{ $post->created_at->format('d.m.Y H:i') }}</span>
                            </div>
                            <div class="text-gray-700 mb-3">
                                {!! nl2br(e($post->body)) !!}
                            </div>

                            @auth
                            <button @click="showCommentForm = !showCommentForm"
                                    class="text-sm text-steiner-blue hover:text-steiner-dark">
                                <span x-show="!showCommentForm">Kommentieren</span>
                                <span x-show="showCommentForm" x-cloak>Kommentar abbrechen</span>
                            </button>
                            @endauth

                            @if($post->comments->count() > 0)
                                <div class="mt-4 ml-6 space-y-3">
                                    @foreach($post->comments as $comment)
                                        <div class="bg-gray-50 rounded-lg p-3">
                                            <div class="flex justify-between items-start mb-1">
                                                <h5 class="font-medium text-sm">
                                                    <a href="{{ route('profile.show', $comment->user_id) }}" class="text-steiner-blue hover:text-steiner-dark hover:underline">
                                                        {{ $comment->user->name }}
                                                    </a>
                                                </h5>
                                                <span class="text-xs text-gray-500">{{ $comment->created_at->format('d.m.Y H:i') }}</span>
                                            </div>
                                            <div class="text-gray-600 text-sm">
                                                {!! nl2br(e($comment->body)) !!}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            @auth
                            <div x-show="showCommentForm" x-cloak class="mt-4 ml-6" x-data="{
                                commentBody: '',
                                commentLoading: false,
                                async submitComment() {
                                    if (!this.commentBody.trim() || this.commentLoading) return;
                                    this.commentLoading = true;

                                    try {
                                        const response = await fetch('/api/posts/{{ $post->id }}/comments', {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                                            },
                                            body: JSON.stringify({ body: this.commentBody })
                                        });

                                        const data = await response.json();

                                        if (data.success) {
                                            this.commentBody = '';
                                            location.reload();
                                        }
                                    } catch (error) {
                                        console.error('Fehler:', error);
                                    }

                                    this.commentLoading = false;
                                }
                            }">
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <div class="mb-2">
                                        <textarea x-model="commentBody" rows="2" required
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-steiner-blue focus:border-transparent text-sm"
                                                  placeholder="Ihr Kommentar..."
                                                  maxlength="800"></textarea>
                                    </div>

                                    <button @click="submitComment()" :disabled="commentLoading"
                                            class="px-3 py-1 bg-steiner-blue text-white rounded-md hover:bg-steiner-dark transition-colors duration-200 text-sm disabled:opacity-50">
                                        <span x-show="!commentLoading">Kommentar veröffentlichen</span>
                                        <span x-show="commentLoading">Wird verarbeitet...</span>
                                    </button>
                                </div>
                            </div>
                            @endauth
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-8">
                    Noch keine Beiträge vorhanden. Seien Sie der Erste!
                </p>
            @endif
            @else
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <p class="text-sm text-yellow-800">
                    <a href="{{ route('login') }}" class="text-steiner-blue hover:text-steiner-dark underline">Melden Sie sich an</a>,
                    um an der Diskussion teilzunehmen.
                </p>
            </div>
            @endauth
        </div>
        @endif

        @if($bulletinPost->has_shifts)
        <!-- Shifts Tab Content -->
        <div x-show="{{ $bulletinPost->has_forum ? "activeTab === 'shifts'" : 'true' }}" x-cloak class="p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Schichten & Helfer</h2>

            @if($bulletinPost->shifts->count() > 0)
                <div class="space-y-4">
                    @foreach($bulletinPost->shifts as $shift)
                        <div class="border border-gray-200 rounded-lg p-4"
                             x-data="{
                                 filled: {{ $shift->filled }},
                                 needed: {{ $shift->needed }},
                                 volunteers: {{ $shift->volunteers->map(function($v) {
                                     return ['id' => $v->id, 'name' => $v->name, 'user_id' => $v->user_id];
                                 })->toJson() }},
                                 loading: false,

                                 get isSignedUp() {
                                     return this.volunteers.some(v => v.user_id === {{ auth()->id() ?? 'null' }});
                                 },

                                 get isFull() {
                                     return this.filled >= this.needed;
                                 },

                                 async signup() {
                                     if (this.loading) return;
                                     this.loading = true;

                                     try {
                                         const response = await fetch('/api/shifts/{{ $shift->id }}/signup', {
                                             method: 'POST',
                                             headers: {
                                                 'Content-Type': 'application/json',
                                                 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                                             }
                                         });

                                         const data = await response.json();

                                         if (data.success) {
                                             this.volunteers.push(data.volunteer);
                                             this.filled = data.filled;
                                         }
                                     } catch (error) {
                                         console.error('Fehler:', error);
                                     }

                                     this.loading = false;
                                 },

                                 async withdraw() {
                                     if (this.loading) return;
                                     this.loading = true;

                                     try {
                                         const response = await fetch('/api/shifts/{{ $shift->id }}/withdraw', {
                                             method: 'DELETE',
                                             headers: {
                                                 'Content-Type': 'application/json',
                                                 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                                             }
                                         });

                                         const data = await response.json();

                                         if (data.success) {
                                             this.volunteers = this.volunteers.filter(v => v.user_id !== {{ auth()->id() ?? 'null' }});
                                             this.filled = data.filled;
                                         }
                                     } catch (error) {
                                         console.error('Fehler:', error);
                                     }

                                     this.loading = false;
                                 }
                             }">
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-3">
                                <div>
                                    <h3 class="font-semibold text-gray-800">{{ $shift->role }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">{{ $shift->time }}</p>
                                </div>
                                <div class="mt-2 sm:mt-0">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                          :class="filled >= needed ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'">
                                        <span x-text="filled"></span>/<span x-text="needed"></span> besetzt
                                    </span>
                                </div>
                            </div>

                            @auth
                            <template x-if="volunteers.length > 0">
                                <div class="mb-3">
                                    <p class="text-sm font-medium text-gray-700 mb-2">Angemeldete Helfer:</p>
                                    <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                                        <template x-for="volunteer in volunteers" :key="volunteer.id">
                                            <li class="flex items-center justify-between">
                                                <template x-if="volunteer.user_id">
                                                    <a :href="'/profile/' + volunteer.user_id"
                                                       class="text-steiner-blue hover:text-steiner-dark hover:underline"
                                                       x-text="volunteer.name"></a>
                                                </template>
                                                <template x-if="!volunteer.user_id">
                                                    <span x-text="volunteer.name"></span>
                                                </template>
                                                <button x-show="volunteer.user_id === {{ auth()->id() }}"
                                                        @click="withdraw()"
                                                        :disabled="loading"
                                                        class="text-xs text-red-600 hover:text-red-800 underline ml-2">
                                                    Abmelden
                                                </button>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </template>

                            <template x-if="volunteers.length === 0">
                                <p class="text-sm text-gray-500 mb-3">
                                    Noch keine Anmeldungen
                                </p>
                            </template>
                            @endauth

                            @auth
                                <button x-show="!isFull && !isSignedUp"
                                        @click="signup()"
                                        :disabled="loading"
                                        class="px-4 py-2 bg-steiner-blue text-white rounded-md hover:bg-steiner-dark transition-colors text-sm disabled:opacity-50">
                                    <span x-show="!loading">Für diese Schicht anmelden</span>
                                    <span x-show="loading">Wird verarbeitet...</span>
                                </button>
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
                    Für dieses Hilfegesuch wurden keine Schichten geplant.
                </p>
            @endif
        </div>
        @endif
    </div>
    @endif
@endsection

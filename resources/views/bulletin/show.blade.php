@extends('layouts.app')

@section('title', $bulletinPost->title)

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Back Navigation -->
        <div class="mb-4">
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

        <!-- Main Content Card -->
        <x-card class="mb-6" :class="$bulletinPost->label ? 'border-' . $bulletinPost->label_color . '-400 border-2' : ''">
            <!-- Title and Badges -->
            <div class="mb-4">
                <div class="flex items-start justify-between">
                    <h1 class="text-2xl font-bold text-gray-800">{{ $bulletinPost->title }}</h1>
                    <div class="flex flex-wrap gap-2 ml-4">
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
            </div>

            <!-- Date and Location Info -->
            <div class="space-y-2 text-sm text-gray-600 mb-6">
                @if($bulletinPost->start_at || $bulletinPost->end_at)
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        {{ $bulletinPost->location }}
                    </div>
                @endif
            </div>

            <!-- Description -->
            <div class="prose max-w-none mb-6">
                <p class="text-gray-700 whitespace-pre-wrap">{{ $bulletinPost->description }}</p>
            </div>

            <!-- Contact Information -->
            <x-contact-info
                :name="$bulletinPost->organizer_name"
                :email="$bulletinPost->organizer_email"
                :phone="$bulletinPost->organizer_phone" />
        </x-card>

        <!-- Forum and Shifts Section -->
        @if($bulletinPost->has_forum || $bulletinPost->has_shifts)
        <x-card>
            @if($bulletinPost->has_forum && $bulletinPost->has_shifts)
            <!-- Tab Navigation -->
            <div class="flex border-b border-gray-200 -mx-6 -mt-6 mb-6" x-data="{ activeTab: 'forum' }">
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
            <!-- Forum Content -->
            <div x-show="{{ $bulletinPost->has_shifts ? "activeTab === 'forum'" : 'true' }}">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Diskussion</h2>
                @auth
                <!-- New Post Form -->
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

                <!-- Posts List -->
                @if($bulletinPost->posts->count() > 0)
                    <div class="space-y-4">
                        @foreach($bulletinPost->posts as $post)
                            <x-card compact="true" x-data="{ showCommentForm: false }">
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
                                            <div class="bg-steiner-lighter rounded-lg p-3">
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
                                            const response = await fetch('/api/bulletin-posts/{{ $post->id }}/comments', {
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
                                    <div class="bg-steiner-lighter rounded-lg p-3">
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
                            </x-card>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">
                        Noch keine Beiträge vorhanden. Seien Sie der Erste!
                    </p>
                @endif
                @else
                <x-alert type="warning">
                    <a href="{{ route('login') }}" class="text-steiner-blue hover:text-steiner-dark underline">Melden Sie sich an</a>,
                    um an der Diskussion teilzunehmen.
                </x-alert>
                @endauth
            </div>
            @endif

            @if($bulletinPost->has_shifts)
            <!-- Shifts Content -->
            <div x-show="{{ $bulletinPost->has_forum ? "activeTab === 'shifts'" : 'true' }}" {{ $bulletinPost->has_forum ? 'x-cloak' : '' }}>
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Schichten</h2>

                <div class="space-y-4" x-data="{
                    shifts: {{ json_encode($bulletinPost->shifts->map(function($shift) {
                        return [
                            'id' => $shift->id,
                            'date' => $shift->date->format('d.m.Y'),
                            'start' => $shift->start_time,
                            'end' => $shift->end_time,
                            'title' => $shift->title,
                            'description' => $shift->description,
                            'needed' => $shift->needed,
                            'filled' => $shift->filled,
                            'volunteers' => $shift->volunteers->map(function($volunteer) {
                                return [
                                    'id' => $volunteer->id,
                                    'user_id' => $volunteer->user_id,
                                    'name' => $volunteer->user->name ?? 'Unbekannt',
                                    'profile_url' => $volunteer->user_id ? route('profile.show', $volunteer->user_id) : null
                                ];
                            })->values(),
                            'isSignedUp' => auth()->check() ? $shift->volunteers->where('user_id', auth()->id())->count() > 0 : false
                        ];
                    })->values()) }},
                    async toggleShift(shiftId, index) {
                        const shift = this.shifts[index];

                        if (!{{ auth()->check() ? 'true' : 'false' }}) {
                            window.location.href = '{{ route('login') }}';
                            return;
                        }

                        try {
                            const response = await fetch(`/api/shifts/${shiftId}/toggle`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                                }
                            });

                            const data = await response.json();

                            if (data.success) {
                                // Update local state
                                shift.isSignedUp = data.isSignedUp;
                                shift.filled = data.filled;

                                // Update volunteers list
                                if (data.isSignedUp) {
                                    shift.volunteers.push({
                                        user_id: {{ auth()->id() ?? 'null' }},
                                        name: '{{ auth()->user()->name ?? '' }}',
                                        profile_url: '{{ auth()->check() ? route('profile.show', auth()->id()) : '' }}'
                                    });
                                } else {
                                    shift.volunteers = shift.volunteers.filter(v => v.user_id !== {{ auth()->id() ?? 'null' }});
                                }
                            } else {
                                alert(data.message || 'Ein Fehler ist aufgetreten');
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            alert('Ein Fehler ist aufgetreten');
                        }
                    }
                }">
                    <template x-for="(shift, index) in shifts" :key="shift.id">
                        <x-card compact="true">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h3 class="font-semibold text-gray-800" x-text="shift.title"></h3>
                                    <div class="text-sm text-gray-600 mt-1">
                                        <span x-text="shift.date"></span>,
                                        <span x-text="shift.start"></span> - <span x-text="shift.end"></span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm" :class="shift.filled >= shift.needed ? 'text-green-600 font-medium' : 'text-orange-600 font-medium'">
                                        <span x-text="shift.filled"></span>/<span x-text="shift.needed"></span> angemeldet
                                    </div>
                                    @auth
                                    <button @click="toggleShift(shift.id, index)"
                                            :class="shift.isSignedUp ? 'bg-red-600 hover:bg-red-700' : 'bg-steiner-blue hover:bg-steiner-dark'"
                                            class="mt-2 px-3 py-1 text-white text-sm rounded-md transition-colors duration-200">
                                        <span x-show="!shift.isSignedUp">Anmelden</span>
                                        <span x-show="shift.isSignedUp">Abmelden</span>
                                    </button>
                                    @endauth
                                </div>
                            </div>

                            <p class="text-gray-700 text-sm mb-3" x-text="shift.description"></p>

                            <template x-if="shift.volunteers.length > 0">
                                <div class="mb-3">
                                    <p class="text-sm font-medium text-gray-700 mb-2">Angemeldete Helfer:</p>
                                    <div class="flex flex-wrap gap-2">
                                        <template x-for="volunteer in shift.volunteers" :key="volunteer.id">
                                            <span class="inline-flex items-center px-3 py-1 bg-steiner-lighter rounded-full text-sm">
                                                <template x-if="volunteer.profile_url">
                                                    <a :href="volunteer.profile_url" class="text-steiner-blue hover:text-steiner-dark" x-text="volunteer.name"></a>
                                                </template>
                                                <template x-if="!volunteer.profile_url">
                                                    <span x-text="volunteer.name"></span>
                                                </template>
                                            </span>
                                        </template>
                                    </div>
                                </div>
                            </template>

                            <template x-if="shift.volunteers.length === 0 && shift.filled > 0">
                                <div class="text-sm text-gray-500">
                                    <span x-text="shift.filled"></span> Person(en) bereits angemeldet (offline)
                                </div>
                            </template>
                        </x-card>
                    </template>
                </div>
            </div>
            @endif
        </x-card>
        @endif
    </div>
@endsection
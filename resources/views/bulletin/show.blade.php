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
        <x-card class="mb-6">
            <!-- Pinnwand Badge to distinguish from Activities -->
            <div class="mb-4">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-steiner-blue text-white">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    Pinnwand - Unterstützung gesucht
                </span>
            </div>

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
                :name="$bulletinPost->contact_name"
                :email="$bulletinPost->contact_email"
                :phone="$bulletinPost->contact_phone" />
        </x-card>

        <!-- Forum and Shifts Section -->
        @if($bulletinPost->has_forum || $bulletinPost->has_shifts)
        <x-card x-data="{ activeTab: 'forum' }">
            @if($bulletinPost->has_forum && $bulletinPost->has_shifts)
            <!-- Tab Navigation -->
            <div class="flex border-b border-gray-200 -mx-6 -mt-6 mb-6">
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
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm text-gray-500">{{ $post->created_at->format('d.m.Y H:i') }}</span>
                                        @if(auth()->check() && (auth()->id() === $post->user_id || auth()->user()->is_admin))
                                            <button type="button"
                                                    @click="$dispatch('open-delete-modal-post-{{ $post->id }}')"
                                                    class="text-red-600 hover:text-red-800 text-sm">
                                                Löschen
                                            </button>
                                        @endif
                                    </div>
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
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-xs text-gray-500">{{ $comment->created_at->format('d.m.Y H:i') }}</span>
                                                        @if(auth()->check() && (auth()->id() === $comment->user_id || auth()->user()->is_admin))
                                                            <button type="button"
                                                                    @click="$dispatch('open-delete-modal-comment-{{ $comment->id }}')"
                                                                    class="text-red-600 hover:text-red-800 text-xs">
                                                                Löschen
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="text-gray-600 text-sm">
                                                    {!! nl2br(e($comment->body)) !!}
                                                </div>
                                            </div>

                                            {{-- Delete Modal for Comment --}}
                                            @if(auth()->check() && (auth()->id() === $comment->user_id || auth()->user()->is_admin))
                                                <x-delete-modal id="comment-{{ $comment->id }}"
                                                               action="{{ route('comments.destroy', $comment) }}"
                                                               title="Kommentar löschen"
                                                               message="Möchten Sie diesen Kommentar wirklich löschen?" />
                                            @endif
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

                            {{-- Delete Modal for Post --}}
                            @if(auth()->check() && (auth()->id() === $post->user_id || auth()->user()->is_admin))
                                <x-delete-modal id="post-{{ $post->id }}"
                                               action="{{ route('posts.destroy', $post) }}"
                                               title="Beitrag löschen"
                                               message="Möchten Sie diesen Beitrag wirklich löschen? Alle zugehörigen Kommentare werden ebenfalls gelöscht." />
                            @endif
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

                @guest
                <x-alert type="warning" class="mb-6">
                    <a href="{{ route('login') }}" class="text-steiner-blue hover:text-steiner-dark underline">Melden Sie sich an</a>,
                    um sich für Schichten anzumelden.
                </x-alert>
                @endguest

                <div class="space-y-4" x-data="{
                    shifts: {{ json_encode($bulletinPost->shifts->map(function($shift) {
                        return [
                            'id' => $shift->id,
                            'role' => $shift->role,
                            'time' => $shift->time,
                            'needed' => $shift->needed,
                            'offline_filled' => $shift->offline_filled,
                            'online_filled' => $shift->online_filled,
                            'total_filled' => $shift->total_filled,
                            'is_full' => $shift->is_full,
                            'remaining' => $shift->remaining,
                            'capacity_display' => $shift->capacity_display,
                            'volunteers' => auth()->check() ? $shift->volunteers->map(function($volunteer) {
                                return [
                                    'id' => $volunteer->id,
                                    'user_id' => $volunteer->user_id,
                                    'name' => $volunteer->user ? $volunteer->user->name : $volunteer->name,
                                    'profile_url' => $volunteer->user_id ? route('profile.show', $volunteer->user_id) : null
                                ];
                            })->values() : [],
                            'isSignedUp' => auth()->check() ? $shift->volunteers->where('user_id', auth()->id())->count() > 0 : false
                        ];
                    })->values()) }},
                    showErrorModal: false,
                    errorMessage: '',
                    async toggleShift(shiftId, index) {
                        const shift = this.shifts[index];

                        if (!{{ auth()->check() ? 'true' : 'false' }}) {
                            window.location.href = '{{ route('login') }}';
                            return;
                        }

                        try {
                            const endpoint = shift.isSignedUp ? `/api/shifts/${shiftId}/withdraw` : `/api/shifts/${shiftId}/signup`;
                            const response = await fetch(endpoint, {
                                method: shift.isSignedUp ? 'DELETE' : 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                                }
                            });

                            const data = await response.json();

                            if (data.success) {
                                // Update local state
                                shift.offline_filled = data.offline_filled;
                                shift.online_filled = data.online_count;
                                shift.total_filled = data.offline_filled + data.online_count;
                                shift.is_full = shift.needed ? shift.total_filled >= shift.needed : false;
                                shift.remaining = shift.needed ? Math.max(0, shift.needed - shift.total_filled) : 999;
                                shift.capacity_display = shift.needed ? shift.total_filled + '/' + shift.needed : shift.total_filled + ' angemeldet';

                                // Update volunteers list and isSignedUp based on action
                                if (!shift.isSignedUp) {
                                    // User is signing up
                                    shift.isSignedUp = true;
                                    shift.volunteers.push({
                                        id: data.volunteer.id,
                                        user_id: data.volunteer.user_id,
                                        name: data.volunteer.name,
                                        profile_url: '{{ auth()->check() ? route('profile.show', auth()->id()) : '' }}'
                                    });
                                } else {
                                    // User is withdrawing
                                    shift.isSignedUp = false;
                                    shift.volunteers = shift.volunteers.filter(v => v.user_id !== {{ auth()->id() ?? 'null' }});
                                }
                            } else {
                                this.errorMessage = data.message || data.error || 'Ein Fehler ist aufgetreten';
                                this.showErrorModal = true;
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            this.errorMessage = 'Ein Fehler ist aufgetreten. Bitte versuchen Sie es später erneut.';
                            this.showErrorModal = true;
                        }
                    }
                }">
                    <template x-for="(shift, index) in shifts" :key="shift.id">
                        <x-card compact="true">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h3 class="font-semibold text-gray-800" x-text="shift.role"></h3>
                                    <div class="text-sm text-gray-600 mt-1" x-text="shift.time"></div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm" :class="shift.is_full ? 'text-green-600 font-medium' : 'text-orange-600 font-medium'">
                                        <span x-text="shift.capacity_display"></span> angemeldet
                                    </div>
                                    @auth
                                    <button @click="toggleShift(shift.id, index)"
                                            :disabled="!shift.isSignedUp && shift.is_full"
                                            :class="{
                                                'bg-red-600 hover:bg-red-700': shift.isSignedUp,
                                                'bg-steiner-blue hover:bg-steiner-dark': !shift.isSignedUp && !shift.is_full,
                                                'bg-gray-400 cursor-not-allowed': !shift.isSignedUp && shift.is_full
                                            }"
                                            class="mt-2 px-3 py-1 text-white text-sm rounded-md transition-colors duration-200">
                                        <span x-show="!shift.isSignedUp && shift.is_full">Voll besetzt</span>
                                        <span x-show="!shift.isSignedUp && !shift.is_full">Anmelden</span>
                                        <span x-show="shift.isSignedUp">Abmelden</span>
                                    </button>
                                    @endauth
                                </div>
                            </div>

                            @auth
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
                            @endauth

                            @auth
                            <!-- Show offline registration count if there are offline registrations -->
                            <template x-if="shift.offline_filled > 0">
                                <div class="text-sm text-gray-500" :class="shift.volunteers.length > 0 ? 'mt-2' : ''">
                                    <span x-text="shift.offline_filled"></span> Person(en) bereits angemeldet (offline)
                                </div>
                            </template>
                            @else
                            <!-- For non-logged users, just show total count -->
                            <template x-if="(shift.offline_filled + shift.online_count) > 0">
                                <div class="text-sm text-gray-500">
                                    <span x-text="shift.offline_filled + shift.online_count"></span> Person(en) bereits angemeldet
                                </div>
                            </template>
                            @endauth
                        </x-card>
                    </template>

                    <!-- Error Modal -->
                    <template x-teleport="body">
                        <div x-show="showErrorModal"
                             x-cloak
                             @click.away="showErrorModal = false"
                             @keydown.escape.window="showErrorModal = false"
                             class="fixed inset-0 z-50 flex items-center justify-center">
                            <!-- Background overlay -->
                            <div x-show="showErrorModal"
                                 x-transition:enter="ease-out duration-300"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 x-transition:leave="ease-in duration-200"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 class="fixed inset-0 bg-gray-500 bg-opacity-75"
                                 @click="showErrorModal = false"></div>

                            <!-- Modal content -->
                            <div x-show="showErrorModal"
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
                                            Fehler bei der Anmeldung
                                        </h3>
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-500" x-text="errorMessage"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                                    <button type="button"
                                            @click="showErrorModal = false"
                                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-steiner-blue text-base font-medium text-white hover:bg-steiner-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-steiner-blue sm:ml-3 sm:w-auto sm:text-sm">
                                        OK
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
            @endif
        </x-card>
        @endif
    </div>
@endsection

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
        <x-card class="mb-6">
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
            <x-contact-info
                :name="$activity->contact_name"
                :email="$activity->contact_email"
                :phone="$activity->contact_phone" />

            @if($activity->meeting_time || $activity->meeting_location)
                <div class="bg-steiner-lighter rounded-lg p-4 mt-4">
                    <h3 class="font-semibold text-gray-800 mb-3">Treffen & Termine</h3>
                    <div class="space-y-2 text-sm">
                        @if($activity->meeting_time)
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>{{ $activity->meeting_time }}</span>
                            </div>
                        @endif
                        @if($activity->meeting_location)
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>{{ $activity->meeting_location }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </x-card>

        <!-- Forum Section -->
        @if($activity->has_forum)
            <x-card>
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
                            const response = await fetch('/api/elternaktivitaeten/{{ $activity->slug }}/posts', {
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
                @if($activity->posts->count() > 0)
                    <div class="space-y-4">
                        @foreach($activity->posts as $post)
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
                                            const response = await fetch('/api/activity-posts/{{ $post->id }}/comments', {
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
            </x-card>
        @endif
    </div>
@endsection
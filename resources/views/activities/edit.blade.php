@extends('layouts.app')

@section('title', 'Aktivität bearbeiten')

@section('content')
    <div class="mb-6">
        <a href="{{ route('activities.show', $activity->slug) }}" class="inline-flex items-center text-steiner-blue hover:text-steiner-dark">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Zurück zur Aktivität
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Aktivität bearbeiten</h1>

        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <p class="text-sm text-yellow-800">
                <strong>Hinweis:</strong> Sie bearbeiten diese Aktivität über einen speziellen Link.
                Bewahren Sie diesen Link sicher auf, um später weitere Änderungen vornehmen zu können.
            </p>
        </div>

        <form action="{{ route('activities.update', $activity->slug) }}?token={{ request('token') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Titel *</label>
                    <input type="text" id="title" name="title" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           value="{{ old('title', $activity->title) }}"
                           maxlength="255">
                </div>

                <div>
                    <label for="start_at" class="block text-sm font-medium text-gray-700 mb-1">Startdatum und -zeit *</label>
                    <input type="datetime-local" id="start_at" name="start_at" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           value="{{ old('start_at', $activity->start_at->format('Y-m-d\TH:i')) }}">
                </div>

                <div>
                    <label for="end_at" class="block text-sm font-medium text-gray-700 mb-1">Enddatum und -zeit</label>
                    <input type="datetime-local" id="end_at" name="end_at"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           value="{{ old('end_at', $activity->end_at?->format('Y-m-d\TH:i')) }}">
                </div>

                <div class="md:col-span-2">
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Ort *</label>
                    <input type="text" id="location" name="location" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           value="{{ old('location', $activity->location) }}"
                           maxlength="255">
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Beschreibung *</label>
                    <textarea id="description" name="description" rows="5" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description', $activity->description) }}</textarea>
                </div>

                <div>
                    <label for="organizer_name" class="block text-sm font-medium text-gray-700 mb-1">Organisator Name *</label>
                    <input type="text" id="organizer_name" name="organizer_name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           value="{{ old('organizer_name', $activity->organizer_name) }}"
                           maxlength="255">
                </div>

                <div>
                    <label for="organizer_phone" class="block text-sm font-medium text-gray-700 mb-1">Telefonnummer</label>
                    <input type="tel" id="organizer_phone" name="organizer_phone"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           value="{{ old('organizer_phone', $activity->organizer_phone) }}"
                           maxlength="50">
                </div>

                <div>
                    <label for="organizer_email" class="block text-sm font-medium text-gray-700 mb-1">E-Mail-Adresse</label>
                    <input type="email" id="organizer_email" name="organizer_email"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           value="{{ old('organizer_email', $activity->organizer_email) }}"
                           maxlength="255">
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                    <select id="status" name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="published" {{ old('status', $activity->status) === 'published' ? 'selected' : '' }}>
                            Veröffentlicht
                        </option>
                        <option value="archived" {{ old('status', $activity->status) === 'archived' ? 'selected' : '' }}>
                            Archiviert
                        </option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <div class="space-y-3">
                        <h3 class="text-sm font-medium text-gray-700">Funktionen aktivieren</h3>
                        <div class="flex items-center space-x-6">
                            <label class="flex items-center">
                                <input type="checkbox" name="has_forum" value="1"
                                       {{ old('has_forum', $activity->has_forum) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-steiner-blue focus:ring-steiner-blue">
                                <span class="ml-2 text-sm text-gray-700">Diskussionsforum</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="has_shifts" value="1"
                                       {{ old('has_shifts', $activity->has_shifts) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-steiner-blue focus:ring-steiner-blue">
                                <span class="ml-2 text-sm text-gray-700">Schichtplanung</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-between">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200">
                    Änderungen speichern
                </button>
                <a href="{{ route('activities.show', $activity->slug) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors duration-200 inline-block">
                    Abbrechen
                </a>
            </div>
        </form>
    </div>

    @if($activity->allPosts->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mt-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Moderation</h2>

            <div class="space-y-4">
                @foreach($activity->allPosts as $post)
                    <div class="border border-gray-200 rounded-lg p-4 {{ $post->is_hidden ? 'bg-red-50' : '' }}">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h4 class="font-semibold text-gray-800">{{ $post->author_name }}</h4>
                                <span class="text-sm text-gray-500">{{ $post->created_at->format('d.m.Y H:i') }}</span>
                                @if($post->is_hidden)
                                    <span class="ml-2 text-xs bg-red-100 text-red-800 px-2 py-1 rounded">Versteckt</span>
                                @endif
                            </div>
                            <form action="{{ route('moderation.post.toggle', $post) }}?token={{ request('token') }}" method="POST">
                                @csrf
                                <button type="submit" class="px-2 py-1 text-xs rounded {{ $post->is_hidden ? 'bg-gray-200 text-gray-700 hover:bg-gray-300' : 'bg-red-500 text-white hover:bg-red-600' }} transition-colors duration-200">
                                    {{ $post->is_hidden ? 'Anzeigen' : 'Verstecken' }}
                                </button>
                            </form>
                        </div>
                        <div class="text-gray-700">
                            {!! nl2br(e($post->body)) !!}
                        </div>

                        @if($post->allComments->count() > 0)
                            <div class="mt-4 ml-6 space-y-2">
                                @foreach($post->allComments as $comment)
                                    <div class="bg-gray-50 rounded-lg p-3 {{ $comment->is_hidden ? 'bg-red-50' : '' }}">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h5 class="font-medium text-gray-700 text-sm">{{ $comment->author_name }}</h5>
                                                <span class="text-xs text-gray-500">{{ $comment->created_at->format('d.m.Y H:i') }}</span>
                                                @if($comment->is_hidden)
                                                    <span class="ml-2 text-xs bg-red-100 text-red-800 px-2 py-1 rounded">Versteckt</span>
                                                @endif
                                            </div>
                                            <form action="{{ route('moderation.comment.toggle', $comment) }}?token={{ request('token') }}" method="POST">
                                                @csrf
                                                <button type="submit" class="px-2 py-1 text-xs rounded {{ $comment->is_hidden ? 'bg-gray-200 text-gray-700 hover:bg-gray-300' : 'bg-red-500 text-white hover:bg-red-600' }} transition-colors duration-200">
                                                    {{ $comment->is_hidden ? 'Anzeigen' : 'Verstecken' }}
                                                </button>
                                            </form>
                                        </div>
                                        <div class="text-gray-600 text-sm mt-1">
                                            {!! nl2br(e($comment->body)) !!}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mt-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-3">Bearbeitungslink</h3>
        <p class="text-sm text-gray-600 mb-3">
            Speichern Sie diesen Link, um später weitere Änderungen vornehmen zu können:
        </p>
        <div class="bg-gray-50 p-3 rounded-lg">
            <code class="text-xs break-all">{{ url()->current() }}?token={{ request('token') }}</code>
        </div>
    </div>
@endsection
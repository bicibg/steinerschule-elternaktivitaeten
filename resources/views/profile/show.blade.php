@extends('layouts.app')

@section('title', $user->name)

@section('content')
    <div class="max-w-3xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="javascript:history.back()" class="inline-flex items-center text-steiner-blue hover:text-steiner-dark">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Zurück
            </a>
        </div>

        <!-- Profile Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">{{ $user->name }}</h1>
                    @if($user->is_admin)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium mt-2
                            {{ $user->is_super_admin ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ $user->is_super_admin ? 'Super Administrator' : 'Administrator' }}
                        </span>
                    @endif
                </div>
                @if(auth()->check() && auth()->id() === $user->id)
                    <a href="{{ route('profile.edit') }}"
                       class="px-3 py-1.5 text-sm border border-steiner-blue text-steiner-blue rounded hover:bg-steiner-lighter hover:border-steiner-dark hover:text-steiner-dark transition-colors">
                        Profil bearbeiten
                    </a>
                @endif
            </div>

            <div class="space-y-4">
                <!-- Contact Information -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Kontaktinformationen</h3>
                    <div class="space-y-2">
                        @if($user->email && (auth()->check() && (auth()->user()->is_admin || auth()->id() === $user->id)))
                            <div class="flex items-center text-sm">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <a href="mailto:{{ $user->email }}" class="text-steiner-blue hover:text-steiner-dark">
                                    {{ $user->email }}
                                </a>
                            </div>
                        @endif
                        @if($user->phone)
                            <div class="flex items-center text-sm">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <a href="tel:{{ $user->phone }}" class="text-steiner-blue hover:text-steiner-dark">
                                    {{ $user->phone }}
                                </a>
                            </div>
                        @endif
                        @if(!$user->phone && (!auth()->check() || auth()->id() !== $user->id))
                            <p class="text-sm text-gray-500">Keine öffentlichen Kontaktinformationen verfügbar</p>
                        @endif
                    </div>
                </div>

                <!-- Remarks -->
                @if($user->remarks)
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Über mich</h3>
                        <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $user->remarks }}</p>
                    </div>
                @endif

                <!-- Member Since -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Mitglied seit</h3>
                    <p class="text-sm text-gray-700">{{ $user->created_at->format('d.m.Y') }}</p>
                </div>
            </div>
        </div>

        <!-- User's Activities -->
        @if(auth()->check() && auth()->id() === $user->id)
            <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Meine Aktivitäten</h2>
                <div class="space-y-2">
                    <a href="{{ route('profile.shifts') }}"
                       class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-gray-700">Meine Schichten</span>
                        </div>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection
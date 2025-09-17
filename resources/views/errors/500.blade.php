@extends('layouts.app')

@section('title', 'Serverfehler')

@section('content')
<div class="min-h-[50vh] flex items-center justify-center">
    <div class="text-center">
        <div class="mb-8">
            <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>

        <h1 class="text-4xl font-bold text-gray-800 mb-4">500</h1>
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Serverfehler</h2>

        <p class="text-gray-600 mb-8 max-w-md mx-auto">
            Ein unerwarteter Fehler ist aufgetreten. Bitte versuchen Sie es später erneut.
        </p>

        <div class="space-x-4">
            <a href="{{ route('bulletin.index') }}"
               class="inline-flex items-center px-4 py-2 bg-steiner-blue text-white rounded-md hover:bg-steiner-dark transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Zur Pinnwand
            </a>

            <button onclick="history.back()"
                    class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Zurück
            </button>
        </div>
    </div>
</div>
@endsection
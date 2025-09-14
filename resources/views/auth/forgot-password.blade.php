@extends('layouts.app')

@section('title', 'Passwort zurücksetzen')

@section('content')
    <div class="flex items-center justify-center min-h-[60vh]">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Passwort zurücksetzen</h2>

                @if (session('status'))
                    <div class="mb-4 text-sm text-green-600 bg-green-50 border border-green-200 rounded-lg p-3">
                        {{ session('status') }}
                    </div>
                @endif

                <p class="text-gray-600 mb-6 text-sm">
                    Geben Sie Ihre E-Mail-Adresse ein und wir senden Ihnen einen Link zum Zurücksetzen Ihres Passworts.
                </p>

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-Mail-Adresse</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-steiner-blue focus:border-transparent @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full py-2 px-4 bg-steiner-blue text-white rounded-md hover:bg-steiner-dark transition-colors duration-200">
                        Link zum Zurücksetzen senden
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}" class="text-sm text-steiner-blue hover:text-steiner-dark">
                        Zurück zur Anmeldung
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
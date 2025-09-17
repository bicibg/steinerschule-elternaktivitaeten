@extends('layouts.app')

@section('title', 'Anmelden')

@section('content')
    <div class="max-w-md mx-auto">
        <x-card>
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Anmelden</h2>

            <form action="{{ route('login') }}" method="POST">
                @csrf

                <x-form.input
                    label="E-Mail-Adresse"
                    name="email"
                    type="email"
                    :value="old('email')"
                    required
                    autofocus
                />

                <x-form.input
                    label="Passwort"
                    name="password"
                    type="password"
                    required
                />

                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-steiner-blue focus:ring-steiner-blue">
                        <span class="ml-2 text-sm text-gray-600">Angemeldet bleiben</span>
                    </label>
                </div>

                <x-button type="submit" variant="primary" block>
                    Anmelden
                </x-button>
            </form>

            <div class="mt-4 text-center">
                <a href="{{ route('password.request') }}" class="text-sm text-steiner-blue hover:text-steiner-dark">
                    Passwort vergessen?
                </a>
            </div>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Noch kein Konto?
                    <a href="{{ route('register') }}" class="text-steiner-blue hover:text-steiner-dark">
                        Jetzt registrieren
                    </a>
                </p>
            </div>

            <div class="mt-4 pt-4 border-t border-gray-200">
                <form action="{{ route('demo.login') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors duration-200">
                        Mit Demo-Konto anmelden
                    </button>
                </form>
            </div>
        </x-card>
    </div>
@endsection
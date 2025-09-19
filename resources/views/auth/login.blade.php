@extends('layouts.app')

@section('title', 'Anmelden')

@section('content')
    <div class="max-w-3xl mx-auto">
        <x-card>
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Anmelden</h2>

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <x-honeypot />

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

                <x-spam-protection-notice />
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
                <div class="space-y-3">
                    <form action="{{ route('demo.login') }}" method="POST">
                        @csrf
                        <x-button type="submit" variant="success" block>
                            Als Gast testen (Demo-Benutzer)
                        </x-button>
                    </form>

                    <form action="{{ route('demo.admin.login') }}" method="POST">
                        @csrf
                        <x-button type="submit" variant="warning" block>
                            Als Admin testen (Demo-Admin)
                        </x-button>
                    </form>
                </div>

                <p class="mt-3 text-xs text-gray-500 text-center">
                    Demo-Konten werden automatisch zurückgesetzt. Keine echten Daten werden gespeichert.
                </p>
            </div>
        </x-card>
    </div>
@endsection
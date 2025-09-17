@extends('layouts.app')

@section('title', 'Registrieren')

@section('content')
    <div class="max-w-3xl mx-auto">
        <x-card>
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Registrieren</h2>

            <form action="{{ route('register') }}" method="POST">
                @csrf

                <x-form.input
                    label="Name"
                    name="name"
                    type="text"
                    :value="old('name')"
                    required
                    autofocus
                />

                <x-form.input
                    label="E-Mail-Adresse"
                    name="email"
                    type="email"
                    :value="old('email')"
                    required
                />

                <x-form.input
                    label="Passwort"
                    name="password"
                    type="password"
                    required
                />

                <x-form.input
                    label="Passwort bestätigen"
                    name="password_confirmation"
                    type="password"
                    required
                />

                <x-button type="submit" variant="primary" block>
                    Registrieren
                </x-button>
            </form>

            <div class="mt-4 text-xs text-gray-600 text-center">
                Mit der Registrierung akzeptieren Sie unsere
                <a href="{{ route('legal.privacy') }}" class="text-steiner-blue hover:text-steiner-dark underline">Datenschutzerklärung</a>
                und das
                <a href="{{ route('legal.impressum') }}" class="text-steiner-blue hover:text-steiner-dark underline">Impressum</a>.
            </div>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Bereits ein Konto?
                    <a href="{{ route('login') }}" class="text-steiner-blue hover:text-steiner-dark">
                        Jetzt anmelden
                    </a>
                </p>
            </div>
        </x-card>
    </div>
@endsection

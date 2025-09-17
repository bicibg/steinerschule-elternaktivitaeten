@extends('layouts.app')

@section('title', 'Passwort zurücksetzen')

@section('content')
    <div class="flex items-center justify-center min-h-[60vh]">
        <div class="w-full max-w-3xl">
            <x-card>
                <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Passwort zurücksetzen</h2>

                @if (session('status'))
                    <x-alert type="success">
                        {{ session('status') }}
                    </x-alert>
                @endif

                <p class="text-gray-600 mb-6 text-sm">
                    Geben Sie Ihre E-Mail-Adresse ein und wir senden Ihnen einen Link zum Zurücksetzen Ihres Passworts.
                </p>

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <x-form.input
                        label="E-Mail-Adresse"
                        name="email"
                        type="email"
                        :value="old('email')"
                        required
                        autofocus
                    />

                    <x-button type="submit" variant="primary" block>
                        Link zum Zurücksetzen senden
                    </x-button>
                </form>

                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}" class="text-sm text-steiner-blue hover:text-steiner-dark">
                        Zurück zur Anmeldung
                    </a>
                </div>
            </x-card>
        </div>
    </div>
@endsection
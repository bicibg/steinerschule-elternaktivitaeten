@extends('layouts.app')

@section('title', 'Neues Passwort setzen')

@section('content')
    <div class="flex items-center justify-center min-h-[60vh]">
        <div class="w-full max-w-3xl">
            <x-card>
                <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Neues Passwort setzen</h2>

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <x-form.input
                        label="E-Mail-Adresse"
                        name="email"
                        type="email"
                        :value="$email ?? old('email')"
                        required
                        autofocus />

                    <x-form.input
                        label="Neues Passwort"
                        name="password"
                        type="password"
                        required />

                    <x-form.input
                        label="Passwort bestätigen"
                        name="password_confirmation"
                        type="password"
                        required />

                    <x-button type="submit" variant="primary" block>
                        Passwort zurücksetzen
                    </x-button>
                </form>
            </x-card>
        </div>
    </div>
@endsection
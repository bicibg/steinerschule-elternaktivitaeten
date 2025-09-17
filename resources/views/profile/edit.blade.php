@extends('layouts.app')

@section('title', 'Profil bearbeiten')

@section('content')
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Profil bearbeiten</h1>

        @if(session('success'))
            <x-alert type="success">
                {{ session('success') }}
            </x-alert>
        @endif

        <!-- Profile Information Form -->
        <x-card class="mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Persönliche Informationen</h2>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')

                <x-form.input
                    label="Name"
                    name="name"
                    type="text"
                    :value="old('name', $user->name)"
                    required />

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">E-Mail-Adresse</label>
                    <input type="email" value="{{ $user->email }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100"
                           disabled>
                    <p class="mt-1 text-xs text-gray-500">E-Mail-Adresse kann nicht geändert werden</p>
                </div>

                <x-form.input
                    label="Telefonnummer"
                    name="phone"
                    type="tel"
                    :value="old('phone', $user->phone)"
                    placeholder="+41 79 123 45 67" />

                <div>
                    <x-form.textarea
                        label="Bemerkungen"
                        name="remarks"
                        :value="old('remarks', $user->remarks)"
                        rows="3"
                        placeholder="Optionale Informationen über Sie (z.B. Verfügbarkeit, besondere Fähigkeiten, etc.)" />
                    <p class="mt-1 text-xs text-gray-500">Max. 500 Zeichen</p>
                </div>

                <x-button type="submit" variant="primary">
                    Änderungen speichern
                </x-button>
            </form>
        </x-card>

        <!-- Password Change Form -->
        <x-card>
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Passwort ändern</h2>

            <form method="POST" action="{{ route('profile.password.update') }}">
                @csrf
                @method('PATCH')

                <x-form.input
                    label="Aktuelles Passwort"
                    name="current_password"
                    type="password"
                    required />

                <x-form.input
                    label="Neues Passwort"
                    name="password"
                    type="password"
                    required />

                <x-form.input
                    label="Neues Passwort bestätigen"
                    name="password_confirmation"
                    type="password"
                    required />

                <x-button type="submit" variant="primary">
                    Passwort ändern
                </x-button>
            </form>
        </x-card>
    </div>
@endsection
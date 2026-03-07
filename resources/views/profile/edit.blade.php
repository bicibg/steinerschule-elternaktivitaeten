@extends('layouts.app')

@section('title', 'Profil bearbeiten')

@section('content')
    <div class="max-w-3xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Profil bearbeiten</h1>

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

                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">E-Mail-Adresse</label>
                    <input type="email" value="{{ $user->email }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm bg-gray-50 text-gray-600 cursor-not-allowed"
                           disabled>
                    <p class="mt-2 text-xs text-gray-500">E-Mail-Adresse kann nicht geändert werden</p>
                </div>

                <div class="mb-5">
                    <x-form.input
                        label="Telefonnummer (optional)"
                        name="phone"
                        type="tel"
                        :value="old('phone', $user->phone)"
                        placeholder="+41 79 123 45 67" />
                    <p class="-mt-3 text-xs text-gray-500">Ihre Telefonnummer wird nur für wichtige Mitteilungen verwendet</p>
                </div>

                <div class="mb-5" x-data="{
                    remarksText: '{{ old('remarks', $user->remarks) ?? '' }}',
                    get charCount() { return this.remarksText.length; }
                }">
                    <x-form.textarea
                        label="Bemerkungen"
                        name="remarks"
                        :value="old('remarks', $user->remarks)"
                        rows="3"
                        maxlength="200"
                        x-model="remarksText"
                        placeholder="Optionale Informationen über Sie (z.B. Verfügbarkeit, besondere Fähigkeiten, etc.)" />
                    <p class="-mt-3 text-xs text-gray-500">
                        <span x-text="charCount"></span>/200 Zeichen
                    </p>
                </div>

                <div class="mb-5">
                    <label class="flex items-center cursor-pointer">
                        <input type="hidden" name="hide_contact_details" value="0">
                        <input type="checkbox" name="hide_contact_details" value="1"
                               {{ old('hide_contact_details', $user->hide_contact_details) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-steiner-blue focus:ring-steiner-blue">
                        <span class="ml-3 text-sm font-medium text-gray-700">Kontaktdaten nur für angemeldete Nutzer sichtbar</span>
                    </label>
                    <p class="mt-1 ml-7 text-xs text-gray-500">Wenn aktiviert, können nur angemeldete Eltern Ihre E-Mail und Telefonnummer sehen. Ihr Name wird für nicht angemeldete Besucher gekürzt angezeigt.</p>
                </div>

                <div class="mb-5">
                    <label class="flex items-center cursor-pointer">
                        <input type="hidden" name="email_notifications" value="0">
                        <input type="checkbox" name="email_notifications" value="1"
                               {{ old('email_notifications', $user->email_notifications) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-steiner-blue focus:ring-steiner-blue">
                        <span class="ml-3 text-sm font-medium text-gray-700">E-Mail-Benachrichtigungen erhalten</span>
                    </label>
                    <p class="mt-1 ml-7 text-xs text-gray-500">Sie werden per E-Mail benachrichtigt, wenn sich jemand für Ihre Schichten anmeldet oder in Ihren Foren schreibt.</p>
                </div>

                <div class="flex justify-end">
                    <x-button type="submit" variant="primary">
                        Änderungen speichern
                    </x-button>
                </div>
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

                <div class="flex justify-end">
                    <x-button type="submit" variant="primary">
                        Passwort ändern
                    </x-button>
                </div>
            </form>
        </x-card>
        <!-- Delete Account Section -->
        @unless(auth()->user()->is_admin || auth()->user()->is_super_admin)
        <x-card class="mt-6 border-red-200">
            <h2 class="text-lg font-semibold text-red-700 mb-4">Konto löschen</h2>

            <div class="mb-4 text-sm text-gray-600 space-y-2">
                <p>Wenn Sie Ihr Konto löschen:</p>
                <ul class="list-disc list-inside space-y-1 ml-2">
                    <li>Ihr Konto wird sofort deaktiviert und Sie werden abgemeldet.</li>
                    <li>Nach <strong>30 Tagen</strong> werden alle persönlichen Daten unwiderruflich anonymisiert.</li>
                    <li>Ihre Beiträge bleiben erhalten, werden aber als «Anonymer Benutzer» angezeigt.</li>
                    <li>Sie können Ihr Konto innerhalb von 30 Tagen reaktivieren, indem Sie sich erneut anmelden.</li>
                </ul>
            </div>

            <form method="POST" action="{{ route('profile.destroy') }}"
                  x-data="{ confirmed: false }"
                  @submit.prevent="if (confirmed) { $el.submit() } else { confirmed = true }">
                @csrf
                @method('DELETE')

                <div x-show="!confirmed">
                    <x-button type="submit" variant="danger">
                        Konto löschen
                    </x-button>
                </div>

                <div x-show="confirmed" x-cloak class="space-y-4">
                    <div class="p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
                        Bitte geben Sie Ihr Passwort ein, um die Löschung zu bestätigen.
                    </div>

                    <x-form.input
                        label="Passwort bestätigen"
                        name="current_password"
                        type="password"
                        required />

                    <div class="flex items-center gap-3">
                        <x-button type="submit" variant="danger">
                            Endgültig löschen
                        </x-button>
                        <button type="button" @click="confirmed = false"
                                class="text-sm text-gray-500 hover:text-gray-700">
                            Abbrechen
                        </button>
                    </div>
                </div>
            </form>
        </x-card>
        @else
        <x-card class="mt-6 border-gray-200">
            <h2 class="text-lg font-semibold text-gray-500 mb-2">Konto löschen</h2>
            <p class="text-sm text-gray-500">Als Administrator können Sie Ihr eigenes Konto nicht löschen. Bitte wenden Sie sich an einen anderen Super-Administrator.</p>
        </x-card>
        @endunless
    </div>
@endsection
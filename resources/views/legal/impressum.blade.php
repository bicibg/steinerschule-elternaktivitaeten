@extends('layouts.app')

@section('title', 'Impressum')

@section('content')
<div class="max-w-4xl mx-auto">
    <x-card>
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Impressum</h1>

        <div class="prose prose-sm max-w-none text-gray-600">
            <h2 class="text-lg font-semibold text-gray-800 mt-6 mb-3">Betreiber der Webseite</h2>
            <p class="mb-4">
                Rudolf Steinerschule Bern in Langnau<br>
                Schlossstrasse 6<br>
                3550 Langnau im Emmental<br>
                Schweiz
            </p>
            <p class="mb-4">
                <strong>Telefon:</strong> +41 (0)34 402 12 80<br>
                <strong>E-Mail:</strong> info@steinerschule-bern.ch<br>
                <strong>Website:</strong> <a href="https://www.steinerschule-bern.ch" target="_blank" class="text-steiner-blue hover:text-steiner-dark underline">www.steinerschule-bern.ch</a>
            </p>

            <h2 class="text-lg font-semibold text-gray-800 mt-6 mb-3">Zweck der Plattform</h2>
            <p class="mb-4">
                Diese Plattform dient ausschliesslich der internen Koordination von Elternaktivitäten an der Rudolf Steinerschule Bern in Langnau.
                Sie ist nicht für die Öffentlichkeit bestimmt und wird von Freiwilligen betreut.
            </p>

            <h2 class="text-lg font-semibold text-gray-800 mt-6 mb-3">Haftungsausschluss</h2>
            <p class="mb-4">
                Diese Plattform wird ehrenamtlich von Eltern der Schule betrieben. Wir übernehmen keine Garantie für die ständige Verfügbarkeit
                oder Fehlerfreiheit der Webseite. Die Nutzung erfolgt auf eigene Verantwortung.
            </p>

            <h2 class="text-lg font-semibold text-gray-800 mt-6 mb-3">Inhalte und Urheberrecht</h2>
            <p class="mb-4">
                Die auf dieser Plattform veröffentlichten Inhalte unterliegen dem schweizerischen Urheberrecht.
                Die Vervielfältigung, Bearbeitung, Verbreitung und jede Art der Verwertung ausserhalb der Grenzen des Urheberrechts
                bedürfen der schriftlichen Zustimmung des jeweiligen Autors bzw. Erstellers.
            </p>

            <h2 class="text-lg font-semibold text-gray-800 mt-6 mb-3">Disclaimer für benutzergenerierte Inhalte</h2>
            <p class="mb-4">
                Für Inhalte, die von Nutzern erstellt werden (Forenbeiträge, Kommentare, Aktivitätsbeschreibungen),
                liegt die Verantwortung beim jeweiligen Verfasser. Die Plattformbetreiber überprüfen diese Inhalte nicht systematisch
                und übernehmen keine Haftung für deren Richtigkeit oder Rechtmässigkeit.
            </p>

            <h2 class="text-lg font-semibold text-gray-800 mt-6 mb-3">Technischer Support</h2>
            <p class="mb-4">
                Bei technischen Fragen oder Problemen wenden Sie sich bitte an:<br>
                <a href="{{ route('legal.contact') }}" class="text-steiner-blue hover:text-steiner-dark underline">Technischer Support</a>
            </p>

            <p class="text-xs text-gray-500 mt-8">
                Stand: {{ now()->format('d.m.Y') }}
            </p>
        </div>
    </x-card>
</div>
@endsection
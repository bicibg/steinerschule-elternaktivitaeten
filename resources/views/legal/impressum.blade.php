@extends('layouts.app')

@section('title', 'Impressum')

@section('content')
<div class="max-w-4xl mx-auto">
    <x-card>
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Impressum</h1>

        <div class="prose prose-sm max-w-none text-gray-600">
            <h2 class="text-lg font-semibold text-gray-800 mt-6 mb-3">Verantwortlich für diese Plattform</h2>
            <p class="mb-4">
                Diese Plattform wurde ehrenamtlich für die Elterngemeinschaft der<br>
                Rudolf Steinerschule Langnau entwickelt.
            </p>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                <p class="text-sm text-blue-900">
                    <strong>Hinweis:</strong> Dies ist keine offizielle Webseite der Rudolf Steinerschule Langnau,
                    sondern eine unabhängige Initiative von Eltern für Eltern zur Koordination der Elternaktivitäten.
                </p>
            </div>

            <p class="mb-4">
                <strong>Technische Entwicklung & Betreuung:</strong><br>
                Buğra Ergin (Ehrenamtlicher Entwickler)<br>
                E-Mail: <a href="mailto:bugraergin@gmail.com" class="text-steiner-blue hover:text-steiner-dark underline">bugraergin@gmail.com</a><br>
                Telefon: <a href="tel:+41796929136" class="text-steiner-blue hover:text-steiner-dark underline">079 692 91 36</a>
            </p>

            <p class="mb-4">
                <strong>Schulkontakt:</strong><br>
                Rudolf Steinerschule Langnau<br>
                Schlossstrasse 6<br>
                3550 Langnau im Emmental<br>
                Telefon: +41 (0)34 402 12 80<br>
                Website: <a href="https://www.steinerschule-bern.ch" target="_blank" class="text-steiner-blue hover:text-steiner-dark underline">www.steinerschule-bern.ch</a>
            </p>

            <h2 class="text-lg font-semibold text-gray-800 mt-6 mb-3">Zweck der Plattform</h2>
            <p class="mb-4">
                Diese Plattform dient ausschliesslich der internen Koordination von Elternaktivitäten an der Rudolf Steinerschule Langnau.
                Sie ist nicht für die Öffentlichkeit bestimmt und wird ehrenamtlich betreut.
            </p>

            <h2 class="text-lg font-semibold text-gray-800 mt-6 mb-3">Technische Details</h2>
            <p class="mb-4">
                Dies ist eine <strong>massgeschneiderte Eigenentwicklung</strong> - keine kommerzielle Software oder Standard-CMS wie WordPress oder Joomla.
                Ich habe die Plattform speziell für unsere Bedürfnisse programmiert.
            </p>
            <ul class="list-disc pl-6 mb-4">
                <li><strong>Technologie:</strong> Laravel Framework (PHP), Alpine.js, Tailwind CSS</li>
                <li><strong>Hosting:</strong> DigitalOcean Server in Amsterdam (Niederlande)</li>
                <li><strong>Datenbank:</strong> MySQL</li>
                <li><strong>Entwicklung:</strong> Komplett selbst programmiert, kein Baukastensystem</li>
                <li><strong>Quellcode:</strong> Kann bei Bedarf an die Schule oder Nachfolger übergeben werden</li>
            </ul>
            <p class="mb-4">
                Die Wahl dieser Technologien gewährleistet Stabilität, Sicherheit und einfache Wartbarkeit -
                auch für eventuelle zukünftige Entwickler.
            </p>

            <h2 class="text-lg font-semibold text-gray-800 mt-6 mb-3">Was diese Plattform NICHT ist</h2>
            <p class="mb-4">
                Nur um das klarzustellen - ich mache das in meiner Freizeit als Elternteil:
            </p>
            <ul class="list-disc pl-6 mb-4">
                <li>Dies ist kein Geschäft - alles ist kostenlos</li>
                <li>Ich verdiene nichts daran</li>
                <li>Es gibt keine Werbung</li>
                <li>Ihre Daten interessieren mich nur für die Organisation der Aktivitäten</li>
            </ul>
            <p class="mb-4">
                Es ist einfach ein Werkzeug von Eltern für Eltern, damit wir uns besser organisieren können.
            </p>

            <h2 class="text-lg font-semibold text-gray-800 mt-6 mb-3">Haftungsausschluss</h2>
            <p class="mb-4">
                Da dies ein ehrenamtliches Projekt ist, kann ich nicht garantieren, dass immer alles perfekt funktioniert.
                Die Nutzung erfolgt auf eigene Verantwortung.
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
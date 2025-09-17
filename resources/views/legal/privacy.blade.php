@extends('layouts.app')

@section('title', 'Datenschutz')

@section('content')
<div class="max-w-4xl mx-auto">
    <x-card>
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Datenschutzerklärung</h1>

        <div class="prose prose-sm max-w-none text-gray-600">
            <h2 class="text-lg font-semibold text-gray-800 mt-6 mb-3">1. Worum geht es hier?</h2>
            <p class="mb-4">
                Als Elternteil an der Rudolf Steinerschule Langnau habe ich diese Plattform in meiner Freizeit entwickelt,
                um uns allen die Koordination der Elternaktivitäten zu erleichtern.
            </p>

            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                <p class="text-sm text-green-900">
                    <strong>Kurz gesagt:</strong> Dies ist ein privates Projekt von einem Elternteil für die Schulgemeinschaft.
                    Ihre Daten bleiben hier und werden nur für die Koordination der Aktivitäten verwendet - mehr nicht.
                </p>
            </div>

            <h2 class="text-lg font-semibold text-gray-800 mt-6 mb-3">2. Welche Daten werden erhoben?</h2>
            <ul class="list-disc pl-6 mb-4">
                <li>Name und E-Mail-Adresse (bei Registrierung)</li>
                <li>Telefonnummer (optional, für dringende Mitteilungen)</li>
                <li>Schichtanmeldungen und Verfügbarkeiten</li>
                <li>Forenbeiträge und Kommentare</li>
            </ul>

            <h2 class="text-lg font-semibold text-gray-800 mt-6 mb-3">3. Verwendungszweck</h2>
            <p class="mb-4">
                Ihre Daten werden ausschliesslich für folgende Zwecke verwendet:
            </p>
            <ul class="list-disc pl-6 mb-4">
                <li>Koordination von Elternaktivitäten</li>
                <li>Kommunikation bezüglich Schichten und Helfereinsätzen</li>
                <li>Ermöglichung der Teilnahme an Diskussionen</li>
                <li>Kontaktaufnahme bei dringenden Anliegen</li>
            </ul>

            <h2 class="text-lg font-semibold text-gray-800 mt-6 mb-3">4. Was passiert mit Ihren Daten?</h2>
            <p class="mb-4">
                Ganz einfach: Sie bleiben hier auf dieser Plattform für die Organisation unserer Aktivitäten.
                Ich habe weder das Interesse noch die Absicht, irgendetwas damit anzufangen - es geht nur darum,
                dass wir uns als Eltern besser organisieren können.
            </p>

            <h2 class="text-lg font-semibold text-gray-800 mt-6 mb-3">5. Datenspeicherung</h2>
            <p class="mb-4">
                Ihre Daten werden auf einem Server bei DigitalOcean in Amsterdam gespeichert und nur solange aufbewahrt,
                wie sie für den Betrieb der Plattform notwendig sind.
            </p>
            <p class="mb-4">
                <strong>Wichtig zum "Recht auf Vergessenwerden":</strong><br>
                Wenn Sie möchten, dass Ihre Daten vollständig anonymisiert werden (DSGVO Art. 17),
                kontaktieren Sie mich bitte direkt. Eine einfache Kontolöschung deaktiviert nur den Zugang,
                aber für eine vollständige Anonymisierung muss ich das manuell machen.
            </p>

            <h2 class="text-lg font-semibold text-gray-800 mt-6 mb-3">6. Ihre Rechte</h2>
            <p class="mb-4">
                Sie haben jederzeit das Recht:
            </p>
            <ul class="list-disc pl-6 mb-4">
                <li>Auskunft über Ihre gespeicherten Daten zu erhalten</li>
                <li>Ihre Daten korrigieren zu lassen</li>
                <li>Ihre Daten löschen zu lassen</li>
                <li>Der Datenverarbeitung zu widersprechen</li>
            </ul>

            <h2 class="text-lg font-semibold text-gray-800 mt-6 mb-3">7. Sessions & Cookies</h2>
            <p class="mb-4">
                Die Webseite verwendet nur Session-Cookies (technisch notwendig), damit Sie eingeloggt bleiben können.
                Das ist alles - keine Tracking, keine Werbung, keine Analyse.
            </p>

            <h2 class="text-lg font-semibold text-gray-800 mt-6 mb-3">8. Kontakt</h2>
            <p class="mb-4">
                Bei Fragen zum Datenschutz wenden Sie sich bitte an:<br>
                <a href="{{ route('legal.contact') }}" class="text-steiner-blue hover:text-steiner-dark underline">Technischer Support</a>
            </p>

            <p class="text-xs text-gray-500 mt-8">
                Stand: {{ now()->format('d.m.Y') }}
            </p>
        </div>
    </x-card>
</div>
@endsection
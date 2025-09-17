@extends('layouts.app')

@section('title', 'Datenschutz')

@section('content')
<div class="max-w-4xl mx-auto">
    <x-card>
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Datenschutzerklärung</h1>

        <div class="prose prose-sm max-w-none text-gray-600">
            <h2 class="text-lg font-semibold text-gray-800 mt-6 mb-3">1. Grundsätze</h2>
            <p class="mb-4">
                Diese Plattform wurde ehrenamtlich für die Elterngemeinschaft der Rudolf Steinerschule Bern in Langnau entwickelt.
                Es handelt sich um eine nicht-kommerzielle Initiative von Eltern für Eltern.
                Wir erheben nur die minimal notwendigen Daten zur Koordination der Elternaktivitäten.
            </p>

            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                <p class="text-sm text-green-900">
                    <strong>Wichtig:</strong> Wir verkaufen keine Daten, schalten keine Werbung und verwenden keine Tracking-Tools.
                    Diese Plattform dient ausschliesslich der Schulgemeinschaft.
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

            <h2 class="text-lg font-semibold text-gray-800 mt-6 mb-3">4. Datenweitergabe</h2>
            <p class="mb-4">
                Ihre persönlichen Daten werden nicht an Dritte weitergegeben. Die Daten sind nur für registrierte Nutzer der Plattform sichtbar.
                Ausnahme: Kontaktdaten von Aktivitäts-Organisatoren sind öffentlich einsehbar zur Ermöglichung der Kontaktaufnahme.
            </p>

            <h2 class="text-lg font-semibold text-gray-800 mt-6 mb-3">5. Datenspeicherung</h2>
            <p class="mb-4">
                Ihre Daten werden lokal auf einem Server in der Schweiz gespeichert und nur solange aufbewahrt, wie sie für den Betrieb der Plattform notwendig sind.
                Bei Löschung Ihres Kontos werden Ihre persönlichen Daten anonymisiert.
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

            <h2 class="text-lg font-semibold text-gray-800 mt-6 mb-3">7. Cookies</h2>
            <p class="mb-4">
                Diese Webseite verwendet nur technisch notwendige Cookies für die Anmeldung und Session-Verwaltung.
                Es werden keine Tracking- oder Analyse-Cookies verwendet.
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
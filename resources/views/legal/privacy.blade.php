@extends('layouts.app')

@section('title', 'Datenschutz')

@section('content')
<div class="max-w-4xl mx-auto">

    <!-- Auf einen Blick -->
    <x-card class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Datenschutz</h1>
        <p class="text-gray-500 mb-6">Wie Ihre Daten auf dieser Plattform geschützt sind</p>

        <!-- Kein Tracking -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-green-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <div>
                        <p class="font-semibold text-green-900">Kein Tracking</p>
                        <p class="text-sm text-green-800">Kein Google Analytics, keine Werbung, keine Analyse Ihres Verhaltens. Niemand schaut Ihnen zu.</p>
                    </div>
                </div>
            </div>
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-green-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <div>
                        <p class="font-semibold text-green-900">Nicht auf Google</p>
                        <p class="text-sm text-green-800">Die Seite ist komplett von Suchmaschinen ausgeschlossen. Niemand findet sie durch Googeln.</p>
                    </div>
                </div>
            </div>
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-green-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <div>
                        <p class="font-semibold text-green-900">Keine Weitergabe</p>
                        <p class="text-sm text-green-800">Ihre Daten werden an niemanden weitergegeben oder verkauft. Punkt.</p>
                    </div>
                </div>
            </div>
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-green-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <div>
                        <p class="font-semibold text-green-900">Server in Europa</p>
                        <p class="text-sm text-green-800">Alle Daten werden in Amsterdam (Niederlande) gespeichert, nicht in den USA oder anderswo.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Was wir speichern -->
        <h2 class="text-lg font-semibold text-gray-800 mb-3">Was wir speichern</h2>
        <div class="bg-gray-50 rounded-lg p-4 mb-8">
            <div class="space-y-3">
                <div class="flex items-start">
                    <span class="text-gray-400 mr-3 mt-0.5 flex-shrink-0">1.</span>
                    <div>
                        <p class="font-medium text-gray-800">Ihr Name und Ihre E-Mail-Adresse</p>
                        <p class="text-sm text-gray-500">Damit Sie sich anmelden und andere Eltern Sie erkennen.</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <span class="text-gray-400 mr-3 mt-0.5 flex-shrink-0">2.</span>
                    <div>
                        <p class="font-medium text-gray-800">Telefonnummer <span class="text-gray-400 font-normal">(freiwillig)</span></p>
                        <p class="text-sm text-gray-500">Nur wenn Sie sie selbst eingeben. Kann jederzeit gelöscht werden.</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <span class="text-gray-400 mr-3 mt-0.5 flex-shrink-0">3.</span>
                    <div>
                        <p class="font-medium text-gray-800">Schichtanmeldungen und Forenbeiträge</p>
                        <p class="text-sm text-gray-500">Was Sie sich für Aktivitäten anmelden und was Sie im Forum schreiben.</p>
                    </div>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-gray-200">
                <p class="text-sm text-gray-500">Das ist alles. Kein Geburtsdatum, keine Adresse, keine Fotos, kein Standort.</p>
            </div>
        </div>

        <!-- Wer kann was sehen -->
        <h2 class="text-lg font-semibold text-gray-800 mb-3">Wer kann was sehen?</h2>
        <div class="overflow-x-auto mb-8">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 pr-4 font-semibold text-gray-800"></th>
                        <th class="text-center py-3 px-3 font-semibold text-gray-800">Besucher<br><span class="font-normal text-gray-400 text-xs">(nicht angemeldet)</span></th>
                        <th class="text-center py-3 px-3 font-semibold text-gray-800">Eltern<br><span class="font-normal text-gray-400 text-xs">(angemeldet)</span></th>
                        <th class="text-center py-3 px-3 font-semibold text-gray-800">Admins</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600">
                    <tr class="border-b border-gray-100">
                        <td class="py-3 pr-4">Pinnwand-Einträge und Aktivitäten</td>
                        <td class="text-center py-3 px-3"><span class="text-green-600">Ja</span></td>
                        <td class="text-center py-3 px-3"><span class="text-green-600">Ja</span></td>
                        <td class="text-center py-3 px-3"><span class="text-green-600">Ja</span></td>
                    </tr>
                    <tr class="border-b border-gray-100">
                        <td class="py-3 pr-4">Name der Kontaktperson</td>
                        <td class="text-center py-3 px-3"><span class="text-yellow-600">Gekürzt*</span></td>
                        <td class="text-center py-3 px-3"><span class="text-green-600">Ja</span></td>
                        <td class="text-center py-3 px-3"><span class="text-green-600">Ja</span></td>
                    </tr>
                    <tr class="border-b border-gray-100">
                        <td class="py-3 pr-4">E-Mail / Telefon der Kontaktperson</td>
                        <td class="text-center py-3 px-3"><span class="text-red-500">Nein*</span></td>
                        <td class="text-center py-3 px-3"><span class="text-green-600">Ja</span></td>
                        <td class="text-center py-3 px-3"><span class="text-green-600">Ja</span></td>
                    </tr>
                    <tr class="border-b border-gray-100">
                        <td class="py-3 pr-4">Forum lesen und schreiben</td>
                        <td class="text-center py-3 px-3"><span class="text-red-500">Nein</span></td>
                        <td class="text-center py-3 px-3"><span class="text-green-600">Ja</span></td>
                        <td class="text-center py-3 px-3"><span class="text-green-600">Ja</span></td>
                    </tr>
                    <tr class="border-b border-gray-100">
                        <td class="py-3 pr-4">Sich für Schichten anmelden</td>
                        <td class="text-center py-3 px-3"><span class="text-red-500">Nein</span></td>
                        <td class="text-center py-3 px-3"><span class="text-green-600">Ja</span></td>
                        <td class="text-center py-3 px-3"><span class="text-green-600">Ja</span></td>
                    </tr>
                    <tr>
                        <td class="py-3 pr-4">Verwaltung (alle Daten sehen/bearbeiten)</td>
                        <td class="text-center py-3 px-3"><span class="text-red-500">Nein</span></td>
                        <td class="text-center py-3 px-3"><span class="text-red-500">Nein</span></td>
                        <td class="text-center py-3 px-3"><span class="text-green-600">Ja</span></td>
                    </tr>
                </tbody>
            </table>
            <p class="text-xs text-gray-400 mt-2">* Wenn Sie in Ihrem Profil "Kontaktdaten nur für angemeldete Nutzer sichtbar" aktiviert haben.</p>
        </div>

        <!-- Sie haben die Kontrolle -->
        <h2 class="text-lg font-semibold text-gray-800 mb-3">Sie haben die Kontrolle</h2>
        <div class="space-y-3 mb-4">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-steiner-blue mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <div>
                    <p class="font-medium text-gray-800">Kontaktdaten verstecken</p>
                    <p class="text-sm text-gray-500">
                        Im <a href="{{ route('profile.edit') }}" class="text-steiner-blue hover:text-steiner-dark underline">Profil</a> können
                        Sie einstellen, dass Ihre E-Mail und Telefonnummer nur für angemeldete Eltern sichtbar sind.
                    </p>
                </div>
            </div>
            <div class="flex items-start">
                <svg class="w-5 h-5 text-steiner-blue mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                <div>
                    <p class="font-medium text-gray-800">Konto löschen</p>
                    <p class="text-sm text-gray-500">
                        Sie können Ihr Konto jederzeit selbst löschen. Nach 30 Tagen werden alle persönlichen Daten
                        unwiderruflich anonymisiert. Ihre Beiträge bleiben erhalten, aber ohne Ihren Namen.
                    </p>
                </div>
            </div>
            <div class="flex items-start">
                <svg class="w-5 h-5 text-steiner-blue mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                <div>
                    <p class="font-medium text-gray-800">Fragen? Einfach fragen.</p>
                    <p class="text-sm text-gray-500">
                        Wenn Sie unsicher sind oder etwas wissen möchten:
                        <a href="{{ route('legal.contact') }}" class="text-steiner-blue hover:text-steiner-dark underline">Schreiben Sie mir</a>.
                        Das ist ein Elternprojekt, kein Konzern - ich antworte persönlich.
                    </p>
                </div>
            </div>
        </div>
    </x-card>

    <!-- Ausführliche Datenschutzerklärung -->
    <x-card>
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Ausführliche Datenschutzerklärung</h2>

        <div class="prose prose-sm max-w-none text-gray-600">
            <h3 class="text-base font-semibold text-gray-800 mt-4 mb-3">1. Worum geht es hier?</h3>
            <p class="mb-4">
                Als Elternteil an der Rudolf Steinerschule Langnau habe ich diese Plattform in meiner Freizeit entwickelt,
                um uns allen die Koordination der Elternaktivitäten zu erleichtern.
            </p>

            <h3 class="text-base font-semibold text-gray-800 mt-4 mb-3">2. Welche Daten werden erhoben?</h3>
            <ul class="list-disc pl-6 mb-4">
                <li>Name und E-Mail-Adresse (bei Registrierung)</li>
                <li>Telefonnummer (optional, für dringende Mitteilungen)</li>
                <li>Schichtanmeldungen und Verfügbarkeiten</li>
                <li>Forenbeiträge und Kommentare</li>
            </ul>

            <h3 class="text-base font-semibold text-gray-800 mt-4 mb-3">3. Verwendungszweck</h3>
            <p class="mb-4">
                Ihre Daten werden ausschliesslich für folgende Zwecke verwendet:
            </p>
            <ul class="list-disc pl-6 mb-4">
                <li>Koordination von Elternaktivitäten</li>
                <li>Kommunikation bezüglich Schichten und Helfereinsätzen</li>
                <li>Ermöglichung der Teilnahme an Diskussionen</li>
                <li>Kontaktaufnahme bei dringenden Anliegen</li>
            </ul>

            <h3 class="text-base font-semibold text-gray-800 mt-4 mb-3">4. Was passiert mit Ihren Daten?</h3>
            <p class="mb-4">
                Ganz einfach: Sie bleiben hier auf dieser Plattform für die Organisation unserer Aktivitäten.
                Ich habe weder das Interesse noch die Absicht, irgendetwas damit anzufangen - es geht nur darum,
                dass wir uns als Eltern besser organisieren können.
            </p>

            <h3 class="text-base font-semibold text-gray-800 mt-4 mb-3">5. Datenspeicherung</h3>
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

            <h3 class="text-base font-semibold text-gray-800 mt-4 mb-3">6. Ihre Rechte</h3>
            <p class="mb-4">
                Sie haben jederzeit das Recht:
            </p>
            <ul class="list-disc pl-6 mb-4">
                <li>Auskunft über Ihre gespeicherten Daten zu erhalten</li>
                <li>Ihre Daten korrigieren zu lassen</li>
                <li>Ihre Daten löschen zu lassen</li>
                <li>Der Datenverarbeitung zu widersprechen</li>
            </ul>

            <h3 class="text-base font-semibold text-gray-800 mt-4 mb-3">7. Sessions & Cookies</h3>
            <p class="mb-4">
                Die Webseite verwendet nur Session-Cookies (technisch notwendig), damit Sie eingeloggt bleiben können.
                Das ist alles - keine Tracking, keine Werbung, keine Analyse.
            </p>

            <h3 class="text-base font-semibold text-gray-800 mt-4 mb-3">8. Kontakt</h3>
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

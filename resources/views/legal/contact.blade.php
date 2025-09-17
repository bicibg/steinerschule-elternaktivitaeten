@extends('layouts.app')

@section('title', 'Kontakt')

@section('content')
<div class="max-w-4xl mx-auto">
    <x-card>
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Technischer Support</h1>

        <div class="prose prose-sm max-w-none text-gray-600">
            <p class="mb-6">
                Bei technischen Problemen oder Fragen zur Nutzung dieser Plattform können Sie sich gerne an uns wenden.
            </p>

            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Kontaktinformationen</h2>

                <p class="mb-4">
                    <strong>Technischer Support:</strong><br>
                    E-Mail: <a href="mailto:bugraergin@gmail.com" class="text-steiner-blue hover:text-steiner-dark underline">bugraergin@gmail.com</a>
                </p>

                <p class="mb-4">
                    <strong>Ansprechperson:</strong><br>
                    Buğra Ergin<br>
                    (Ehrenamtlicher technischer Betreuer)
                </p>
            </div>

            <h2 class="text-lg font-semibold text-gray-800 mt-6 mb-3">Hilfe bei häufigen Problemen</h2>
            <ul class="list-disc pl-6 mb-4">
                <li><strong>Anmeldung funktioniert nicht:</strong> Stellen Sie sicher, dass Sie die korrekte E-Mail-Adresse verwenden und Cookies aktiviert sind.</li>
                <li><strong>Passwort vergessen:</strong> Nutzen Sie die "Passwort vergessen" Funktion auf der Anmeldeseite.</li>
                <li><strong>Schichtanmeldung nicht möglich:</strong> Überprüfen Sie, ob noch freie Plätze verfügbar sind.</li>
                <li><strong>Seite lädt nicht richtig:</strong> Leeren Sie Ihren Browser-Cache und laden Sie die Seite neu (Strg+F5 bzw. Cmd+Shift+R).</li>
            </ul>

            <h2 class="text-lg font-semibold text-gray-800 mt-6 mb-3">Feedback und Verbesserungsvorschläge</h2>
            <p class="mb-4">
                Wir freuen uns über Ihr Feedback! Wenn Sie Ideen zur Verbesserung der Plattform haben,
                senden Sie diese gerne an die oben genannte E-Mail-Adresse.
            </p>

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mt-6">
                <p class="text-sm text-yellow-800">
                    <strong>Hinweis:</strong> Diese Plattform wird ehrenamtlich betreut.
                    Bitte haben Sie Verständnis, dass Antworten auf Support-Anfragen etwas Zeit in Anspruch nehmen können.
                </p>
            </div>

            <h2 class="text-lg font-semibold text-gray-800 mt-6 mb-3">Allgemeine Schulanfragen</h2>
            <p class="mb-4">
                Für allgemeine Anfragen zur Schule, die nicht die technische Plattform betreffen, wenden Sie sich bitte direkt an:<br><br>
                <strong>Rudolf Steinerschule Bern in Langnau</strong><br>
                Telefon: +41 (0)34 402 12 80<br>
                E-Mail: <a href="mailto:info@steinerschule-bern.ch" class="text-steiner-blue hover:text-steiner-dark underline">info@steinerschule-bern.ch</a>
            </p>
        </div>
    </x-card>
</div>
@endsection
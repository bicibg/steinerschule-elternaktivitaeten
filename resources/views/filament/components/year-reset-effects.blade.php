<div class="space-y-2">
    <ul class="list-disc list-inside text-sm space-y-1">
        <li><strong>{{ $activitiesCount }}</strong> aktive Aktivitäten werden deaktiviert</li>
        <li><strong>{{ $bulletinPostsCount }}</strong> aktive Pinnwand-Einträge werden deaktiviert</li>
        <li><strong>{{ $announcementsCount }}</strong> normale Ankündigungen werden deaktiviert</li>
        <li><strong>{{ $postsCount }}</strong> Forumbeiträge werden archiviert</li>
        <li><strong>{{ $commentsCount }}</strong> Kommentare werden archiviert</li>
    </ul>
    <div class="mt-3 p-3 bg-blue-50 rounded-lg">
        <p class="text-sm text-blue-800 font-semibold">Was bleibt erhalten:</p>
        <ul class="list-disc list-inside text-sm text-blue-700 mt-1">
            <li>Alle Benutzerkonten</li>
            <li>Schichten und Anmeldungen</li>
            <li>Schulkalender-Einträge</li>
            <li>Prioritäre Ankündigungen (is_priority)</li>
        </ul>
    </div>
</div>
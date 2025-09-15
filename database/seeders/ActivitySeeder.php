<?php

namespace Database\Seeders;

use App\Models\Activity;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Elternrat
        $activity1 = Activity::create([
            'title' => 'Elternrat',
            'description' => 'Der Elternrat ist das zentrale Koordinationsgremium für alle Elternaktivitäten an unserer Schule. Wir treffen uns monatlich zur Planung und Organisation verschiedener Schulanlässe und Projekte.

AUFGABEN:
- Koordination aller Elternaktivitäten
- Jahresplanung der Schulanlässe
- Budgetverwaltung
- Kommunikation zwischen Eltern und Schule
- Neue Initiativen entwickeln

Alle interessierten Eltern sind herzlich willkommen!',
            'category' => 'organisation',
            'contact_name' => 'Christine Brunner',
            'contact_email' => 'elternrat@steinerschule-langnau.ch',
            'contact_phone' => '+41 34 402 12 40',
            'meeting_time' => 'Jeden ersten Dienstag im Monat, 20:00 Uhr',
            'meeting_location' => 'Musikzimmer',
            'has_forum' => true,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        // 2. Schulgarten-Gruppe
        $activity2 = Activity::create([
            'title' => 'Schulgarten-Gruppe',
            'description' => 'Die Schulgarten-Gruppe pflegt und gestaltet unseren wunderschönen Schulgarten, der von den Klassen für den Gartenbauunterricht genutzt wird.

TÄTIGKEITEN:
- Saisonale Gartenarbeiten
- Beete vorbereiten und bepflanzen
- Kompostpflege
- Gartengeräte warten
- Erntefeste organisieren
- Kinder beim Gärtnern unterstützen

Der Garten ist während der Schulzeiten jederzeit zugänglich. Hilfe ist immer willkommen!',
            'category' => 'haus_umgebung_taskforces',
            'contact_name' => 'Markus Steiner',
            'contact_email' => 'garten@steinerschule-langnau.ch',
            'contact_phone' => '+41 34 402 12 55',
            'meeting_time' => 'Samstags nach Absprache',
            'meeting_location' => 'Schulgarten hinter der Turnhalle',
            'has_forum' => true,
            'is_active' => true,
            'sort_order' => 2,
        ]);

        // 3. Bibliotheksteam
        $activity3 = Activity::create([
            'title' => 'Schulbibliothek',
            'description' => 'Das Bibliotheksteam betreut unsere Schulbibliothek und macht sie zu einem lebendigen Ort der Begegnung mit Büchern.

AUFGABEN:
- Ausleihe während der Öffnungszeiten
- Neue Bücher katalogisieren
- Lesungen organisieren
- Buchempfehlungen für verschiedene Altersstufen
- Bibliothek gemütlich gestalten

Wir suchen besonders Eltern, die regelmässig einen Bibliotheksdienst übernehmen können.',
            'category' => 'organisation',
            'contact_name' => 'Monika Schmid',
            'contact_email' => 'bibliothek@steinerschule-langnau.ch',
            'contact_phone' => '+41 34 402 12 70',
            'meeting_time' => 'Bibliotheksdienst: Mo & Do, 12:00-14:00 Uhr',
            'meeting_location' => 'Schulbibliothek 1. Stock',
            'has_forum' => true,
            'is_active' => true,
            'sort_order' => 3,
        ]);

        // 4. Märit-Organisation
        $activity4 = Activity::create([
            'title' => 'Märit-Organisation',
            'description' => 'Das Märit-Team organisiert unsere grossen Märkte: Weihnachtsmärit, Frühlingsmärit und weitere Verkaufsanlässe.

BEREICHE:
- Standplanung und -koordination
- Werbung und Öffentlichkeitsarbeit
- Helferkoordination
- Cafeteria-Organisation
- Auf- und Abbau
- Dekoration

Die Planung beginnt jeweils 3 Monate vor dem Anlass. Neue Ideen sind immer willkommen!',
            'category' => 'verkauf',
            'contact_name' => 'Ursula Zimmermann',
            'contact_email' => 'marit@steinerschule-langnau.ch',
            'contact_phone' => '+41 34 402 12 00',
            'meeting_time' => 'Planungstreffen nach Ankündigung',
            'meeting_location' => 'Lehrerzimmer',
            'has_forum' => true,
            'is_active' => true,
            'sort_order' => 4,
        ]);

        // 5. Festkreis
        $activity5 = Activity::create([
            'title' => 'Festkreis',
            'description' => 'Der Festkreis gestaltet die Jahresfeste und besonderen Anlässe unserer Schule mit.

JAHRESFESTE:
- Michaeli-Fest
- Laternenumzug
- Adventsspirale
- Dreikönigsspiel
- Johannifeuer
- Schuljahresabschluss

Wir sorgen für die festliche Gestaltung und koordinieren die verschiedenen Beiträge der Klassen.',
            'category' => 'anlass',
            'contact_name' => 'Daniel Moser',
            'contact_email' => 'feste@steinerschule-langnau.ch',
            'contact_phone' => '+41 34 402 12 60',
            'meeting_time' => 'Vor jedem Fest',
            'has_forum' => true,
            'is_active' => true,
            'sort_order' => 5,
        ]);

        // 6. Pausenkiosk-Team
        $activity6 = Activity::create([
            'title' => 'Pausenkiosk',
            'description' => 'Der Pausenkiosk bietet gesunde Znünis für unsere Schülerinnen und Schüler an.

ANGEBOT:
- Selbstgebackenes Vollkornbrot
- Saisonale Früchte
- Gemüsesticks
- Gesunde Getränke
- Spezielle Aktionen

Der Erlös kommt den Klassenkassen zugute. Wir suchen Helfer für Einkauf, Vorbereitung und Verkauf.',
            'category' => 'verkauf',
            'contact_name' => 'Claudia Baumgartner',
            'contact_email' => 'kiosk@steinerschule-langnau.ch',
            'contact_phone' => '+41 76 456 78 90',
            'meeting_time' => 'Verkauf: Di & Do, 09:30-10:00 Uhr',
            'meeting_location' => 'Pausenhof',
            'has_forum' => true,
            'is_active' => true,
            'sort_order' => 6,
        ]);

        // 7. Handarbeitskreis
        $activity7 = Activity::create([
            'title' => 'Handarbeitskreis',
            'description' => 'Der Handarbeitskreis trifft sich regelmässig zum gemeinsamen Handarbeiten und zur Herstellung von Produkten für den Märit.

AKTIVITÄTEN:
- Puppen und Wichtel nähen
- Jahreszeitenschmuck herstellen
- Stricken und Häkeln
- Filzen
- Kerzen ziehen
- Naturmaterialien verarbeiten

Anfänger sind herzlich willkommen - wir helfen beim Erlernen der verschiedenen Techniken!',
            'category' => 'produktion',
            'contact_name' => 'Ruth Gerber',
            'contact_email' => 'handarbeit@steinerschule-langnau.ch',
            'contact_phone' => '+41 79 234 56 78',
            'meeting_time' => 'Donnerstags, 19:30-21:30 Uhr',
            'meeting_location' => 'Handarbeitsraum',
            'has_forum' => true,
            'is_active' => true,
            'sort_order' => 7,
        ]);

        // 8. Musikgruppe
        $activity8 = Activity::create([
            'title' => 'Eltern-Musikgruppe',
            'description' => 'Die Musikgruppe gestaltet musikalische Beiträge für Schulanlässe und trifft sich zum gemeinsamen Musizieren.

REPERTOIRE:
- Lieder für Jahresfeste
- Instrumentalstücke
- Begleitung bei Schulfeiern
- Adventssingen
- Sommerserenade

Alle Instrumente und Niveaus sind willkommen. Notenkenntnisse sind hilfreich, aber nicht zwingend.',
            'category' => 'anlass',
            'contact_name' => 'Andreas Hofmann',
            'contact_email' => 'musik@steinerschule-langnau.ch',
            'contact_phone' => '+41 34 402 12 35',
            'meeting_time' => 'Mittwochs, 20:00-21:30 Uhr',
            'meeting_location' => 'Musiksaal',
            'has_forum' => true,
            'is_active' => true,
            'sort_order' => 8,
        ]);

        // 9. Schulhaus-Verschönerung
        $activity9 = Activity::create([
            'title' => 'Schulhaus-Verschönerung',
            'description' => 'Diese Gruppe kümmert sich um die Gestaltung und Verschönerung unserer Schulräume.

PROJEKTE:
- Klassenzimmer streichen
- Wandbilder gestalten
- Möbel restaurieren
- Vorhänge nähen
- Jahreszeitliche Dekoration
- Pausenhof gestalten

Handwerkliches Geschick ist willkommen, aber nicht Voraussetzung.',
            'category' => 'haus_umgebung_taskforces',
            'contact_name' => 'Patrick Frei',
            'contact_email' => 'schulhaus@steinerschule-langnau.ch',
            'contact_phone' => '+41 79 345 67 89',
            'meeting_time' => 'Arbeitseinsätze nach Ankündigung',
            'has_forum' => true,
            'is_active' => true,
            'sort_order' => 9,
        ]);

        // 10. Öffentlichkeitsarbeit
        $activity10 = Activity::create([
            'title' => 'Öffentlichkeitsarbeit',
            'description' => 'Das Team Öffentlichkeitsarbeit macht unsere Schule in der Region bekannt und pflegt den Kontakt zu den Medien.

AUFGABEN:
- Website-Inhalte pflegen
- Social Media betreuen
- Pressemitteilungen verfassen
- Flyer und Plakate gestalten
- Tag der offenen Tür organisieren
- Schulbroschüre aktualisieren

Wir suchen Menschen mit Freude an Kommunikation und Gestaltung.',
            'category' => 'kommunikation',
            'contact_name' => 'Sarah Weber',
            'contact_email' => 'pr@steinerschule-langnau.ch',
            'contact_phone' => '+41 76 123 45 67',
            'meeting_time' => 'Monatliches Online-Meeting',
            'has_forum' => true,
            'is_active' => true,
            'sort_order' => 10,
        ]);

        // 11. Eurythmie-Begleitung
        $activity11 = Activity::create([
            'title' => 'Eurythmie-Begleitung',
            'description' => 'Unterstützung bei Eurythmie-Aufführungen und besonderen Eurythmie-Projekten.

MITHILFE BEI:
- Kostüme nähen und anpassen
- Bühnengestaltung
- Beleuchtung
- Aufführungsorganisation
- Garderobe während Aufführungen

Die Eurythmie ist ein wichtiger Teil unserer Pädagogik - helfen Sie mit, sie zur Geltung zu bringen!',
            'category' => 'paedagogik',
            'contact_name' => 'Elisabeth Keller',
            'contact_email' => 'eurythmie@steinerschule-langnau.ch',
            'contact_phone' => '+41 34 402 12 50',
            'has_forum' => true,
            'is_active' => true,
            'sort_order' => 11,
        ]);

        // 12. Lager-Begleitung
        $activity12 = Activity::create([
            'title' => 'Lager-Begleitung',
            'description' => 'Eltern begleiten Klassenlager und Schulreisen als zusätzliche Betreuungspersonen.

VERSCHIEDENE LAGER:
- Skilager
- Landschulwochen
- Abschlussreisen
- Wanderlager

AUFGABEN:
- Betreuung und Aufsicht
- Küchenhilfe
- Abendprogramm
- Notfallbetreuung

Eine tolle Gelegenheit, die Klasse Ihres Kindes näher kennenzulernen!',
            'category' => 'anlass',
            'contact_name' => 'Thomas Roth',
            'contact_email' => 'sport@steinerschule-langnau.ch',
            'contact_phone' => '+41 34 402 12 90',
            'has_forum' => true,
            'is_active' => true,
            'sort_order' => 12,
        ]);

        // Add some sample forum posts
        $post1 = $activity1->posts()->create([
            'author_name' => 'Maria Weber',
            'body' => 'Ich möchte gerne dem Elternrat beitreten. Wie läuft das Aufnahmeverfahren?',
            'ip_hash' => hash('sha256', '192.168.1.10'),
        ]);

        $post1->comments()->create([
            'author_name' => 'Christine Brunner',
            'body' => 'Herzlich willkommen! Kommen Sie einfach zur nächsten Sitzung am ersten Dienstag des Monats. Keine formelle Anmeldung nötig.',
            'ip_hash' => hash('sha256', '192.168.1.11'),
        ]);

        $post2 = $activity2->posts()->create([
            'author_name' => 'Hans Müller',
            'body' => 'Der Kompost muss dringend umgeschichtet werden. Wer kann am Samstag helfen?',
            'ip_hash' => hash('sha256', '192.168.1.12'),
        ]);

        $activity6->posts()->create([
            'author_name' => 'Laura Fischer',
            'body' => 'Könnten wir auch vegane Snacks anbieten? Ich würde gerne Rezepte beisteuern.',
            'ip_hash' => hash('sha256', '192.168.1.13'),
        ]);

        $activity7->posts()->create([
            'author_name' => 'Susanne Meyer',
            'body' => 'Ich bin Anfängerin im Nähen. Kann ich trotzdem mitmachen?',
            'ip_hash' => hash('sha256', '192.168.1.14'),
        ]);
    }
}
<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\BulletinPost;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BulletinPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Helper to find activity by title
        $activity = fn (string $title) => Activity::where('title', $title)->first()?->id;

        // 1. Lagerwoche Zurich - class trip, no parent activity
        $bulletinPost1 = BulletinPost::create([
            'title' => 'Lagerwoche Zürich - Küchenteam gesucht',
            'category' => 'anlass',
            'activity_type' => 'shift_based',
            'description' => 'Für die Lagerwoche der 8. Klasse in Zürich suchen wir ein Küchenteam!

Die 8. Klasse verbringt eine Woche in Zürich und benötigt Unterstützung bei der Verpflegung.

AUFGABEN:
- Zubereitung von Frühstück, Mittagessen und Abendessen
- Einkauf und Planung der Mahlzeiten
- Küche aufräumen und organisieren

Unterkunft wird gestellt. Eine tolle Gelegenheit, die Klasse zu begleiten!',
            'start_at' => now()->year(now()->year + 1)->month(6)->day(3),
            'end_at' => now()->year(now()->year + 1)->month(6)->day(7),
            'location' => 'Jugendherberge Zürich',
            'contact_name' => 'Stefan Berger',
            'contact_phone' => '+41 34 402 12 45',
            'contact_email' => 'klasse8@steinerschule-langnau.ch',
            'status' => 'published',
            'has_forum' => true,
            'has_shifts' => true,
            'show_in_calendar' => true,
        ]);

        $bulletinPost1->shifts()->create([
            'role' => 'Küchenteam Montag-Mittwoch',
            'time' => '03.06.' . (now()->year + 1) . ' - 05.06.' . (now()->year + 1),
            'needed' => 2,
            'offline_filled' => 0,
        ]);

        $bulletinPost1->shifts()->create([
            'role' => 'Küchenteam Mittwoch-Freitag',
            'time' => '05.06.' . (now()->year + 1) . ' - 07.06.' . (now()->year + 1),
            'needed' => 2,
            'offline_filled' => 1,
        ]);

        // 2. Eurythmie-Aufführung - school event, no parent activity
        $bulletinPost2 = BulletinPost::create([
            'title' => 'Eurythmie-Aufführung - Helfer für Bühnenbild',
            'category' => 'anlass',
            'activity_type' => 'shift_based',
            'description' => 'Die 12. Klasse präsentiert ihre Eurythmie-Abschlussaufführung.

Wir suchen Helfer für:
- Bühnenbild aufbauen und abbauen
- Beleuchtung einrichten
- Kostüme vorbereiten
- Garderobe betreuen während der Aufführung',
            'start_at' => now()->year(now()->year + 1)->month(5)->day(17)->setTime(17, 0),
            'end_at' => now()->year(now()->year + 1)->month(5)->day(17)->setTime(22, 0),
            'location' => 'Festsaal Steinerschule',
            'contact_name' => 'Elisabeth Keller',
            'contact_phone' => '+41 34 402 12 50',
            'contact_email' => 'eurythmie@steinerschule-langnau.ch',
            'status' => 'published',
            'has_forum' => true,
            'has_shifts' => true,
            'show_in_calendar' => true,
        ]);

        $bulletinPost2->shifts()->create([
            'role' => 'Bühnenaufbau',
            'time' => '17.05.' . (now()->year + 1) . ', 15:00 - 18:00 Uhr',
            'needed' => 4,
            'offline_filled' => 2,
        ]);

        $bulletinPost2->shifts()->create([
            'role' => 'Garderobe während Aufführung',
            'time' => '17.05.' . (now()->year + 1) . ', 18:30 - 21:00 Uhr',
            'needed' => 2,
            'offline_filled' => 0,
        ]);

        // 3. Ostereiersuche -> Osterstand activity
        $bulletinPost3 = BulletinPost::create([
            'title' => 'Osterstand und Ostereiersuche',
            'category' => 'anlass',
            'activity_type' => 'shift_based',
            'activity_id' => $activity('Osterstand'),
            'description' => 'Traditionelle Ostereiersuche und Osterstand für Kindergarten und Unterstufe!

Wir brauchen Helfer für:
- Osterstand aufbauen und betreuen (Verkauf von Osterdekoration)
- Ostereier verstecken (ab 8:00 Uhr)
- Kinder beaufsichtigen
- Getränke und Snacks verteilen

Je mehr Helfer, desto schöner wird das Fest!',
            'participation_note' => 'Kommt vorbei, wann ihr könnt!',
            'start_at' => now()->year(now()->year + 1)->month(4)->day(11)->setTime(8, 0),
            'end_at' => now()->year(now()->year + 1)->month(4)->day(11)->setTime(14, 0),
            'location' => 'Schulgarten und Pausenhof',
            'contact_name' => 'Julia Winkler',
            'contact_phone' => '+41 34 402 12 20',
            'contact_email' => 'osterstand@steinerschule-langnau.ch',
            'status' => 'published',
            'has_forum' => true,
            'has_shifts' => true,
            'show_in_calendar' => true,
        ]);

        $bulletinPost3->shifts()->create([
            'role' => 'Osterstand Aufbau und Verkauf',
            'time' => '11.04.' . (now()->year + 1) . ', 08:00 - 12:00 Uhr',
            'needed' => 4,
            'offline_filled' => 1,
        ]);

        $bulletinPost3->shifts()->create([
            'role' => 'Ostereier verstecken und Kinderbetreuung',
            'time' => '11.04.' . (now()->year + 1) . ', 08:00 - 10:00 Uhr',
            'needed' => 6,
            'offline_filled' => 2,
        ]);

        // 4. Weihnachtsmärit -> Märit-OK activity
        $bulletinPost4 = BulletinPost::create([
            'title' => 'Weihnachtsmärit - Grosser Helferaufruf',
            'category' => 'anlass',
            'activity_type' => 'shift_based',
            'activity_id' => $activity('Märit-OK'),
            'description' => 'Unser jährlicher Weihnachtsmärit - das grösste Event des Jahres!

Wir brauchen über 100 Helfer für verschiedene Aufgaben:
- Standbetreuung (Verkauf, Cafeteria, Kinderbereich)
- Auf- und Abbau
- Parkplatzeinweisung
- Kassenführung
- Dekoration

Detaillierte Schichtpläne folgen im Oktober.',
            'start_at' => now()->year(now()->year)->month(11)->day(30)->setTime(9, 0),
            'end_at' => now()->year(now()->year)->month(11)->day(30)->setTime(18, 0),
            'location' => 'Gesamtes Schulgelände',
            'contact_name' => 'Swenja Heyers, Yves Bönzli',
            'contact_phone' => '+41 34 402 12 00',
            'contact_email' => 'marit@steinerschule-langnau.ch',
            'status' => 'published',
            'has_forum' => true,
            'has_shifts' => true,
            'show_in_calendar' => true,
            'label' => 'urgent',
        ]);

        $shift1 = $bulletinPost4->shifts()->create([
            'role' => 'Aufbau Freitag',
            'time' => '29.11.' . now()->year . ', 14:00 - 20:00 Uhr',
            'needed' => 20,
            'offline_filled' => 5,
        ]);

        $bulletinPost4->shifts()->create([
            'role' => 'Cafeteria Vormittag',
            'time' => '30.11.' . now()->year . ', 09:00 - 12:00 Uhr',
            'needed' => 6,
            'offline_filled' => 2,
        ]);

        $bulletinPost4->shifts()->create([
            'role' => 'Cafeteria Nachmittag',
            'time' => '30.11.' . now()->year . ', 12:00 - 16:00 Uhr',
            'needed' => 6,
            'offline_filled' => 0,
        ]);

        $bulletinPost4->shifts()->create([
            'role' => 'Kinderbereich',
            'time' => '30.11.' . now()->year . ', 10:00 - 16:00 Uhr',
            'needed' => 8,
            'offline_filled' => 3,
        ]);

        $bulletinPost4->shifts()->create([
            'role' => 'Abbau',
            'time' => '30.11.' . now()->year . ', 16:00 - 19:00 Uhr',
            'needed' => 15,
            'offline_filled' => 0,
        ]);


        // 5. Adventssingen - no parent activity
        BulletinPost::create([
            'title' => 'Adventssingen - Liedhefte vorbereiten',
            'category' => 'produktion',
            'activity_type' => 'production',
            'description' => 'Für das wöchentliche Adventssingen müssen 300 Liedhefte produziert werden.

ARBEITEN:
- Kopieren und Falten der Hefte
- Binden/Heften
- Sortieren nach Klassen

Die Arbeit kann flexibel zwischen dem 15. und 25. November erledigt werden. Material ist im Kopierraum vorhanden.',
            'participation_note' => 'Arbeit kann flexibel eingeteilt werden',
            'start_at' => now()->year(now()->year)->month(11)->day(15),
            'end_at' => now()->year(now()->year)->month(11)->day(25),
            'location' => 'Kopierraum',
            'contact_name' => 'Andreas Hofmann',
            'contact_phone' => '+41 34 402 12 35',
            'contact_email' => 'musik@steinerschule-langnau.ch',
            'status' => 'published',
            'has_forum' => false,
            'has_shifts' => false,
            'show_in_calendar' => true,
        ]);

        // 6. Elternrat Sitzungen -> Elternrat activity
        BulletinPost::create([
            'title' => 'Elternrat Sitzungen',
            'category' => 'organisation',
            'activity_type' => 'meeting',
            'activity_id' => $activity('Elternrat'),
            'description' => 'Regelmässige Elternratssitzungen zur Koordination aller Elternaktivitäten.

THEMEN:
- Jahresplanung
- Anlass-Organisation
- Budget
- Neue Initiativen

Alle interessierten Eltern sind willkommen!',
            'recurring_pattern' => 'Jeden ersten Dienstag im Monat',
            'participation_note' => 'Offene Teilnahme',
            'start_at' => now()->year(now()->year)->month(9)->day(1),
            'end_at' => now()->year(now()->year + 1)->month(7)->day(31),
            'location' => 'Musikzimmer',
            'contact_name' => 'Tatjana Baumgartner, Maria Mani',
            'contact_phone' => '+41 34 402 12 40',
            'contact_email' => 'elternrat@steinerschule-langnau.ch',
            'status' => 'published',
            'has_forum' => false,
            'has_shifts' => false,
            'show_in_calendar' => true,
        ]);

        // 7. Schulgarten-Pflege -> Erneuerung Pausenplatzareal activity
        $bulletinPost7 = BulletinPost::create([
            'title' => 'Schulgarten und Aussenbereich - Pflege',
            'category' => 'haus_umgebung_taskforces',
            'activity_type' => 'flexible_help',
            'activity_id' => $activity('Erneuerung Pausenplatzareal'),
            'description' => 'Unser Schulgarten und Pausenplatzareal brauchen regelmässige Pflege!

ARBEITEN JE NACH SAISON:
- Beete vorbereiten und bepflanzen
- Unkraut jäten
- Spielgeräte kontrollieren
- Kompost pflegen
- Geräteschuppen aufräumen

Kommt vorbei, wann immer ihr Zeit habt. Werkzeug vorhanden.',
            'participation_note' => 'Jederzeit während Schulzeiten',
            'start_at' => now()->year(now()->year)->month(3)->day(1),
            'end_at' => now()->year(now()->year)->month(11)->day(30),
            'location' => 'Schulgarten und Pausenplatz',
            'contact_name' => 'Julia und Sami Eisenhut, Hans Baumgartner',
            'contact_phone' => '+41 34 402 12 55',
            'contact_email' => 'pausenplatz@steinerschule-langnau.ch',
            'status' => 'published',
            'has_forum' => true,
            'has_shifts' => false,
            'show_in_calendar' => true,
        ]);

        // 8. Flohmarkt -> Spielzeug- und Kinderkleiderbörse activity
        $bulletinPost8 = BulletinPost::create([
            'title' => 'Spielzeug- und Kinderkleiderbörse Frühling',
            'category' => 'anlass',
            'activity_type' => 'shift_based',
            'activity_id' => $activity('Spielzeug- und Kinderkleiderbörse'),
            'description' => 'Unsere Frühlings-Börse für Kinderkleidung und Spielsachen!

Wir sammeln und verkaufen:
- Kinderkleidung
- Spielzeug
- Bücher
- Sportgeräte

Helfer gesucht für Annahme, Sortierung und Verkauf.',
            'start_at' => now()->year(now()->year + 1)->month(3)->day(15)->setTime(9, 0),
            'end_at' => now()->year(now()->year + 1)->month(3)->day(15)->setTime(15, 0),
            'location' => 'Turnhalle',
            'contact_name' => 'Linda Denissen, Yael Stanca',
            'contact_phone' => '+41 79 234 56 78',
            'contact_email' => 'boerse@steinerschule-langnau.ch',
            'status' => 'published',
            'has_forum' => true,
            'has_shifts' => true,
            'show_in_calendar' => true,
        ]);

        $bulletinPost8->shifts()->create([
            'role' => 'Warenannahme Freitag',
            'time' => '14.03.' . (now()->year + 1) . ', 14:00 - 18:00 Uhr',
            'needed' => 5,
            'offline_filled' => 1,
        ]);

        $bulletinPost8->shifts()->create([
            'role' => 'Verkauf Vormittag',
            'time' => '15.03.' . (now()->year + 1) . ', 09:00 - 12:00 Uhr',
            'needed' => 8,
            'offline_filled' => 2,
        ]);

        $bulletinPost8->shifts()->create([
            'role' => 'Aufräumen',
            'time' => '15.03.' . (now()->year + 1) . ', 14:00 - 16:00 Uhr',
            'needed' => 6,
            'offline_filled' => 0,
        ]);

        // 9. Johannifeuer - no parent activity (standalone school tradition)
        $bulletinPost9 = BulletinPost::create([
            'title' => 'Johannifeuer - Sommerfest',
            'category' => 'anlass',
            'activity_type' => 'shift_based',
            'description' => 'Unser traditionelles Johannifeuer zum Schuljahresabschluss!

Ein magischer Abend mit:
- Grossem Feuer
- Stockbrot
- Musik und Gesang
- Spielen für Kinder

Helfer für Auf-/Abbau, Feuerwache und Verpflegung gesucht.',
            'start_at' => now()->year(now()->year + 1)->month(6)->day(24)->setTime(18, 0),
            'end_at' => now()->year(now()->year + 1)->month(6)->day(24)->setTime(23, 0),
            'location' => 'Wiese hinter der Schule',
            'contact_name' => 'Daniel Moser',
            'contact_phone' => '+41 34 402 12 60',
            'contact_email' => 'feste@steinerschule-langnau.ch',
            'status' => 'published',
            'has_forum' => true,
            'has_shifts' => true,
            'show_in_calendar' => true,
        ]);

        $bulletinPost9->shifts()->create([
            'role' => 'Holz sammeln und Feuer vorbereiten',
            'time' => '24.06.' . (now()->year + 1) . ', 16:00 - 18:00 Uhr',
            'needed' => 6,
            'offline_filled' => 2,
        ]);

        $bulletinPost9->shifts()->create([
            'role' => 'Feuerwache',
            'time' => '24.06.' . (now()->year + 1) . ', 19:00 - 23:00 Uhr',
            'needed' => 4,
            'offline_filled' => 1,
        ]);

        $bulletinPost9->shifts()->create([
            'role' => 'Stockbrot-Station',
            'time' => '24.06.' . (now()->year + 1) . ', 19:00 - 21:00 Uhr',
            'needed' => 3,
            'offline_filled' => 0,
        ]);

        // 10. Kuchen für Schulanlässe -> Backgruppe activity
        $bulletinPost10 = BulletinPost::create([
            'title' => 'Kuchen für Schulanlässe',
            'category' => 'produktion',
            'activity_type' => 'production',
            'activity_id' => $activity('Backgruppe'),
            'description' => 'Für verschiedene Schulanlässe brauchen wir regelmässig Kuchenbeiträge.

Nächste Anlässe:
- Tag der offenen Tür (23.11.)
- Adventssingen (jeden Montag im Dezember)
- Quartalsfeier (20.12.)

Pro Anlass werden 15-20 Kuchen benötigt. Bitte bei Organisatoren melden.',
            'participation_note' => 'Backen zu Hause',
            'start_at' => now()->year(now()->year)->month(11)->day(1),
            'end_at' => now()->year(now()->year)->month(12)->day(20),
            'location' => 'Abgabe in Schulküche',
            'contact_name' => 'Swenja Heyers, Matthias Frey',
            'contact_phone' => '+41 34 402 12 00',
            'contact_email' => 'backen@steinerschule-langnau.ch',
            'status' => 'published',
            'has_forum' => true,
            'has_shifts' => false,
            'show_in_calendar' => true,
        ]);

        // 11. Bibliothek - no parent activity (standalone)
        $bulletinPost11 = BulletinPost::create([
            'title' => 'Schulbibliothek Betreuung',
            'category' => 'organisation',
            'activity_type' => 'shift_based',
            'description' => 'Unsere Schulbibliothek sucht Helfer für die Ausleihe!

AUFGABEN:
- Buchausleihe und -rückgabe
- Bücher einordnen
- Neue Bücher katalogisieren
- Kindern beim Suchen helfen

Einarbeitung wird geboten. Ideal für Bücherfreunde!',
            'participation_note' => 'Regelmässige Schichten möglich',
            'start_at' => now()->year(now()->year)->month(9)->day(1),
            'end_at' => now()->year(now()->year + 1)->month(7)->day(15),
            'location' => 'Schulbibliothek 1. Stock',
            'contact_name' => 'Monika Schmid',
            'contact_phone' => '+41 34 402 12 70',
            'contact_email' => 'bibliothek@steinerschule-langnau.ch',
            'status' => 'published',
            'has_forum' => false,
            'has_shifts' => true,
            'show_in_calendar' => true,
        ]);

        $bulletinPost11->shifts()->create([
            'role' => 'Bibliotheksdienst Montag',
            'time' => 'Montags, 12:00 - 14:00 Uhr',
            'needed' => 2,
            'offline_filled' => 1,
        ]);

        $bulletinPost11->shifts()->create([
            'role' => 'Bibliotheksdienst Donnerstag',
            'time' => 'Donnerstags, 12:00 - 14:00 Uhr',
            'needed' => 2,
            'offline_filled' => 0,
        ]);

        // 12. Klassenzimmer renovieren -> Hausgruppe activity
        BulletinPost::create([
            'title' => 'Klassenzimmer 3. Klasse renovieren',
            'category' => 'haus_umgebung_taskforces',
            'activity_type' => 'flexible_help',
            'activity_id' => $activity('Hausgruppe'),
            'description' => 'Die 3. Klasse renoviert ihr Klassenzimmer!

ARBEITEN:
- Wände streichen
- Möbel abschleifen und ölen
- Vorhänge nähen
- Dekoration gestalten

Materialkosten werden übernommen. Jede helfende Hand willkommen!',
            'participation_note' => 'Auch stundenweise Hilfe möglich',
            'start_at' => now()->year(now()->year)->month(10)->day(14),
            'end_at' => now()->year(now()->year)->month(10)->day(20),
            'location' => 'Klassenzimmer 3. Klasse',
            'contact_name' => 'Hans Baumgartner',
            'contact_phone' => '+41 79 345 67 89',
            'contact_email' => 'hausgruppe@steinerschule-langnau.ch',
            'status' => 'published',
            'has_forum' => true,
            'has_shifts' => false,
            'show_in_calendar' => true,
        ]);

        // 13. Pausenkiosk - no parent activity (standalone)
        $bulletinPost13 = BulletinPost::create([
            'title' => 'Pausenkiosk',
            'category' => 'verkauf',
            'activity_type' => 'shift_based',
            'description' => 'Gesunder Pausenkiosk jeden Dienstag und Donnerstag!

Wir verkaufen:
- Selbstgebackenes Brot
- Früchte und Gemüse
- Gesunde Snacks
- Getränke

Erlös für Klassenkassen. Helfer für Verkauf und Vorbereitung gesucht.',
            'recurring_pattern' => 'Dienstag und Donnerstag',
            'start_at' => now()->year(now()->year)->month(9)->day(1),
            'end_at' => now()->year(now()->year + 1)->month(7)->day(15),
            'location' => 'Pausenhof',
            'contact_name' => 'Claudia Baumgartner',
            'contact_phone' => '+41 76 456 78 90',
            'contact_email' => 'kiosk@steinerschule-langnau.ch',
            'status' => 'published',
            'has_forum' => true,
            'has_shifts' => true,
            'show_in_calendar' => true,
        ]);

        $bulletinPost13->shifts()->create([
            'role' => 'Kiosk Dienstag',
            'time' => 'Dienstags, 09:30 - 10:00 Uhr',
            'needed' => 2,
            'offline_filled' => 1,
        ]);

        $bulletinPost13->shifts()->create([
            'role' => 'Kiosk Donnerstag',
            'time' => 'Donnerstags, 09:30 - 10:00 Uhr',
            'needed' => 2,
            'offline_filled' => 0,
        ]);

        // 14. Theater-Requisiten - no parent activity (class project)
        BulletinPost::create([
            'title' => 'Theater-Requisiten herstellen',
            'category' => 'produktion',
            'activity_type' => 'production',
            'description' => 'Für das 8.-Klass-Spiel werden verschiedene Requisiten benötigt!

ZU ERSTELLEN:
- Mittelalterliche Kostüme nähen
- Bühnenbilder malen
- Requisiten basteln (Schwerter, Kronen, etc.)
- Möbel umgestalten

Kreative Köpfe und geschickte Hände gesucht!',
            'participation_note' => 'Auch Arbeit zu Hause möglich',
            'start_at' => now()->year(now()->year + 1)->month(1)->day(6),
            'end_at' => now()->year(now()->year + 1)->month(1)->day(24),
            'location' => 'Werkraum und zu Hause',
            'contact_name' => 'Regula Fischer',
            'contact_phone' => '+41 34 402 12 80',
            'contact_email' => 'theater@steinerschule-langnau.ch',
            'status' => 'published',
            'has_forum' => true,
            'has_shifts' => false,
            'show_in_calendar' => true,
        ]);

        // 15. Skilager - no parent activity (class trip)
        $bulletinPost15 = BulletinPost::create([
            'title' => 'Skilager Begleitung',
            'category' => 'anlass',
            'activity_type' => 'shift_based',
            'description' => 'Begleitpersonen für Skilager der 5./6. Klasse gesucht!

AUFGABEN:
- Skibegleitung auf der Piste
- Abendprogramm gestalten
- Nachtwache
- Küchenhilfe

Unterkunft und Verpflegung werden gestellt. Skifahren sollte gut beherrscht werden.',
            'start_at' => now()->year(now()->year + 1)->month(2)->day(10),
            'end_at' => now()->year(now()->year + 1)->month(2)->day(14),
            'location' => 'Adelboden',
            'contact_name' => 'Thomas Roth',
            'contact_phone' => '+41 34 402 12 90',
            'contact_email' => 'sport@steinerschule-langnau.ch',
            'status' => 'published',
            'has_forum' => true,
            'has_shifts' => true,
            'show_in_calendar' => true,
            'label' => 'important',
        ]);

        $bulletinPost15->shifts()->create([
            'role' => 'Begleitperson ganze Woche',
            'time' => '10.02.' . (now()->year + 1) . ' - 14.02.' . (now()->year + 1),
            'needed' => 4,
            'offline_filled' => 1,
        ]);

        $bulletinPost15->shifts()->create([
            'role' => 'Begleitung Mo-Mi',
            'time' => '10.02.' . (now()->year + 1) . ' - 12.02.' . (now()->year + 1),
            'needed' => 2,
            'offline_filled' => 0,
        ]);

        $bulletinPost15->shifts()->create([
            'role' => 'Begleitung Mi-Fr',
            'time' => '12.02.' . (now()->year + 1) . ' - 14.02.' . (now()->year + 1),
            'needed' => 2,
            'offline_filled' => 0,
        ]);

        // === NEW: Pinnwand entries for activities that didn't have one ===

        // 16. Pflanzenmärit -> Pflanzenmärit activity
        $bulletinPost16 = BulletinPost::create([
            'title' => 'Pflanzenmärit - Helfer gesucht',
            'category' => 'anlass',
            'activity_type' => 'shift_based',
            'activity_id' => $activity('Pflanzenmärit'),
            'description' => 'Unser Pflanzenmärit braucht Helfer!

Verkauf von Setzlingen, Pflanzen und Gartenzubehör. Wer hat Setzlinge zu Hause vorgezogen? Bitte melden!

AUFGABEN:
- Pflanzen sammeln und beschriften
- Standaufbau und -betreuung
- Beratung für Besucher
- Abbau und Aufräumen',
            'start_at' => now()->year(now()->year + 1)->month(5)->day(3)->setTime(8, 0),
            'end_at' => now()->year(now()->year + 1)->month(5)->day(3)->setTime(14, 0),
            'location' => 'Pausenhof',
            'contact_name' => 'Helfer gesucht',
            'contact_email' => 'pflanzenmarit@steinerschule-langnau.ch',
            'status' => 'published',
            'has_forum' => true,
            'has_shifts' => true,
            'show_in_calendar' => true,
            'label' => 'last_minute',
        ]);

        $bulletinPost16->shifts()->create([
            'role' => 'Aufbau und Pflanzentransport',
            'time' => '03.05.' . (now()->year + 1) . ', 07:00 - 09:00 Uhr',
            'needed' => 4,
            'offline_filled' => 0,
        ]);

        $bulletinPost16->shifts()->create([
            'role' => 'Standbetreuung Vormittag',
            'time' => '03.05.' . (now()->year + 1) . ', 09:00 - 12:00 Uhr',
            'needed' => 3,
            'offline_filled' => 1,
        ]);

        $bulletinPost16->shifts()->create([
            'role' => 'Abbau und Aufräumen',
            'time' => '03.05.' . (now()->year + 1) . ', 13:00 - 15:00 Uhr',
            'needed' => 3,
            'offline_filled' => 0,
        ]);

        // 17. Grossputz -> Putzorganisation activity
        $bulletinPost17 = BulletinPost::create([
            'title' => 'Grossputz Schulhaus vor Sommerferien',
            'category' => 'haus_umgebung_taskforces',
            'activity_type' => 'shift_based',
            'activity_id' => $activity('Putzorganisation'),
            'description' => 'Zum Schuljahresende machen wir gemeinsam Grossputz!

ARBEITEN:
- Fenster putzen
- Böden wischen und wachsen
- Garderobe ausmisten
- Fundgrube aufräumen
- Schulküche grundreinigen

Putzutensilien vorhanden. Bitte Arbeitskleidung mitbringen.',
            'start_at' => now()->year(now()->year + 1)->month(7)->day(4)->setTime(8, 0),
            'end_at' => now()->year(now()->year + 1)->month(7)->day(4)->setTime(16, 0),
            'location' => 'Schulhaus',
            'contact_name' => 'Susann Glättli, Hans Baumgartner',
            'contact_email' => 'putz@steinerschule-langnau.ch',
            'status' => 'published',
            'has_forum' => false,
            'has_shifts' => true,
            'show_in_calendar' => true,
        ]);

        $bulletinPost17->shifts()->create([
            'role' => 'Putzteam Vormittag',
            'time' => '04.07.' . (now()->year + 1) . ', 08:00 - 12:00 Uhr',
            'needed' => 10,
            'offline_filled' => 3,
        ]);

        $bulletinPost17->shifts()->create([
            'role' => 'Putzteam Nachmittag',
            'time' => '04.07.' . (now()->year + 1) . ', 13:00 - 16:00 Uhr',
            'needed' => 8,
            'offline_filled' => 0,
        ]);

        // 18. Mittagstisch -> Mittagstisch activity
        $bulletinPost18 = BulletinPost::create([
            'title' => 'Mittagstisch - Zusätzliche Köche gesucht',
            'category' => 'haus_umgebung_taskforces',
            'activity_type' => 'shift_based',
            'activity_id' => $activity('Mittagstisch'),
            'description' => 'Für den Mittagstisch suchen wir dringend Verstärkung!

Aktuell fehlen uns Helfer für Dienstag und Donnerstag. Die Aufgaben umfassen:
- Einfaches Mittagessen zubereiten (Rezepte vorhanden)
- Tische decken
- Abwasch und Aufräumen

Einarbeitung durch das bestehende Team. Auch 14-tägliche Einsätze willkommen!',
            'recurring_pattern' => 'Dienstag und Donnerstag, 10:30-13:30 Uhr',
            'start_at' => now()->year(now()->year)->month(9)->day(1),
            'end_at' => now()->year(now()->year + 1)->month(7)->day(15),
            'location' => 'Schulküche',
            'contact_name' => 'Anna Stalder',
            'contact_email' => 'mittagstisch@steinerschule-langnau.ch',
            'status' => 'published',
            'has_forum' => true,
            'has_shifts' => true,
            'show_in_calendar' => true,
            'label' => 'urgent',
        ]);

        $bulletinPost18->shifts()->create([
            'role' => 'Mittagstisch Dienstag',
            'time' => 'Dienstags, 10:30 - 13:30 Uhr',
            'needed' => 2,
            'offline_filled' => 1,
        ]);

        $bulletinPost18->shifts()->create([
            'role' => 'Mittagstisch Donnerstag',
            'time' => 'Donnerstags, 10:30 - 13:30 Uhr',
            'needed' => 2,
            'offline_filled' => 0,
        ]);

        // 19. Sponsorenlauf -> Sponsorenlauf activity
        $bulletinPost19 = BulletinPost::create([
            'title' => 'Sponsorenlauf - Streckenposten und Verpflegung',
            'category' => 'anlass',
            'activity_type' => 'shift_based',
            'activity_id' => $activity('Sponsorenlauf'),
            'description' => 'Für den Sponsorenlauf brauchen wir Eltern an der Strecke!

AUFGABEN:
- Streckenposten (Sicherheit an Kreuzungen)
- Verpflegungsstation betreuen
- Rundenzähler
- Erste-Hilfe-Posten

Der Erlös geht ans Klassenlager der 9. Klasse.',
            'start_at' => now()->year(now()->year + 1)->month(5)->day(23)->setTime(9, 0),
            'end_at' => now()->year(now()->year + 1)->month(5)->day(23)->setTime(13, 0),
            'location' => 'Schulgelände und Umgebung',
            'contact_name' => 'Julia Eisenhut, Matthias Rytz',
            'contact_email' => 'sponsorenlauf@steinerschule-langnau.ch',
            'status' => 'published',
            'has_forum' => true,
            'has_shifts' => true,
            'show_in_calendar' => true,
        ]);

        $bulletinPost19->shifts()->create([
            'role' => 'Streckenposten',
            'time' => '23.05.' . (now()->year + 1) . ', 09:00 - 12:00 Uhr',
            'needed' => 8,
            'offline_filled' => 2,
        ]);

        $bulletinPost19->shifts()->create([
            'role' => 'Verpflegungsstation',
            'time' => '23.05.' . (now()->year + 1) . ', 08:30 - 12:30 Uhr',
            'needed' => 4,
            'offline_filled' => 1,
        ]);

        // 20. Lachsverkauf -> Lachsverkauf activity
        BulletinPost::create([
            'title' => 'Lachsverkauf Weihnachten - Bestellungen sammeln',
            'category' => 'verkauf',
            'activity_type' => 'production',
            'activity_id' => $activity('Lachsverkauf'),
            'description' => 'Der jährliche Räucherlachsverkauf startet!

Bestellformulare liegen im Sekretariat und im Eingangsbereich aus. Bestellschluss ist der 1. Dezember.

Wer hilft beim Verteilen der Bestellformulare in die Fächli und beim Einsammeln? Auch Helfer für die Auslieferung am 18. Dezember gesucht.',
            'start_at' => now()->year(now()->year)->month(11)->day(1),
            'end_at' => now()->year(now()->year)->month(12)->day(18),
            'location' => 'Sekretariat / Eingangsbereich',
            'contact_name' => 'Gisela Wyss',
            'contact_email' => 'lachs@steinerschule-langnau.ch',
            'status' => 'published',
            'has_forum' => true,
            'has_shifts' => false,
            'show_in_calendar' => true,
        ]);

        // Add sample forum posts
        $users = \App\Models\User::where('is_admin', false)->take(4)->get();

        if ($users->count() >= 3) {
            $bulletinPost4->posts()->create([
                'user_id' => $users[0]->id,
                'body' => 'Ich übernehme gerne eine Schicht in der Cafeteria! Bringe auch 3 Kuchen mit.',
                'ip_hash' => hash('sha256', '192.168.1.1'),
            ]);

            $post2 = $bulletinPost7->posts()->create([
                'user_id' => $users[1]->id,
                'body' => 'Komme regelmässig dienstags vormittags vorbei. Wer möchte sich anschliessen?',
                'ip_hash' => hash('sha256', '192.168.1.2'),
            ]);

            $post2->comments()->create([
                'user_id' => $users[2]->id,
                'body' => 'Super Idee! Ich komme nächsten Dienstag dazu.',
                'ip_hash' => hash('sha256', '192.168.1.3'),
            ]);

            if ($users->count() >= 4) {
                $bulletinPost10->posts()->create([
                    'user_id' => $users[3]->id,
                    'body' => 'Wir backen 2 glutenfreie Kuchen für den Tag der offenen Tür.',
                    'ip_hash' => hash('sha256', '192.168.1.4'),
                ]);
            }
        }

        // Add sample volunteers to some shifts
        $volunteers = \App\Models\User::whereIn('email', [
            'peter.mueller@example.com',
            'anna.schmidt@example.com',
            'maria.weber@example.com',
        ])->get();

        if ($volunteers->count() > 0) {
            $maeritShifts = $bulletinPost4->shifts()->get();
            if ($maeritShifts->count() > 0) {
                $maeritShifts->first()->volunteers()->create([
                    'user_id' => $volunteers->first()->id,
                    'name' => $volunteers->first()->name,
                    'email' => $volunteers->first()->email,
                ]);
            }

            $libraryShifts = $bulletinPost11->shifts()->get();
            if ($libraryShifts->count() > 0 && $volunteers->count() > 1) {
                $libraryShifts->first()->volunteers()->create([
                    'user_id' => $volunteers->skip(1)->first()->id,
                    'name' => $volunteers->skip(1)->first()->name,
                    'email' => $volunteers->skip(1)->first()->email,
                ]);
            }
        }
    }
}

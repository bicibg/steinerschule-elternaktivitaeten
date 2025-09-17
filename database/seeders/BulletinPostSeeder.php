<?php

namespace Database\Seeders;

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
        // 1. Lagerwoche Zurich - Shift-based activity
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
            'filled' => 0,
        ]);

        $bulletinPost1->shifts()->create([
            'role' => 'Küchenteam Mittwoch-Freitag',
            'time' => '05.06.' . (now()->year + 1) . ' - 07.06.' . (now()->year + 1),
            'needed' => 2,
            'filled' => 1,
        ]);

        // 2. Eurythmie-Aufführung - Shift-based
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
            'filled' => 2,
        ]);

        $bulletinPost2->shifts()->create([
            'role' => 'Garderobe während Aufführung',
            'time' => '17.05.' . (now()->year + 1) . ', 18:30 - 21:00 Uhr',
            'needed' => 2,
            'filled' => 0,
        ]);

        // 3. Ostereiersuche - Flexible help
        $bulletinPost3 = BulletinPost::create([
            'title' => 'Ostereiersuche im Schulgarten',
            'category' => 'anlass',
            'activity_type' => 'flexible_help',
            'description' => 'Traditionelle Ostereiersuche für Kindergarten und Unterstufe!

Wir brauchen Helfer für:
- Ostereier verstecken (ab 8:00 Uhr)
- Parcours aufbauen
- Kinder beaufsichtigen
- Getränke und Snacks verteilen

Je mehr Helfer, desto schöner wird das Fest!',
            'participation_note' => 'Kommt vorbei, wann ihr könnt!',
            'start_at' => now()->year(now()->year + 1)->month(4)->day(11)->setTime(8, 0),
            'end_at' => now()->year(now()->year + 1)->month(4)->day(11)->setTime(12, 0),
            'location' => 'Schulgarten und Pausenhof',
            'contact_name' => 'Sandra Müller',
            'contact_phone' => '+41 34 402 12 20',
            'contact_email' => 'kindergarten@steinerschule-langnau.ch',
            'status' => 'published',
            'has_forum' => true,
            'has_shifts' => false,
            'show_in_calendar' => true,
        ]);

        // 4. Weihnachtsbazar (Märit) - Major event with many shifts
        $bulletinPost4 = BulletinPost::create([
            'title' => 'Weihnachtsmärit - Grosser Helferaufruf',
            'category' => 'anlass',
            'activity_type' => 'shift_based',
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
            'contact_name' => 'Ursula Zimmermann',
            'contact_phone' => '+41 34 402 12 00',
            'contact_email' => 'marit@steinerschule-langnau.ch',
            'status' => 'published',
            'has_forum' => true,
            'has_shifts' => true,
            'show_in_calendar' => true,
            'label' => 'urgent',
        ]);

        // Multiple shifts for Märit
        $shift1 = $bulletinPost4->shifts()->create([
            'role' => 'Aufbau Freitag',
            'time' => '29.11.' . now()->year . ', 14:00 - 20:00 Uhr',
            'needed' => 20,
            'filled' => 5,  // 5 people registered offline, 1 will be added online below
        ]);

        $bulletinPost4->shifts()->create([
            'role' => 'Cafeteria Vormittag',
            'time' => '30.11.' . now()->year . ', 09:00 - 12:00 Uhr',
            'needed' => 6,
            'filled' => 2,  // 2 people registered offline
        ]);

        $bulletinPost4->shifts()->create([
            'role' => 'Cafeteria Nachmittag',
            'time' => '30.11.' . now()->year . ', 12:00 - 16:00 Uhr',
            'needed' => 6,
            'filled' => 0,
        ]);

        $bulletinPost4->shifts()->create([
            'role' => 'Kinderbereich',
            'time' => '30.11.' . now()->year . ', 10:00 - 16:00 Uhr',
            'needed' => 8,
            'filled' => 3,  // 3 people registered offline
        ]);

        $bulletinPost4->shifts()->create([
            'role' => 'Abbau',
            'time' => '30.11.' . now()->year . ', 16:00 - 19:00 Uhr',
            'needed' => 15,
            'filled' => 0,
        ]);

        // Add a volunteer to the first shift
        $users = \App\Models\User::where('is_admin', false)->first();
        if ($users) {
            \App\Models\ShiftVolunteer::create([
                'shift_id' => $shift1->id,
                'user_id' => $users->id,
                'name' => $users->name,
                'email' => $users->email,
            ]);
        }

        // 5. Adventssingen - Production activity
        $bulletinPost5 = BulletinPost::create([
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

        // 6. Elternrat - Regular meeting
        $bulletinPost6 = BulletinPost::create([
            'title' => 'Elternrat Sitzungen',
            'category' => 'organisation',
            'activity_type' => 'meeting',
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
            'contact_name' => 'Christine Brunner',
            'contact_phone' => '+41 34 402 12 40',
            'contact_email' => 'elternrat@steinerschule-langnau.ch',
            'status' => 'published',
            'has_forum' => false,
            'has_shifts' => false,
            'show_in_calendar' => true,
        ]);

        // 7. Schulgarten - Flexible help
        $bulletinPost7 = BulletinPost::create([
            'title' => 'Schulgarten-Pflege',
            'category' => 'haus_umgebung_taskforces',
            'activity_type' => 'flexible_help',
            'description' => 'Unser Schulgarten braucht regelmässige Pflege!

ARBEITEN JE NACH SAISON:
- Beete vorbereiten und bepflanzen
- Unkraut jäten
- Ernten
- Kompost pflegen
- Geräteschuppen aufräumen

Kommt vorbei, wann immer ihr Zeit habt. Werkzeug vorhanden.',
            'participation_note' => 'Jederzeit während Schulzeiten',
            'start_at' => now()->year(now()->year)->month(3)->day(1),
            'end_at' => now()->year(now()->year)->month(11)->day(30),
            'location' => 'Schulgarten hinter Turnhalle',
            'contact_name' => 'Markus Steiner',
            'contact_phone' => '+41 34 402 12 55',
            'contact_email' => 'garten@steinerschule-langnau.ch',
            'status' => 'published',
            'has_forum' => true,
            'has_shifts' => false,
            'show_in_calendar' => true,
        ]);

        // 8. Flohmarkt - Shift-based
        $bulletinPost8 = BulletinPost::create([
            'title' => 'Flohmarkt im Frühling',
            'category' => 'verkauf',
            'activity_type' => 'shift_based',
            'description' => 'Grosser Flohmarkt auf dem Schulgelände!

Wir sammeln und verkaufen:
- Kinderkleidung
- Spielzeug
- Bücher
- Sportgeräte
- Haushaltswaren

Helfer gesucht für Annahme, Sortierung und Verkauf.',
            'start_at' => now()->year(now()->year + 1)->month(3)->day(15)->setTime(9, 0),
            'end_at' => now()->year(now()->year + 1)->month(3)->day(15)->setTime(15, 0),
            'location' => 'Turnhalle',
            'contact_name' => 'Barbara Wyss',
            'contact_phone' => '+41 79 234 56 78',
            'contact_email' => 'flohmarkt@steinerschule-langnau.ch',
            'status' => 'published',
            'has_forum' => true,
            'has_shifts' => true,
            'show_in_calendar' => true,
        ]);

        $bulletinPost8->shifts()->create([
            'role' => 'Warenannahme Freitag',
            'time' => '14.03.' . (now()->year + 1) . ', 14:00 - 18:00 Uhr',
            'needed' => 5,
            'filled' => 1,
        ]);

        $bulletinPost8->shifts()->create([
            'role' => 'Verkauf Vormittag',
            'time' => '15.03.' . (now()->year + 1) . ', 09:00 - 12:00 Uhr',
            'needed' => 8,
            'filled' => 2,
        ]);

        $bulletinPost8->shifts()->create([
            'role' => 'Aufräumen',
            'time' => '15.03.' . (now()->year + 1) . ', 14:00 - 16:00 Uhr',
            'needed' => 6,
            'filled' => 0,
        ]);

        // 9. Johannifeuer - Shift-based
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
            'filled' => 2,
        ]);

        $bulletinPost9->shifts()->create([
            'role' => 'Feuerwache',
            'time' => '24.06.' . (now()->year + 1) . ', 19:00 - 23:00 Uhr',
            'needed' => 4,
            'filled' => 1,
        ]);

        $bulletinPost9->shifts()->create([
            'role' => 'Stockbrot-Station',
            'time' => '24.06.' . (now()->year + 1) . ', 19:00 - 21:00 Uhr',
            'needed' => 3,
            'filled' => 0,
        ]);

        // 10. Kuchenbuffet für Anlässe - Production
        $bulletinPost10 = BulletinPost::create([
            'title' => 'Kuchen für Schulanlässe',
            'category' => 'produktion',
            'activity_type' => 'production',
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
            'contact_name' => 'Ruth Gerber',
            'contact_phone' => '+41 34 402 12 00',
            'contact_email' => 'info@steinerschule-langnau.ch',
            'status' => 'published',
            'has_forum' => true,
            'has_shifts' => false,
            'show_in_calendar' => true,
        ]);

        // 11. Bibliothek - Regular shifts with flexible capacity
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
            'needed' => null,
            'flexible_capacity' => true,
            'filled' => 1,
        ]);

        $bulletinPost11->shifts()->create([
            'role' => 'Bibliotheksdienst Donnerstag',
            'time' => 'Donnerstags, 12:00 - 14:00 Uhr',
            'needed' => null,
            'flexible_capacity' => true,
            'filled' => 0,
        ]);

        // 12. Renovierung Klassenzimmer - Flexible help
        $bulletinPost12 = BulletinPost::create([
            'title' => 'Klassenzimmer renovieren',
            'category' => 'haus_umgebung_taskforces',
            'activity_type' => 'flexible_help',
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
            'contact_name' => 'Patrick Frei',
            'contact_phone' => '+41 79 345 67 89',
            'contact_email' => 'klasse3@steinerschule-langnau.ch',
            'status' => 'published',
            'has_forum' => true,
            'has_shifts' => false,
            'show_in_calendar' => true,
        ]);

        // 13. Pausenkiosk - Regular shifts
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
            'filled' => 1,
        ]);

        $bulletinPost13->shifts()->create([
            'role' => 'Kiosk Donnerstag',
            'time' => 'Donnerstags, 09:30 - 10:00 Uhr',
            'needed' => 2,
            'filled' => 0,
        ]);

        // 14. Theater-Requisiten - Production
        $bulletinPost14 = BulletinPost::create([
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

        // 15. Skilager-Begleitung - Shift-based
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
            'filled' => 1,
        ]);

        $bulletinPost15->shifts()->create([
            'role' => 'Begleitung Mo-Mi',
            'time' => '10.02.' . (now()->year + 1) . ' - 12.02.' . (now()->year + 1),
            'needed' => 2,
            'filled' => 0,
        ]);

        $bulletinPost15->shifts()->create([
            'role' => 'Begleitung Mi-Fr',
            'time' => '12.02.' . (now()->year + 1) . ' - 14.02.' . (now()->year + 1),
            'needed' => 2,
            'filled' => 0,
        ]);

        // Add sample forum posts
        // Get some users for posts
        $users = \App\Models\User::where('is_admin', false)->take(4)->get();

        if ($users->count() >= 3) {
            $post1 = $bulletinPost4->posts()->create([
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
                $post3 = $bulletinPost10->posts()->create([
                    'user_id' => $users[3]->id,
                    'body' => 'Wir backen 2 glutenfreie Kuchen für den Tag der offenen Tür.',
                    'ip_hash' => hash('sha256', '192.168.1.4'),
                ]);
            }
        }

        // Add sample volunteers to some shifts
        $users = \App\Models\User::whereIn('email', [
            'peter.mueller@example.com',
            'anna.schmidt@example.com',
            'maria.weber@example.com',
        ])->get();

        if ($users->count() > 0) {
            // Add volunteers to Märit
            $maeritShifts = $bulletinPost4->shifts()->get();
            if ($maeritShifts->count() > 0 && $users->count() > 0) {
                $maeritShifts->first()->volunteers()->create([
                    'user_id' => $users->first()->id,
                    'name' => $users->first()->name,
                    'email' => $users->first()->email,
                ]);
            }

            // Add volunteer to library
            $libraryShifts = $bulletinPost11->shifts()->get();
            if ($libraryShifts->count() > 0 && $users->count() > 1) {
                $libraryShifts->first()->volunteers()->create([
                    'user_id' => $users->skip(1)->first()->id,
                    'name' => $users->skip(1)->first()->name,
                    'email' => $users->skip(1)->first()->email,
                ]);
            }
        }
    }
}

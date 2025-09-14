<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activity1 = Activity::create([
            'title' => 'Helfer für Märit - Aufbau und Standbetreuung',
            'category' => 'anlass',
            'description' => 'Für unseren traditionellen Märit im November suchen wir noch viele helfende Hände!

AUFBAU FREITAG:
Wir benötigen Helfer für den Aufbau der Stände, das Herrichten der Räume und die Dekoration. Arbeitszeit: 14:00 - 20:00 Uhr (auch stundenweise möglich).

STANDBETREUUNG SAMSTAG:
- Betreuung des Blumenstandes (Schichten à 2 Stunden)
- Mithilfe beim Kinderprogramm (Filzen, Basteln)
- Verkauf von Schülerarbeiten
- Unterstützung in der Cafeteria

ABBAU SAMSTAG:
Nach Marktende (16:00 Uhr) benötigen wir Unterstützung beim Aufräumen und Zurückstellen der Möbel.

Bitte meldet euch für einzelne Schichten oder den ganzen Tag. Jede Hilfe ist willkommen!',
            'start_at' => now()->year(now()->year)->month(11)->day(9)->setTime(14, 0),
            'end_at' => now()->year(now()->year)->month(11)->day(9)->setTime(16, 0),
            'location' => 'Schulgelände Steinerschule Langnau, Schlossstrasse 6',
            'organizer_name' => 'Maria Müller',
            'organizer_phone' => '+41 34 402 12 34',
            'organizer_email' => 'maria.mueller@steinerschule-langnau.ch',
            'status' => 'published',
            'has_forum' => true,
            'has_shifts' => true,
        ]);

        $post1 = $activity1->posts()->create([
            'author_name' => 'Anna Schmidt',
            'body' => 'Super! Ich kann beim Märit mit selbstgemachten Bienenwachskerzen dabei sein. Gibt es noch freie Standplätze für Handarbeiten?',
            'ip_hash' => hash('sha256', '192.168.1.1'),
        ]);

        $post1->comments()->create([
            'author_name' => 'Maria Müller',
            'body' => 'Liebe Anna, ja es gibt noch einige Plätze im Handarbeitsbereich. Bitte melde dich bis Ende Oktober bei mir für die Standreservierung.',
            'ip_hash' => hash('sha256', '192.168.1.2'),
        ]);

        // Add shifts for Märit
        $shift1 = $activity1->shifts()->create([
            'role' => 'Aufbau Freitag',
            'time' => 'Freitag, 08.11.' . now()->year . ', 14:00 - 20:00 Uhr',
            'needed' => 8,
            'filled' => 2,
        ]);

        // Add sample volunteers with real users
        $users = \App\Models\User::whereIn('email', [
            'peter.mueller@example.com',
            'anna.schmidt@example.com',
        ])->get();

        foreach ($users as $user) {
            $shift1->volunteers()->create([
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]);
        }

        $shift2 = $activity1->shifts()->create([
            'role' => 'Blumenstand Vormittag',
            'time' => 'Samstag, 09.11.' . now()->year . ', 09:00 - 11:00 Uhr',
            'needed' => 2,
            'filled' => 1,
        ]);

        $mariaUser = \App\Models\User::where('email', 'maria.weber@example.com')->first();
        if ($mariaUser) {
            $shift2->volunteers()->create([
                'user_id' => $mariaUser->id,
                'name' => $mariaUser->name,
                'email' => $mariaUser->email,
            ]);
        }

        $activity1->shifts()->create([
            'role' => 'Cafeteria-Team',
            'time' => 'Samstag, 09.11.' . now()->year . ', 11:00 - 14:00 Uhr',
            'needed' => 4,
            'filled' => 0,
        ]);

        $activity1->shifts()->create([
            'role' => 'Kinderbetreuung',
            'time' => 'Samstag, 09.11.' . now()->year . ', 10:00 - 15:00 Uhr',
            'needed' => 3,
            'filled' => 0,
        ]);

        $activity1->shifts()->create([
            'role' => 'Abbau-Team',
            'time' => 'Samstag, 09.11.' . now()->year . ', 16:00 - 18:00 Uhr',
            'needed' => 10,
            'filled' => 0,
        ]);

        $post2 = $activity1->posts()->create([
            'author_name' => 'Stefan Bauer',
            'body' => 'Für die Cafeteria suchen wir noch dringend Kuchenbäcker! Wer kann einen Kuchen beisteuern? Bitte bis Donnerstag melden.',
            'ip_hash' => hash('sha256', '192.168.1.3'),
        ]);

        $activity2 = Activity::create([
            'title' => 'Helferteam für Kerzenziehen gesucht',
            'category' => 'produktion',
            'description' => 'Für das traditionelle Kerzenziehen im Advent suchen wir engagierte Eltern!

AUFGABENBEREICHE:

1. WACHSVORBEREITUNG (Montag-Mittwoch)
- Wachs schmelzen und vorbereiten
- Farben mischen
- Arbeitsplätze einrichten

2. BETREUUNG DER KERZENZIEH-STATIONEN (täglich)
- Anleitung der Besucher beim Kerzenziehen
- Wachs nachfüllen und Temperatur kontrollieren
- Kinder beim Ziehen unterstützen

3. VERKAUFSSTAND
- Fertige Kerzen verkaufen
- Kasse führen
- Beratung der Kunden

Schichten: 3-4 Stunden, flexibel einteilbar
Zeitraum: 1. bis 2. Adventswoche

Bitte meldet euch für einzelne Tage oder regelmässige Schichten.',
            'start_at' => now()->addDays(20)->setTime(9, 0),
            'end_at' => now()->addDays(35)->setTime(17, 0),
            'location' => 'Werkraum Steinerschule Langnau',
            'organizer_name' => 'Manuela Weber',
            'organizer_phone' => '+41 78 870 04 40',
            'organizer_email' => 'manuela.weber@steinerschule-langnau.ch',
            'status' => 'published',
            'has_forum' => false,
            'has_shifts' => true,
        ]);

        // Add shifts for Kerzenziehen
        $shift3 = $activity2->shifts()->create([
            'role' => 'Wachsvorbereitung',
            'time' => 'Montag, 02.12.' . now()->year . ', 08:00 - 12:00 Uhr',
            'needed' => 3,
            'filled' => 1,
        ]);

        $thomasUser = \App\Models\User::where('email', 'thomas.fischer@example.com')->first();
        if ($thomasUser) {
            $shift3->volunteers()->create([
                'user_id' => $thomasUser->id,
                'name' => $thomasUser->name,
                'email' => $thomasUser->email,
            ]);
        }

        $activity2->shifts()->create([
            'role' => 'Betreuung Kerzenzieh-Station',
            'time' => 'Dienstag, 03.12.' . now()->year . ', 14:00 - 18:00 Uhr',
            'needed' => 2,
            'filled' => 0,
        ]);

        $activity2->shifts()->create([
            'role' => 'Verkaufsstand',
            'time' => 'Mittwoch, 04.12.' . now()->year . ', 14:00 - 18:00 Uhr',
            'needed' => 2,
            'filled' => 0,
        ]);

        $activity2->shifts()->create([
            'role' => 'Aufräumen und Reinigung',
            'time' => 'Freitag, 06.12.' . now()->year . ', 18:00 - 20:00 Uhr',
            'needed' => 4,
            'filled' => 0,
        ]);

        $activity3 = Activity::create([
            'title' => 'Mithilfe Frühlings-Märit',
            'category' => 'anlass',
            'description' => 'Der Frühlings-Märit steht vor der Tür und wir brauchen eure Unterstützung!

BENÖTIGTE HELFER:

1. VORBEREITUNG (Freitag, 14:00-20:00)
- Stände aufbauen
- Beschilderung anbringen
- Tische und Bänke aufstellen

2. MARKTTAG (Samstag, verschiedene Schichten)
- Crêpes-Stand (2er-Schichten)
- Marktkafi-Team (Kaffee, Tee, Kuchen)
- Kinderbereich betreuen (Kistenklettern, Basteln)
- Parkplatz-Einweisung
- Kassenführung verschiedene Stände

3. BACKWAREN
Wir freuen uns über selbstgebackene Kuchen, Waffeln, Zopf für den Verkauf. Bitte bis Donnerstag anmelden.

4. ABBAU (Samstag ab 17:00)
- Stände abbauen
- Aufräumen und Reinigung

Bitte tragt euch für Schichten ein. Auch stundenweise Hilfe ist willkommen!',
            'start_at' => now()->year(now()->year + 1)->month(3)->day(25)->setTime(9, 0),
            'end_at' => now()->year(now()->year + 1)->month(3)->day(25)->setTime(17, 0),
            'location' => 'Schulhaus Langnau, Turnhalle',
            'organizer_name' => 'Sandra Koch',
            'organizer_phone' => '+41 31 345 67 89',
            'organizer_email' => 'sandra.koch@example.com',
            'status' => 'published',
            'has_forum' => true,
            'has_shifts' => false,
        ]);

        $post3 = $activity3->posts()->create([
            'author_name' => 'Lisa Meier',
            'body' => 'Gibt es auch vegetarisches Essen am Märit?',
            'ip_hash' => hash('sha256', '192.168.1.4'),
        ]);

        $post3->comments()->create([
            'author_name' => 'Sandra Koch',
            'body' => 'Ja, wir haben eine grosse Auswahl an vegetarischen und veganen Speisen!',
            'ip_hash' => hash('sha256', '192.168.1.5'),
        ]);

        // Adventskranzbinden
        $activity4 = Activity::create([
            'title' => 'Helfer für Adventskranzbinden',
            'category' => 'produktion',
            'description' => 'In der Woche vor dem ersten Advent binden wir Adventskränze und Dekorationen. Dafür suchen wir kreative Hände!

ARBEITEN:

1. MATERIAL SAMMELN (Montag/Dienstag)
- Tannenreisig schneiden und sortieren
- Moos und Zapfen sammeln
- Material zum Werkraum transportieren

2. KRANZBINDEN (Mittwoch - Freitag)
- Kränze binden (Anleitung vorhanden)
- Türschmuck gestalten
- Gestecke anfertigen
- Dekoration mit Bändern und Kerzen

3. VERKAUFSVORBEREITUNG (Freitag)
- Kränze für Verkauf beschriften
- Preise auszeichnen
- Verkaufsraum vorbereiten

Arbeitszeiten flexibel zwischen 14:00 und 20:00 Uhr.
Keine Vorkenntnisse nötig - wir zeigen euch gerne die Techniken!

Kinder können gerne mitgebracht werden.',
            'start_at' => now()->addDays(15)->setTime(14, 0),
            'end_at' => now()->addDays(18)->setTime(18, 0),
            'location' => 'Werkraum Steinerschule Langnau',
            'organizer_name' => 'Elternrat',
            'organizer_phone' => '+41 34 402 12 40',
            'organizer_email' => 'elternrat@steinerschule-langnau.ch',
            'status' => 'published',
            'has_forum' => true,
            'has_shifts' => true,
            'label' => 'important',
        ]);

        // Add shifts for Adventskranzbinden
        $activity4->shifts()->create([
            'role' => 'Material vorbereiten',
            'time' => 'Mittwoch, 27.11.' . now()->year . ', 14:00 - 18:00 Uhr',
            'needed' => 3,
            'filled' => 0,
        ]);

        $activity4->shifts()->create([
            'role' => 'Kranzbinden Donnerstag',
            'time' => 'Donnerstag, 28.11.' . now()->year . ', 14:00 - 18:00 Uhr',
            'needed' => 5,
            'filled' => 1,
        ]);

        // Elternkafi
        $activity5 = Activity::create([
            'title' => 'Team für Elternkafi am Schulsamstag',
            'category' => 'haus_umgebung_taskforces',
            'description' => 'Für das Elternkafi am kommenden Schulsamstag benötigen wir dringend Helfer!

AUFGABEN:

1. AUFBAU (7:00 - 7:45 Uhr)
- Tische und Stühle aufstellen
- Kaffeemaschine vorbereiten
- Geschirr bereitstellen

2. BETRIEB (7:45 - 11:30 Uhr)
- Kaffee und Tee ausschenken
- Gipfeli verkaufen
- Kasse führen
- Tische abräumen

3. KUCHENBEITRÄGE
Wer kann einen Kuchen beisteuern? Bitte bis Donnerstag melden.

4. ABBAU (11:30 - 12:00 Uhr)
- Aufräumen
- Geschirr spülen
- Möbel zurückstellen

Bitte meldet euch für einzelne Schichten. Die Einnahmen kommen der Schule zugute.',
            'start_at' => now()->addDays(7)->setTime(7, 45),
            'end_at' => now()->addDays(7)->setTime(11, 30),
            'location' => 'Pavillon / Schulhof Steinerschule Langnau',
            'organizer_name' => 'Elternrat',
            'organizer_phone' => '+41 34 402 12 40',
            'organizer_email' => 'elternrat@steinerschule-langnau.ch',
            'status' => 'published',
            'has_forum' => false,
            'has_shifts' => true,
            'label' => 'urgent',
        ]);

        // Add shifts for Elternkafi
        $activity5->shifts()->create([
            'role' => 'Kafi-Aufbau',
            'time' => 'Samstag, 21.09.' . now()->year . ', 07:00 - 07:45 Uhr',
            'needed' => 2,
            'filled' => 0,
        ]);

        $activity5->shifts()->create([
            'role' => 'Kafi-Betreuung Vormittag',
            'time' => 'Samstag, 21.09.' . now()->year . ', 07:45 - 09:30 Uhr',
            'needed' => 2,
            'filled' => 0,
        ]);

    }
}

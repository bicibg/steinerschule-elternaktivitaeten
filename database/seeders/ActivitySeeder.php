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
        // Shift-based activity: Märit
        $activity1 = Activity::create([
            'title' => 'Helfer für Märit - Aufbau und Standbetreuung',
            'category' => 'anlass',
            'activity_type' => 'shift_based',
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
            'show_in_calendar' => true,
        ]);

        // Add shifts for Märit
        $shift1 = $activity1->shifts()->create([
            'role' => 'Aufbau Freitag',
            'time' => 'Freitag, 08.11.' . now()->year . ', 14:00 - 20:00 Uhr',
            'needed' => 8,
            'filled' => 2,
        ]);

        $shift2 = $activity1->shifts()->create([
            'role' => 'Blumenstand Vormittag',
            'time' => 'Samstag, 09.11.' . now()->year . ', 09:00 - 11:00 Uhr',
            'needed' => 2,
            'filled' => 1,
        ]);

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

        // Production activity: Kuchen backen
        $activity2 = Activity::create([
            'title' => '200 Kuchen für Märit backen',
            'category' => 'produktion',
            'activity_type' => 'production',
            'description' => 'Wir benötigen 200 Kuchen für den Märit-Verkauf!

Jede Familie wird gebeten, mindestens 2 Kuchen beizusteuern. Die Kuchen können zu Hause gebacken werden und müssen bis Freitag, 8. November, 18:00 Uhr in der Schulküche abgegeben werden.

WICHTIG:
- Bitte Allergene kennzeichnen (Nüsse, Gluten, etc.)
- Kuchen in Einwegverpackungen oder beschrifteten Behältern
- Beliebt sind: Zitronenkuchen, Schokoladenkuchen, Apfelkuchen, Marmorkuchen
- Auch vegane und glutenfreie Kuchen sind sehr willkommen!

Koordination über die WhatsApp-Gruppe "Märit Kuchen 2024".',
            'participation_note' => 'Backen zu Hause möglich',
            'start_at' => now()->year(now()->year)->month(10)->day(20),
            'end_at' => now()->year(now()->year)->month(11)->day(8),
            'location' => 'Zu Hause / Abgabe in Schulküche',
            'organizer_name' => 'Sandra Koch',
            'organizer_phone' => '+41 31 345 67 89',
            'organizer_email' => 'sandra.koch@example.com',
            'status' => 'published',
            'has_forum' => true,
            'has_shifts' => false,
            'show_in_calendar' => true,
        ]);

        // Meeting activity: Elternrat
        $activity3 = Activity::create([
            'title' => 'Elternrat Sitzungen',
            'category' => 'organisation',
            'activity_type' => 'meeting',
            'description' => 'Der Elternrat trifft sich jeden ersten Donnerstag im Monat zur Koordination der Elternaktivitäten.

THEMEN:
- Planung kommender Anlässe
- Koordination der Helfergruppen
- Budget-Besprechungen
- Austausch mit Schulleitung

Neue Mitglieder sind herzlich willkommen! Keine Verpflichtung zur regelmässigen Teilnahme.',
            'recurring_pattern' => 'Jeden ersten Donnerstag im Monat',
            'participation_note' => 'Offene Teilnahme, keine Anmeldung nötig',
            'start_at' => now()->year(now()->year)->month(9)->day(1),
            'end_at' => now()->year(now()->year + 1)->month(7)->day(31),
            'location' => 'Lehrerzimmer',
            'organizer_name' => 'Elternrat',
            'organizer_phone' => '+41 34 402 12 40',
            'organizer_email' => 'elternrat@steinerschule-langnau.ch',
            'status' => 'published',
            'has_forum' => false,
            'has_shifts' => false,
            'show_in_calendar' => true,
        ]);

        // Flexible help activity
        $activity4 = Activity::create([
            'title' => 'Gartenpflege - Herbstputz',
            'category' => 'haus_umgebung_taskforces',
            'activity_type' => 'flexible_help',
            'description' => 'Grosser Herbstputz im Schulgarten!

Wir treffen uns am Samstag für die Gartenpflege. Kommt vorbei, wann es euch passt - jede helfende Hand ist willkommen!

ARBEITEN:
- Laub rechen
- Beete winterfest machen
- Sträucher schneiden
- Kompost umsetzen
- Spielplatz reinigen

Bringt gerne eigene Gartengeräte mit. Für Verpflegung ist gesorgt!',
            'participation_note' => 'Flexible Teilnahme - kommt wann ihr könnt!',
            'start_at' => now()->year(now()->year)->month(10)->day(26)->setTime(9, 0),
            'end_at' => now()->year(now()->year)->month(10)->day(26)->setTime(16, 0),
            'location' => 'Schulgarten',
            'organizer_name' => 'Thomas Fischer',
            'organizer_phone' => '+41 79 123 45 67',
            'organizer_email' => 'garten@steinerschule-langnau.ch',
            'status' => 'published',
            'has_forum' => true,
            'has_shifts' => false,
            'show_in_calendar' => true,
        ]);

        // Shift-based with flexible capacity
        $activity5 = Activity::create([
            'title' => 'Adventskranzbinden',
            'category' => 'produktion',
            'activity_type' => 'shift_based',
            'description' => 'In der Woche vor dem ersten Advent binden wir Adventskränze für den Verkauf.

Je mehr Helfer, desto mehr Kränze können wir produzieren! Keine Vorkenntnisse nötig - wir zeigen euch die Techniken.

MATERIAL:
- Tannenreisig wird gestellt
- Draht und Kerzen vorhanden
- Dekoration kann mitgebracht werden

Erlös geht an die Klassenkassen.',
            'participation_note' => 'Je mehr Helfer, desto besser!',
            'start_at' => now()->year(now()->year)->month(11)->day(27)->setTime(14, 0),
            'end_at' => now()->year(now()->year)->month(11)->day(28)->setTime(18, 0),
            'location' => 'Werkraum Steinerschule Langnau',
            'organizer_name' => 'Elternrat',
            'organizer_phone' => '+41 34 402 12 40',
            'organizer_email' => 'elternrat@steinerschule-langnau.ch',
            'status' => 'published',
            'has_forum' => true,
            'has_shifts' => true,
            'show_in_calendar' => true,
            'label' => 'important',
        ]);

        // Add shifts with flexible capacity
        $activity5->shifts()->create([
            'role' => 'Kranzbinden',
            'time' => 'Mittwoch, 27.11.' . now()->year . ', 14:00 - 18:00 Uhr',
            'needed' => null,
            'flexible_capacity' => true,
            'filled' => 3,
        ]);

        $activity5->shifts()->create([
            'role' => 'Kranzbinden',
            'time' => 'Donnerstag, 28.11.' . now()->year . ', 14:00 - 18:00 Uhr',
            'needed' => null,
            'flexible_capacity' => true,
            'filled' => 1,
        ]);

        // Production: Kerzenziehen
        $activity6 = Activity::create([
            'title' => 'Kerzenziehen Produktion',
            'category' => 'produktion',
            'activity_type' => 'production',
            'description' => 'Während der Adventszeit produzieren wir Bienenwachskerzen für den Verkauf.

Die Kerzen können flexibel während der Öffnungszeiten gezogen werden. Material und Anleitung sind vorhanden.

ÖFFNUNGSZEITEN:
- Montag bis Freitag: 14:00 - 18:00 Uhr
- Samstag: 10:00 - 16:00 Uhr

Kinder können gerne mithelfen!',
            'participation_note' => 'Kommt vorbei, wann es passt',
            'start_at' => now()->year(now()->year)->month(12)->day(2),
            'end_at' => now()->year(now()->year)->month(12)->day(20),
            'location' => 'Werkstatt im Keller',
            'organizer_name' => 'Lisa Meier',
            'organizer_phone' => '+41 76 234 56 78',
            'organizer_email' => 'kerzen@steinerschule-langnau.ch',
            'status' => 'published',
            'has_forum' => false,
            'has_shifts' => false,
            'show_in_calendar' => true,
        ]);

        // Add sample forum posts for some activities
        $post1 = $activity1->posts()->create([
            'author_name' => 'Anna Schmidt',
            'body' => 'Ich kann beim Aufbau helfen und bringe noch 2 Helfer mit!',
            'ip_hash' => hash('sha256', '192.168.1.1'),
        ]);

        $post1->comments()->create([
            'author_name' => 'Maria Müller',
            'body' => 'Super! Danke Anna. Bitte kommt direkt zum Haupteingang.',
            'ip_hash' => hash('sha256', '192.168.1.2'),
        ]);

        $post2 = $activity2->posts()->create([
            'author_name' => 'Stefan Bauer',
            'body' => 'Wir backen 3 Zitronenkuchen und 2 vegane Schokoladenkuchen.',
            'ip_hash' => hash('sha256', '192.168.1.3'),
        ]);

        $post3 = $activity4->posts()->create([
            'author_name' => 'Peter Weber',
            'body' => 'Ich bringe eine Motorsäge mit für die grösseren Äste.',
            'ip_hash' => hash('sha256', '192.168.1.4'),
        ]);

        $post3->comments()->create([
            'author_name' => 'Thomas Fischer',
            'body' => 'Perfekt! Wir haben einige grosse Äste, die geschnitten werden müssen.',
            'ip_hash' => hash('sha256', '192.168.1.5'),
        ]);

        // Add sample volunteers to shifts
        $users = \App\Models\User::whereIn('email', [
            'peter.mueller@example.com',
            'anna.schmidt@example.com',
            'maria.weber@example.com',
        ])->get();

        foreach ($users->take(2) as $user) {
            $shift1->volunteers()->create([
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]);
        }

        if ($users->count() > 2) {
            $shift2->volunteers()->create([
                'user_id' => $users->skip(2)->first()->id,
                'name' => $users->skip(2)->first()->name,
                'email' => $users->skip(2)->first()->email,
            ]);
        }
    }
}
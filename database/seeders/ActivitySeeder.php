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
            'title' => 'Osterbazar',
            'description' => 'Dieser grosse Anlass findet traditionellerweise immer am Wochenende vor Ostern statt. In den Schulräumen und auf dem Hof werden viele Verkaufstände eingerichtet.

Schülerarbeiten, Blumen, Bücher, Spielsachen, Kunsthandwerkliches und österliche Überraschungen werden verkauft. Für die Kinder gibt es verschiedenste Aktivitäten.

Im Sihlau-Restaurant, in Cafés und an Ständen kann sich jeder verpflegen und verwöhnen lassen. Im Rahmen des Osterverkaufs finden auch kulturelle Höhepunkte wie Konzerte oder Variétés statt.

Ein buntes Treiben, sowie gemütliche Treffpunkte zum Plaudern und Verweilen prägen das Bild dieses Anlasses.

Viele Eltern engagieren sich neben ihrem wirtschaftlichen Beitrag in Aktivitäten, welche weitere finanzielle Mittel für die Schule erbringen. Von der Realisierung neuer Ideen und von der Initiativkraft lebt die Schule.',
            'start_at' => now()->addDays(45)->setTime(9, 0),
            'end_at' => now()->addDays(45)->setTime(16, 0),
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
            'body' => 'Wunderbar! Wir werden mit selbstgemachten Bienenwachskerzen dabei sein. Gibt es noch freie Standplätze für Handarbeiten?',
            'ip_hash' => hash('sha256', '192.168.1.1'),
        ]);

        $post1->comments()->create([
            'author_name' => 'Maria Müller',
            'body' => 'Liebe Anna, ja es gibt noch einige Plätze im Handarbeitsbereich. Bitte melde dich bis Ende März bei mir für die Standreservierung.',
            'ip_hash' => hash('sha256', '192.168.1.2'),
        ]);

        // Add shifts for Frühlingsmarkt
        $shift1 = $activity1->shifts()->create([
            'role' => 'Aufbau am Vorabend',
            'time' => 'Freitag, 17:00 - 20:00 Uhr',
            'needed' => 6,
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
            'role' => 'Cafeteria Vormittag',
            'time' => 'Samstag, 09:00 - 12:30 Uhr',
            'needed' => 4,
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
            'role' => 'Cafeteria Nachmittag',
            'time' => 'Samstag, 12:30 - 16:00 Uhr',
            'needed' => 4,
            'filled' => 0,
        ]);

        $activity1->shifts()->create([
            'role' => 'Kinderbetreuung',
            'time' => 'Samstag, 10:00 - 15:00 Uhr',
            'needed' => 3,
            'filled' => 0,
        ]);

        $activity1->shifts()->create([
            'role' => 'Abbau und Aufräumen',
            'time' => 'Samstag, 16:00 - 18:00 Uhr',
            'needed' => 8,
            'filled' => 0,
        ]);

        $post2 = $activity1->posts()->create([
            'author_name' => 'Stefan Bauer',
            'body' => 'Suchen noch 2-3 Helfer für den Pizzastand. Wer hat Lust und Zeit? Die Schichten sind flexibel einteilbar.',
            'ip_hash' => hash('sha256', '192.168.1.3'),
        ]);

        $activity2 = Activity::create([
            'title' => 'Kerzenziehen im Advent',
            'description' => 'Tauchen Sie ein in eine lichtvolle Stimmung, in einen Ort der Ruhe und der Begegnung.

Die Eltern der jeweiligen 2. Klasse organisieren in der näheren Umgebung ein öffentliches, vorweihnachtliches Kerzenziehen. Eine Woche lang sich mit Gleichgesinnten treffen und in dieser stimmungsvollen Atmosphäre Kontakte knüpfen, Gedanken austauschen und den alltäglichen Kleinkram vergessen.

Die einen empfinden es als Meditation, als Abtauchen in Bienenwachs-Düfte und in eine angenehme Wärme, die anderen sehen es als erfüllende Arbeit, verbunden mit guten Gesprächen.

Wir suchen Frauen und Männer, die Zeit und Lust haben, sich für das Kerzenziehen zu engagieren. Exaktes Arbeiten ist ebenso wichtig wie die Freude am Material und an den Kerzen.

Die Arbeitszeiten orientieren sich an der Kerzenart:
- Baumkerzen: ca. 4 Stunden am Stück
- Kranzkerzen: ca. 6-8 Stunden – am Stück oder verteilt auf zwei Tage',
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
            'role' => 'Baumkerzen ziehen',
            'time' => 'Montag Vormittag, 09:00 - 13:00 Uhr',
            'needed' => 2,
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
            'role' => 'Standbetreuung Freitag',
            'time' => 'Freitag, 14:00 - 18:00 Uhr',
            'needed' => 2,
            'filled' => 0,
        ]);

        $activity2->shifts()->create([
            'role' => 'Standbetreuung Samstag',
            'time' => 'Samstag, 10:00 - 14:00 Uhr',
            'needed' => 2,
            'filled' => 0,
        ]);

        $activity2->shifts()->create([
            'role' => 'Kerzen vorbereiten',
            'time' => 'Donnerstag, 16:00 - 18:00 Uhr',
            'needed' => 3,
            'filled' => 0,
        ]);

        $activity3 = Activity::create([
            'title' => 'Herbst-Märit Langnau',
            'description' => 'Marktstände drinnen und draussen begleitet von Musik, Jodeln, Zauberei und Theater.

Jodlerklub Langnau: 11:00 - 15:00 Uhr durchgehend
Puppentheater «Lubomir»: 10:30, 13:00 und 15:15 Uhr im Kellertheater (ab 4 Jahren)
Zauberer «Fjodoro»: 11:45 und 14:15 Uhr
Duo Adelante / Koro Mundartfolk: 10:00 und 16:00 Uhr

Zusätzliche Aktivitäten:
- Kistenklettern
- Kerzenziehen
- Crêpes
- Olivenöl
- Alpkäse
- Blumenkränze
- Marktkafi
- Wolle und Gebackenes
und vieles mehr!

Ein buntes Treiben, sowie gemütliche Treffpunkte zum Plaudern und Verweilen prägen das Bild dieses Anlasses.',
            'start_at' => now()->addDays(30)->setTime(9, 0),
            'end_at' => now()->addDays(30)->setTime(17, 0),
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
            'title' => 'Adventskranzbinden',
            'description' => 'In der Woche vor dem ersten Advent werden in den Werkräumen der Schule Kränze, Türschmuck und weitere Adventsdekorationen für den Verkauf und für den Eigengebrauch kunstvoll gefertigt.

Das Kranzteam ist froh um alle HelferInnen, die gerne mit Naturmaterial gestalten. Mit der Schulpost gelangt der Aufruf zur Mithilfe in die Familien.

Gemeinsam entstehen wunderschöne Adventskränze und Dekorationen in gemütlicher Atmosphäre. Die Tradition des gemeinsamen Kranzbindens stärkt die Schulgemeinschaft und stimmt uns auf die besinnliche Adventszeit ein.',
            'start_at' => now()->addDays(15)->setTime(14, 0),
            'end_at' => now()->addDays(18)->setTime(18, 0),
            'location' => 'Werkraum Steinerschule Langnau',
            'organizer_name' => 'Elternrat',
            'organizer_phone' => '+41 34 402 12 40',
            'organizer_email' => 'elternrat@steinerschule-langnau.ch',
            'status' => 'published',
            'has_forum' => true,
            'has_shifts' => true,
            'label' => 'help_needed',
        ]);

        // Add shifts for Adventskranzbinden
        $activity4->shifts()->create([
            'role' => 'Material vorbereiten',
            'time' => 'Mittwoch, 14:00 - 18:00 Uhr',
            'needed' => 3,
            'filled' => 0,
        ]);

        $activity4->shifts()->create([
            'role' => 'Kranzbinden Donnerstag',
            'time' => 'Donnerstag, 14:00 - 18:00 Uhr',
            'needed' => 5,
            'filled' => 1,
        ]);

        // Elternkafi
        $activity5 = Activity::create([
            'title' => 'Elternkafi an Schulsamstagen',
            'description' => 'Das Elternkafi ist immer ein gemütlicher Treffpunkt an Schulsamstagen, am Tag der Offenen Tür und bei anderen Gelegenheiten.

Es findet wetterabhängig im Pavillon oder auf dem Schulhof statt und wird vom Elternrat organisiert. Es werden Kaffee, Tee und Gipfeli angeboten.

Das Kafi ist morgens vor der Semesterfeier ab 07.45 Uhr, und bei trockenem Wetter auch nach der Feier bis 11.30 Uhr geöffnet.

Der Elternrat freut sich auf Ihren Besuch! Die Einnahmen kommen vollständig der Schule zugute.',
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
            'time' => 'Samstag, 07:00 - 07:45 Uhr',
            'needed' => 2,
            'filled' => 0,
        ]);

        $activity5->shifts()->create([
            'role' => 'Kafi-Betreuung Vormittag',
            'time' => 'Samstag, 07:45 - 09:30 Uhr',
            'needed' => 2,
            'filled' => 0,
        ]);

        echo "\n=== Aktivitäten mit Edit-Links erstellt ===\n\n";

        $activities = Activity::all();
        foreach ($activities as $activity) {
            echo "Aktivität: {$activity->title}\n";
            echo "Edit URL: /aktivitaeten/{$activity->slug}/edit?token={$activity->edit_token}\n\n";
        }
    }
}

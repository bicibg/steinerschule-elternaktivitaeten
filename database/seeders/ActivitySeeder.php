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
            'title' => 'Frühlingsmarkt',
            'description' => 'Die Steinerschule Langnau lädt herzlich zum traditionellen Frühlingsmarkt ein! Ein buntes Markttreiben mit handgefertigten Produkten, kulinarischen Köstlichkeiten und einem vielfältigen Kinderprogramm erwartet Sie.

Im Angebot: Selbstgemachte Seifen, Kerzen, Holzspielzeug, Eurythmiekleider, Bücher aus dem Antiquariat, biologisches Gemüse und Setzlinge für den Garten. Die Schülerfirmen präsentieren ihre Produkte und in der Cafeteria gibt es hausgemachte Kuchen und fair gehandelten Kaffee.

Für die Kinder: Puppenspiel um 11 und 14 Uhr, Filzen, Kerzenziehen und eine Schatzsuche im Schulgarten.

Der Erlös unterstützt die Anschaffung neuer Musikinstrumente für den Schulunterricht.',
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
            'description' => 'Eine wundervolle Arbeit zugunsten der Steinerschule Langnau!

Die einen empfinden es als Meditation, als Abtauchen in Bienenwachs-Düfte und in eine angenehme Wärme, die anderen sehen es als erfüllende Arbeit, verbunden mit guten Gesprächen.

Wir suchen Frauen und Männer, die Zeit und Lust haben, sich für das Kerzenziehen zu engagieren. Exaktes Arbeiten ist ebenso wichtig wie die Freude am Material und an den Kerzen.

Die Arbeitszeiten orientieren sich an der Kerzenart:
- Baumkerzen: ca. 4 Stunden am Stück (passt mit den Schulzeiten)
- Kranzkerzen: ca. 6-8 Stunden – am Stück oder verteilt auf zwei Tage

Sehr willkommen sind auch Zweierteams, die gemeinsam ins Kerzenziehen eintauchen wollen.',
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
            'title' => 'Herbst-Märit',
            'description' => 'Traditioneller Herbst-Märit mit bunten Marktständen und lokalen Produkten.

Es gibt handgefertigte Dekorationen, Spielzeug, Bücher, selbstgemachte Leckereien und vieles mehr.

Zusätzlich gibt es einen Kinderbereich mit Bastelecke und Märchenerzählung.',
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

        echo "\n=== Aktivitäten mit Edit-Links erstellt ===\n\n";

        $activities = Activity::all();
        foreach ($activities as $activity) {
            echo "Aktivität: {$activity->title}\n";
            echo "Edit URL: /aktivitaeten/{$activity->slug}/edit?token={$activity->edit_token}\n\n";
        }
    }
}

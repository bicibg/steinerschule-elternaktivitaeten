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
            'title' => 'Herbst-Flohmarkt',
            'description' => 'Zu Gast in der Aula der Steinerschule Langnau, freut sich die Schulgemeinschaft auf einen bunten Herbst-Flohmarkt mit vielen schönen Sachen für die ganze Familie.

Verkauft werden Kinderkleider, Spielsachen, Bücher, Haushaltsgegenstände und vieles mehr. Der Erlös kommt der Schule zugute.

Standanmeldungen bitte bis zum 20. Februar bei Maria Müller.',
            'start_at' => now()->addDays(15)->setTime(9, 0),
            'end_at' => now()->addDays(15)->setTime(16, 0),
            'location' => 'Aula Steinerschule Langnau',
            'organizer_name' => 'Maria Müller',
            'organizer_phone' => '+41 31 123 45 67',
            'organizer_email' => 'maria.mueller@example.com',
            'status' => 'published',
        ]);

        $post1 = $activity1->posts()->create([
            'author_name' => 'Anna Schmidt (2a)',
            'body' => 'Freue mich schon sehr auf den Flohmarkt! Gibt es auch einen Kindertisch, wo die Kinder selbst ihre Sachen verkaufen können?',
            'ip_hash' => hash('sha256', '192.168.1.1'),
        ]);

        $post1->comments()->create([
            'author_name' => 'Maria Müller',
            'body' => 'Ja, natürlich! Wir richten einen speziellen Kinderbereich ein. Die Kinder können gerne ihre eigenen Sachen verkaufen.',
            'ip_hash' => hash('sha256', '192.168.1.2'),
        ]);

        $post2 = $activity1->posts()->create([
            'author_name' => 'Peter Weber',
            'body' => 'Kann ich auch selbstgemachte Marmeladen und Chutneys verkaufen?',
            'ip_hash' => hash('sha256', '192.168.1.3'),
        ]);

        $activity2 = Activity::create([
            'title' => 'Kerzenstand Weihnachtsmarkt',
            'description' => 'Die Steinerschule Langnau betreibt wieder einen Kerzenstand auf dem Langnauer Weihnachtsmarkt.

Kinder und Erwachsene können bei uns schöne Kerzen ziehen. Das ist ein besonderes Erlebnis für die ganze Familie!

Wir suchen noch Helfer für die Standbetreuung. Bitte meldet euch bei Thomas Weber.',
            'start_at' => now()->addDays(45)->setTime(10, 0),
            'end_at' => now()->addDays(48)->setTime(20, 0),
            'location' => 'Langnauer Weihnachtsmarkt, Stand Nr. 15',
            'organizer_name' => 'Thomas Weber',
            'organizer_phone' => '+41 31 234 56 78',
            'organizer_email' => 'thomas.weber@example.com',
            'status' => 'published',
        ]);

        $activity3 = Activity::create([
            'title' => 'Basar in Ittigen',
            'description' => 'Traditioneller Basar mit bunten Marktständen und lokalen Produkten.

Es gibt handgefertigte Weihnachtsdekorationen, Spielzeug, Bücher, selbstgemachte Leckereien und vieles mehr.

Zusätzlich gibt es einen Kinderbasar mit Bastelecke und Märchenerzählung.',
            'start_at' => now()->addDays(30)->setTime(10, 0),
            'end_at' => now()->addDays(30)->setTime(17, 0),
            'location' => 'Schulhaus Ittigen, Turnhalle',
            'organizer_name' => 'Sandra Koch',
            'organizer_phone' => '+41 31 345 67 89',
            'organizer_email' => 'sandra.koch@example.com',
            'status' => 'published',
        ]);

        $post3 = $activity3->posts()->create([
            'author_name' => 'Lisa Meier',
            'body' => 'Gibt es auch vegetarisches Essen am Basar?',
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

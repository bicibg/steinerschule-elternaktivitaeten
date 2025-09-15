<?php

namespace Database\Seeders;

use App\Models\SchoolEvent;
use Illuminate\Database\Seeder;

class SchoolEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing events
        SchoolEvent::truncate();

        // Current year events (2025-2026 school year)
        $events = [
            // September 2025
            [
                'title' => 'Herbstferien',
                'description' => 'Schulferien',
                'start_date' => '2025-09-29',
                'end_date' => '2025-10-10',
                'location' => null,
                'event_type' => 'holiday',
                'all_day' => true,
            ],

            // Oktober 2025
            [
                'title' => 'Laternenzeit',
                'description' => 'Zeit der Laternen und Lichter',
                'start_date' => '2025-10-27',
                'end_date' => '2025-10-27',
                'location' => 'Schulgelände',
                'event_type' => 'festival',
                'all_day' => false,
            ],

            // November 2025
            [
                'title' => 'Orchesterreise in Ittigen',
                'description' => 'Konzertreise des Schulorchesters',
                'start_date' => '2025-11-06',
                'end_date' => '2025-11-06',
                'location' => 'Ittigen',
                'event_type' => 'performance',
                'all_day' => false,
            ],
            [
                'title' => 'Orientierungsabend in Ittigen',
                'description' => 'Informationsabend für neue Familien',
                'start_date' => '2025-11-07',
                'end_date' => '2025-11-07',
                'event_time' => '19:30 Uhr',
                'location' => 'Ittigen',
                'event_type' => 'meeting',
                'all_day' => false,
            ],
            [
                'title' => 'Bazar',
                'description' => 'Grosser Weihnachtsbazar',
                'start_date' => '2025-11-29',
                'end_date' => '2025-11-29',
                'location' => 'Schulgelände',
                'event_type' => 'festival',
                'all_day' => true,
            ],

            // Dezember 2025
            [
                'title' => 'Informationsveranstaltung zur IMS',
                'description' => 'Info-Veranstaltung zur Integrativen Mittelschule',
                'start_date' => '2025-12-03',
                'end_date' => '2025-12-03',
                'event_time' => '00:00 Uhr',
                'location' => 'Aula',
                'event_type' => 'meeting',
                'all_day' => false,
            ],
            [
                'title' => 'Weihnachtsferien',
                'description' => 'Schulferien',
                'start_date' => '2025-12-20',
                'end_date' => '2026-01-02',
                'location' => null,
                'event_type' => 'holiday',
                'all_day' => true,
            ],

            // Januar 2026
            [
                'title' => 'Chorkonzert',
                'description' => 'Konzert des Schulchors',
                'start_date' => '2026-01-17',
                'end_date' => '2026-01-17',
                'event_time' => '19:00 Uhr',
                'location' => 'Festsaal',
                'event_type' => 'performance',
                'all_day' => false,
            ],
            [
                'title' => 'Chorkonzert',
                'description' => 'Zweite Aufführung des Schulchors',
                'start_date' => '2026-01-18',
                'end_date' => '2026-01-18',
                'event_time' => '17:00 Uhr',
                'location' => 'Festsaal',
                'event_type' => 'performance',
                'all_day' => false,
            ],
            [
                'title' => 'Orchesterreise in Ittigen',
                'description' => 'Konzertreise des Schulorchesters',
                'start_date' => '2026-01-19',
                'end_date' => '2026-01-19',
                'location' => 'Ittigen',
                'event_type' => 'performance',
                'all_day' => false,
            ],
            [
                'title' => 'Chorkonzert',
                'description' => 'Dritte Aufführung des Schulchors',
                'start_date' => '2026-01-24',
                'end_date' => '2026-01-24',
                'location' => 'Festsaal',
                'event_type' => 'performance',
                'all_day' => false,
            ],
            [
                'title' => 'Sportwoche',
                'description' => 'Wintersportwoche',
                'start_date' => '2026-01-26',
                'end_date' => '2026-01-30',
                'location' => 'Verschiedene Orte',
                'event_type' => 'sports',
                'all_day' => true,
            ],

            // Februar 2026
            [
                'title' => 'Sportferien',
                'description' => 'Schulferien',
                'start_date' => '2026-02-07',
                'end_date' => '2026-02-15',
                'location' => null,
                'event_type' => 'holiday',
                'all_day' => true,
            ],
            [
                'title' => 'Elternwoche',
                'description' => 'Woche der offenen Klassenzimmer',
                'start_date' => '2026-02-28',
                'end_date' => '2026-03-06',
                'location' => 'Alle Klassenzimmer',
                'event_type' => 'other',
                'all_day' => true,
            ],

            // März 2026
            [
                'title' => '6. Klass-Theater Ittigen',
                'description' => 'Theateraufführung der 6. Klasse',
                'start_date' => '2026-03-01',
                'end_date' => '2026-03-01',
                'location' => 'Ittigen',
                'event_type' => 'performance',
                'all_day' => false,
            ],
            [
                'title' => 'Elternwoche',
                'description' => 'Fortsetzung der Elternwoche',
                'start_date' => '2026-03-01',
                'end_date' => '2026-03-06',
                'location' => 'Alle Klassenzimmer',
                'event_type' => 'other',
                'all_day' => true,
            ],
            [
                'title' => '6. Klass-Theater Ittigen',
                'description' => 'Zweite Aufführung',
                'start_date' => '2026-03-07',
                'end_date' => '2026-03-07',
                'location' => 'Ittigen',
                'event_type' => 'performance',
                'all_day' => false,
            ],
            [
                'title' => '8. Klass-Theater Ittigen',
                'description' => 'Theateraufführung der 8. Klasse',
                'start_date' => '2026-03-08',
                'end_date' => '2026-03-08',
                'location' => 'Ittigen',
                'event_type' => 'performance',
                'all_day' => false,
            ],
            [
                'title' => 'Langnauer',
                'description' => 'Schulfest in Langnau',
                'start_date' => '2026-03-16',
                'end_date' => '2026-03-16',
                'location' => 'Langnau',
                'event_type' => 'festival',
                'all_day' => false,
            ],
            [
                'title' => '8. Klass-Theater Ittigen',
                'description' => 'Zweite Aufführung',
                'start_date' => '2026-03-20',
                'end_date' => '2026-03-20',
                'location' => 'Ittigen',
                'event_type' => 'performance',
                'all_day' => false,
            ],
            [
                'title' => '8. Klass-Theater Ittigen',
                'description' => 'Dritte Aufführung',
                'start_date' => '2026-03-21',
                'end_date' => '2026-03-21',
                'location' => 'Ittigen',
                'event_type' => 'performance',
                'all_day' => false,
            ],
            [
                'title' => '6. Klass-Theater Bern',
                'description' => 'Theateraufführung der 6. Klasse in Bern',
                'start_date' => '2026-03-22',
                'end_date' => '2026-03-22',
                'location' => 'Bern',
                'event_type' => 'performance',
                'all_day' => false,
            ],
            [
                'title' => '6. Klass-Theater Bern',
                'description' => 'Zweite Aufführung in Bern',
                'start_date' => '2026-03-23',
                'end_date' => '2026-03-23',
                'location' => 'Bern',
                'event_type' => 'performance',
                'all_day' => false,
            ],
            [
                'title' => '8. Klass-Theater Bern',
                'description' => 'Theateraufführung der 8. Klasse in Bern',
                'start_date' => '2026-03-26',
                'end_date' => '2026-03-26',
                'location' => 'Bern',
                'event_type' => 'performance',
                'all_day' => false,
            ],
            [
                'title' => '8. Klass-Theater Bern',
                'description' => 'Zweite Aufführung in Bern',
                'start_date' => '2026-03-28',
                'end_date' => '2026-03-28',
                'location' => 'Bern',
                'event_type' => 'performance',
                'all_day' => false,
            ],

            // April 2026
            [
                'title' => 'Frühlingsferien',
                'description' => 'Schulferien',
                'start_date' => '2026-04-03',
                'end_date' => '2026-04-18',
                'location' => null,
                'event_type' => 'holiday',
                'all_day' => true,
            ],

            // Mai 2026
            [
                'title' => 'Orientierung in Ittigen',
                'description' => 'Orientierungsveranstaltung',
                'start_date' => '2026-05-04',
                'end_date' => '2026-05-04',
                'location' => 'Ittigen',
                'event_type' => 'meeting',
                'all_day' => false,
            ],
            [
                'title' => 'Langnauer',
                'description' => 'Schulfest in Langnau',
                'start_date' => '2026-05-04',
                'end_date' => '2026-05-04',
                'location' => 'Langnau',
                'event_type' => 'festival',
                'all_day' => false,
            ],
            [
                'title' => 'Langnauer',
                'description' => 'Zweiter Tag des Schulfests',
                'start_date' => '2026-05-29',
                'end_date' => '2026-05-29',
                'location' => 'Langnau',
                'event_type' => 'festival',
                'all_day' => false,
            ],

            // Juni 2026
            [
                'title' => 'Langnauer',
                'description' => 'Schulfest in Langnau',
                'start_date' => '2026-06-20',
                'end_date' => '2026-06-20',
                'location' => 'Langnau',
                'event_type' => 'festival',
                'all_day' => false,
            ],

            // Juli 2026
            [
                'title' => 'Sommerferien',
                'description' => 'Schulferien',
                'start_date' => '2026-07-06',
                'end_date' => '2026-08-07',
                'location' => null,
                'event_type' => 'holiday',
                'all_day' => true,
            ],
        ];

        foreach ($events as $event) {
            SchoolEvent::create($event);
        }
    }
}
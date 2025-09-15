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

        // Current year events
        $currentYear = now()->year;
        $nextYear = $currentYear + 1;

        $events = [
            // September
            [
                'title' => 'Schuljahresbeginn',
                'description' => 'Erster Schultag des neuen Schuljahres',
                'start_date' => "$currentYear-08-12 08:00:00",
                'end_date' => null,
                'location' => 'Alle Klassenzimmer',
                'event_type' => 'other',
                'all_day' => false,
            ],
            [
                'title' => 'Elternabend 1. Klasse',
                'description' => 'Informationsabend für Eltern der Erstklässler',
                'start_date' => "$currentYear-09-05 19:30:00",
                'end_date' => "$currentYear-09-05 21:00:00",
                'location' => 'Festsaal',
                'event_type' => 'meeting',
                'all_day' => false,
            ],
            [
                'title' => 'Michaeli-Fest',
                'description' => 'Traditionelles Michaeli-Fest mit Mutproben und Spielen',
                'start_date' => "$currentYear-09-29 09:00:00",
                'end_date' => "$currentYear-09-29 12:00:00",
                'location' => 'Schulhof und Turnhalle',
                'event_type' => 'festival',
                'all_day' => false,
            ],

            // Oktober
            [
                'title' => 'Herbstferien',
                'description' => 'Schulferien',
                'start_date' => "$currentYear-10-07",
                'end_date' => "$currentYear-10-18",
                'location' => null,
                'event_type' => 'holiday',
                'all_day' => true,
            ],
            [
                'title' => 'Tag der offenen Tür',
                'description' => 'Einblick in den Schulalltag für interessierte Familien',
                'start_date' => "$currentYear-10-26 09:00:00",
                'end_date' => "$currentYear-10-26 13:00:00",
                'location' => 'Gesamtes Schulgelände',
                'event_type' => 'other',
                'all_day' => false,
            ],

            // November
            [
                'title' => 'Laternenumzug',
                'description' => 'St. Martin Laternenumzug für Kindergarten und Unterstufe',
                'start_date' => "$currentYear-11-11 17:00:00",
                'end_date' => "$currentYear-11-11 19:00:00",
                'location' => 'Start: Schulhof',
                'event_type' => 'festival',
                'all_day' => false,
            ],
            [
                'title' => 'Weihnachtsmärit',
                'description' => 'Grosser Weihnachtsmarkt mit Verkauf, Cafeteria und Kinderbereich',
                'start_date' => "$currentYear-11-30 10:00:00",
                'end_date' => "$currentYear-11-30 17:00:00",
                'location' => 'Gesamtes Schulgelände',
                'event_type' => 'festival',
                'all_day' => false,
            ],

            // Dezember
            [
                'title' => 'Adventsspirale',
                'description' => 'Besinnliche Adventsspirale für alle Klassen',
                'start_date' => "$currentYear-12-01 16:00:00",
                'end_date' => "$currentYear-12-01 18:00:00",
                'location' => 'Festsaal',
                'event_type' => 'festival',
                'all_day' => false,
            ],
            [
                'title' => 'Weihnachtsspiel Oberuferer',
                'description' => 'Aufführung des traditionellen Weihnachtsspiels',
                'start_date' => "$currentYear-12-19 19:00:00",
                'end_date' => "$currentYear-12-19 21:00:00",
                'location' => 'Festsaal',
                'event_type' => 'performance',
                'all_day' => false,
            ],
            [
                'title' => 'Weihnachtsferien',
                'description' => 'Schulferien',
                'start_date' => "$currentYear-12-23",
                'end_date' => "$nextYear-01-03",
                'location' => null,
                'event_type' => 'holiday',
                'all_day' => true,
            ],

            // Januar
            [
                'title' => 'Dreikönigsspiel',
                'description' => 'Aufführung des Dreikönigsspiels',
                'start_date' => "$nextYear-01-06 10:00:00",
                'end_date' => "$nextYear-01-06 11:30:00",
                'location' => 'Festsaal',
                'event_type' => 'performance',
                'all_day' => false,
            ],
            [
                'title' => 'Elternsprechtag',
                'description' => 'Individuelle Gespräche mit Klassenlehrern',
                'start_date' => "$nextYear-01-24",
                'end_date' => "$nextYear-01-25",
                'location' => 'Klassenzimmer',
                'event_type' => 'meeting',
                'all_day' => true,
            ],

            // Februar
            [
                'title' => 'Fasnacht',
                'description' => 'Fasnachtsfeier mit Kostümen und Spielen',
                'start_date' => "$nextYear-02-25 09:00:00",
                'end_date' => "$nextYear-02-25 12:00:00",
                'location' => 'Turnhalle',
                'event_type' => 'festival',
                'all_day' => false,
            ],
            [
                'title' => 'Sportferien',
                'description' => 'Schulferien',
                'start_date' => "$nextYear-02-10",
                'end_date' => "$nextYear-02-21",
                'location' => null,
                'event_type' => 'holiday',
                'all_day' => true,
            ],
            [
                'title' => 'Skilager 5./6. Klasse',
                'description' => 'Skilager in Adelboden',
                'start_date' => "$nextYear-02-10",
                'end_date' => "$nextYear-02-14",
                'location' => 'Adelboden',
                'event_type' => 'excursion',
                'all_day' => true,
            ],

            // März
            [
                'title' => '8. Klass-Theaterstück',
                'description' => 'Aufführung des Theaterstücks der 8. Klasse',
                'start_date' => "$nextYear-03-20 19:30:00",
                'end_date' => "$nextYear-03-20 21:30:00",
                'location' => 'Festsaal',
                'event_type' => 'performance',
                'all_day' => false,
            ],
            [
                'title' => '8. Klass-Theaterstück',
                'description' => 'Zweite Aufführung',
                'start_date' => "$nextYear-03-21 19:30:00",
                'end_date' => "$nextYear-03-21 21:30:00",
                'location' => 'Festsaal',
                'event_type' => 'performance',
                'all_day' => false,
            ],

            // April
            [
                'title' => 'Ostereiersuche',
                'description' => 'Ostereiersuche für Kindergarten und Unterstufe',
                'start_date' => "$nextYear-04-11 09:00:00",
                'end_date' => "$nextYear-04-11 11:00:00",
                'location' => 'Schulgarten',
                'event_type' => 'festival',
                'all_day' => false,
            ],
            [
                'title' => 'Frühlingsferien',
                'description' => 'Schulferien',
                'start_date' => "$nextYear-04-06",
                'end_date' => "$nextYear-04-21",
                'location' => null,
                'event_type' => 'holiday',
                'all_day' => true,
            ],

            // Mai
            [
                'title' => 'Eurythmie-Abschluss 12. Klasse',
                'description' => 'Abschlussaufführung der 12. Klasse',
                'start_date' => "$nextYear-05-17 19:00:00",
                'end_date' => "$nextYear-05-17 21:00:00",
                'location' => 'Festsaal',
                'event_type' => 'performance',
                'all_day' => false,
            ],
            [
                'title' => 'Pfingstmontag',
                'description' => 'Feiertag - schulfrei',
                'start_date' => "$nextYear-05-20",
                'end_date' => null,
                'location' => null,
                'event_type' => 'holiday',
                'all_day' => true,
            ],

            // Juni
            [
                'title' => 'Lagerwoche Zürich 8. Klasse',
                'description' => 'Klassenfahrt nach Zürich',
                'start_date' => "$nextYear-06-03",
                'end_date' => "$nextYear-06-07",
                'location' => 'Zürich',
                'event_type' => 'excursion',
                'all_day' => true,
            ],
            [
                'title' => 'Johannifeuer',
                'description' => 'Sommerfest mit grossem Feuer',
                'start_date' => "$nextYear-06-24 18:00:00",
                'end_date' => "$nextYear-06-24 22:00:00",
                'location' => 'Wiese hinter der Schule',
                'event_type' => 'festival',
                'all_day' => false,
            ],
            [
                'title' => 'Quartalsfeier',
                'description' => 'Präsentationen aus allen Klassen',
                'start_date' => "$nextYear-06-28 09:00:00",
                'end_date' => "$nextYear-06-28 11:00:00",
                'location' => 'Festsaal',
                'event_type' => 'performance',
                'all_day' => false,
            ],

            // Juli
            [
                'title' => 'Verabschiedung 12. Klasse',
                'description' => 'Abschlussfeier der Abgangsklasse',
                'start_date' => "$nextYear-07-04 18:00:00",
                'end_date' => "$nextYear-07-04 21:00:00",
                'location' => 'Festsaal',
                'event_type' => 'festival',
                'all_day' => false,
            ],
            [
                'title' => 'Schuljahresabschluss',
                'description' => 'Letzter Schultag mit Zeugnisausgabe',
                'start_date' => "$nextYear-07-05 08:00:00",
                'end_date' => "$nextYear-07-05 12:00:00",
                'location' => 'Klassenzimmer',
                'event_type' => 'other',
                'all_day' => false,
            ],
            [
                'title' => 'Sommerferien',
                'description' => 'Schulferien',
                'start_date' => "$nextYear-07-08",
                'end_date' => "$nextYear-08-09",
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
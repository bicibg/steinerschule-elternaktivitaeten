<?php

namespace Database\Seeders;

use App\Models\CalendarEvent;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CalendarEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currentYear = now()->year;

        // September
        CalendarEvent::create([
            'title' => 'Schulbeginn',
            'description' => 'Erster Schultag nach den Sommerferien',
            'date' => Carbon::create($currentYear, 8, 19),
            'start_time' => '08:00',
            'type' => 'other',
            'location' => 'Schulgelände',
        ]);

        CalendarEvent::create([
            'title' => 'Elternabend 1. Klasse',
            'description' => 'Kennenlernen und Einführung in die Waldorfpädagogik',
            'date' => Carbon::create($currentYear, 9, 5),
            'start_time' => '19:30',
            'end_time' => '21:00',
            'type' => 'parent_evening',
            'location' => 'Klassenzimmer 1. Klasse',
        ]);

        CalendarEvent::create([
            'title' => 'Michaeli-Fest',
            'description' => 'Traditionelles Michaeli-Fest mit Mutproben für die Mittelstufe',
            'date' => Carbon::create($currentYear, 9, 29),
            'start_time' => '10:00',
            'end_time' => '14:00',
            'type' => 'festival',
            'location' => 'Schulgelände und Wald',
        ]);

        // Oktober
        CalendarEvent::create([
            'title' => 'Herbstferien',
            'description' => 'Zwei Wochen Herbstferien',
            'date' => Carbon::create($currentYear, 10, 7),
            'all_day' => true,
            'type' => 'holiday',
        ]);

        CalendarEvent::create([
            'title' => 'Herbstferien Ende',
            'description' => 'Letzter Tag der Herbstferien',
            'date' => Carbon::create($currentYear, 10, 20),
            'all_day' => true,
            'type' => 'holiday',
        ]);

        CalendarEvent::create([
            'title' => 'Quartalsfeier',
            'description' => 'Präsentation von Schülerarbeiten aus allen Klassenstufen',
            'date' => Carbon::create($currentYear, 10, 26),
            'start_time' => '10:00',
            'end_time' => '11:30',
            'type' => 'festival',
            'location' => 'Festsaal',
        ]);

        // November
        CalendarEvent::create([
            'title' => 'Räbeliechtli-Umzug',
            'description' => 'Laternenumzug für Kindergarten und Unterstufe',
            'date' => Carbon::create($currentYear, 11, 11),
            'start_time' => '17:30',
            'end_time' => '19:00',
            'type' => 'festival',
            'location' => 'Schulgelände und Dorf',
        ]);

        CalendarEvent::create([
            'title' => 'Elternsprechtag',
            'description' => 'Individuelle Gespräche mit Klassenlehrern',
            'date' => Carbon::create($currentYear, 11, 15),
            'start_time' => '14:00',
            'end_time' => '19:00',
            'type' => 'parent_evening',
            'location' => 'Klassenzimmer',
        ]);

        CalendarEvent::create([
            'title' => 'Tag der offenen Tür',
            'description' => 'Einblick in den Unterricht für interessierte Familien',
            'date' => Carbon::create($currentYear, 11, 23),
            'start_time' => '09:00',
            'end_time' => '13:00',
            'type' => 'other',
            'location' => 'Gesamtes Schulgelände',
        ]);

        // Dezember
        CalendarEvent::create([
            'title' => 'Adventsgärtlein',
            'description' => 'Besinnliche Adventsfeier für die jüngeren Klassen',
            'date' => Carbon::create($currentYear, 12, 1),
            'start_time' => '17:00',
            'end_time' => '18:30',
            'type' => 'festival',
            'location' => 'Festsaal',
        ]);

        CalendarEvent::create([
            'title' => 'Christgeburtspiel',
            'description' => 'Aufführung des traditionellen Oberuferer Christgeburtspiels',
            'date' => Carbon::create($currentYear, 12, 19),
            'start_time' => '17:00',
            'end_time' => '18:30',
            'type' => 'concert',
            'location' => 'Festsaal',
        ]);

        CalendarEvent::create([
            'title' => 'Weihnachtsferien Beginn',
            'description' => 'Beginn der Weihnachtsferien',
            'date' => Carbon::create($currentYear, 12, 23),
            'all_day' => true,
            'type' => 'holiday',
        ]);

        // Januar
        CalendarEvent::create([
            'title' => 'Weihnachtsferien Ende',
            'description' => 'Schulbeginn nach den Weihnachtsferien',
            'date' => Carbon::create($currentYear + 1, 1, 6),
            'all_day' => true,
            'type' => 'holiday',
        ]);

        CalendarEvent::create([
            'title' => 'Dreikönigsspiel',
            'description' => 'Aufführung des Dreikönigsspiels',
            'date' => Carbon::create($currentYear + 1, 1, 6),
            'start_time' => '10:00',
            'end_time' => '11:00',
            'type' => 'concert',
            'location' => 'Festsaal',
        ]);

        CalendarEvent::create([
            'title' => '8. Klass-Spiel',
            'description' => 'Theateraufführung der 8. Klasse',
            'date' => Carbon::create($currentYear + 1, 1, 25),
            'start_time' => '19:00',
            'end_time' => '21:00',
            'type' => 'concert',
            'location' => 'Festsaal',
        ]);

        // Februar
        CalendarEvent::create([
            'title' => 'Sportferien',
            'description' => 'Eine Woche Sportferien',
            'date' => Carbon::create($currentYear + 1, 2, 10),
            'all_day' => true,
            'type' => 'holiday',
        ]);

        CalendarEvent::create([
            'title' => 'Sportferien Ende',
            'description' => 'Ende der Sportferien',
            'date' => Carbon::create($currentYear + 1, 2, 17),
            'all_day' => true,
            'type' => 'holiday',
        ]);

        CalendarEvent::create([
            'title' => 'Fasnacht',
            'description' => 'Fasnachtsfeier mit Verkleidung',
            'date' => Carbon::create($currentYear + 1, 2, 28),
            'start_time' => '14:00',
            'end_time' => '16:00',
            'type' => 'festival',
            'location' => 'Schulgelände',
        ]);

        // März
        CalendarEvent::create([
            'title' => 'Frühlingsmärit',
            'description' => 'Grosser Frühlingsmarkt - siehe Aktivitäten für Mithilfe',
            'date' => Carbon::create($currentYear + 1, 3, 25),
            'start_time' => '09:00',
            'end_time' => '17:00',
            'type' => 'festival',
            'location' => 'Schulgelände',
        ]);

        CalendarEvent::create([
            'title' => 'Quartalsfeier',
            'description' => 'Präsentation von Schülerarbeiten',
            'date' => Carbon::create($currentYear + 1, 3, 29),
            'start_time' => '10:00',
            'end_time' => '11:30',
            'type' => 'festival',
            'location' => 'Festsaal',
        ]);

        // April
        CalendarEvent::create([
            'title' => 'Osterferien Beginn',
            'description' => 'Beginn der Osterferien',
            'date' => Carbon::create($currentYear + 1, 4, 6),
            'all_day' => true,
            'type' => 'holiday',
        ]);

        CalendarEvent::create([
            'title' => 'Osterferien Ende',
            'description' => 'Ende der Osterferien',
            'date' => Carbon::create($currentYear + 1, 4, 22),
            'all_day' => true,
            'type' => 'holiday',
        ]);

        // Mai
        CalendarEvent::create([
            'title' => 'Maifest',
            'description' => 'Traditionelles Maifest mit Maibaumaufstellen',
            'date' => Carbon::create($currentYear + 1, 5, 1),
            'start_time' => '10:00',
            'end_time' => '14:00',
            'type' => 'festival',
            'location' => 'Pausenhof',
        ]);

        CalendarEvent::create([
            'title' => 'Eurythmie-Abschluss 12. Klasse',
            'description' => 'Eurythmie-Abschlussaufführung der 12. Klasse',
            'date' => Carbon::create($currentYear + 1, 5, 17),
            'start_time' => '19:00',
            'end_time' => '20:30',
            'type' => 'concert',
            'location' => 'Festsaal',
        ]);

        // Juni
        CalendarEvent::create([
            'title' => '12. Klass-Spiel',
            'description' => 'Abschluss-Theaterstück der 12. Klasse',
            'date' => Carbon::create($currentYear + 1, 6, 7),
            'start_time' => '19:00',
            'end_time' => '21:30',
            'type' => 'concert',
            'location' => 'Festsaal',
        ]);

        CalendarEvent::create([
            'title' => 'Johanni-Fest',
            'description' => 'Sommerfest mit Johannifeuer',
            'date' => Carbon::create($currentYear + 1, 6, 24),
            'start_time' => '18:00',
            'end_time' => '22:00',
            'type' => 'festival',
            'location' => 'Schulgelände',
        ]);

        CalendarEvent::create([
            'title' => 'Schuljahresabschluss',
            'description' => 'Zeugnisübergabe und Verabschiedung',
            'date' => Carbon::create($currentYear + 1, 6, 28),
            'start_time' => '10:00',
            'end_time' => '12:00',
            'type' => 'other',
            'location' => 'Festsaal',
        ]);

        // Juli
        CalendarEvent::create([
            'title' => 'Sommerferien Beginn',
            'description' => 'Beginn der sechswöchigen Sommerferien',
            'date' => Carbon::create($currentYear + 1, 6, 29),
            'all_day' => true,
            'type' => 'holiday',
        ]);
    }
}

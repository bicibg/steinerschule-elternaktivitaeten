<?php

namespace Database\Seeders;

use App\Models\CalendarEvent;
use Illuminate\Database\Seeder;

class CalendarEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Current month events
        CalendarEvent::create([
            'title' => 'Elternabend 5. Klasse',
            'description' => 'Quartalsrückblick und Vorschau auf kommende Projekte',
            'date' => now()->addDays(5),
            'start_time' => '19:30',
            'end_time' => '21:00',
            'type' => 'parent_evening',
            'location' => 'Klassenzimmer 5. Klasse',
        ]);

        CalendarEvent::create([
            'title' => 'Michaeli-Fest',
            'description' => 'Traditionelles Michaeli-Fest mit Mutproben und gemeinsamem Essen',
            'date' => now()->addDays(12),
            'start_time' => '10:00',
            'end_time' => '14:00',
            'type' => 'festival',
            'location' => 'Schulgelände',
        ]);

        // Next month
        CalendarEvent::create([
            'title' => 'Herbstferien',
            'description' => 'Zwei Wochen Herbstferien',
            'date' => now()->addMonth()->startOfMonth(),
            'all_day' => true,
            'type' => 'holiday',
        ]);

        CalendarEvent::create([
            'title' => 'Oberstufenkonzert',
            'description' => 'Die Schüler der Oberstufe präsentieren ihr musikalisches Können',
            'date' => now()->addMonth()->addDays(15),
            'start_time' => '19:00',
            'end_time' => '20:30',
            'type' => 'concert',
            'location' => 'Festsaal',
        ]);

        CalendarEvent::create([
            'title' => 'Tag der offenen Tür',
            'description' => 'Einblick in den Schulalltag für interessierte Eltern',
            'date' => now()->addMonth()->addDays(20),
            'start_time' => '09:00',
            'end_time' => '13:00',
            'type' => 'other',
            'location' => 'Gesamtes Schulgelände',
        ]);

        // Winter events
        CalendarEvent::create([
            'title' => 'Adventsbasar',
            'description' => 'Traditioneller Adventsbasar mit Handarbeiten und Leckereien',
            'date' => now()->addMonths(2)->startOfMonth()->addDays(5),
            'start_time' => '14:00',
            'end_time' => '18:00',
            'type' => 'festival',
            'location' => 'Schulhaus und Pausenhof',
        ]);

        CalendarEvent::create([
            'title' => 'Weihnachtsspiel',
            'description' => 'Aufführung des traditionellen Oberuferer Weihnachtsspiels',
            'date' => now()->year(now()->year)->month(12)->day(20),
            'start_time' => '17:00',
            'end_time' => '18:30',
            'type' => 'concert',
            'location' => 'Festsaal',
        ]);

        CalendarEvent::create([
            'title' => 'Weihnachtsferien',
            'description' => 'Schulferien über Weihnachten und Neujahr',
            'date' => now()->year(now()->year)->month(12)->day(23),
            'all_day' => true,
            'type' => 'holiday',
        ]);
    }
}

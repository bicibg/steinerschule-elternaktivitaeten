<?php

namespace Database\Seeders;

use App\Models\LunchShift;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class LunchShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some existing users for assignments
        $users = User::all();

        // Create shifts for the next 2 months
        $startDate = Carbon::now()->startOfWeek();
        $endDate = Carbon::now()->addMonths(2)->endOfMonth();

        $current = $startDate->copy();
        $userIndex = 0;

        while ($current <= $endDate) {
            // Only create for weekdays
            if ($current->isWeekday()) {
                $shift = [
                    'date' => $current->format('Y-m-d'),
                    'expected_meals' => rand(50, 70),
                ];

                // Randomly assign some shifts
                if ($current->isPast() || rand(0, 100) > 40) {
                    // 60% chance of being filled
                    if (rand(0, 100) > 30 && $users->count() > 0) {
                        // 70% chance of registered user
                        $user = $users->get($userIndex % $users->count());
                        $shift['user_id'] = $user->id;
                        $shift['is_filled'] = true;
                        $userIndex++;
                    } else {
                        // 30% chance of manual entry
                        $names = [
                            'Maria Müller',
                            'Thomas Schmidt',
                            'Anna Stalder',
                            'Peter Weber',
                            'Julia Fischer',
                            'Michael Huber',
                            'Sandra Meyer',
                            'Stefan Keller',
                        ];
                        $shift['cook_name'] = $names[array_rand($names)];
                        $shift['is_filled'] = true;
                    }
                } else {
                    $shift['is_filled'] = false;
                }

                // Add random notes for some shifts
                if (rand(0, 100) > 70) {
                    $notes = [
                        'Gemüsesuppe mit Brot',
                        'Pasta mit Tomatensauce',
                        'Kartoffelgratin',
                        'Linsenbolognese mit Nudeln',
                        'Gemüsecurry mit Reis',
                        'Pizza Margherita',
                        'Kürbissuppe',
                        'Risotto mit Gemüse',
                        'Spätzle mit Käse',
                        'Couscous-Salat',
                    ];
                    $shift['notes'] = $notes[array_rand($notes)];
                }

                LunchShift::create($shift);
            }

            $current->addDay();
        }
    }
}
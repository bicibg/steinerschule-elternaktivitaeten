<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Announcement;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first super admin, admin, or any user to be the creator
        $creator = User::where('is_super_admin', true)->first()
            ?? User::where('is_admin', true)->first()
            ?? User::first();

        // If no users exist, skip seeding
        if (!$creator) {
            $this->command->warn('No users found. Skipping notification seeding.');
            return;
        }

        // Create welcome notification if it doesn't exist
        Announcement::firstOrCreate(
            ['title' => 'Willkommen!'],
            [
                'message' => 'Herzlich willkommen auf der Plattform der Elternaktivitäten der Rudolf Steiner Schule Langnau. Hier finden Sie alle wichtigen Informationen zu Elternaktivitäten, Veranstaltungen und können sich für Helferschichten anmelden.',
                'type' => 'info',
                'is_active' => true,
                'is_priority' => true,
                'starts_at' => null,
                'expires_at' => null,
                'created_by' => $creator->id,
            ]
        );
    }
}

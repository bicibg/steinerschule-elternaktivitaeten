<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Database\Seeder;

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
        if (! $creator) {
            $this->command->warn('No users found. Skipping notification seeding.');

            return;
        }

        // Create welcome notification if it doesn't exist
        Announcement::firstOrCreate(
            ['title' => 'Willkommen!'],
            [
                'message' => 'Willkommen auf der Elternaktivitäten-Plattform der Rudolf Steiner Schule Langnau. Hier erhalten Sie alle wichtigen Informationen zu Elternaktivitäten und Veranstaltungen und haben die Möglichkeit, sich für Helferschichten anzumelden.',
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

<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserDeletionLog;
use Illuminate\Console\Command;

class AnonymizeExpiredUsers extends Command
{
    protected $signature = 'app:anonymize-expired-users';

    protected $description = 'Anonymize users whose 30-day deletion grace period has expired';

    public function handle(): int
    {
        $users = User::withTrashed()
            ->whereNotNull('deletion_requested_at')
            ->whereNull('anonymized_at')
            ->where('deletion_requested_at', '<=', now()->subDays(30))
            ->get();

        $count = 0;

        foreach ($users as $user) {
            UserDeletionLog::create([
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'action_type' => 'auto_anonymized',
                'performed_by' => $user->id,
                'performed_by_name' => 'System',
            ]);

            $user->anonymize();
            $count++;
        }

        $this->info("Anonymized {$count} expired user account(s).");

        return Command::SUCCESS;
    }
}

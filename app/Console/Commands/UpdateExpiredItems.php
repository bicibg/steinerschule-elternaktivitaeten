<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Announcement;
use App\Models\BulletinPost;

class UpdateExpiredItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-expired-items';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update status of expired notifications and bulletin posts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Update expired notifications
        $expiredNotifications = Announcement::where('is_active', true)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->update(['is_active' => false]);

        $this->info("Updated {$expiredNotifications} expired notifications to inactive.");

        // Update expired bulletin posts to 'ended' status
        $expiredPosts = BulletinPost::where('status', 'published')
            ->where(function($query) {
                $query->where('end_at', '<', now())
                    ->orWhere(function($q) {
                        $q->whereNull('end_at')
                            ->where('start_at', '<', now()->subDay());
                    });
            })
            ->update(['status' => 'ended']);

        $this->info("Updated {$expiredPosts} expired bulletin posts to 'ended' status.");

        return Command::SUCCESS;
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Activity;
use App\Models\Announcement;
use App\Models\AuditLog;
use App\Models\BulletinPost;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class YearResetCommand extends Command
{
    protected $signature = 'school:year-reset
                            {--force : Skip confirmation prompt}
                            {--school-year= : The school year that is ending (e.g., 2024/2025)}';

    protected $description = 'Reset the system for a new school year (deactivate activities, archive posts/comments)';

    public function handle()
    {
        // Check if recently reset
        if (AuditLog::wasActionPerformedRecently('year_reset', 30)) {
            $this->error('System was reset within the last 30 days. Cannot reset again so soon.');
            return 1;
        }

        // Show current statistics
        $activitiesCount = Activity::where('is_active', true)->count();
        $bulletinPostsCount = BulletinPost::where('status', 'published')->count();
        $announcementsCount = Announcement::where('is_active', true)->where('is_priority', false)->count();
        $postsCount = Post::whereNull('deleted_at')->count();
        $commentsCount = Comment::whereNull('deleted_at')->count();

        $this->warn('⚠️  ACHTUNG: KRITISCHE SYSTEMFUNKTION ⚠️');
        $this->line('');
        $this->info('Diese Funktion wird folgende Aktionen durchführen:');
        $this->line("- {$activitiesCount} aktive Aktivitäten deaktivieren");
        $this->line("- {$bulletinPostsCount} aktive Pinnwand-Einträge archivieren");
        $this->line("- {$announcementsCount} normale Ankündigungen deaktivieren (prioritäre bleiben aktiv)");
        $this->line("- {$postsCount} Forumbeiträge archivieren");
        $this->line("- {$commentsCount} Kommentare archivieren");
        $this->line('');
        $this->error('Diese Aktion kann NICHT rückgängig gemacht werden!');
        $this->line('');

        // Get school year
        $schoolYear = $this->option('school-year');
        if (!$schoolYear) {
            $currentYear = now()->year;
            $nextYear = $currentYear + 1;
            $defaultYear = "{$currentYear}/{$nextYear}";
            $schoolYear = $this->ask('Welches Schuljahr wird beendet?', $defaultYear);
        }

        // Confirmation
        if (!$this->option('force')) {
            $confirmText = $this->ask('Zum Bestätigen tippen Sie: SCHULJAHR ZURÜCKSETZEN');
            if ($confirmText !== 'SCHULJAHR ZURÜCKSETZEN') {
                $this->error('Ungültige Bestätigung. Abbruch.');
                return 1;
            }

            if (!$this->confirm('LETZTE WARNUNG: Sind Sie absolut sicher?')) {
                $this->info('Abbruch.');
                return 0;
            }
        }

        $this->info('Starte Zurücksetzung...');

        DB::beginTransaction();

        try {
            // 1. Deactivate all activities
            Activity::where('is_active', true)->update(['is_active' => false]);
            $this->info("✓ {$activitiesCount} Aktivitäten deaktiviert");

            // 2. Archive all bulletin posts
            BulletinPost::where('status', 'published')->update(['status' => 'archived']);
            $this->info("✓ {$bulletinPostsCount} Pinnwand-Einträge archiviert");

            // 3. Deactivate non-priority announcements
            Announcement::where('is_active', true)
                ->where('is_priority', false)
                ->update(['is_active' => false]);
            $this->info("✓ {$announcementsCount} Ankündigungen deaktiviert");

            // 4. Archive all posts
            Post::whereNull('deleted_at')->update([
                'deletion_reason' => 'year_archived',
                'deleted_at' => now(),
            ]);
            $this->info("✓ {$postsCount} Forumbeiträge archiviert");

            // 5. Archive all comments
            Comment::whereNull('deleted_at')->update([
                'deletion_reason' => 'year_archived',
                'deleted_at' => now(),
            ]);
            $this->info("✓ {$commentsCount} Kommentare archiviert");

            // 6. Create audit log
            AuditLog::create([
                'action_type' => 'year_reset',
                'action_name' => 'Schuljahr zurückgesetzt (Konsole)',
                'performed_by' => 1, // System user
                'performed_by_name' => 'System (Konsole)',
                'ip_address' => '127.0.0.1',
                'metadata' => [
                    'school_year' => $schoolYear,
                    'activities_deactivated' => $activitiesCount,
                    'bulletin_posts_deactivated' => $bulletinPostsCount,
                    'announcements_deactivated' => $announcementsCount,
                    'posts_archived' => $postsCount,
                    'comments_archived' => $commentsCount,
                    'via' => 'console',
                ],
                'description' => "Schuljahr {$schoolYear} über Konsole zurückgesetzt",
                'severity' => 'critical',
            ]);

            DB::commit();

            $this->newLine();
            $this->info('✅ Schuljahr erfolgreich zurückgesetzt!');
            $this->table(
                ['Aktion', 'Anzahl'],
                [
                    ['Aktivitäten deaktiviert', $activitiesCount],
                    ['Pinnwand-Einträge archiviert', $bulletinPostsCount],
                    ['Ankündigungen deaktiviert', $announcementsCount],
                    ['Forumbeiträge archiviert', $postsCount],
                    ['Kommentare archiviert', $commentsCount],
                ]
            );

            return 0;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Fehler beim Zurücksetzen: ' . $e->getMessage());
            return 1;
        }
    }
}
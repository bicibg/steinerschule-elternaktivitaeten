<?php

namespace App\Filament\Pages;

use App\Models\Activity;
use App\Models\Announcement;
use App\Models\BulletinPost;
use App\Models\Post;
use App\Models\Comment;
use App\Models\AuditLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class YearReset extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';
    protected static ?string $navigationLabel = 'Neues Schuljahr';
    protected static ?string $navigationGroup = 'Super Admin';
    protected static ?int $navigationSort = 100;
    protected static string $view = 'filament.pages.year-reset';

    public ?string $confirmationText = '';
    public ?string $password = '';
    public ?string $schoolYear = '';
    public ?string $notes = '';

    protected static ?string $title = 'Neues Schuljahr';

    public static function canAccess(): bool
    {
        return auth()->user()?->is_super_admin ?? false;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->is_super_admin ?? false;
    }

    public function mount(): void
    {
        // Set default school year (current year / next year)
        $currentYear = now()->year;
        $nextYear = $currentYear + 1;
        $this->schoolYear = "{$currentYear}/{$nextYear}";
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('⚠️ ACHTUNG: KRITISCHE AKTION')
                    ->description('Diese Aktion kann nicht rückgängig gemacht werden!')
                    ->schema([
                        Forms\Components\Placeholder::make('warning')
                            ->content('Diese Funktion bereitet das System für den Start des neuen Schuljahres vor.')
                            ->extraAttributes(['class' => 'text-danger-600 font-bold']),

                        Forms\Components\Placeholder::make('effects')
                            ->label('Folgende Aktionen werden durchgeführt:')
                            ->content(function () {
                                return view('filament.components.year-reset-effects', [
                                    'activitiesCount' => Activity::where('is_active', true)->count(),
                                    'bulletinPostsCount' => BulletinPost::where('is_active', true)->count(),
                                    'announcementsCount' => Announcement::where('is_active', true)->where('is_priority', false)->count(),
                                    'postsCount' => Post::whereNull('deleted_at')->count(),
                                    'commentsCount' => Comment::whereNull('deleted_at')->count(),
                                ]);
                            }),

                        Forms\Components\TextInput::make('schoolYear')
                            ->label('Neues Schuljahr')
                            ->required()
                            ->placeholder('2024/2025')
                            ->helperText('Geben Sie das neue Schuljahr ein, das beginnt'),

                        Forms\Components\Textarea::make('notes')
                            ->label('Notizen (optional)')
                            ->placeholder('Zusätzliche Informationen zum Reset...')
                            ->rows(3),

                        Forms\Components\TextInput::make('confirmationText')
                            ->label('Bestätigung')
                            ->required()
                            ->placeholder('Tippen Sie: NEUES SCHULJAHR STARTEN')
                            ->helperText('Geben Sie exakt "NEUES SCHULJAHR STARTEN" ein')
                            ->dehydrateStateUsing(fn ($state) => $state)
                            ->rule(fn () => function ($attribute, $value, $fail) {
                                if ($value !== 'NEUES SCHULJAHR STARTEN') {
                                    $fail('Der Bestätigungstext muss exakt "NEUES SCHULJAHR STARTEN" lauten.');
                                }
                            }),

                        Forms\Components\TextInput::make('password')
                            ->label('Ihr Passwort')
                            ->password()
                            ->required()
                            ->helperText('Geben Sie Ihr Passwort zur Bestätigung ein')
                            ->rule(fn () => function ($attribute, $value, $fail) {
                                if (!Hash::check($value, auth()->user()->password)) {
                                    $fail('Das Passwort ist nicht korrekt.');
                                }
                            }),
                    ])
                    ->collapsible(false),
            ])
            ->statePath('data');
    }

    public function getLastResetInfo(): ?array
    {
        $lastReset = AuditLog::getLastAction('year_reset');

        if (!$lastReset) {
            return null;
        }

        return [
            'date' => $lastReset->created_at->format('d.m.Y H:i'),
            'user' => $lastReset->performed_by_name,
            'schoolYear' => $lastReset->metadata['school_year'] ?? 'N/A',
            'daysAgo' => $lastReset->created_at->diffInDays(now()),
            'activities' => $lastReset->metadata['activities_deactivated'] ?? 0,
            'bulletinPosts' => $lastReset->metadata['bulletin_posts_deactivated'] ?? 0,
            'announcements' => $lastReset->metadata['announcements_deactivated'] ?? 0,
            'posts' => $lastReset->metadata['posts_archived'] ?? 0,
            'comments' => $lastReset->metadata['comments_archived'] ?? 0,
        ];
    }

    public function performReset(): void
    {
        // Validate form
        $this->form->getState();

        // Check if recently reset
        if (AuditLog::wasActionPerformedRecently('year_reset', 30)) {
            Notification::make()
                ->title('Vorbereitung nicht möglich')
                ->body('Das neue Schuljahr wurde in den letzten 30 Tagen bereits vorbereitet.')
                ->danger()
                ->send();
            return;
        }

        // Final confirmation check
        if ($this->confirmationText !== 'NEUES SCHULJAHR STARTEN') {
            Notification::make()
                ->title('Ungültige Bestätigung')
                ->body('Der Bestätigungstext ist nicht korrekt.')
                ->danger()
                ->send();
            return;
        }

        // Check password
        if (!Hash::check($this->password, auth()->user()->password)) {
            Notification::make()
                ->title('Ungültiges Passwort')
                ->body('Das eingegebene Passwort ist nicht korrekt.')
                ->danger()
                ->send();
            return;
        }

        DB::beginTransaction();

        try {
            // Count items before changes
            $activitiesCount = Activity::where('is_active', true)->count();
            $bulletinPostsCount = BulletinPost::where('is_active', true)->count();
            $announcementsCount = Announcement::where('is_active', true)->where('is_priority', false)->count();
            $postsCount = Post::whereNull('deleted_at')->count();
            $commentsCount = Comment::whereNull('deleted_at')->count();

            // 1. Deactivate all activities
            Activity::where('is_active', true)->update(['is_active' => false]);

            // 2. Deactivate all bulletin posts
            BulletinPost::where('is_active', true)->update(['is_active' => false]);

            // 3. Deactivate non-priority announcements
            Announcement::where('is_active', true)
                ->where('is_priority', false)
                ->update(['is_active' => false]);

            // 4. Archive all posts
            Post::whereNull('deleted_at')->update([
                'deletion_reason' => 'year_archived',
                'deleted_at' => now(),
            ]);

            // 5. Archive all comments
            Comment::whereNull('deleted_at')->update([
                'deletion_reason' => 'year_archived',
                'deleted_at' => now(),
            ]);

            // 6. Create audit log entry
            AuditLog::log(
                'year_reset',
                'Neues Schuljahr vorbereitet',
                [
                    'school_year' => $this->schoolYear,
                    'activities_deactivated' => $activitiesCount,
                    'bulletin_posts_deactivated' => $bulletinPostsCount,
                    'announcements_deactivated' => $announcementsCount,
                    'posts_archived' => $postsCount,
                    'comments_archived' => $commentsCount,
                    'notes' => $this->notes,
                ],
                "Neues Schuljahr {$this->schoolYear} vorbereitet: {$activitiesCount} Aktivitäten, {$bulletinPostsCount} Pinnwand-Einträge und {$announcementsCount} Ankündigungen deaktiviert, {$postsCount} Beiträge und {$commentsCount} Kommentare archiviert.",
                'critical'
            );

            DB::commit();

            // Clear form
            $this->confirmationText = '';
            $this->password = '';
            $this->notes = '';

            Notification::make()
                ->title('System erfolgreich vorbereitet')
                ->body("Das System ist bereit für das neue Schuljahr. {$activitiesCount} Aktivitäten, {$bulletinPostsCount} Pinnwand-Einträge und {$announcementsCount} Ankündigungen deaktiviert, {$postsCount} Beiträge und {$commentsCount} Kommentare archiviert.")
                ->success()
                ->persistent()
                ->send();

            // Redirect to dashboard
            redirect('/admin');

        } catch (\Exception $e) {
            DB::rollBack();

            Notification::make()
                ->title('Fehler bei der Vorbereitung')
                ->body('Es ist ein Fehler aufgetreten: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
}
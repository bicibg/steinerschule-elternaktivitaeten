<?php

namespace App\Filament\Pages;

use App\Models\Activity;
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
    protected static ?string $navigationLabel = 'Schuljahr zurücksetzen';
    protected static ?string $navigationGroup = 'Super Admin';
    protected static ?int $navigationSort = 100;
    protected static string $view = 'filament.pages.year-reset';

    public ?string $confirmationText = '';
    public ?string $password = '';
    public ?string $schoolYear = '';
    public ?string $notes = '';

    protected static ?string $title = 'Schuljahr zurücksetzen';

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
                            ->content('Diese Funktion setzt das System für ein neues Schuljahr zurück.')
                            ->extraAttributes(['class' => 'text-danger-600 font-bold']),

                        Forms\Components\Placeholder::make('effects')
                            ->label('Folgende Aktionen werden durchgeführt:')
                            ->content(function () {
                                return view('filament.components.year-reset-effects', [
                                    'activitiesCount' => Activity::where('is_active', true)->count(),
                                    'postsCount' => Post::count(),
                                    'commentsCount' => Comment::count(),
                                ]);
                            }),

                        Forms\Components\TextInput::make('schoolYear')
                            ->label('Schuljahr')
                            ->required()
                            ->placeholder('2024/2025')
                            ->helperText('Geben Sie das Schuljahr ein, das beendet wird'),

                        Forms\Components\Textarea::make('notes')
                            ->label('Notizen (optional)')
                            ->placeholder('Zusätzliche Informationen zum Reset...')
                            ->rows(3),

                        Forms\Components\TextInput::make('confirmationText')
                            ->label('Bestätigung')
                            ->required()
                            ->placeholder('Tippen Sie: SCHULJAHR ZURÜCKSETZEN')
                            ->helperText('Geben Sie exakt "SCHULJAHR ZURÜCKSETZEN" ein')
                            ->dehydrateStateUsing(fn ($state) => $state)
                            ->rule(fn () => function ($attribute, $value, $fail) {
                                if ($value !== 'SCHULJAHR ZURÜCKSETZEN') {
                                    $fail('Der Bestätigungstext muss exakt "SCHULJAHR ZURÜCKSETZEN" lauten.');
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
                ->title('Reset nicht möglich')
                ->body('Das System wurde in den letzten 30 Tagen bereits zurückgesetzt.')
                ->danger()
                ->send();
            return;
        }

        // Final confirmation check
        if ($this->confirmationText !== 'SCHULJAHR ZURÜCKSETZEN') {
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
            $postsCount = Post::whereNull('deleted_at')->count();
            $commentsCount = Comment::whereNull('deleted_at')->count();

            // 1. Deactivate all activities
            Activity::where('is_active', true)->update(['is_active' => false]);

            // 2. Archive all posts
            Post::whereNull('deleted_at')->update([
                'deletion_reason' => 'year_archived',
                'deleted_at' => now(),
            ]);

            // 3. Archive all comments
            Comment::whereNull('deleted_at')->update([
                'deletion_reason' => 'year_archived',
                'deleted_at' => now(),
            ]);

            // 4. Create audit log entry
            AuditLog::log(
                'year_reset',
                'Schuljahr zurückgesetzt',
                [
                    'school_year' => $this->schoolYear,
                    'activities_deactivated' => $activitiesCount,
                    'posts_archived' => $postsCount,
                    'comments_archived' => $commentsCount,
                    'notes' => $this->notes,
                ],
                "Schuljahr {$this->schoolYear} zurückgesetzt: {$activitiesCount} Aktivitäten deaktiviert, {$postsCount} Beiträge und {$commentsCount} Kommentare archiviert.",
                'critical'
            );

            DB::commit();

            // Clear form
            $this->confirmationText = '';
            $this->password = '';
            $this->notes = '';

            Notification::make()
                ->title('Schuljahr erfolgreich zurückgesetzt')
                ->body("Das System wurde für das neue Schuljahr vorbereitet. {$activitiesCount} Aktivitäten deaktiviert, {$postsCount} Beiträge und {$commentsCount} Kommentare archiviert.")
                ->success()
                ->persistent()
                ->send();

            // Redirect to dashboard
            redirect('/admin');

        } catch (\Exception $e) {
            DB::rollBack();

            Notification::make()
                ->title('Fehler beim Zurücksetzen')
                ->body('Es ist ein Fehler aufgetreten: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
}
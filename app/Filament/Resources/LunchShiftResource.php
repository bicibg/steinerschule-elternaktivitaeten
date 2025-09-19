<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LunchShiftResource\Pages;
use App\Models\LunchShift;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Carbon\Carbon;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Database\Eloquent\Builder;

class LunchShiftResource extends Resource
{
    protected static ?string $model = LunchShift::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Küchendienst';

    protected static ?string $navigationGroup = 'Aktivitäten';

    protected static ?string $modelLabel = 'Küchendienst';

    protected static ?string $pluralModelLabel = 'Küchendienste';

    protected static ?int $navigationSort = 25;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date')
                    ->label('Datum')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->minDate(now()->startOfDay())
                    ->displayFormat('d.m.Y')
                    ->native(false),

                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Verantwortliche Person')
                            ->options(User::orderBy('name')->pluck('name', 'id'))
                            ->searchable()
                            ->nullable()
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set) =>
                                $state ? $set('cook_name', null) : null
                            ),

                        Forms\Components\TextInput::make('cook_name')
                            ->label('Verantwortliche Person (Manuell)')
                            ->placeholder('Falls Person nicht registriert')
                            ->nullable()
                            ->disabled(fn (callable $get) => (bool) $get('user_id')),
                    ]),

                Forms\Components\TextInput::make('expected_meals')
                    ->label('Erwartete Mahlzeiten')
                    ->numeric()
                    ->default(60)
                    ->minValue(0)
                    ->maxValue(200)
                    ->suffix('Portionen'),

                Forms\Components\Textarea::make('notes')
                    ->label('Notizen / Menü')
                    ->placeholder('z.B. Gemüsesuppe, Pasta, Kartoffelgratin, etc.')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label('Datum')
                    ->date('D, d.m.Y')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(function ($state) {
                        $date = Carbon::parse($state);
                        $dayName = [
                            'Mon' => 'Mo',
                            'Tue' => 'Di',
                            'Wed' => 'Mi',
                            'Thu' => 'Do',
                            'Fri' => 'Fr',
                            'Sat' => 'Sa',
                            'Sun' => 'So',
                        ][$date->format('D')] ?? $date->format('D');

                        return $dayName . ', ' . $date->format('d.m.Y');
                    })
                    ->color(fn (LunchShift $record): string =>
                        $record->isToday() ? 'warning' :
                        ($record->isPast() ? 'gray' : 'primary')
                    ),

                Tables\Columns\TextColumn::make('cook_display_name')
                    ->label('Küchendienst')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where(function ($q) use ($search) {
                            $q->where('cook_name', 'like', "%{$search}%")
                              ->orWhereHas('user', function ($q) use ($search) {
                                  $q->where('name', 'like', "%{$search}%");
                              });
                        });
                    })
                    ->badge()
                    ->color(fn ($state): string =>
                        $state === 'Noch offen' ? 'danger' : 'success'
                    ),

                Tables\Columns\TextColumn::make('expected_meals')
                    ->label('Mahlzeiten')
                    ->numeric()
                    ->suffix(' Port.')
                    ->alignCenter(),

                Tables\Columns\IconColumn::make('is_filled')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('notes')
                    ->label('Notizen')
                    ->limit(30)
                    ->tooltip(fn ($state): ?string => strlen($state) > 30 ? $state : null),
            ])
            ->defaultSort('date', 'asc')
            ->filters([
                TernaryFilter::make('is_filled')
                    ->label('Status')
                    ->placeholder('Alle')
                    ->trueLabel('Besetzt')
                    ->falseLabel('Noch offen'),

                Filter::make('upcoming')
                    ->label('Nur zukünftige')
                    ->query(fn (Builder $query): Builder => $query->upcoming())
                    ->default(),

                Filter::make('needs_volunteers')
                    ->label('Helfer benötigt')
                    ->query(fn (Builder $query): Builder => $query->needingVolunteers()),

                Filter::make('current_month')
                    ->label('Aktueller Monat')
                    ->query(fn (Builder $query): Builder =>
                        $query->whereMonth('date', now()->month)
                              ->whereYear('date', now()->year)
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->after(function (LunchShift $record) {
                        $record->updateFilledStatus();
                    }),
                Tables\Actions\DeleteAction::make()
                    ->label('Löschen')
                    ->modalHeading('Küchendienst löschen')
                    ->modalDescription('Soll dieser Tag wirklich gelöscht werden? (z.B. weil kein Mittagstisch stattfindet)')
                    ->modalSubmitActionLabel('Löschen'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                // Quick single day creation
                Tables\Actions\CreateAction::make()
                    ->label('+1 Tag')
                    ->icon('heroicon-o-plus')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['expected_meals'] = $data['expected_meals'] ?? 60;
                        return $data;
                    }),

                Tables\Actions\Action::make('generate_shifts')
                    ->label('Mehrere Tage generieren')
                    ->icon('heroicon-o-calendar-days')
                    ->form([
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Startdatum')
                            ->displayFormat('d.m.Y')
                            ->default(now()->addWeek()->startOfWeek())
                            ->minDate(now())
                            ->required(),

                        Forms\Components\Select::make('duration')
                            ->label('Dauer')
                            ->options([
                                '1_week' => '1 Woche',
                                '2_weeks' => '2 Wochen',
                                '1_month' => '1 Monat',
                                '2_months' => '2 Monate',
                                '3_months' => '3 Monate',
                                'until_date' => 'Bis zu einem bestimmten Datum',
                            ])
                            ->default('1_month')
                            ->reactive()
                            ->required(),

                        Forms\Components\DatePicker::make('end_date')
                            ->label('Enddatum')
                            ->displayFormat('d.m.Y')
                            ->visible(fn (callable $get) => $get('duration') === 'until_date')
                            ->minDate(fn (callable $get) => $get('start_date'))
                            ->required(fn (callable $get) => $get('duration') === 'until_date'),

                        Forms\Components\CheckboxList::make('weekdays')
                            ->label('Wochentage')
                            ->options([
                                '1' => 'Montag',
                                '2' => 'Dienstag',
                                '3' => 'Mittwoch',
                                '4' => 'Donnerstag',
                                '5' => 'Freitag',
                            ])
                            ->default(['1', '2', '3', '4', '5'])
                            ->columns(5)
                            ->required(),

                        Forms\Components\TextInput::make('expected_meals')
                            ->label('Erwartete Mahlzeiten (Standard)')
                            ->numeric()
                            ->default(60)
                            ->minValue(10)
                            ->maxValue(100),
                    ])
                    ->action(function (array $data) {
                        static::generateShifts($data);
                    })
                    ->modalHeading('Küchendienste generieren')
                    ->modalDescription('Erstellt leere Einträge für die ausgewählten Wochentage. Bereits existierende Tage werden übersprungen.')
                    ->modalSubmitActionLabel('Generieren'),

                // Quick actions for common patterns
                Tables\Actions\Action::make('quick_next_week')
                    ->label('Nächste Woche')
                    ->icon('heroicon-o-forward')
                    ->action(function () {
                        $nextMonday = now()->next('Monday');
                        static::generateShifts([
                            'start_date' => $nextMonday,
                            'duration' => '1_week',
                            'weekdays' => ['1', '2', '3', '4', '5'],
                            'expected_meals' => 60
                        ]);
                    })
                    ->requiresConfirmation()
                    ->modalDescription('Erstellt Küchendienste ab nächstem Montag für eine Woche (Mo-Fr).'),

                Tables\Actions\Action::make('quick_next_month')
                    ->label('Nächster Monat')
                    ->icon('heroicon-o-calendar-days')
                    ->action(function () {
                        $nextMonth = now()->addMonth()->startOfMonth();
                        static::generateShifts([
                            'start_date' => $nextMonth,
                            'duration' => '1_month',
                            'weekdays' => ['1', '2', '3', '4', '5'],
                            'expected_meals' => 60
                        ]);
                    })
                    ->requiresConfirmation()
                    ->modalDescription('Erstellt Küchendienste für alle Wochentage des nächsten Monats.'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLunchShifts::route('/'),
            'create' => Pages\CreateLunchShift::route('/create'),
            'edit' => Pages\EditLunchShift::route('/{record}/edit'),
        ];
    }

    /**
     * Generate lunch shifts based on configuration.
     */
    public static function generateShifts(array $config): void
    {
        $weekdays = $config['weekdays'] ?? ['1', '2', '3', '4', '5'];
        $expectedMeals = $config['expected_meals'] ?? 60;
        $startDate = Carbon::parse($config['start_date'])->startOfDay();
        $duration = $config['duration'];

        // Determine end date based on duration
        switch ($duration) {
            case '1_week':
                $endDate = $startDate->copy()->addWeek();
                break;
            case '2_weeks':
                $endDate = $startDate->copy()->addWeeks(2);
                break;
            case '1_month':
                $endDate = $startDate->copy()->addMonth();
                break;
            case '2_months':
                $endDate = $startDate->copy()->addMonths(2);
                break;
            case '3_months':
                $endDate = $startDate->copy()->addMonths(3);
                break;
            case 'until_date':
                $endDate = Carbon::parse($config['end_date'])->endOfDay();
                break;
            default:
                return;
        }

        $current = $startDate->copy();
        $created = 0;
        $skipped = 0;

        while ($current <= $endDate) {
            // Check if this day of week is selected (1=Monday, 5=Friday)
            // Convert dayOfWeekIso to string for comparison
            if (in_array((string)$current->dayOfWeekIso, $weekdays)) {
                try {
                    $result = LunchShift::firstOrCreate([
                        'date' => $current->toDateString(),
                    ], [
                        'is_filled' => false,
                        'expected_meals' => $expectedMeals,
                    ]);

                    if ($result->wasRecentlyCreated) {
                        $created++;
                    } else {
                        $skipped++;
                    }
                } catch (\Exception $e) {
                    // Skip if there's a unique constraint violation
                    $skipped++;
                }
            }

            $current->addDay();
        }

        // Show notification about results
        if ($created > 0) {
            \Filament\Notifications\Notification::make()
                ->title('Küchendienste erstellt')
                ->body("{$created} neue Einträge erstellt" . ($skipped > 0 ? ", {$skipped} existierende übersprungen." : '.'))
                ->success()
                ->send();
        } else {
            \Filament\Notifications\Notification::make()
                ->title('Keine neuen Einträge')
                ->body('Alle ausgewählten Tage existieren bereits.')
                ->warning()
                ->send();
        }
    }
}
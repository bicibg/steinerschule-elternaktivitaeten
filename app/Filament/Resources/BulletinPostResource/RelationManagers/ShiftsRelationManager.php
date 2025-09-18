<?php

namespace App\Filament\Resources\BulletinPostResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShiftsRelationManager extends RelationManager
{
    protected static string $relationship = 'shifts';
    protected static ?string $title = 'Schichten';
    protected static ?string $modelLabel = 'Schicht';
    protected static ?string $pluralModelLabel = 'Schichten';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('role')
                    ->label('Rolle/Aufgabe')
                    ->required()
                    ->placeholder('z.B. Aufbau, Cafeteria, Kinderbetreuung')
                    ->maxLength(255),
                Forms\Components\DatePicker::make('date')
                    ->label('Datum')
                    ->required()
                    ->displayFormat('d.m.Y')
                    ->native(false)
                    ->reactive()
                    ->afterStateHydrated(function ($state, $record, Forms\Set $set) {
                        if ($record && $record->time) {
                            $parsed = self::parseTimeString($record->time);
                            if ($parsed) {
                                $set('date', $parsed['date']);
                            }
                        }
                    })
                    ->afterStateUpdated(fn ($state, Forms\Set $set, Forms\Get $get) =>
                        self::updateTimeField($state, $get, $set)
                    ),
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TimePicker::make('start_time')
                            ->label('Von')
                            ->required()
                            ->displayFormat('H:i')
                            ->seconds(false)
                            ->reactive()
                            ->afterStateHydrated(function ($state, $record, Forms\Set $set) {
                                if ($record && $record->time) {
                                    $parsed = self::parseTimeString($record->time);
                                    if ($parsed) {
                                        $set('start_time', $parsed['start_time']);
                                    }
                                }
                            })
                            ->afterStateUpdated(fn ($state, Forms\Set $set, Forms\Get $get) =>
                                self::updateTimeField($get('date'), $get, $set)
                            ),
                        Forms\Components\TimePicker::make('end_time')
                            ->label('Bis')
                            ->required()
                            ->displayFormat('H:i')
                            ->seconds(false)
                            ->reactive()
                            ->afterStateHydrated(function ($state, $record, Forms\Set $set) {
                                if ($record && $record->time) {
                                    $parsed = self::parseTimeString($record->time);
                                    if ($parsed) {
                                        $set('end_time', $parsed['end_time']);
                                    }
                                }
                            })
                            ->afterStateUpdated(fn ($state, Forms\Set $set, Forms\Get $get) =>
                                self::updateTimeField($get('date'), $get, $set)
                            ),
                    ]),
                Forms\Components\Hidden::make('time')
                    ->dehydrateStateUsing(fn (Forms\Get $get) =>
                        self::formatTimeString($get('date'), $get('start_time'), $get('end_time'))
                    ),
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('needed')
                            ->label('Benötigt')
                            ->numeric()
                            ->required()
                            ->default(1)
                            ->minValue(1),
                        Forms\Components\TextInput::make('filled')
                            ->label('Besetzt')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->helperText('Anzahl der offline/vorregistrierten Personen'),
                    ]),
            ]);
    }

    private static function updateTimeField($date, $get, $set): void
    {
        if ($date && $get('start_time') && $get('end_time')) {
            $timeString = self::formatTimeString($date, $get('start_time'), $get('end_time'));
            $set('time', $timeString);
        }
    }

    private static function formatTimeString($date, $startTime, $endTime): ?string
    {
        if (!$date || !$startTime || !$endTime) {
            return null;
        }

        $dateObj = \Carbon\Carbon::parse($date);
        $dayName = match($dateObj->dayOfWeek) {
            0 => 'Sonntag',
            1 => 'Montag',
            2 => 'Dienstag',
            3 => 'Mittwoch',
            4 => 'Donnerstag',
            5 => 'Freitag',
            6 => 'Samstag',
        };

        return sprintf(
            '%s, %s, %s - %s Uhr',
            $dayName,
            $dateObj->format('d.m.Y'),
            $startTime,
            $endTime
        );
    }

    private static function parseTimeString($timeString): ?array
    {
        if (!$timeString) {
            return null;
        }

        $pattern = '/([A-Za-z]+),\s+(\d{2}\.\d{2}\.\d{4}),\s+(\d{2}:\d{2})\s+-\s+(\d{2}:\d{2})\s+Uhr/';
        if (preg_match($pattern, $timeString, $matches)) {
            return [
                'date' => \Carbon\Carbon::createFromFormat('d.m.Y', $matches[2])->format('Y-m-d'),
                'start_time' => $matches[3],
                'end_time' => $matches[4],
            ];
        }

        return null;
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('role')
            ->columns([
                Tables\Columns\TextColumn::make('role')
                    ->label('Rolle/Aufgabe')
                    ->searchable(),
                Tables\Columns\TextColumn::make('time')
                    ->label('Zeit'),
                Tables\Columns\TextColumn::make('needed')
                    ->label('Benötigt')
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('filled')
                    ->label('Offline-Zusagen')
                    ->badge()
                    ->color(fn ($record) => $record->filled > 0 ? 'warning' : 'gray'),
                Tables\Columns\TextColumn::make('volunteers_count')
                    ->label('Online-Anmeldungen')
                    ->counts('volunteers')
                    ->badge()
                    ->color(fn ($record) => $record->volunteers_count > 0 ? 'info' : 'gray'),
                Tables\Columns\TextColumn::make('total_filled')
                    ->label('Total')
                    ->getStateUsing(fn ($record) => $record->filled + $record->volunteers_count)
                    ->badge()
                    ->color(fn ($record) =>
                        ($record->filled + $record->volunteers_count) >= $record->needed ? 'success' :
                        (($record->filled + $record->volunteers_count) > 0 ? 'warning' : 'danger')
                    ),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Neue Schicht'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Bearbeiten'),
                Tables\Actions\DeleteAction::make()
                    ->label('Löschen'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Ausgewählte löschen'),
                ]),
            ]);
    }
}

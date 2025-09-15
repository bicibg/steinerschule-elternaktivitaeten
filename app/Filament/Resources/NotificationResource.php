<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotificationResource\Pages;
use App\Models\Notification;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class NotificationResource extends Resource
{
    protected static ?string $model = Notification::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell-alert';

    protected static ?string $navigationLabel = 'Benachrichtigungen';

    protected static ?string $navigationGroup = 'Kommunikation';

    protected static ?string $modelLabel = 'Benachrichtigung';

    protected static ?string $pluralModelLabel = 'Benachrichtigungen';

    protected static ?int $navigationSort = 30;

    public static function canViewAny(): bool
    {
        return auth()->user()?->is_super_admin ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->is_super_admin ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()?->is_super_admin ?? false;
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->is_super_admin ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Titel')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('message')
                    ->label('Nachricht')
                    ->required()
                    ->maxLength(300)
                    ->validationMessages([
                        'max' => 'Die Nachricht darf maximal 300 Zeichen lang sein.',
                    ])
                    ->helperText(fn (?string $state): string => strlen($state ?? '') . '/300 Zeichen')
                    ->live()
                    ->rows(4)
                    ->columnSpanFull(),

                Forms\Components\ToggleButtons::make('type')
                    ->label('Typ')
                    ->inline()
                    ->options([
                        'info' => 'Information',
                        'announcement' => 'Ankündigung',
                        'reminder' => 'Erinnerung',
                        'urgent' => 'Dringend',
                    ])
                    ->icons([
                        'info' => 'heroicon-o-information-circle',
                        'announcement' => 'heroicon-o-megaphone',
                        'reminder' => 'heroicon-o-clock',
                        'urgent' => 'heroicon-o-exclamation-triangle',
                    ])
                    ->colors([
                        'info' => 'info',
                        'announcement' => 'primary',
                        'reminder' => 'warning',
                        'urgent' => 'danger',
                    ])
                    ->default('info')
                    ->required(),

                Forms\Components\Toggle::make('is_active')
                    ->label('Aktiv')
                    ->default(true),

                Forms\Components\Toggle::make('is_priority')
                    ->label('Priorität (Immer anzeigen)')
                    ->helperText('Prioritäts-Benachrichtigungen werden immer angezeigt und haben kein automatisches Ablaufdatum')
                    ->default(false)
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                        if (!$state) {
                            // Non-priority: Set default expiry to 14 days from now if not already set
                            if (!$get('expires_at')) {
                                $set('expires_at', now()->addDays(14));
                            }
                        }
                        // When priority is selected, we keep the expiry date if user set it manually
                    }),

                Forms\Components\Section::make('Zeitraum')
                    ->description(fn (Get $get) =>
                        $get('is_priority')
                            ? 'Optional: Prioritäts-Benachrichtigungen laufen standardmäßig nie ab'
                            : 'Normale Benachrichtigungen laufen automatisch nach 14 Tagen ab (anpassbar)'
                    )
                    ->schema([
                        Forms\Components\DateTimePicker::make('starts_at')
                            ->label('Startet am')
                            ->nullable()
                            ->native(false),

                        Forms\Components\DateTimePicker::make('expires_at')
                            ->label('Läuft ab am')
                            ->nullable()
                            ->native(false)
                            ->default(fn () => now()->addDays(14)),
                    ])
                    ->columns(2),

                Forms\Components\Hidden::make('created_by')
                    ->default(fn () => auth()->id()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Titel')
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('type')
                    ->label('Typ')
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'info' => 'Information',
                        'announcement' => 'Ankündigung',
                        'reminder' => 'Erinnerung',
                        'urgent' => 'Dringend',
                        default => $state,
                    })
                    ->colors([
                        'secondary' => 'info',
                        'primary' => 'announcement',
                        'warning' => 'reminder',
                        'danger' => 'urgent',
                    ]),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktiv')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_priority')
                    ->label('Priorität')
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-star')
                    ->trueColor('warning')
                    ->falseColor('gray'),

                Tables\Columns\TextColumn::make('dismissals_count')
                    ->label('Abgewiesen')
                    ->counts('dismissals')
                    ->suffix(' mal'),

                Tables\Columns\TextColumn::make('starts_at')
                    ->label('Start')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Ablauf')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Erstellt')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Typ')
                    ->options([
                        'info' => 'Information',
                        'announcement' => 'Ankündigung',
                        'reminder' => 'Erinnerung',
                        'urgent' => 'Dringend',
                    ]),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Aktiv'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNotifications::route('/'),
            'create' => Pages\CreateNotification::route('/create'),
            'edit' => Pages\EditNotification::route('/{record}/edit'),
        ];
    }
}
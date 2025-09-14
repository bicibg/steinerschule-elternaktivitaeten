<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityResource\Pages;
use App\Filament\Resources\ActivityResource\RelationManagers;
use App\Models\Activity;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ActivityResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Aktivitäten';
    protected static ?string $navigationGroup = 'Aktivitäten';
    protected static ?string $modelLabel = 'Aktivität';
    protected static ?string $pluralModelLabel = 'Aktivitäten';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Aktivitätsinformationen')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Titel')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) =>
                                $operation === 'create' ? $set('slug', Str::slug($state)) : null
                            ),
                        Forms\Components\Hidden::make('slug')
                            ->dehydrateStateUsing(fn ($state, Forms\Get $get) =>
                                $state ?? Str::slug($get('title'))
                            ),
                        Forms\Components\Textarea::make('description')
                            ->label('Beschreibung')
                            ->required()
                            ->rows(8)
                            ->columnSpanFull(),
                        Forms\Components\DateTimePicker::make('end_at')
                            ->label('Endet am')
                            ->displayFormat('d.m.Y H:i'),
                        Forms\Components\TextInput::make('location')
                            ->label('Ort')
                            ->helperText('Optional - leer lassen für allgemeine Aktivitäten'),
                    ]),
                Forms\Components\Section::make('Organisator')
                    ->schema([
                        Forms\Components\TextInput::make('organizer_name')
                            ->label('Name')
                            ->required(),
                        Forms\Components\TextInput::make('organizer_phone')
                            ->label('Telefon')
                            ->tel(),
                        Forms\Components\TextInput::make('organizer_email')
                            ->label('E-Mail')
                            ->email(),
                    ]),
                Forms\Components\Section::make('Einstellungen')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'draft' => 'Entwurf',
                                'published' => 'Veröffentlicht',
                                'archived' => 'Archiviert',
                            ])
                            ->default('draft')
                            ->required(),
                        Forms\Components\Select::make('label')
                            ->label('Kennzeichnung')
                            ->options(\App\Models\Activity::getAvailableLabels())
                            ->placeholder('Keine Kennzeichnung')
                            ->helperText('Nur für Super-Admins sichtbar')
                            ->visible(fn () => auth()->user()?->is_super_admin),
                        Forms\Components\Toggle::make('has_forum')
                            ->label('Diskussionsforum aktivieren'),
                        Forms\Components\Toggle::make('has_shifts')
                            ->label('Schichtplanung aktivieren')
                            ->reactive()
                            ->helperText('Schichten können nach dem Speichern im Tab "Schichten" verwaltet werden'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Titel')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('organizer_name')
                    ->label('Organisator')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_at')
                    ->label('Endet am')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->placeholder('Kein Enddatum'),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'gray' => 'draft',
                        'success' => 'published',
                        'warning' => 'archived',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'draft' => 'Entwurf',
                        'published' => 'Veröffentlicht',
                        'archived' => 'Archiviert',
                        default => $state,
                    }),
                Tables\Columns\BadgeColumn::make('label')
                    ->label('Kennzeichnung')
                    ->colors([
                        'danger' => 'urgent',
                        'warning' => 'important',
                        'info' => 'featured',
                        'gray' => 'last_minute',
                        'primary' => 'help_needed',
                    ])
                    ->formatStateUsing(fn (?string $state): ?string =>
                        $state ? \App\Models\Activity::getAvailableLabels()[$state] ?? null : null
                    )
                    ->visible(fn () => auth()->user()?->is_super_admin),
                Tables\Columns\IconColumn::make('has_forum')
                    ->label('Forum')
                    ->boolean()
                    ->trueIcon('heroicon-o-chat-bubble-left-right')
                    ->falseIcon('heroicon-o-x-circle'),
                Tables\Columns\IconColumn::make('has_shifts')
                    ->label('Schichten')
                    ->boolean()
                    ->trueIcon('heroicon-o-user-group')
                    ->falseIcon('heroicon-o-x-circle'),
                Tables\Columns\TextColumn::make('url')
                    ->label('')
                    ->getStateUsing(fn (Activity $record): string => '')
                    ->url(fn (Activity $record): string =>
                        url("/aktivitaeten/{$record->slug}")
                    )
                    ->openUrlInNewTab()
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->tooltip('Aktivität anzeigen'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ShiftsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivities::route('/'),
            'create' => Pages\CreateActivity::route('/create'),
            'edit' => Pages\EditActivity::route('/{record}/edit'),
        ];
    }
}

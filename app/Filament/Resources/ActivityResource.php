<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityResource\Pages;
use App\Models\Activity;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ActivityResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Elternaktivitäten';
    protected static ?string $navigationGroup = 'Aktivitäten';
    protected static ?string $modelLabel = 'Aktivität';
    protected static ?string $pluralModelLabel = 'Aktivitäten';
    protected static ?int $navigationSort = 2;

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
                            ->rows(8)
                            ->columnSpanFull()
                            ->helperText('Optional - Falls keine Beschreibung vorhanden'),
                        Forms\Components\Select::make('category')
                            ->label('Kategorie')
                            ->options(Activity::getCategories())
                            ->required(),
                        Forms\Components\TextInput::make('meeting_time')
                            ->label('Treffzeiten')
                            ->placeholder('z.B. Jeden Dienstag, 20:00 Uhr'),
                        Forms\Components\TextInput::make('meeting_location')
                            ->label('Treffpunkt')
                            ->placeholder('z.B. Musikzimmer'),
                    ]),
                Forms\Components\Section::make('Kontaktperson')
                    ->schema([
                        Forms\Components\TextInput::make('contact_name')
                            ->label('Name')
                            ->required(),
                        Forms\Components\TextInput::make('contact_email')
                            ->label('E-Mail')
                            ->email(),
                        Forms\Components\TextInput::make('contact_phone')
                            ->label('Telefon')
                            ->tel(),
                    ]),
                Forms\Components\Section::make('Einstellungen')
                    ->schema([
                        Forms\Components\Toggle::make('has_forum')
                            ->label('Diskussionsforum aktivieren')
                            ->default(true),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktiv')
                            ->default(true),
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
                    ->sortable()
                    ->limit(40),
                Tables\Columns\TextColumn::make('contact_name')
                    ->label('Kontakt')
                    ->formatStateUsing(fn ($state) =>
                        implode('<br>', array_filter(explode(' ', $state, 2)))
                    )
                    ->html()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('category')
                    ->label('Kategorie')
                    ->formatStateUsing(fn ($state) => Activity::getCategories()[$state] ?? '-')
                    ->colors([
                        'primary' => 'anlass',
                        'success' => 'haus_umgebung_taskforces',
                        'warning' => 'produktion',
                        'info' => 'organisation',
                        'danger' => 'verkauf',
                        'secondary' => 'paedagogik',
                        'gray' => 'kommunikation',
                    ]),
                Tables\Columns\TextColumn::make('meeting_time')
                    ->label('Treffen')
                    ->placeholder('Keine Angabe'),
                Tables\Columns\IconColumn::make('has_forum')
                    ->label('Forum')
                    ->boolean()
                    ->trueIcon('heroicon-o-chat-bubble-left-right')
                    ->falseIcon('heroicon-o-x-circle'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktiv')
                    ->boolean(),
                Tables\Columns\TextColumn::make('posts_count')
                    ->label('')
                    ->counts('posts')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->tooltip('Anzahl der Forumsbeiträge')
                    ->sortable(),
                Tables\Columns\TextColumn::make('url')
                    ->label('')
                    ->getStateUsing(fn (Activity $record): string => '')
                    ->url(fn (Activity $record): string =>
                        url("/elternaktivitaeten/{$record->slug}")
                    )
                    ->openUrlInNewTab()
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->tooltip('Aktivität anzeigen'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategorie')
                    ->options(Activity::getCategories()),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Aktiv'),
                Tables\Filters\TernaryFilter::make('has_forum')
                    ->label('Forum'),
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
            //
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

    public static function canViewAny(): bool
    {
        return auth()->user()?->is_admin ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->is_admin ?? false;
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->is_admin ?? false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->is_admin ?? false;
    }
}
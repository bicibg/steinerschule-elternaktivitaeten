<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BulletinPostResource\Pages;
use App\Filament\Resources\BulletinPostResource\RelationManagers;
use App\Models\BulletinPost;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class BulletinPostResource extends Resource
{
    protected static ?string $model = BulletinPost::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Pinnwand';
    protected static ?string $navigationGroup = 'Aktivitäten';
    protected static ?string $modelLabel = 'Eintrag';
    protected static ?string $pluralModelLabel = 'Einträge';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Eintrag-Informationen')
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
                        Forms\Components\DateTimePicker::make('start_at')
                            ->label('Beginnt am')
                            ->displayFormat('d.m.Y H:i'),
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
                        Forms\Components\Select::make('category')
                            ->label('Kategorie')
                            ->options(\App\Models\BulletinPost::getAvailableCategories())
                            ->placeholder('Keine Kategorie')
                            ->helperText('Wählen Sie eine Kategorie für diese Aktivität'),
                        Forms\Components\Select::make('label')
                            ->label('Kennzeichnung')
                            ->options(\App\Models\BulletinPost::getAvailableLabels())
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
                    ->sortable()
                    ->limit(40),
                Tables\Columns\TextColumn::make('organizer_name')
                    ->label('Organisator')
                    ->formatStateUsing(fn ($state) =>
                        implode('<br>', array_filter(explode(' ', $state, 2)))
                    )
                    ->html()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('category')
                    ->label('Kategorie')
                    ->formatStateUsing(fn ($state) => \App\Models\BulletinPost::getAvailableCategories()[$state] ?? '-')
                    ->colors([
                        'primary' => 'anlass',
                        'success' => 'haus_umgebung_taskforces',
                        'warning' => 'produktion',
                        'info' => 'organisation',
                        'danger' => 'verkauf',
                    ]),
                Tables\Columns\TextColumn::make('start_at')
                    ->label('Beginnt')
                    ->formatStateUsing(fn ($state) => $state ?
                        $state->format('d.m.Y') . '<br>' .
                        $state->format('H:i') . ' Uhr' : '-'
                    )
                    ->html()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_at')
                    ->label('Endet')
                    ->formatStateUsing(fn ($state) => $state ?
                        $state->format('d.m.Y') . '<br>' .
                        $state->format('H:i') . ' Uhr' : '-'
                    )
                    ->html()
                    ->sortable(),
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
                    ])
                    ->formatStateUsing(fn (?string $state): ?string =>
                        $state ? \App\Models\BulletinPost::getAvailableLabels()[$state] ?? null : null
                    )
                    ->visible(fn () => auth()->user()?->is_super_admin),
                Tables\Columns\TextColumn::make('url')
                    ->label('')
                    ->getStateUsing(fn (BulletinPost $record): string => '')
                    ->url(fn (BulletinPost $record): string =>
                        url("/pinnwand/{$record->slug}")
                    )
                    ->openUrlInNewTab()
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->tooltip('Eintrag anzeigen'),
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
            'index' => Pages\ListBulletinPosts::route('/'),
            'create' => Pages\CreateBulletinPost::route('/create'),
            'edit' => Pages\EditBulletinPost::route('/{record}/edit'),
        ];
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->is_super_admin ?? false;
    }
}

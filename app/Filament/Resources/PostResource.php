<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationLabel = 'Forumbeiträge';
    protected static ?string $navigationGroup = 'Kommunikation';
    protected static ?string $modelLabel = 'Forumbeitrag';
    protected static ?string $pluralModelLabel = 'Forumbeiträge';
    protected static ?int $navigationSort = 20;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Placeholder::make('bulletinPost.title')
                    ->label('Pinnwand-Eintrag')
                    ->content(fn (?\App\Models\Post $record): string => $record?->bulletinPost?->title ?? '-'),
                Forms\Components\Placeholder::make('user.name')
                    ->label('Benutzer')
                    ->content(fn (?\App\Models\Post $record): string => $record?->user?->name ?? '-'),
                Forms\Components\Placeholder::make('body')
                    ->label('Nachricht')
                    ->content(fn (?\App\Models\Post $record): string => $record?->body ?? '-')
                    ->columnSpanFull(),
                Forms\Components\Placeholder::make('ip_hash')
                    ->label('IP-Hash')
                    ->content(fn (?\App\Models\Post $record): string => $record?->ip_hash ?? '-'),
                Forms\Components\Select::make('deletion_reason')
                    ->label('Löschgrund')
                    ->options([
                        'year_archived' => 'Jahresarchivierung',
                        'spam' => 'Spam',
                        'inappropriate' => 'Unangemessen',
                        'user_requested' => 'Auf Anfrage des Benutzers',
                        'duplicate' => 'Duplikat',
                    ])
                    ->nullable()
                    ->visible(fn (?\App\Models\Post $record) => $record?->deleted_at !== null),
                Forms\Components\Placeholder::make('deleted_at')
                    ->label('Gelöscht am')
                    ->content(fn (?\App\Models\Post $record): string => $record?->deleted_at?->format('d.m.Y H:i') ?? '-')
                    ->visible(fn (?\App\Models\Post $record) => $record?->deleted_at !== null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('bulletinPost.title')
                    ->label('Pinnwand-Eintrag')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Autor')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('body')
                    ->label('Nachricht')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Erstellt am')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()
                    ->label('Gelöschte Einträge')
                    ->placeholder('Nur aktive')
                    ->trueLabel('Nur gelöschte')
                    ->falseLabel('Mit gelöschten'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Löschen')
                        ->requiresConfirmation()
                        ->modalHeading('Ausgewählte Beiträge löschen')
                        ->modalDescription('Bitte wählen Sie einen Grund für die Löschung.')
                        ->form([
                            Forms\Components\Select::make('deletion_reason')
                                ->label('Löschgrund')
                                ->options([
                                    'year_archived' => 'Jahresarchivierung',
                                    'spam' => 'Spam',
                                    'inappropriate' => 'Unangemessen',
                                    'user_requested' => 'Auf Anfrage des Benutzers',
                                    'duplicate' => 'Duplikat',
                                ])
                                ->required(),
                        ])
                        ->before(function (\Illuminate\Support\Collection $records, array $data): void {
                            $records->each(function ($record) use ($data) {
                                $record->deletion_reason = $data['deletion_reason'];
                                $record->save();
                            });
                        })
                        ->visible(fn () => auth()->user()?->is_admin ?? false),
                    Tables\Actions\RestoreBulkAction::make()
                        ->label('Wiederherstellen')
                        ->visible(fn () => auth()->user()?->is_admin ?? false),
                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->label('Endgültig löschen')
                        ->visible(fn () => auth()->user()?->is_super_admin ?? false),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->is_admin ?? false;
    }
}

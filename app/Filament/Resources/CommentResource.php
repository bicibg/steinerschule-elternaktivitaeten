<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommentResource\Pages;
use App\Filament\Resources\CommentResource\RelationManagers;
use App\Models\Comment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';
    protected static ?string $navigationLabel = 'Antworten';
    protected static ?string $navigationGroup = 'Kommunikation';
    protected static ?string $slug = 'antworten';
    protected static ?string $modelLabel = 'Antwort';
    protected static ?string $pluralModelLabel = 'Antworten';
    protected static ?int $navigationSort = 21;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('post_id')
                    ->label('Forumbeitrag')
                    ->relationship('post', 'body')
                    ->getOptionLabelFromRecordUsing(fn ($record) => \Str::limit($record->body, 50))
                    ->required()
                    ->searchable(),
                Forms\Components\Placeholder::make('user.name')
                    ->label('Benutzer')
                    ->content(fn (?\App\Models\Comment $record): string => $record?->user?->name ?? '-'),
                Forms\Components\Placeholder::make('body')
                    ->label('Antwort')
                    ->content(fn (?\App\Models\Comment $record): string => $record?->body ?? '-')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('ip_hash')
                    ->label('IP-Hash')
                    ->disabled()
                    ->dehydrated(false),
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
                    ->visible(fn (?\App\Models\Comment $record) => $record?->deleted_at !== null),
                Forms\Components\Placeholder::make('deleted_at')
                    ->label('Gelöscht am')
                    ->content(fn (?\App\Models\Comment $record): string => $record?->deleted_at?->format('d.m.Y H:i') ?? '-')
                    ->visible(fn (?\App\Models\Comment $record) => $record?->deleted_at !== null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('post.body')
                    ->label('Forumbeitrag')
                    ->limit(30)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Autor')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('body')
                    ->label('Antwort')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Erstellt am')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()
                    ->label('Gelöschte Einträge'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn () => auth()->user()?->is_admin ?? false),
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
            'index' => Pages\ListComments::route('/'),
            'create' => Pages\CreateComment::route('/create'),
            'edit' => Pages\EditComment::route('/{record}/edit'),
        ];
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->is_admin ?? false;
    }
}

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
                Forms\Components\Select::make('user_id')
                    ->label('Benutzer')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable(),
                Forms\Components\Textarea::make('body')
                    ->label('Antwort')
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(800),
                Forms\Components\TextInput::make('ip_hash')
                    ->label('IP-Hash')
                    ->disabled()
                    ->dehydrated(false),
                Forms\Components\Toggle::make('is_hidden')
                    ->label('Versteckt')
                    ->default(false),
                Forms\Components\TextInput::make('hidden_reason')
                    ->label('Grund für Verstecken')
                    ->visible(fn (callable $get) => $get('is_hidden')),
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
                Tables\Columns\IconColumn::make('is_hidden')
                    ->label('Versteckt')
                    ->boolean(),
                Tables\Columns\TextColumn::make('hidden_reason')
                    ->label('Versteckungsgrund')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Erstellt am')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_hidden')
                    ->label('Versteckt')
                    ->placeholder('Alle')
                    ->trueLabel('Nur versteckte')
                    ->falseLabel('Nur sichtbare'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn () => auth()->user()?->is_super_admin ?? false),
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
        return auth()->user()?->is_super_admin ?? false;
    }
}

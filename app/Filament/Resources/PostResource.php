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
                Forms\Components\Select::make('bulletin_post_id')
                    ->label('Pinnwand-Eintrag')
                    ->relationship('bulletinPost', 'title')
                    ->required()
                    ->searchable(),
                Forms\Components\Placeholder::make('user.name')
                    ->label('Benutzer')
                    ->content(fn (?\App\Models\Post $record): string => $record?->user?->name ?? '-'),
                Forms\Components\Placeholder::make('body')
                    ->label('Nachricht')
                    ->content(fn (?\App\Models\Post $record): string => $record?->body ?? '-')
                    ->columnSpanFull(),
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
                Tables\Columns\IconColumn::make('is_hidden')
                    ->label('Versteckt')
                    ->boolean(),
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

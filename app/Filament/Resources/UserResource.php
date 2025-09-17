<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use App\Models\UserDeletionLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Benutzer';
    protected static ?string $navigationGroup = 'Super Admin';
    protected static ?string $modelLabel = 'Benutzer';
    protected static ?string $pluralModelLabel = 'Benutzer';
    protected static ?int $navigationSort = 10;

    public static function canViewAny(): bool
    {
        return auth()->user()?->is_super_admin ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->label('E-Mail')
                    ->required(),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->label('Passwort')
                    ->dehydrateStateUsing(fn ($state) => bcrypt($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create'),
                Forms\Components\Toggle::make('is_admin')
                    ->label('Administrator')
                    ->helperText('Administratoren können auf das Admin-Panel zugreifen')
                    ->disabled(fn () => !auth()->user()->is_super_admin)
                    ->dehydrated(fn () => auth()->user()->is_super_admin),
                Forms\Components\Toggle::make('is_super_admin')
                    ->label('Super Administrator')
                    ->helperText('Super-Administratoren können andere Administratoren verwalten')
                    ->visible(fn () => auth()->user()->is_super_admin)
                    ->disabled(fn (?User $record) => $record?->id === auth()->id())
                    ->dehydrated(fn () => auth()->user()->is_super_admin),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->formatStateUsing(function ($state, User $record) {
                        if ($record->isAnonymized()) {
                            return $state . ' (Anonymisiert)';
                        }
                        if ($record->trashed()) {
                            return $state . ' (Deaktiviert)';
                        }
                        return $state;
                    }),
                Tables\Columns\TextColumn::make('email')
                    ->label('E-Mail')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_admin')
                    ->label('Admin')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-circle'),
                Tables\Columns\IconColumn::make('is_super_admin')
                    ->label('Super Admin')
                    ->boolean()
                    ->trueIcon('heroicon-o-shield-check')
                    ->falseIcon('heroicon-o-x-circle')
                    ->visible(fn () => auth()->user()->is_super_admin),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label('Deaktiviert am')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('anonymized_at')
                    ->label('Anonymisiert am')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Erstellt am')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()
                    ->label('Deaktivierte Benutzer'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn (User $record) => !$record->isAnonymized()),

                // Soft Delete (Deactivation) Action
                Action::make('deactivate')
                    ->label('Deaktivieren')
                    ->icon('heroicon-o-no-symbol')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Benutzer deaktivieren')
                    ->modalDescription('Sind Sie sicher, dass Sie diesen Benutzer deaktivieren möchten? Der Benutzer kann sich nicht mehr anmelden, aber alle Daten bleiben erhalten.')
                    ->modalSubmitActionLabel('Ja, deaktivieren')
                    ->visible(fn (User $record) => !$record->trashed() && !$record->isAnonymized() && $record->id !== auth()->id())
                    ->action(function (User $record) {
                        $record->deleted_by = auth()->id();
                        $record->save();
                        $record->delete();

                        UserDeletionLog::logAction($record, 'deactivated');

                        Notification::make()
                            ->title('Benutzer deaktiviert')
                            ->success()
                            ->send();
                    }),

                // Restore Action
                Tables\Actions\RestoreAction::make()
                    ->label('Wiederherstellen')
                    ->requiresConfirmation()
                    ->modalHeading('Benutzer wiederherstellen')
                    ->modalDescription('Möchten Sie diesen Benutzer wiederherstellen? Der Benutzer kann sich wieder anmelden.')
                    ->modalSubmitActionLabel('Ja, wiederherstellen')
                    ->after(function (User $record) {
                        UserDeletionLog::logAction($record, 'reactivated');
                    }),

                // GDPR Anonymization Action
                Action::make('anonymize')
                    ->label('Anonymisieren (DSGVO)')
                    ->icon('heroicon-o-shield-exclamation')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Benutzer anonymisieren (DSGVO)')
                    ->modalDescription('WARNUNG: Diese Aktion kann nicht rückgängig gemacht werden! Alle persönlichen Daten werden dauerhaft anonymisiert.')
                    ->modalSubmitActionLabel('Unwiderruflich anonymisieren')
                    ->visible(fn (User $record) => !$record->isAnonymized() && $record->id !== auth()->id())
                    ->action(function (User $record) {
                        UserDeletionLog::logAction($record, 'anonymized');

                        $record->anonymize(auth()->id());

                        Notification::make()
                            ->title('Benutzer anonymisiert')
                            ->body('Die persönlichen Daten wurden gemäss DSGVO dauerhaft anonymisiert.')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Deaktivieren')
                        ->modalHeading('Ausgewählte Benutzer deaktivieren')
                        ->modalDescription('Die ausgewählten Benutzer werden deaktiviert und können sich nicht mehr anmelden.'),
                    Tables\Actions\RestoreBulkAction::make()
                        ->label('Wiederherstellen'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->is_super_admin ?? false;
    }
}

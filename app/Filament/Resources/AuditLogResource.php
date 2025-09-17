<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuditLogResource\Pages;
use App\Models\AuditLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AuditLogResource extends Resource
{
    protected static ?string $model = AuditLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationLabel = 'Audit-Protokoll';
    protected static ?string $navigationGroup = 'System';
    protected static ?string $modelLabel = 'Audit-Eintrag';
    protected static ?string $pluralModelLabel = 'Audit-Protokoll';
    protected static ?int $navigationSort = 52;

    public static function canCreate(): bool
    {
        return false; // Audit logs should never be manually created
    }

    public static function canEdit($record): bool
    {
        return false; // Audit logs should never be edited
    }

    public static function canDelete($record): bool
    {
        return false; // Audit logs should never be deleted
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->is_admin ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('action_type')
                            ->label('Aktionstyp')
                            ->disabled(),
                        Forms\Components\TextInput::make('action_name')
                            ->label('Aktion')
                            ->disabled(),
                        Forms\Components\TextInput::make('performed_by_name')
                            ->label('Durchgeführt von')
                            ->disabled(),
                        Forms\Components\TextInput::make('ip_address')
                            ->label('IP-Adresse')
                            ->disabled(),
                        Forms\Components\Select::make('severity')
                            ->label('Schweregrad')
                            ->options([
                                'info' => 'Information',
                                'warning' => 'Warnung',
                                'critical' => 'Kritisch',
                            ])
                            ->disabled(),
                        Forms\Components\DateTimePicker::make('created_at')
                            ->label('Zeitpunkt')
                            ->disabled(),
                        Forms\Components\Textarea::make('description')
                            ->label('Beschreibung')
                            ->disabled()
                            ->columnSpanFull(),
                        Forms\Components\KeyValue::make('metadata')
                            ->label('Metadaten')
                            ->disabled()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('action_name')
                    ->label('Aktion')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('action_type')
                    ->label('Typ')
                    ->badge()
                    ->searchable()
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'year_reset' => 'Neues Schuljahr',
                        'user_deletion' => 'Benutzer gelöscht',
                        'bulk_import' => 'Massenimport',
                        'permission_change' => 'Berechtigung geändert',
                        default => $state
                    }),
                Tables\Columns\TextColumn::make('performed_by_name')
                    ->label('Benutzer')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('severity')
                    ->label('Schweregrad')
                    ->colors([
                        'success' => 'info',
                        'warning' => 'warning',
                        'danger' => 'critical',
                    ])
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'info' => 'Information',
                        'warning' => 'Warnung',
                        'critical' => 'Kritisch',
                        default => $state
                    }),
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Zeitpunkt')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('action_type')
                    ->label('Aktionstyp')
                    ->options([
                        'year_reset' => 'Neues Schuljahr',
                        'user_deletion' => 'Benutzer gelöscht',
                        'bulk_import' => 'Massenimport',
                        'permission_change' => 'Berechtigung geändert',
                    ]),
                Tables\Filters\SelectFilter::make('severity')
                    ->label('Schweregrad')
                    ->options([
                        'info' => 'Information',
                        'warning' => 'Warnung',
                        'critical' => 'Kritisch',
                    ]),
                Tables\Filters\Filter::make('critical_only')
                    ->label('Nur kritische')
                    ->query(fn (Builder $query): Builder => $query->where('severity', 'critical')),
                Tables\Filters\Filter::make('last_30_days')
                    ->label('Letzte 30 Tage')
                    ->query(fn (Builder $query): Builder => $query->where('created_at', '>=', now()->subDays(30)))
                    ->default(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // No bulk actions for audit logs
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAuditLogs::route('/'),
            'view' => Pages\ViewAuditLog::route('/{record}'),
        ];
    }
}
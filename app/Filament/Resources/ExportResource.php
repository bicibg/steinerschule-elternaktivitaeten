<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExportResource\Pages;
use App\Filament\Resources\ExportResource\RelationManagers;
use Filament\Actions\Exports\Models\Export;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class ExportResource extends Resource
{
    protected static ?string $model = Export::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-tray';

    protected static ?string $navigationLabel = 'Exporte';

    protected static ?string $navigationGroup = 'Aktivitäten';

    protected static ?string $modelLabel = 'Export';

    protected static ?string $pluralModelLabel = 'Exporte';

    protected static ?int $navigationSort = 50;

    public static function canViewAny(): bool
    {
        return auth()->user()?->is_admin ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('exporter')
                    ->label('Typ')
                    ->formatStateUsing(fn (string $state): string => class_basename($state))
                    ->badge(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Benutzer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_rows')
                    ->label('Zeilen')
                    ->numeric(),
                Tables\Columns\TextColumn::make('successful_rows')
                    ->label('Erfolgreich')
                    ->numeric()
                    ->color(fn ($record) => $record->successful_rows === $record->total_rows ? 'success' : 'warning'),
                Tables\Columns\TextColumn::make('file_name')
                    ->label('Dateiname')
                    ->limit(30),
                Tables\Columns\TextColumn::make('completed_at')
                    ->label('Abgeschlossen')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('completed_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('download_csv')
                    ->label('.csv')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->url(fn (Export $record) => "/filament/exports/{$record->id}/download?format=csv")
                    ->openUrlInNewTab(false)
                    ->visible(fn (Export $record) => $record->completed_at),
                Tables\Actions\Action::make('download_xlsx')
                    ->label('.xlsx')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->url(fn (Export $record) => "/filament/exports/{$record->id}/download?format=xlsx")
                    ->openUrlInNewTab(false)
                    ->visible(fn (Export $record) => $record->completed_at),
                Tables\Actions\DeleteAction::make()
                    ->label('Löschen'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageExports::route('/'),
        ];
    }
}

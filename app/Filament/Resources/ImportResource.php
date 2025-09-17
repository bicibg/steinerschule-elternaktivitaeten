<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ImportResource\Pages;
use App\Filament\Resources\ImportResource\RelationManagers;
use Filament\Actions\Imports\Models\Import;
use Filament\Actions\Imports\Models\FailedImportRow;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class ImportResource extends Resource
{
    protected static ?string $model = Import::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-tray';

    protected static ?string $navigationLabel = '🔒 Importe';

    protected static ?string $navigationGroup = 'Administration';

    protected static ?string $modelLabel = 'Import';

    protected static ?string $pluralModelLabel = 'Importe';

    protected static ?int $navigationSort = 51;

    public static function canViewAny(): bool
    {
        return auth()->user()?->is_super_admin ?? false;
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
                Tables\Columns\TextColumn::make('importer')
                    ->label('Typ')
                    ->formatStateUsing(fn (string $state): string => class_basename($state))
                    ->badge(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Benutzer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('file_name')
                    ->label('Dateiname')
                    ->limit(30),
                Tables\Columns\TextColumn::make('total_rows')
                    ->label('Zeilen gesamt')
                    ->numeric(),
                Tables\Columns\TextColumn::make('successful_rows')
                    ->label('Erfolgreich')
                    ->numeric()
                    ->color(fn ($record) => $record->successful_rows === $record->total_rows ? 'success' : 'warning'),
                Tables\Columns\TextColumn::make('failed_rows_count')
                    ->label('Fehlgeschlagen')
                    ->getStateUsing(fn (Import $record) => $record->getFailedRowsCount())
                    ->numeric()
                    ->color(fn ($record) => $record->getFailedRowsCount() > 0 ? 'danger' : 'gray'),
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
                Tables\Actions\Action::make('downloadFailedRows')
                    ->label('Fehler herunterladen')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('danger')
                    ->action(function (Import $record) {
                        $failedRows = FailedImportRow::where('import_id', $record->id)->get();

                        if ($failedRows->isEmpty()) {
                            \Filament\Notifications\Notification::make()
                                ->title('Keine fehlgeschlagenen Zeilen')
                                ->success()
                                ->send();
                            return;
                        }

                        // Create CSV content
                        $csv = fopen('php://temp', 'w+');

                        // Add headers
                        $firstRow = $failedRows->first();
                        if ($firstRow) {
                            $data = json_decode($firstRow->data, true);
                            fputcsv($csv, array_merge(array_keys($data), ['Fehler']));

                            // Add data rows
                            foreach ($failedRows as $row) {
                                $data = json_decode($row->data, true);
                                $validationErrors = json_decode($row->validation_error, true);
                                $errorMessage = is_array($validationErrors)
                                    ? implode('; ', array_map(fn($errors) => implode(', ', $errors), $validationErrors))
                                    : $row->validation_error;
                                fputcsv($csv, array_merge(array_values($data), [$errorMessage]));
                            }
                        }

                        rewind($csv);
                        $csvContent = stream_get_contents($csv);
                        fclose($csv);

                        return response()->streamDownload(
                            fn () => print($csvContent),
                            'failed_rows_' . $record->id . '.csv',
                            ['Content-Type' => 'text/csv']
                        );
                    })
                    ->visible(fn (Import $record) => $record->getFailedRowsCount() > 0),
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
            'index' => Pages\ManageImports::route('/'),
        ];
    }
}

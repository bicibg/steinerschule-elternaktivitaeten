<?php

namespace App\Filament\Exports;

use App\Models\User;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;

class UserExporter extends Exporter
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('name')
                ->label('Name'),
            ExportColumn::make('email')
                ->label('E-Mail'),
            ExportColumn::make('is_admin')
                ->label('Administrator')
                ->formatStateUsing(fn ($state) => $state ? 'Ja' : 'Nein'),
            ExportColumn::make('is_super_admin')
                ->label('Super Administrator')
                ->formatStateUsing(fn ($state) => $state ? 'Ja' : 'Nein'),
            ExportColumn::make('created_at')
                ->label('Erstellt am')
                ->formatStateUsing(fn ($state) => $state?->format('d.m.Y H:i')),
        ];
    }

    public static function modifyQuery(Builder $query): Builder
    {
        return $query->whereNull('deleted_at')->whereNull('anonymized_at');
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Der Export der Benutzer wurde abgeschlossen. ' . number_format($export->successful_rows) . ' ' . str('Zeile')->plural($export->successful_rows) . ' exportiert.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('Zeile')->plural($failedRowsCount) . ' konnte nicht exportiert werden.';
        }

        return $body;
    }
}
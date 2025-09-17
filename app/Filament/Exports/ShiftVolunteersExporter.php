<?php

namespace App\Filament\Exports;

use App\Models\ShiftVolunteer;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;

class ShiftVolunteersExporter extends Exporter
{
    protected static ?string $model = ShiftVolunteer::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('shift.bulletinPost.title')
                ->label('Aktivität'),
            ExportColumn::make('shift.role')
                ->label('Schicht'),
            ExportColumn::make('shift.time')
                ->label('Zeit'),
            ExportColumn::make('name')
                ->label('Name'),
            ExportColumn::make('email')
                ->label('E-Mail'),
            ExportColumn::make('phone')
                ->label('Telefon'),
            ExportColumn::make('created_at')
                ->label('Angemeldet am')
                ->formatStateUsing(fn ($state) => $state?->format('d.m.Y H:i')),
        ];
    }

    public static function modifyQuery(Builder $query): Builder
    {
        return $query->with(['shift.bulletinPost']);
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Der Export der Schicht-Anmeldungen wurde abgeschlossen. ' . number_format($export->successful_rows) . ' ' . str('Anmeldung')->plural($export->successful_rows) . ' exportiert.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('Anmeldung')->plural($failedRowsCount) . ' fehlgeschlagen.';
        }

        return $body;
    }
}
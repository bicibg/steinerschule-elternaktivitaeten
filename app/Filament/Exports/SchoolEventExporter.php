<?php

namespace App\Filament\Exports;

use App\Models\SchoolEvent;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;

class SchoolEventExporter extends Exporter
{
    protected static ?string $model = SchoolEvent::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('title')
                ->label('Titel'),
            ExportColumn::make('description')
                ->label('Beschreibung'),
            ExportColumn::make('start_date')
                ->label('Startdatum')
                ->formatStateUsing(fn ($state) => $state?->format('d.m.Y')),
            ExportColumn::make('end_date')
                ->label('Enddatum')
                ->formatStateUsing(fn ($state) => $state?->format('d.m.Y')),
            ExportColumn::make('event_time')
                ->label('Uhrzeit'),
            ExportColumn::make('location')
                ->label('Ort'),
            ExportColumn::make('event_type')
                ->label('Veranstaltungstyp')
                ->formatStateUsing(fn ($state) => SchoolEvent::getEventTypes()[$state] ?? $state),
            ExportColumn::make('all_day')
                ->label('Ganztägig')
                ->formatStateUsing(fn ($state) => $state ? 'Ja' : 'Nein'),
            ExportColumn::make('is_recurring')
                ->label('Wiederkehrend')
                ->formatStateUsing(fn ($state) => $state ? 'Ja' : 'Nein'),
            ExportColumn::make('recurrence_pattern')
                ->label('Wiederholungsmuster'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Der Export der Schulveranstaltungen wurde abgeschlossen. ' . number_format($export->successful_rows) . ' ' . str('Zeile')->plural($export->successful_rows) . ' exportiert.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('Zeile')->plural($failedRowsCount) . ' konnte nicht exportiert werden.';
        }

        return $body;
    }
}
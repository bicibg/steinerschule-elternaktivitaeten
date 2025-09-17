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
            ExportColumn::make('date')
                ->label('Datum'),
            ExportColumn::make('event_time')
                ->label('Zeit'),
            ExportColumn::make('location')
                ->label('Ort'),
            ExportColumn::make('event_type')
                ->label('Typ')
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'holiday' => 'Ferien',
                    'event' => 'Anlass',
                    'meeting' => 'Sitzung',
                    'performance' => 'Aufführung',
                    'other' => 'Andere',
                    default => $state,
                }),
            ExportColumn::make('is_recurring')
                ->label('Wiederkehrend')
                ->formatStateUsing(fn (bool $state): string => $state ? 'Ja' : 'Nein'),
            ExportColumn::make('recurrence_pattern')
                ->label('Wiederholungsmuster'),
            ExportColumn::make('target_audience')
                ->label('Zielgruppe'),
            ExportColumn::make('registration_required')
                ->label('Anmeldung erforderlich')
                ->formatStateUsing(fn (bool $state): string => $state ? 'Ja' : 'Nein'),
            ExportColumn::make('registration_link')
                ->label('Anmeldelink'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Der Export der Schulkalender-Einträge wurde abgeschlossen. ' . number_format($export->successful_rows) . ' ' . str('Eintrag')->plural($export->successful_rows) . ' exportiert.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('Eintrag')->plural($failedRowsCount) . ' fehlgeschlagen.';
        }

        return $body;
    }
}
<?php

namespace App\Filament\Imports;

use App\Models\SchoolEvent;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class SchoolEventImporter extends Importer
{
    protected static ?string $model = SchoolEvent::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('title')
                ->label('Titel')
                ->requiredMapping()
                ->example('Sommerfest 2025'),
            ImportColumn::make('description')
                ->label('Beschreibung')
                ->example('Grosses Sommerfest mit Spielen und Verpflegung'),
            ImportColumn::make('date')
                ->label('Datum')
                ->requiredMapping()
                ->example('2025-06-15'),
            ImportColumn::make('event_time')
                ->label('Zeit')
                ->example('14:00'),
            ImportColumn::make('location')
                ->label('Ort')
                ->example('Schulgelände'),
            ImportColumn::make('event_type')
                ->label('Typ')
                ->example('event')
                ->default('event'),
            ImportColumn::make('is_recurring')
                ->label('Wiederkehrend')
                ->boolean()
                ->example('Nein'),
            ImportColumn::make('recurrence_pattern')
                ->label('Wiederholungsmuster')
                ->example('Wöchentlich'),
            ImportColumn::make('target_audience')
                ->label('Zielgruppe')
                ->example('Alle Klassen'),
            ImportColumn::make('registration_required')
                ->label('Anmeldung erforderlich')
                ->boolean()
                ->example('Ja'),
            ImportColumn::make('registration_link')
                ->label('Anmeldelink')
                ->example('https://example.com/anmeldung'),
        ];
    }

    public function resolveRecord(): ?SchoolEvent
    {
        return SchoolEvent::firstOrNew([
            'title' => $this->data['title'],
            'date' => $this->data['date'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Der Import der Schulkalender-Einträge wurde abgeschlossen. ' . number_format($import->successful_rows) . ' ' . str('Eintrag')->plural($import->successful_rows) . ' importiert.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('Eintrag')->plural($failedRowsCount) . ' fehlgeschlagen.';
        }

        return $body;
    }
}
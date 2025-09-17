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
                ->rules(['required', 'max:255'])
                ->example('Sommerfest 2025'),
            ImportColumn::make('description')
                ->label('Beschreibung')
                ->rules(['nullable'])
                ->example('Grosses Sommerfest mit Spielen und Verpflegung'),
            ImportColumn::make('start_date')
                ->label('Startdatum')
                ->requiredMapping()
                ->rules(['required', 'date'])
                ->example('2025-06-15'),
            ImportColumn::make('end_date')
                ->label('Enddatum')
                ->rules(['nullable', 'date', 'after_or_equal:start_date'])
                ->example('2025-06-15'),
            ImportColumn::make('event_time')
                ->label('Uhrzeit')
                ->rules(['nullable', 'max:255'])
                ->example('14:00 Uhr'),
            ImportColumn::make('location')
                ->label('Ort')
                ->rules(['nullable', 'max:255'])
                ->example('Schulgelände'),
            ImportColumn::make('event_type')
                ->label('Veranstaltungstyp')
                ->rules(['in:festival,meeting,performance,holiday,sports,excursion,other'])
                ->example('festival')
                ->castStateUsing(function (string $state): string {
                    // Try to match the German event type names to the keys
                    $types = array_flip(SchoolEvent::getEventTypes());
                    return $types[trim($state)] ?? trim($state);
                }),
            ImportColumn::make('all_day')
                ->label('Ganztägig')
                ->boolean()
                ->example('Ja'),
            ImportColumn::make('is_recurring')
                ->label('Wiederkehrend')
                ->boolean()
                ->example('Nein'),
            ImportColumn::make('recurrence_pattern')
                ->label('Wiederholungsmuster')
                ->rules(['nullable', 'max:255'])
                ->example('Wöchentlich'),
        ];
    }

    public function resolveRecord(): ?SchoolEvent
    {
        // Update existing records by matching title and start_date
        return SchoolEvent::firstOrNew([
            'title' => $this->data['title'],
            'start_date' => $this->data['start_date'],
        ]);
    }

    protected function beforeSave(): void
    {
        // Set default values
        if (!isset($this->data['event_type'])) {
            $this->data['event_type'] = 'other';
        }

        if (!isset($this->data['all_day'])) {
            $this->data['all_day'] = true;
        }

        if (!isset($this->data['is_recurring'])) {
            $this->data['is_recurring'] = false;
        }
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Der Import der Schulveranstaltungen wurde abgeschlossen. ' . number_format($import->successful_rows) . ' ' . str('Zeile')->plural($import->successful_rows) . ' importiert.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('Zeile')->plural($failedRowsCount) . ' konnte nicht importiert werden.';
        }

        return $body;
    }
}
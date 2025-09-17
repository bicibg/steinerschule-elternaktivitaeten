<?php

namespace App\Filament\Imports;

use App\Models\Activity;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Str;

class ActivityImporter extends Importer
{
    protected static ?string $model = Activity::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('title')
                ->label('Titel')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->example('Chor'),
            ImportColumn::make('description')
                ->label('Beschreibung')
                ->rules(['nullable'])
                ->example('Gemeinsames Singen für alle Interessierten'),
            ImportColumn::make('category')
                ->label('Kategorie')
                ->rules(['in:anlass,haus_umgebung_taskforces,produktion,organisation,verkauf,paedagogik,kommunikation'])
                ->example('anlass')
                ->castStateUsing(function (string $state): string {
                    // Try to match the German category names to the keys
                    $categories = array_flip(Activity::getCategories());
                    return $categories[trim($state)] ?? trim($state);
                }),
            ImportColumn::make('contact_name')
                ->label('Kontaktperson')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->example('Max Mustermann'),
            ImportColumn::make('contact_email')
                ->label('E-Mail')
                ->rules(['nullable', 'email'])
                ->example('max@example.com'),
            ImportColumn::make('contact_phone')
                ->label('Telefon')
                ->rules(['nullable', 'max:50'])
                ->example('031 123 45 67'),
            ImportColumn::make('meeting_time')
                ->label('Treffzeiten')
                ->rules(['nullable', 'max:255'])
                ->example('Jeden Dienstag, 20:00 Uhr'),
            ImportColumn::make('meeting_location')
                ->label('Treffpunkt')
                ->rules(['nullable', 'max:255'])
                ->example('Musikzimmer'),
        ];
    }

    public function resolveRecord(): ?Activity
    {
        // Update existing records by matching title
        return Activity::firstOrNew([
            'title' => $this->data['title'],
        ]);
    }

    protected function beforeSave(): void
    {
        // Generate slug if not present
        if (empty($this->record->slug)) {
            $this->record->slug = Str::slug($this->record->title);
        }

        // Set default values
        if (!isset($this->record->is_active)) {
            $this->record->is_active = true;
        }

        if (!isset($this->record->has_forum)) {
            $this->record->has_forum = true;
        }

        if (!isset($this->record->sort_order)) {
            $this->record->sort_order = 0;
        }
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Der Import der Aktivitäten wurde abgeschlossen. ' . number_format($import->successful_rows) . ' ' . str('Zeile')->plural($import->successful_rows) . ' importiert.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('Zeile')->plural($failedRowsCount) . ' konnte nicht importiert werden.';
        }

        return $body;
    }
}

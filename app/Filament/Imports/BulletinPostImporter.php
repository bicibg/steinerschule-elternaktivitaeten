<?php

namespace App\Filament\Imports;

use App\Models\BulletinPost;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Str;

class BulletinPostImporter extends Importer
{
    protected static ?string $model = BulletinPost::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('title')
                ->label('Titel')
                ->requiredMapping()
                ->example('Weihnachtsbazar 2025'),
            ImportColumn::make('description')
                ->label('Beschreibung')
                ->requiredMapping()
                ->example('Großer Weihnachtsbazar mit vielen Ständen'),
            ImportColumn::make('start_at')
                ->label('Beginnt am')
                ->example('2025-12-15 10:00:00'),
            ImportColumn::make('end_at')
                ->label('Endet am')
                ->example('2025-12-15 18:00:00'),
            ImportColumn::make('location')
                ->label('Ort')
                ->example('Große Halle'),
            ImportColumn::make('contact_name')
                ->label('Kontakt Name')
                ->requiredMapping()
                ->example('Maria Müller'),
            ImportColumn::make('contact_phone')
                ->label('Kontakt Telefon')
                ->example('079 123 45 67'),
            ImportColumn::make('contact_email')
                ->label('Kontakt E-Mail')
                ->example('maria.mueller@example.com'),
            ImportColumn::make('status')
                ->label('Status')
                ->example('published')
                ->default('draft'),
            ImportColumn::make('category')
                ->label('Kategorie')
                ->example('anlass'),
            ImportColumn::make('label')
                ->label('Markierung')
                ->example('important'),
            ImportColumn::make('has_forum')
                ->label('Forum aktiv')
                ->boolean()
                ->example('Ja'),
            ImportColumn::make('has_shifts')
                ->label('Schichten aktiv')
                ->boolean()
                ->example('Ja'),
        ];
    }

    public function resolveRecord(): ?BulletinPost
    {
        $bulletinPost = BulletinPost::firstOrNew([
            'title' => $this->data['title'],
        ]);

        // Generate slug if it's a new record
        if (!$bulletinPost->exists) {
            $bulletinPost->slug = Str::slug($this->data['title']);
        }

        return $bulletinPost;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Der Import der Pinnwand-Einträge wurde abgeschlossen. ' . number_format($import->successful_rows) . ' ' . str('Eintrag')->plural($import->successful_rows) . ' importiert.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('Eintrag')->plural($failedRowsCount) . ' fehlgeschlagen.';
        }

        return $body;
    }
}
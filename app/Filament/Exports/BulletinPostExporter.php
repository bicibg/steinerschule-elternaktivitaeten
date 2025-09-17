<?php

namespace App\Filament\Exports;

use App\Models\BulletinPost;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;

class BulletinPostExporter extends Exporter
{
    protected static ?string $model = BulletinPost::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('title')
                ->label('Titel'),
            ExportColumn::make('description')
                ->label('Beschreibung'),
            ExportColumn::make('start_at')
                ->label('Beginnt am'),
            ExportColumn::make('end_at')
                ->label('Endet am'),
            ExportColumn::make('location')
                ->label('Ort'),
            ExportColumn::make('contact_name')
                ->label('Kontakt Name'),
            ExportColumn::make('contact_phone')
                ->label('Kontakt Telefon'),
            ExportColumn::make('contact_email')
                ->label('Kontakt E-Mail'),
            ExportColumn::make('status')
                ->label('Status')
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'draft' => 'Entwurf',
                    'published' => 'Veröffentlicht',
                    'archived' => 'Archiviert',
                    default => $state,
                }),
            ExportColumn::make('category')
                ->label('Kategorie')
                ->formatStateUsing(fn (?string $state): string =>
                    $state ? (BulletinPost::getAvailableCategories()[$state] ?? '-') : '-'
                ),
        ];
    }

    public static function modifyQuery(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Der Export der Pinnwand-Einträge wurde abgeschlossen. ' . number_format($export->successful_rows) . ' ' . str('Eintrag')->plural($export->successful_rows) . ' exportiert.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('Eintrag')->plural($failedRowsCount) . ' fehlgeschlagen.';
        }

        return $body;
    }
}
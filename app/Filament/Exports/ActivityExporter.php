<?php

namespace App\Filament\Exports;

use App\Models\Activity;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;

class ActivityExporter extends Exporter
{
    protected static ?string $model = Activity::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('title')
                ->label('Titel'),
            ExportColumn::make('description')
                ->label('Beschreibung'),
            ExportColumn::make('category')
                ->label('Kategorie')
                ->formatStateUsing(fn ($state) => Activity::getCategories()[$state] ?? $state),
            ExportColumn::make('contact_name')
                ->label('Kontaktperson'),
            ExportColumn::make('contact_email')
                ->label('E-Mail'),
            ExportColumn::make('contact_phone')
                ->label('Telefon'),
            ExportColumn::make('meeting_time')
                ->label('Treffzeiten'),
            ExportColumn::make('meeting_location')
                ->label('Treffpunkt'),
        ];
    }

    public static function modifyQuery(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Der Export der Aktivitäten wurde abgeschlossen. ' . number_format($export->successful_rows) . ' ' . str('Zeile')->plural($export->successful_rows) . ' exportiert.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('Zeile')->plural($failedRowsCount) . ' konnte nicht exportiert werden.';
        }

        return $body;
    }
}

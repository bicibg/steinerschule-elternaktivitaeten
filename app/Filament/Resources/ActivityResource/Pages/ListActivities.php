<?php

namespace App\Filament\Resources\ActivityResource\Pages;

use App\Filament\Exports\ActivityExporter;
use App\Filament\Imports\ActivityImporter;
use App\Filament\Resources\ActivityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListActivities extends ListRecords
{
    protected static string $resource = ActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ImportAction::make()
                ->label('Importieren')
                ->importer(ActivityImporter::class)
                ->icon('heroicon-o-arrow-down-tray'),
            Actions\ExportAction::make()
                ->label('Exportieren')
                ->exporter(ActivityExporter::class)
                ->icon('heroicon-o-arrow-up-tray'),
            Actions\CreateAction::make()
                ->label('Neue Aktivität'),
        ];
    }
}

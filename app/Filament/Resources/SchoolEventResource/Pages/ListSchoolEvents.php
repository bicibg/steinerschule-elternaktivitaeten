<?php

namespace App\Filament\Resources\SchoolEventResource\Pages;

use App\Filament\Exports\SchoolEventExporter;
use App\Filament\Imports\SchoolEventImporter;
use App\Filament\Resources\SchoolEventResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSchoolEvents extends ListRecords
{
    protected static string $resource = SchoolEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ImportAction::make()
                ->label('Importieren')
                ->importer(SchoolEventImporter::class)
                ->icon('heroicon-o-arrow-down-tray'),
            Actions\ExportAction::make()
                ->label('Exportieren')
                ->exporter(SchoolEventExporter::class)
                ->icon('heroicon-o-arrow-up-tray'),
            Actions\CreateAction::make(),
        ];
    }
}

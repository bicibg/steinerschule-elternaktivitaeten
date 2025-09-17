<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Exports\UserExporter;
use App\Filament\Imports\UserImporter;
use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ImportAction::make()
                ->label('Importieren')
                ->importer(UserImporter::class)
                ->icon('heroicon-o-arrow-down-tray'),
            Actions\ExportAction::make()
                ->label('Exportieren')
                ->exporter(UserExporter::class)
                ->icon('heroicon-o-arrow-up-tray'),
            Actions\CreateAction::make()
                ->label('Neuer Benutzer'),
        ];
    }
}

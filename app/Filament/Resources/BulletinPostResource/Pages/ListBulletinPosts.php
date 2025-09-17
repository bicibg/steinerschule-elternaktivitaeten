<?php

namespace App\Filament\Resources\BulletinPostResource\Pages;

use App\Filament\Exports\BulletinPostExporter;
use App\Filament\Imports\BulletinPostImporter;
use App\Filament\Resources\BulletinPostResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBulletinPosts extends ListRecords
{
    protected static string $resource = BulletinPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ImportAction::make()
                ->label('Importieren')
                ->importer(BulletinPostImporter::class)
                ->icon('heroicon-o-arrow-down-tray'),
            Actions\ExportAction::make()
                ->label('Exportieren')
                ->exporter(BulletinPostExporter::class)
                ->icon('heroicon-o-arrow-up-tray'),
            Actions\CreateAction::make()
                ->label('Neuer Eintrag'),
        ];
    }
}
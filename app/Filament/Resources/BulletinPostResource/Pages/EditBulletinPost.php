<?php

namespace App\Filament\Resources\BulletinPostResource\Pages;

use App\Filament\Exports\ShiftVolunteersExporter;
use App\Filament\Resources\BulletinPostResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBulletinPost extends EditRecord
{
    protected static string $resource = BulletinPostResource::class;

    protected static ?string $title = 'Eintrag bearbeiten';

    protected function getHeaderActions(): array
    {
        return [
            Actions\ExportAction::make()
                ->label('Helfer exportieren')
                ->exporter(ShiftVolunteersExporter::class)
                ->icon('heroicon-o-arrow-up-tray')
                ->modifyQueryUsing(function ($query) {
                    return $query->whereHas('shift', function ($q) {
                        $q->where('bulletin_post_id', $this->record->id);
                    });
                })
                ->visible(fn () => $this->record->has_shifts),
            Actions\DeleteAction::make()
                ->label('Löschen'),
        ];
    }
}
<?php

namespace App\Filament\Resources\BulletinPostResource\Pages;

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
            Actions\DeleteAction::make()
                ->label('Löschen'),
        ];
    }
}
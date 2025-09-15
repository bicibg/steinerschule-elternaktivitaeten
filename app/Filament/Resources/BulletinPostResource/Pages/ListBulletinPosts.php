<?php

namespace App\Filament\Resources\BulletinPostResource\Pages;

use App\Filament\Resources\BulletinPostResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBulletinPosts extends ListRecords
{
    protected static string $resource = BulletinPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Neuer Eintrag'),
        ];
    }
}
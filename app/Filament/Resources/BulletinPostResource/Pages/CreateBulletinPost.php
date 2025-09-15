<?php

namespace App\Filament\Resources\BulletinPostResource\Pages;

use App\Filament\Resources\BulletinPostResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBulletinPost extends CreateRecord
{
    protected static string $resource = BulletinPostResource::class;

    protected static ?string $title = 'Eintrag erstellen';
}

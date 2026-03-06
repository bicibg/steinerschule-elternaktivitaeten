<?php

namespace App\Filament\Resources\ActivityResource\Pages;

use App\Filament\Resources\ActivityResource;
use App\Filament\Resources\BulletinPostResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditActivity extends EditRecord
{
    protected static string $resource = ActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('createBulletinPost')
                ->label('Pinnwand-Eintrag erstellen')
                ->icon('heroicon-o-plus-circle')
                ->color('success')
                ->url(fn () => BulletinPostResource::getUrl('create', ['activity_id' => $this->record->id])),
            Actions\DeleteAction::make(),
        ];
    }
}

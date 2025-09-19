<?php

namespace App\Filament\Resources\LunchShiftResource\Pages;

use App\Filament\Resources\LunchShiftResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLunchShift extends EditRecord
{
    protected static string $resource = LunchShiftResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

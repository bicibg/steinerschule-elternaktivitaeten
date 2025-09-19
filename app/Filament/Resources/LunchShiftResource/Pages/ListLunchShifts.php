<?php

namespace App\Filament\Resources\LunchShiftResource\Pages;

use App\Filament\Resources\LunchShiftResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLunchShifts extends ListRecords
{
    protected static string $resource = LunchShiftResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Remove default create action - it's now in the table header
        ];
    }
}

<?php

namespace App\Filament\Resources\SchoolEventResource\Pages;

use App\Filament\Exports\SchoolEventExporter;
use App\Filament\Imports\SchoolEventImporter;
use App\Filament\Resources\SchoolEventResource;
use App\Services\IcsImportService;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;

class ListSchoolEvents extends ListRecords
{
    protected static string $resource = SchoolEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('importIcs')
                ->label('ICS importieren')
                ->icon('heroicon-o-calendar-days')
                ->color('success')
                ->form([
                    FileUpload::make('ics_file')
                        ->label('ICS-Datei')
                        ->disk('public')
                        ->directory('ics-imports')
                        ->acceptedFileTypes(['text/calendar', '.ics'])
                        ->required()
                        ->helperText('Kalender-Datei (.ics) aus dem Schulkalender-Export hochladen.'),
                ])
                ->action(function (array $data) {
                    $disk = Storage::disk('public');
                    $filePath = $disk->path($data['ics_file']);

                    $service = new IcsImportService;
                    $results = $service->import($filePath);

                    $disk->delete($data['ics_file']);

                    $message = "{$results['created']} neue Termine importiert.";
                    if ($results['updated'] > 0) {
                        $message .= " {$results['updated']} bestehende aktualisiert.";
                    }
                    if ($results['skipped'] > 0) {
                        $message .= " {$results['skipped']} übersprungen.";
                    }

                    Notification::make()
                        ->title('ICS-Import abgeschlossen')
                        ->body($message)
                        ->success()
                        ->send();
                }),
            Actions\ImportAction::make()
                ->label('CSV importieren')
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

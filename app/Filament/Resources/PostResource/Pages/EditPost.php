<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\RestoreAction::make()
                ->visible(fn ($record) => $record->trashed() && auth()->user()?->is_admin),
            Actions\ForceDeleteAction::make()
                ->visible(fn ($record) => $record->trashed() && auth()->user()?->is_super_admin),
            Actions\DeleteAction::make()
                ->requiresConfirmation()
                ->modalHeading('Forumbeitrag löschen')
                ->modalDescription('Bitte wählen Sie einen Grund für die Löschung.')
                ->form([
                    Forms\Components\Select::make('deletion_reason')
                        ->label('Löschgrund')
                        ->options([
                            'year_archived' => 'Jahresarchivierung',
                            'spam' => 'Spam',
                            'inappropriate' => 'Unangemessen',
                            'user_requested' => 'Auf Anfrage des Benutzers',
                            'duplicate' => 'Duplikat',
                        ])
                        ->required(),
                ])
                ->before(function ($record, array $data): void {
                    $record->deletion_reason = $data['deletion_reason'];
                    $record->save();
                })
                ->visible(fn ($record) => !$record->trashed() && auth()->user()?->is_admin),
        ];
    }
}

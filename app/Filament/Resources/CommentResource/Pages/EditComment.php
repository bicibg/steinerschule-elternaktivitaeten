<?php

namespace App\Filament\Resources\CommentResource\Pages;

use App\Filament\Resources\CommentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms;

class EditComment extends EditRecord
{
    protected static string $resource = CommentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->requiresConfirmation()
                ->modalHeading('Antwort löschen')
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
                ->visible(fn () => auth()->user()?->is_admin ?? false),
        ];
    }
}

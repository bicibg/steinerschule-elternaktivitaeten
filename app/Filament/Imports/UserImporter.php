<?php

namespace App\Filament\Imports;

use App\Models\User;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Hash;

class UserImporter extends Importer
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->label('Name')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->example('Max Mustermann'),
            ImportColumn::make('email')
                ->label('E-Mail')
                ->requiredMapping()
                ->rules(['required', 'email', 'max:255'])
                ->example('max.mustermann@example.com'),
            ImportColumn::make('phone')
                ->label('Telefon')
                ->rules(['nullable', 'max:50'])
                ->example('079 123 45 67'),
            ImportColumn::make('password')
                ->label('Passwort')
                ->rules(['nullable', 'min:8'])
                ->example('12345678')
                ->helperText('Wird automatisch generiert, falls leer gelassen'),
            ImportColumn::make('is_admin')
                ->label('Administrator')
                ->boolean()
                ->example('Nein'),
            ImportColumn::make('is_super_admin')
                ->label('Super Administrator')
                ->boolean()
                ->example('Nein'),
        ];
    }

    public function resolveRecord(): ?User
    {
        // Find existing user by email (including soft deleted)
        $user = User::withTrashed()->firstOrNew([
            'email' => $this->data['email'],
        ]);

        return $user;
    }

    protected function beforeSave(): void
    {
        // Set default values
        if (!isset($this->data['is_admin'])) {
            $this->data['is_admin'] = false;
        }

        if (!isset($this->data['is_super_admin'])) {
            $this->data['is_super_admin'] = false;
        }

        // Generate or hash password
        if (empty($this->data['password'])) {
            $this->data['password'] = '12345678'; // Default password
        }

        // Hash the password if it's not already hashed (for new users or password updates)
        if (!$this->record->exists || !empty($this->data['password'])) {
            $this->data['password'] = Hash::make($this->data['password']);
        }

        // If the user was soft deleted, restore them
        if ($this->record->trashed()) {
            $this->record->restore();
        }
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Der Import der Benutzer wurde abgeschlossen. ' . number_format($import->successful_rows) . ' ' . str('Zeile')->plural($import->successful_rows) . ' importiert.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('Zeile')->plural($failedRowsCount) . ' konnte nicht importiert werden.';
        }

        return $body;
    }
}
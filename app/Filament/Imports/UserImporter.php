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
                ->example('Max Mustermann'),
            ImportColumn::make('email')
                ->label('E-Mail')
                ->requiredMapping()
                ->rules(['email', 'unique:users,email'])
                ->example('max.mustermann@example.com'),
            ImportColumn::make('phone')
                ->label('Telefon')
                ->example('079 123 45 67'),
            ImportColumn::make('password')
                ->label('Passwort')
                ->example('geheim123')
                ->default('12345678'),
            ImportColumn::make('is_admin')
                ->label('Admin')
                ->boolean()
                ->example('Nein')
                ->default(false),
            ImportColumn::make('is_super_admin')
                ->label('Super Admin')
                ->boolean()
                ->example('Nein')
                ->default(false),
        ];
    }

    public function resolveRecord(): ?User
    {
        $user = User::firstOrNew([
            'email' => $this->data['email'],
        ]);

        // Hash password if it's a new user or password is provided
        if (!$user->exists || !empty($this->data['password'])) {
            $this->data['password'] = Hash::make($this->data['password'] ?? '12345678');
        } else {
            unset($this->data['password']);
        }

        return $user;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Der Import der Benutzer wurde abgeschlossen. ' . number_format($import->successful_rows) . ' ' . str('Benutzer')->plural($import->successful_rows) . ' importiert.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('Benutzer')->plural($failedRowsCount) . ' fehlgeschlagen.';
        }

        return $body;
    }
}
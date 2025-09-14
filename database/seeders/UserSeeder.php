<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin user
        User::create([
            'name' => 'Bugra Ergin',
            'email' => 'bugraergin@gmail.com',
            'password' => Hash::make('123456789'),
            'is_admin' => true,
            'is_super_admin' => true,
        ]);

        // Demo user
        User::create([
            'name' => 'Demo User',
            'email' => 'demo@example.com',
            'password' => Hash::make('demo123456'),
            'is_admin' => false,
        ]);

        // Sample parent users
        $users = [
            ['name' => 'Anna Schmidt', 'email' => 'anna.schmidt@example.com'],
            ['name' => 'Peter Müller', 'email' => 'peter.mueller@example.com'],
            ['name' => 'Maria Weber', 'email' => 'maria.weber@example.com'],
            ['name' => 'Thomas Fischer', 'email' => 'thomas.fischer@example.com'],
            ['name' => 'Lisa Meier', 'email' => 'lisa.meier@example.com'],
            ['name' => 'Michael Wagner', 'email' => 'michael.wagner@example.com'],
            ['name' => 'Sandra Koch', 'email' => 'sandra.koch@example.com'],
            ['name' => 'Stefan Bauer', 'email' => 'stefan.bauer@example.com'],
        ];

        foreach ($users as $userData) {
            User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make('password123'),
            ]);
        }
    }
}

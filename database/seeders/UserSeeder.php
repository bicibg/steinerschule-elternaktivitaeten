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
        // Demo user
        User::create([
            'name' => 'Demo User',
            'email' => 'demo@example.com',
            'password' => Hash::make('demo123456'),
        ]);

        // Sample parent users
        $users = [
            ['name' => 'Anna Schmidt (2a)', 'email' => 'anna.schmidt@example.com'],
            ['name' => 'Peter Müller (3b)', 'email' => 'peter.mueller@example.com'],
            ['name' => 'Maria Weber (1a)', 'email' => 'maria.weber@example.com'],
            ['name' => 'Thomas Fischer (4c)', 'email' => 'thomas.fischer@example.com'],
            ['name' => 'Lisa Meier (2b)', 'email' => 'lisa.meier@example.com'],
            ['name' => 'Michael Wagner (5a)', 'email' => 'michael.wagner@example.com'],
            ['name' => 'Sandra Koch (3a)', 'email' => 'sandra.koch@example.com'],
            ['name' => 'Stefan Bauer (1b)', 'email' => 'stefan.bauer@example.com'],
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
<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Bugra Ergin',
            'email' => 'bugraergin@gmail.com',
            'password' => Hash::make('123456789'),
            'is_admin' => true,
            'is_super_admin' => true,
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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

        // All contact persons from activities and bulletin posts
        $contacts = [
            // Activity contacts
            'Julia Winkler',
            'Julia Eisenhut',
            'Matthias Rytz',
            'Maria Mani',
            'Selina Lüchiger',
            'Bylie Beese',
            'Anna Stalder',
            'Linda Denissen',
            'Yael Stanca',
            'Swenja Heyers',
            'Yves Bönzli',
            'Susann Glättli',
            'Hans Baumgartner',
            'Ioana Wigger',
            'Katharina Baumgartner',
            'Céline Zaugg',
            'Sami Eisenhut',
            'Tinu Brenner',
            'Tom Schick',
            'Susanne Marienfeld',
            'Rene Winkler',
            'Manila Dür',
            'Elsa Zürcher Ledermann',
            'Claudia Pereira',
            'Matthias Frey',
            'Manuela Tschanz',
            'Christian Konopka',
            'Tatjana Baumgartner',
            'Rebekka Schaerer',
            'Marianne Wey',
            'Sandra Lanz',
            'Heinz Ledermann',
            'Tamás Mokos',
            'Marisa Frey',
            'Matthias Hartmann',
            'Christa Aeschlimann',
            'Christian Brendle',
            'Daniela Wüthrich',
            'Gisela Wyss',
            'Rémy Reist',
            // Bulletin post contacts (not already in activities)
            'Stefan Berger',
            'Elisabeth Keller',
            'Daniel Moser',
            'Monika Schmid',
            'Claudia Baumgartner',
            'Regula Fischer',
            'Thomas Roth',
            'Andreas Hofmann',
        ];

        foreach ($contacts as $name) {
            $email = Str::slug($name, '.').'@example.com';

            User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make('password123'),
            ]);
        }
    }
}

<?php

namespace Database\Factories;

use App\Models\BulletinPost;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shift>
 */
class ShiftFactory extends Factory
{
    public function definition(): array
    {
        $date = $this->faker->dateTimeBetween('now', '+2 months');

        return [
            'bulletin_post_id' => BulletinPost::factory(),
            'role' => $this->faker->randomElement(['Aufbau', 'Abbau', 'Küche', 'Kasse', 'Betreuung']),
            'time' => $date->format('d.m.Y') . ', 09:00 - 12:00 Uhr',
            'needed' => $this->faker->numberBetween(2, 10),
            'offline_filled' => 0,
        ];
    }
}

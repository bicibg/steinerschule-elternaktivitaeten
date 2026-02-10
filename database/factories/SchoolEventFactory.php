<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SchoolEvent>
 */
class SchoolEventFactory extends Factory
{
    public function definition(): array
    {
        $title = $this->faker->words(3, true);

        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . Str::random(6),
            'description' => $this->faker->sentence(),
            'start_date' => $this->faker->dateTimeBetween('now', '+3 months'),
            'end_date' => null,
            'location' => 'Steinerschule Bern',
            'event_type' => $this->faker->randomElement(['festival', 'meeting', 'performance', 'holiday', 'sports']),
            'all_day' => true,
            'is_recurring' => false,
        ];
    }

    public function holiday(): static
    {
        return $this->state(fn (array $attributes) => [
            'event_type' => 'holiday',
            'all_day' => true,
        ]);
    }
}

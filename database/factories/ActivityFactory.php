<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Activity>
 */
class ActivityFactory extends Factory
{
    public function definition(): array
    {
        $title = $this->faker->words(3, true);

        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.Str::random(6),
            'description' => $this->faker->paragraph(),
            'category' => $this->faker->randomElement(['anlass', 'haus_umgebung_taskforces', 'produktion', 'organisation', 'verkauf']),
            'contact_name' => $this->faker->name(),
            'contact_email' => $this->faker->safeEmail(),
            'has_forum' => false,
            'is_active' => true,
        ];
    }

    public function withForum(): static
    {
        return $this->state(fn (array $attributes) => [
            'has_forum' => true,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BulletinPost>
 */
class BulletinPostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence(3);

        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . Str::random(6),
            'description' => $this->faker->paragraph(),
            'participation_note' => 'Bitte melden Sie sich an, wenn Sie teilnehmen möchten.',
            'start_at' => $this->faker->dateTimeBetween('now', '+1 month'),
            'end_at' => $this->faker->dateTimeBetween('+1 month', '+2 months'),
            'location' => 'Steinerschule Bern',
            'contact_name' => $this->faker->name(),
            'contact_phone' => $this->faker->phoneNumber(),
            'contact_email' => $this->faker->email(),
            'status' => 'published',
            'category' => 'anlass',
            'activity_type' => 'shift_based',
            'recurring_pattern' => null,
            'show_in_calendar' => true,
            'edit_token' => Str::random(64),
            'has_forum' => false,
            'has_shifts' => true,
            'label' => 'label-yellow',
        ];
    }

    /**
     * Indicate that the bulletin post has forum enabled.
     */
    public function withForum(): static
    {
        return $this->state(fn (array $attributes) => [
            'has_forum' => true,
        ]);
    }

    /**
     * Indicate that the bulletin post has no shifts.
     */
    public function withoutShifts(): static
    {
        return $this->state(fn (array $attributes) => [
            'has_shifts' => false,
        ]);
    }
}
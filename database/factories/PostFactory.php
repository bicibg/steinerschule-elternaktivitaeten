<?php

namespace Database\Factories;

use App\Models\BulletinPost;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'bulletin_post_id' => BulletinPost::factory(),
            'user_id' => User::factory(),
            'body' => $this->faker->paragraph(),
            'ip_hash' => hash('sha256', $this->faker->ipv4()),
        ];
    }

    public function anonymous(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => null,
        ]);
    }
}

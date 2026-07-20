<?php

namespace Database\Factories;

use App\Enums\PostVisibility;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    public function definition(): array
    {
        $title = fake('ru_RU')->unique()->realText(70);

        return [
            'user_id' => User::factory(),
            'title' => $title,
            'slug' => Str::slug($title).'-'.Str::lower(Str::random(6)),
            'content' => collect(range(1, 6))
                ->map(fn (): string => fake('ru_RU')->realText(500))
                ->implode("\n\n"),
            'visibility' => fake()->randomElement(PostVisibility::cases()),
            'published_at' => fake()->dateTimeBetween('-6 months', 'now'),
        ];
    }
}

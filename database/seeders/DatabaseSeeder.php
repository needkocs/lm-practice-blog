<?php

namespace Database\Seeders;

use App\Enums\AccessRequestStatus;
use App\Enums\PostVisibility;
use App\Models\Follow;
use App\Models\Post;
use App\Models\PostAccessRequest;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $demoUser = User::factory()->create([
            'name' => 'Демо Пользователь',
            'email' => 'demo@example.com',
            'password' => Hash::make('password'),
        ]);

        $users = User::factory(10)->create()->prepend($demoUser);

        $tagNames = ['#лайфстайл', '#повседневность', '#настроение', '#блогер', '#этотдень'];
        $tags = collect($tagNames)->map(fn (string $name): Tag => Tag::query()->create([
            'name' => $name,
            'slug' => Str::slug($name),
        ]));

        $posts = collect();

        foreach (range(1, 55) as $index) {
            $title = fake('ru_RU')->unique()->realText(70);
            $posts->push(Post::query()->create([
                'user_id' => $users->random()->id,
                'title' => $title,
                'slug' => Str::slug($title).'-'.$index,
                'content' => collect(range(1, 7))
                    ->map(fn (): string => fake('ru_RU')->realText(500))
                    ->implode("\n\n"),
                'visibility' => $index % 4 === 0 ? PostVisibility::RequestOnly : PostVisibility::Public,
                'published_at' => now()->subDays(60 - $index),
            ]));
        }

        $posts->each(fn (Post $post) => $post->tags()->sync($tags->random(fake()->numberBetween(1, 4))->pluck('id')));

        foreach ($users as $follower) {
            $users->where('id', '!=', $follower->id)
                ->random(min(4, $users->count() - 1))
                ->each(fn (User $followed) => Follow::query()->firstOrCreate([
                    'follower_id' => $follower->id,
                    'followed_id' => $followed->id,
                ]));
        }

        $privatePosts = $posts->where('visibility', PostVisibility::RequestOnly);

        foreach ($privatePosts->take(12) as $post) {
            $requester = $users->where('id', '!=', $post->user_id)->random();
            PostAccessRequest::query()->firstOrCreate(
                ['post_id' => $post->id, 'user_id' => $requester->id],
                [
                    'message' => fake('ru_RU')->optional()->realText(120),
                    'status' => fake()->randomElement(AccessRequestStatus::cases()),
                ],
            );
        }
    }
}

<?php

namespace App\Services;

use App\DTO\CreatePostData;
use App\DTO\PostFilterData;
use App\DTO\UpdatePostData;
use App\Models\Post;
use App\Models\User;
use App\Repositories\Contracts\PostRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PostService
{
    public function __construct(
        private readonly PostRepositoryInterface $posts,
        private readonly TagService $tags,
    ) {}

    public function create(User $author, CreatePostData $data): Post
    {
        return DB::transaction(function () use ($author, $data): Post {
            $post = $this->posts->create($author, [
                'title' => $data->title,
                'slug' => $this->uniqueSlug($data->title),
                'content' => $data->content,
                'visibility' => $data->visibility,
                'published_at' => now(),
            ]);

            $post->tags()->sync($this->tags->idsForNames($data->tags));

            return $post->load(['author:id,name', 'tags:id,name,slug']);
        });
    }

    public function update(Post $post, UpdatePostData $data): Post
    {
        return DB::transaction(function () use ($post, $data): Post {
            $updated = $this->posts->update($post, [
                'title' => $data->title,
                'content' => $data->content,
                'visibility' => $data->visibility,
            ]);

            $updated->tags()->sync($this->tags->idsForNames($data->tags));

            return $updated->load(['author:id,name', 'tags:id,name,slug']);
        });
    }

    public function delete(Post $post): void
    {
        $post->delete();
    }

    public function publicPosts(PostFilterData $filters): LengthAwarePaginator
    {
        return $this->posts->publicPaginated($filters);
    }

    public function authorPosts(User $author, PostFilterData $filters): LengthAwarePaginator
    {
        return $this->posts->authorPaginated($author, $filters);
    }

    public function profilePosts(User $author): LengthAwarePaginator
    {
        return $this->posts->profilePaginated($author);
    }

    public function findBySlug(string $slug): ?Post
    {
        return $this->posts->findBySlug($slug);
    }

    protected function uniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $counter = 2;

        while ($this->posts->findBySlug($slug) !== null) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}

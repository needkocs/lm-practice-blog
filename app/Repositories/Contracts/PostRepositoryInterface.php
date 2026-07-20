<?php

namespace App\Repositories\Contracts;

use App\DTO\PostFilterData;
use App\Models\Post;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface PostRepositoryInterface
{
    public function create(User $author, array $attributes): Post;

    public function update(Post $post, array $attributes): Post;

    public function publicPaginated(PostFilterData $filters): LengthAwarePaginator;

    public function authorPaginated(User $author, PostFilterData $filters): LengthAwarePaginator;

    public function feedPaginated(User $user, PostFilterData $filters): LengthAwarePaginator;

    public function profilePaginated(User $author): LengthAwarePaginator;

    public function findBySlug(string $slug): ?Post;

    public function allTaggable(): Collection;
}

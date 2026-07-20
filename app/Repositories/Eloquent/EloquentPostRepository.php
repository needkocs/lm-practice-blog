<?php

namespace App\Repositories\Eloquent;

use App\DTO\PostFilterData;
use App\Enums\PostVisibility;
use App\Models\Post;
use App\Models\User;
use App\Repositories\Contracts\PostRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class EloquentPostRepository implements PostRepositoryInterface
{
    public function create(User $author, array $attributes): Post
    {
        return $author->posts()->create($attributes);
    }

    public function update(Post $post, array $attributes): Post
    {
        $post->update($attributes);

        return $post->refresh();
    }

    public function publicPaginated(PostFilterData $filters): LengthAwarePaginator
    {
        return $this->baseListQuery()
            ->where('visibility', PostVisibility::Public)
            ->tap(fn (Builder $query) => $this->applyFilters($query, $filters))
            ->tap(fn (Builder $query) => $this->applySort($query, $filters->sort))
            ->paginate(12)
            ->withQueryString();
    }

    public function authorPaginated(User $author, PostFilterData $filters): LengthAwarePaginator
    {
        return $this->baseListQuery()
            ->where('user_id', $author->id)
            ->tap(fn (Builder $query) => $this->applyFilters($query, $filters, includeVisibility: true))
            ->tap(fn (Builder $query) => $this->applySort($query, $filters->sort))
            ->paginate(12)
            ->withQueryString();
    }

    public function feedPaginated(User $user, PostFilterData $filters): LengthAwarePaginator
    {
        $followedIds = $user->following()->pluck('users.id');

        return $this->baseListQuery()
            ->whereIn('user_id', $followedIds)
            ->tap(fn (Builder $query) => $this->applyFilters($query, $filters))
            ->tap(fn (Builder $query) => $this->applySort($query, $filters->sort))
            ->paginate(12)
            ->withQueryString();
    }

    public function profilePaginated(User $author): LengthAwarePaginator
    {
        return $this->baseListQuery()
            ->where('user_id', $author->id)
            ->where('visibility', PostVisibility::Public)
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();
    }

    public function findBySlug(string $slug): ?Post
    {
        return Post::query()
            ->with(['author:id,name,email,created_at', 'tags:id,name,slug'])
            ->where('slug', $slug)
            ->first();
    }

    public function allTaggable(): Collection
    {
        return Post::query()->with('tags:id,name,slug')->get();
    }

    protected function baseListQuery(): Builder
    {
        return Post::query()
            ->select(['id', 'user_id', 'title', 'slug', 'visibility', 'published_at', 'created_at', 'updated_at'])
            ->selectRaw('substr(content, 1, 220) as excerpt')
            ->with(['author:id,name', 'tags:id,name,slug'])
            ->published();
    }

    protected function applyFilters(Builder $query, PostFilterData $filters, bool $includeVisibility = false): void
    {
        if ($filters->search !== null) {
            $query->where('title', 'like', '%'.$filters->search.'%');
        }

        if ($filters->authorId !== null) {
            $query->where('user_id', $filters->authorId);
        }

        if ($includeVisibility && $filters->visibility !== null) {
            $query->where('visibility', $filters->visibility);
        }

        foreach ($filters->tags as $slug) {
            $query->whereHas('tags', fn (Builder $tagQuery) => $tagQuery->where('slug', $slug));
        }
    }

    protected function applySort(Builder $query, string $sort): void
    {
        match ($sort) {
            'oldest' => $query->oldest('published_at'),
            'title_asc' => $query->orderBy('title'),
            'title_desc' => $query->orderByDesc('title'),
            default => $query->latest('published_at'),
        };
    }
}

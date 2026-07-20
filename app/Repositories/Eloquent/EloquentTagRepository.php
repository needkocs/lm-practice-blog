<?php

namespace App\Repositories\Eloquent;

use App\Models\Tag;
use App\Repositories\Contracts\TagRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class EloquentTagRepository implements TagRepositoryInterface
{
    public function findBySlug(string $slug): ?Tag
    {
        return Tag::query()->where('slug', $slug)->first();
    }

    public function findOrCreateByName(string $name): Tag
    {
        $normalized = Str::of($name)->squish()->lower()->toString();
        $displayName = Str::of($normalized)->title()->toString();
        $slug = Str::slug($normalized);

        return Tag::query()->firstOrCreate(
            ['slug' => $slug],
            ['name' => $displayName],
        );
    }

    public function all(): Collection
    {
        return Tag::query()->orderBy('name')->get(['id', 'name', 'slug']);
    }
}

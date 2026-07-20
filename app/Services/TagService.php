<?php

namespace App\Services;

use App\Models\Tag;
use App\Repositories\Contracts\TagRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class TagService
{
    public function __construct(
        private readonly TagRepositoryInterface $tags,
    ) {}

    /**
     * @param  list<string>  $names
     * @return list<int>
     */
    public function idsForNames(array $names): array
    {
        return collect($names)
            ->map(fn (string $name): string => Str::of($name)->squish()->lower()->toString())
            ->filter()
            ->unique()
            ->map(fn (string $name): int => $this->tags->findOrCreateByName($name)->id)
            ->values()
            ->all();
    }

    public function all(): Collection
    {
        return $this->tags->all();
    }

    public function findBySlug(string $slug): ?Tag
    {
        return $this->tags->findBySlug($slug);
    }
}

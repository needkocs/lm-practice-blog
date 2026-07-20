<?php

namespace App\Repositories\Contracts;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;

interface TagRepositoryInterface
{
    public function findBySlug(string $slug): ?Tag;

    public function findOrCreateByName(string $name): Tag;

    public function all(): Collection;
}

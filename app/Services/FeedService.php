<?php

namespace App\Services;

use App\DTO\PostFilterData;
use App\Models\User;
use App\Repositories\Contracts\PostRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class FeedService
{
    public function __construct(
        private readonly PostRepositoryInterface $posts,
    ) {}

    public function feedFor(User $user, PostFilterData $filters): LengthAwarePaginator
    {
        return $this->posts->feedPaginated($user, $filters);
    }
}

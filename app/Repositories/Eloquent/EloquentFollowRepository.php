<?php

namespace App\Repositories\Eloquent;

use App\Models\Follow;
use App\Models\User;
use App\Repositories\Contracts\FollowRepositoryInterface;

class EloquentFollowRepository implements FollowRepositoryInterface
{
    public function exists(User $follower, User $followed): bool
    {
        return Follow::query()
            ->where('follower_id', $follower->id)
            ->where('followed_id', $followed->id)
            ->exists();
    }

    public function follow(User $follower, User $followed): void
    {
        Follow::query()->firstOrCreate([
            'follower_id' => $follower->id,
            'followed_id' => $followed->id,
        ]);
    }

    public function unfollow(User $follower, User $followed): void
    {
        Follow::query()
            ->where('follower_id', $follower->id)
            ->where('followed_id', $followed->id)
            ->delete();
    }
}

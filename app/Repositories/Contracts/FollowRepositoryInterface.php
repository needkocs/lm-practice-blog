<?php

namespace App\Repositories\Contracts;

use App\Models\User;

interface FollowRepositoryInterface
{
    public function exists(User $follower, User $followed): bool;

    public function follow(User $follower, User $followed): void;

    public function unfollow(User $follower, User $followed): void;
}

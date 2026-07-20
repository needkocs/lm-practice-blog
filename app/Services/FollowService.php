<?php

namespace App\Services;

use App\Exceptions\DomainException;
use App\Models\User;
use App\Repositories\Contracts\FollowRepositoryInterface;

class FollowService
{
    public function __construct(
        private readonly FollowRepositoryInterface $follows,
    ) {}

    public function follow(User $follower, User $followed): void
    {
        if ($follower->id === $followed->id) {
            throw new DomainException('Нельзя подписаться на самого себя.');
        }

        if ($this->follows->exists($follower, $followed)) {
            throw new DomainException('Вы уже подписаны на этого пользователя.');
        }

        $this->follows->follow($follower, $followed);
    }

    public function unfollow(User $follower, User $followed): void
    {
        $this->follows->unfollow($follower, $followed);
    }

    public function isFollowing(?User $viewer, User $user): bool
    {
        return $viewer !== null && $this->follows->exists($viewer, $user);
    }
}

<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function create(array $attributes): User
    {
        return User::query()->create($attributes);
    }

    public function findByEmail(string $email): ?User
    {
        return User::query()->where('email', $email)->first();
    }

    public function findWithProfileCounts(User $user): User
    {
        return $user->loadCount(['followers', 'following']);
    }

    public function followers(User $user): LengthAwarePaginator
    {
        return $user->followers()->latest('follows.created_at')->paginate(20)->withQueryString();
    }

    public function following(User $user): LengthAwarePaginator
    {
        return $user->following()->latest('follows.created_at')->paginate(20)->withQueryString();
    }
}

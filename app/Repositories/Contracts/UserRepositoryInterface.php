<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function create(array $attributes): User;

    public function findByEmail(string $email): ?User;

    public function findWithProfileCounts(User $user): User;

    public function followers(User $user): LengthAwarePaginator;

    public function following(User $user): LengthAwarePaginator;
}

<?php

namespace App\Policies;

use App\Models\PostAccessRequest;
use App\Models\User;

class PostAccessRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function update(User $user, PostAccessRequest $request): bool
    {
        return $request->post()->where('user_id', $user->id)->exists();
    }
}

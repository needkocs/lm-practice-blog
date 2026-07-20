<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use App\Services\PostAccessService;

class PostPolicy
{
    public function view(?User $user, Post $post): bool
    {
        return $post->isPublic() || $this->viewFullContent($user, $post);
    }

    public function viewFullContent(?User $user, Post $post): bool
    {
        return app(PostAccessService::class)->canViewFullContent($user, $post);
    }

    public function update(User $user, Post $post): bool
    {
        return $post->user_id === $user->id;
    }

    public function delete(User $user, Post $post): bool
    {
        return $post->user_id === $user->id;
    }
}

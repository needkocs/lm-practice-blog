<?php

namespace App\Repositories\Contracts;

use App\Enums\AccessRequestStatus;
use App\Models\Post;
use App\Models\PostAccessRequest;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PostAccessRequestRepositoryInterface
{
    public function findForPostAndUser(Post $post, User $user): ?PostAccessRequest;

    public function create(Post $post, User $user, ?string $message): PostAccessRequest;

    public function updateStatus(PostAccessRequest $request, AccessRequestStatus $status): PostAccessRequest;

    public function incomingForAuthor(User $author): LengthAwarePaginator;

    public function hasApprovedAccess(Post $post, User $user): bool;
}

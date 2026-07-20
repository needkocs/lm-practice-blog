<?php

namespace App\Services;

use App\DTO\CreateAccessRequestData;
use App\Enums\AccessRequestStatus;
use App\Exceptions\DomainException;
use App\Models\Post;
use App\Models\PostAccessRequest;
use App\Models\User;
use App\Repositories\Contracts\PostAccessRequestRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PostAccessService
{
    public function __construct(
        private readonly PostAccessRequestRepositoryInterface $requests,
    ) {}

    public function createRequest(User $user, Post $post, CreateAccessRequestData $data): PostAccessRequest
    {
        if ($post->isPublic()) {
            throw new DomainException('Для публичных постов запрос доступа не нужен.');
        }

        if ($post->user_id === $user->id) {
            throw new DomainException('Автор уже имеет доступ к своему посту.');
        }

        if ($this->requests->findForPostAndUser($post, $user) !== null) {
            throw new DomainException('Вы уже запрашивали доступ к этому посту.');
        }

        return DB::transaction(fn () => $this->requests->create($post, $user, $data->message));
    }

    public function updateStatus(PostAccessRequest $request, AccessRequestStatus $status): PostAccessRequest
    {
        return DB::transaction(fn () => $this->requests->updateStatus($request, $status));
    }

    public function incomingForAuthor(User $author): LengthAwarePaginator
    {
        return $this->requests->incomingForAuthor($author);
    }

    public function canViewFullContent(?User $user, Post $post): bool
    {
        if ($post->isPublic()) {
            return true;
        }

        if ($user === null) {
            return false;
        }

        if ($post->user_id === $user->id) {
            return true;
        }

        return $this->requests->hasApprovedAccess($post, $user);
    }

    public function statusFor(?User $user, Post $post): ?AccessRequestStatus
    {
        if ($user === null) {
            return null;
        }

        return $this->requests->findForPostAndUser($post, $user)?->status;
    }
}

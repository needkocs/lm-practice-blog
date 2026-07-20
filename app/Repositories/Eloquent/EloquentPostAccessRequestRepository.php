<?php

namespace App\Repositories\Eloquent;

use App\Enums\AccessRequestStatus;
use App\Models\Post;
use App\Models\PostAccessRequest;
use App\Models\User;
use App\Repositories\Contracts\PostAccessRequestRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentPostAccessRequestRepository implements PostAccessRequestRepositoryInterface
{
    public function findForPostAndUser(Post $post, User $user): ?PostAccessRequest
    {
        return PostAccessRequest::query()
            ->where('post_id', $post->id)
            ->where('user_id', $user->id)
            ->first();
    }

    public function create(Post $post, User $user, ?string $message): PostAccessRequest
    {
        return PostAccessRequest::query()->create([
            'post_id' => $post->id,
            'user_id' => $user->id,
            'message' => $message,
            'status' => AccessRequestStatus::Pending,
        ]);
    }

    public function updateStatus(PostAccessRequest $request, AccessRequestStatus $status): PostAccessRequest
    {
        $request->update(['status' => $status]);

        return $request->refresh();
    }

    public function incomingForAuthor(User $author): LengthAwarePaginator
    {
        return PostAccessRequest::query()
            ->with(['user:id,name,email', 'post:id,user_id,title,slug'])
            ->whereHas('post', fn ($query) => $query->where('user_id', $author->id))
            ->latest()
            ->paginate(20)
            ->withQueryString();
    }

    public function hasApprovedAccess(Post $post, User $user): bool
    {
        return PostAccessRequest::query()
            ->where('post_id', $post->id)
            ->where('user_id', $user->id)
            ->where('status', AccessRequestStatus::Approved)
            ->exists();
    }
}

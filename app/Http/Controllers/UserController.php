<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\FollowService;
use App\Services\PostService;
use App\Support\DateFormatter;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function show(Request $request, User $user, UserRepositoryInterface $users, PostService $posts, FollowService $follows): Response
    {
        return Inertia::render('Users/Show', [
            'profile' => $this->profileData($users->findWithProfileCounts($user)),
            'posts' => $posts->profilePosts($user)->through(fn (Post $post) => PostResource::make($post)->resolve($request)),
            'isFollowing' => $follows->isFollowing($request->user(), $user),
        ]);
    }

    public function followers(User $user, UserRepositoryInterface $users): Response
    {
        return Inertia::render('Users/Followers', [
            'profile' => $this->profileData($user),
            'users' => $users->followers($user),
        ]);
    }

    public function following(User $user, UserRepositoryInterface $users): Response
    {
        return Inertia::render('Users/Following', [
            'profile' => $this->profileData($user),
            'users' => $users->following($user),
        ]);
    }

    protected function profileData(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'created_at' => DateFormatter::date($user->created_at),
            'followers_count' => $user->followers_count ?? null,
            'following_count' => $user->following_count ?? null,
        ];
    }
}

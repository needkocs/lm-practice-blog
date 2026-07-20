<?php

namespace App\Http\Controllers;

use App\Enums\PostVisibility;
use App\Http\Requests\Posts\PostFilterRequest;
use App\Http\Requests\Posts\StorePostRequest;
use App\Http\Requests\Posts\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Services\PostAccessService;
use App\Services\PostService;
use App\Services\TagService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class PostController extends Controller
{
    public function index(PostFilterRequest $request, PostService $posts, TagService $tags): Response
    {
        return Inertia::render('Posts/Index', [
            'posts' => $posts->publicPosts($request->toDto())->through(fn (Post $post) => PostResource::make($post)->resolve($request)),
            'tags' => $tags->all(),
            'filters' => ['sort' => 'newest', ...$request->validated()],
            'sorts' => $this->sorts(),
        ]);
    }

    public function myPosts(PostFilterRequest $request, PostService $posts, TagService $tags): Response
    {
        return Inertia::render('Posts/MyPosts', [
            'posts' => $posts->authorPosts($request->user(), $request->toDto())->through(fn (Post $post) => PostResource::make($post)->resolve($request)),
            'tags' => $tags->all(),
            'filters' => ['sort' => 'newest', ...$request->validated()],
            'sorts' => $this->sorts(),
            'visibilities' => $this->visibilities(),
        ]);
    }

    public function create(TagService $tags): Response
    {
        return Inertia::render('Posts/Create', [
            'tags' => $tags->all(),
            'visibilities' => $this->visibilities(),
        ]);
    }

    public function store(StorePostRequest $request, PostService $posts): RedirectResponse
    {
        $post = $posts->create($request->user(), $request->toDto());

        return redirect()->route('posts.show', $post)->with('success', 'Пост создан.');
    }

    public function show(Request $request, Post $post, PostAccessService $access): Response
    {
        $post->loadMissing(['author:id,name,email,created_at', 'tags:id,name,slug']);
        $canViewFullContent = $access->canViewFullContent($request->user(), $post);

        return Inertia::render('Posts/Show', [
            'post' => PostResource::make($post)->additional(['canViewFullContent' => $canViewFullContent])->resolve($request),
            'accessStatus' => $access->statusFor($request->user(), $post)?->value,
        ]);
    }

    public function edit(Post $post, TagService $tags): Response
    {
        Gate::authorize('update', $post);

        return Inertia::render('Posts/Edit', [
            'post' => PostResource::make($post->load(['author:id,name', 'tags:id,name,slug']))->additional(['canViewFullContent' => true])->resolve(request()),
            'tags' => $tags->all(),
            'visibilities' => $this->visibilities(),
        ]);
    }

    public function update(UpdatePostRequest $request, Post $post, PostService $posts): RedirectResponse
    {
        $posts->update($post, $request->toDto());

        return redirect()->route('posts.show', $post)->with('success', 'Пост обновлен.');
    }

    public function destroy(Request $request, Post $post, PostService $posts): RedirectResponse
    {
        Gate::authorize('delete', $post);
        $posts->delete($post);

        return redirect()->route('posts.mine')->with('success', 'Пост удален.');
    }

    protected function sorts(): array
    {
        return [
            ['value' => 'newest', 'label' => 'Сначала новые'],
            ['value' => 'oldest', 'label' => 'Сначала старые'],
            ['value' => 'title_asc', 'label' => 'Заголовок А-Я'],
            ['value' => 'title_desc', 'label' => 'Заголовок Я-А'],
        ];
    }

    protected function visibilities(): array
    {
        return collect(PostVisibility::cases())->map(fn (PostVisibility $visibility): array => [
            'value' => $visibility->value,
            'label' => $visibility->label(),
        ])->all();
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\Posts\PostFilterRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Services\FeedService;
use App\Services\PostAccessService;
use App\Services\TagService;
use Inertia\Inertia;
use Inertia\Response;

class FeedController extends Controller
{
    public function __invoke(PostFilterRequest $request, FeedService $feed, PostAccessService $access, TagService $tags): Response
    {
        return Inertia::render('Feed/Index', [
            'posts' => $feed->feedFor($request->user(), $request->toDto())->through(
                fn (Post $post) => PostResource::make($post)
                    ->additional(['canViewFullContent' => $access->canViewFullContent($request->user(), $post)])
                    ->resolve($request),
            ),
            'tags' => $tags->all(),
            'filters' => ['sort' => 'newest', ...$request->validated()],
            'sorts' => [
                ['value' => 'newest', 'label' => 'Сначала новые'],
                ['value' => 'oldest', 'label' => 'Сначала старые'],
                ['value' => 'title_asc', 'label' => 'Заголовок А-Я'],
                ['value' => 'title_desc', 'label' => 'Заголовок Я-А'],
            ],
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Exceptions\DomainException;
use App\Http\Requests\AccessRequests\StoreAccessRequest;
use App\Http\Requests\AccessRequests\UpdateAccessRequestStatusRequest;
use App\Models\Post;
use App\Models\PostAccessRequest;
use App\Services\PostAccessService;
use App\Support\DateFormatter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class PostAccessRequestController extends Controller
{
    public function index(Request $request, PostAccessService $access): Response
    {
        Gate::authorize('viewAny', PostAccessRequest::class);

        return Inertia::render('AccessRequests/Index', [
            'requests' => $access->incomingForAuthor($request->user())->through(fn (PostAccessRequest $accessRequest): array => [
                'id' => $accessRequest->id,
                'message' => $accessRequest->message,
                'status' => $accessRequest->status->value,
                'created_at' => DateFormatter::date($accessRequest->created_at),
                'user' => $accessRequest->user->only(['id', 'name', 'email']),
                'post' => $accessRequest->post->only(['id', 'title', 'slug']),
            ]),
        ]);
    }

    public function store(StoreAccessRequest $request, Post $post, PostAccessService $access): RedirectResponse
    {
        try {
            $access->createRequest($request->user(), $post, $request->toDto());
        } catch (DomainException $exception) {
            return back()->withErrors(['access' => $exception->getMessage()]);
        }

        return back()->with('success', 'Запрос доступа отправлен.');
    }

    public function update(UpdateAccessRequestStatusRequest $request, PostAccessRequest $accessRequest, PostAccessService $access): RedirectResponse
    {
        $access->updateStatus($accessRequest, $request->status());

        return back()->with('success', 'Запрос доступа обновлен.');
    }
}

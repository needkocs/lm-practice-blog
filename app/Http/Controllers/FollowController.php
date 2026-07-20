<?php

namespace App\Http\Controllers;

use App\Exceptions\DomainException;
use App\Models\User;
use App\Services\FollowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function store(Request $request, User $user, FollowService $follows): RedirectResponse
    {
        try {
            $follows->follow($request->user(), $user);
        } catch (DomainException $exception) {
            return back()->withErrors(['follow' => $exception->getMessage()]);
        }

        return back()->with('success', 'Подписка оформлена.');
    }

    public function destroy(Request $request, User $user, FollowService $follows): RedirectResponse
    {
        $follows->unfollow($request->user(), $user);

        return back()->with('success', 'Подписка отменена.');
    }
}

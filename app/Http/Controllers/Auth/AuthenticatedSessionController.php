<?php

namespace App\Http\Controllers\Auth;

use App\DTO\LoginData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthenticationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Auth/Login');
    }

    public function store(LoginRequest $request, AuthenticationService $authentication): RedirectResponse
    {
        $request->ensureIsNotRateLimited();

        $authentication->login(new LoginData(
            email: $request->string('email')->toString(),
            password: $request->string('password')->toString(),
            remember: $request->boolean('remember'),
        ));

        RateLimiter::clear($request->throttleKey());
        $request->session()->regenerate();

        return redirect()->intended(route('feed.index'))->with('success', 'Вы вошли в систему.');
    }

    public function destroy(Request $request, AuthenticationService $authentication): RedirectResponse
    {
        $authentication->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('posts.index')->with('success', 'Вы вышли из системы.');
    }
}

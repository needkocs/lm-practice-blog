<?php

namespace App\Http\Controllers\Auth;

use App\DTO\RegisterUserData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\RegistrationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    public function store(RegisterRequest $request, RegistrationService $registration): RedirectResponse
    {
        $user = $registration->register(new RegisterUserData(
            name: $request->string('name')->toString(),
            email: $request->string('email')->toString(),
            password: $request->string('password')->toString(),
        ));

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('feed.index')->with('success', 'Регистрация завершена.');
    }
}

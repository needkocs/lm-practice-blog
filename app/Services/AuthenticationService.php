<?php

namespace App\Services;

use App\DTO\LoginData;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Validation\ValidationException;

class AuthenticationService
{
    public function __construct(
        private readonly StatefulGuard $guard,
    ) {}

    public function login(LoginData $data): void
    {
        if (! $this->guard->attempt(['email' => $data->email, 'password' => $data->password], $data->remember)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }
    }

    public function logout(): void
    {
        $this->guard->logout();
    }
}

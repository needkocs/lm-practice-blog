<?php

namespace App\Services;

use App\DTO\RegisterUserData;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class RegistrationService
{
    public function __construct(
        private readonly UserRepositoryInterface $users,
    ) {}

    public function register(RegisterUserData $data): User
    {
        return $this->users->create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => Hash::make($data->password),
        ]);
    }
}

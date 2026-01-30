<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Actions\Action;
use App\DTOs\User\RegisterUserDTO;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class RegisterUserAction extends Action
{
    public function __construct(
        private UserRepository $users
    ) {}

    public function execute(RegisterUserDTO $dto): User
    {
        return $this->users->create([
            'username' => $dto->username,
            'email' => $dto->email,
            'password' => Hash::make($dto->password),
            'first_name' => $dto->first_name,
            'last_name' => $dto->last_name,
            'status' => 'active',
        ]);
    }
}

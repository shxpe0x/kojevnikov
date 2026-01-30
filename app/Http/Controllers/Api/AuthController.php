<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Auth\RegisterUserAction;
use App\DTOs\User\RegisterUserDTO;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends ApiController
{
    public function __construct(
        private RegisterUserAction $registerUser,
        private UserRepository $users
    ) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        $dto = new RegisterUserDTO(
            username: $request->input('username'),
            email: $request->input('email'),
            password: $request->input('password'),
            first_name: $request->input('first_name'),
            last_name: $request->input('last_name')
        );

        $user = $this->registerUser->execute($dto);
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->created([
            'user' => new UserResource($user),
            'token' => $token,
        ], 'Registration successful');
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = $this->users->findByEmail($request->input('email'));

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return $this->error('Invalid credentials', 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->success([
            'user' => new UserResource($user),
            'token' => $token,
        ], 'Login successful');
    }

    public function logout(): JsonResponse
    {
        Auth::user()->currentAccessToken()->delete();

        return $this->success(null, 'Logged out successfully');
    }

    public function me(): JsonResponse
    {
        return $this->success(new UserResource(Auth::user()));
    }
}

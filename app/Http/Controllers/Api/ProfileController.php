<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\DTOs\User\UpdateProfileDTO;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Repositories\PostRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends ApiController
{
    public function __construct(
        private UserRepository $users,
        private PostRepository $posts
    ) {}

    public function show(string $username): JsonResponse
    {
        $user = $this->users->findByUsername($username);

        if (!$user) {
            return $this->notFound('User not found');
        }

        return $this->success(new UserResource($user));
    }

    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $user = Auth::user();
        $data = $request->validated();

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        if ($request->hasFile('cover_photo')) {
            if ($user->cover_photo) {
                Storage::delete($user->cover_photo);
            }
            $data['cover_photo'] = $request->file('cover_photo')->store('covers', 'public');
        }

        $user = $this->users->update($user, $data);

        return $this->success(new UserResource($user), 'Profile updated successfully');
    }

    public function posts(string $username): JsonResponse
    {
        $user = $this->users->findByUsername($username);

        if (!$user) {
            return $this->notFound('User not found');
        }

        $posts = $this->posts->getUserPosts($user->id);

        return $this->success(PostResource::collection($posts));
    }
}

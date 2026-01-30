<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Follow\FollowUserAction;
use App\Actions\Follow\UnfollowUserAction;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class FollowController extends ApiController
{
    public function __construct(
        private FollowUserAction $followUser,
        private UnfollowUserAction $unfollowUser
    ) {}

    public function followers(): JsonResponse
    {
        $followers = Auth::user()->followers;

        return $this->success(UserResource::collection($followers));
    }

    public function following(): JsonResponse
    {
        $following = Auth::user()->following;

        return $this->success(UserResource::collection($following));
    }

    public function follow(int $userId): JsonResponse
    {
        try {
            $this->followUser->execute(Auth::id(), $userId);

            return $this->success(null, 'User followed successfully');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function unfollow(int $userId): JsonResponse
    {
        try {
            $this->unfollowUser->execute(Auth::id(), $userId);

            return $this->success(null, 'User unfollowed successfully');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}

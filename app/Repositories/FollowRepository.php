<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Follow;

class FollowRepository extends BaseRepository
{
    public function __construct(Follow $model)
    {
        parent::__construct($model);
    }

    public function isFollowing(int $followerId, int $followingId): bool
    {
        return $this->model
            ->where('follower_id', $followerId)
            ->where('following_id', $followingId)
            ->exists();
    }

    public function findByUsers(int $followerId, int $followingId): ?Follow
    {
        return $this->model
            ->where('follower_id', $followerId)
            ->where('following_id', $followingId)
            ->first();
    }
}

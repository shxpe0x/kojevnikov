<?php

declare(strict_types=1);

namespace App\Actions\Follow;

use App\Actions\Action;
use App\Exceptions\BusinessException;
use App\Models\Follow;
use App\Repositories\FollowRepository;

class FollowUserAction extends Action
{
    public function __construct(
        private FollowRepository $follows
    ) {}

    public function execute(int $followerId, int $followingId): Follow
    {
        if ($followerId === $followingId) {
            throw new BusinessException('Cannot follow yourself');
        }

        if ($this->follows->isFollowing($followerId, $followingId)) {
            throw new BusinessException('Already following this user');
        }

        return $this->follows->create([
            'follower_id' => $followerId,
            'following_id' => $followingId,
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Actions\Follow;

use App\Actions\Action;
use App\Exceptions\BusinessException;
use App\Repositories\FollowRepository;

class UnfollowUserAction extends Action
{
    public function __construct(
        private FollowRepository $follows
    ) {}

    public function execute(int $followerId, int $followingId): bool
    {
        $follow = $this->follows->findByUsers($followerId, $followingId);

        if (!$follow) {
            throw new BusinessException('You are not following this user');
        }

        return $this->follows->delete($follow);
    }
}

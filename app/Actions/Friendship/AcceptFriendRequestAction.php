<?php

declare(strict_types=1);

namespace App\Actions\Friendship;

use App\Actions\Action;
use App\Exceptions\BusinessException;
use App\Models\Friendship;
use App\Repositories\FriendshipRepository;

class AcceptFriendRequestAction extends Action
{
    public function __construct(
        private FriendshipRepository $friendships
    ) {}

    public function execute(Friendship $friendship, int $userId): Friendship
    {
        if ($friendship->friend_id !== $userId) {
            throw new BusinessException('You cannot accept this friend request');
        }

        if ($friendship->status !== 'pending') {
            throw new BusinessException('This friend request is not pending');
        }

        return $this->friendships->accept($friendship);
    }
}

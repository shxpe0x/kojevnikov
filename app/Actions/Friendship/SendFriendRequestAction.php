<?php

declare(strict_types=1);

namespace App\Actions\Friendship;

use App\Actions\Action;
use App\Exceptions\BusinessException;
use App\Models\Friendship;
use App\Repositories\FriendshipRepository;

class SendFriendRequestAction extends Action
{
    public function __construct(
        private FriendshipRepository $friendships
    ) {}

    public function execute(int $userId, int $friendId): Friendship
    {
        if ($userId === $friendId) {
            throw new BusinessException('Cannot send friend request to yourself');
        }

        $existing = $this->friendships->findBetween($userId, $friendId);

        if ($existing) {
            throw new BusinessException('Friend request already exists');
        }

        return $this->friendships->create([
            'user_id' => $userId,
            'friend_id' => $friendId,
            'status' => 'pending',
        ]);
    }
}

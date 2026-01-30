<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Friendship;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class FriendshipRepository extends BaseRepository
{
    public function __construct(Friendship $model)
    {
        parent::__construct($model);
    }

    public function findBetween(int $userId, int $friendId): ?Friendship
    {
        return $this->model
            ->where(function ($query) use ($userId, $friendId) {
                $query->where('user_id', $userId)->where('friend_id', $friendId);
            })
            ->orWhere(function ($query) use ($userId, $friendId) {
                $query->where('user_id', $friendId)->where('friend_id', $userId);
            })
            ->first();
    }

    public function getPending(int $userId): Collection
    {
        return $this->model
            ->where('friend_id', $userId)
            ->where('status', 'pending')
            ->with('user')
            ->get();
    }

    public function accept(Friendship $friendship): Friendship
    {
        $friendship->update([
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);

        return $friendship->fresh();
    }
}

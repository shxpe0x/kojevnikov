<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Friendship;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FriendshipPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Friendship $friendship): bool
    {
        return $user->id === $friendship->user_id || $user->id === $friendship->friend_id;
    }

    public function create(User $user): bool
    {
        return $user->status === 'active';
    }

    public function update(User $user, Friendship $friendship): bool
    {
        // Only the recipient can accept/reject friend requests
        return $user->id === $friendship->friend_id;
    }

    public function delete(User $user, Friendship $friendship): bool
    {
        // Both users can delete the friendship
        return $user->id === $friendship->user_id || $user->id === $friendship->friend_id;
    }
}

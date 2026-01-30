<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Follow;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FollowPolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->status === 'active';
    }

    public function delete(User $user, Follow $follow): bool
    {
        return $user->id === $follow->follower_id;
    }
}

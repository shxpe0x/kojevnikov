<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function view(?User $user, User $model): bool
    {
        // Public profiles can be viewed by anyone
        if ($model->status === 'active') {
            return true;
        }

        // Inactive or banned users can only be viewed by themselves
        return $user?->id === $model->id;
    }

    public function update(User $user, User $model): bool
    {
        return $user->id === $model->id;
    }

    public function delete(User $user, User $model): bool
    {
        return $user->id === $model->id;
    }

    public function restore(User $user, User $model): bool
    {
        return $user->id === $model->id;
    }

    public function forceDelete(User $user, User $model): bool
    {
        return $user->id === $model->id;
    }
}

<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(?User $user, Post $post): bool
    {
        if ($post->visibility === 'public') {
            return true;
        }

        if (!$user) {
            return false;
        }

        if ($post->user_id === $user->id) {
            return true;
        }

        if ($post->visibility === 'friends') {
            return $user->friends()->where('id', $post->user_id)->exists();
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->status === 'active';
    }

    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    public function delete(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }
}

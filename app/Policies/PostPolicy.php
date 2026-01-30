<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Post $post): bool
    {
        // Public posts can be viewed by anyone
        if ($post->visibility === 'public') {
            return true;
        }

        // Must be authenticated for non-public posts
        if (!$user) {
            return false;
        }

        // Owner can always view their own posts
        if ($post->user_id === $user->id) {
            return true;
        }

        // Friends-only posts require friendship
        if ($post->visibility === 'friends') {
            return $user->friends()->where('friend_id', $post->user_id)->exists() ||
                   $user->friends()->where('user_id', $post->user_id)->exists();
        }

        // Private posts can only be viewed by owner
        return false;
    }

    public function create(User $user): bool
    {
        return $user->status === 'active';
    }

    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user_id && $user->status === 'active';
    }

    public function delete(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    public function restore(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    public function forceDelete(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }
}

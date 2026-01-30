<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Comment $comment): bool
    {
        // Comments visibility depends on post visibility
        return true; // Will be checked through PostPolicy
    }

    public function create(User $user): bool
    {
        return $user->status === 'active';
    }

    public function update(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id && $user->status === 'active';
    }

    public function delete(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id || $user->id === $comment->post->user_id;
    }

    public function restore(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id;
    }

    public function forceDelete(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id;
    }
}

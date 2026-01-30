<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Collection;

class CommentRepository extends BaseRepository
{
    public function __construct(Comment $model)
    {
        parent::__construct($model);
    }

    public function getPostComments(int $postId): Collection
    {
        return $this->model
            ->where('post_id', $postId)
            ->whereNull('parent_id')
            ->with(['user', 'replies.user'])
            ->orderByDesc('created_at')
            ->get();
    }

    public function incrementLikes(Comment $comment): void
    {
        $comment->increment('likes_count');
    }

    public function decrementLikes(Comment $comment): void
    {
        $comment->decrement('likes_count');
    }
}

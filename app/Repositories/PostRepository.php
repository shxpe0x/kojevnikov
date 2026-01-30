<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PostRepository extends BaseRepository
{
    public function __construct(Post $model)
    {
        parent::__construct($model);
    }

    public function getFeed(User $user, int $perPage = 15): LengthAwarePaginator
    {
        $friendIds = $user->friends()->pluck('id')->toArray();
        $followingIds = $user->following()->pluck('id')->toArray();
        $userIds = array_unique(array_merge([$user->id], $friendIds, $followingIds));

        return $this->model
            ->whereIn('user_id', $userIds)
            ->where('status', 'published')
            ->whereIn('visibility', ['public', 'friends'])
            ->with(['user', 'media', 'comments'])
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function getUserPosts(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('status', 'published')
            ->with(['user', 'media'])
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function incrementLikes(Post $post): void
    {
        $post->increment('likes_count');
    }

    public function decrementLikes(Post $post): void
    {
        $post->decrement('likes_count');
    }

    public function incrementComments(Post $post): void
    {
        $post->increment('comments_count');
    }
}

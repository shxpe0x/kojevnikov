<?php

declare(strict_types=1);

namespace App\Actions\Post;

use App\Actions\Action;
use App\Models\Post;
use App\Repositories\LikeRepository;
use App\Repositories\PostRepository;

class LikePostAction extends Action
{
    public function __construct(
        private LikeRepository $likes,
        private PostRepository $posts
    ) {}

    public function execute(int $userId, Post $post): bool
    {
        $liked = $this->likes->toggle($userId, $post);

        if ($liked) {
            $this->posts->incrementLikes($post);
        } else {
            $this->posts->decrementLikes($post);
        }

        return $liked;
    }
}

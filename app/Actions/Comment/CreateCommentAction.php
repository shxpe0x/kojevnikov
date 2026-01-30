<?php

declare(strict_types=1);

namespace App\Actions\Comment;

use App\Actions\Action;
use App\DTOs\Comment\CreateCommentDTO;
use App\Models\Comment;
use App\Repositories\CommentRepository;
use App\Repositories\PostRepository;

class CreateCommentAction extends Action
{
    public function __construct(
        private CommentRepository $comments,
        private PostRepository $posts
    ) {}

    public function execute(CreateCommentDTO $dto): Comment
    {
        $comment = $this->comments->create([
            'post_id' => $dto->post_id,
            'user_id' => $dto->user_id,
            'content' => $dto->content,
            'parent_id' => $dto->parent_id,
            'status' => 'published',
        ]);

        $post = $this->posts->find($dto->post_id);
        if ($post) {
            $this->posts->incrementComments($post);
        }

        return $comment;
    }
}

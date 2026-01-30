<?php

declare(strict_types=1);

namespace App\Actions\Post;

use App\Actions\Action;
use App\DTOs\Post\CreatePostDTO;
use App\Models\Post;
use App\Repositories\PostRepository;

class CreatePostAction extends Action
{
    public function __construct(
        private PostRepository $posts
    ) {}

    public function execute(CreatePostDTO $dto): Post
    {
        return $this->posts->create([
            'user_id' => $dto->user_id,
            'content' => $dto->content,
            'type' => $dto->type->value,
            'visibility' => $dto->visibility->value,
            'status' => 'published',
            'published_at' => now(),
        ]);
    }
}

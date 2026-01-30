<?php

declare(strict_types=1);

namespace App\DTOs\Comment;

use App\DTOs\DTO;

class CreateCommentDTO extends DTO
{
    public function __construct(
        public readonly int $post_id,
        public readonly int $user_id,
        public readonly string $content,
        public readonly ?int $parent_id = null
    ) {}
}

<?php

declare(strict_types=1);

namespace App\DTOs\Post;

use App\DTOs\DTO;
use App\Enums\PostType;
use App\Enums\PostVisibility;

class CreatePostDTO extends DTO
{
    public function __construct(
        public readonly int $user_id,
        public readonly string $content,
        public readonly PostType $type = PostType::TEXT,
        public readonly PostVisibility $visibility = PostVisibility::PUBLIC
    ) {}
}

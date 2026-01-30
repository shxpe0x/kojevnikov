<?php

declare(strict_types=1);

namespace App\DTOs\User;

use App\DTOs\DTO;

class UpdateProfileDTO extends DTO
{
    public function __construct(
        public readonly ?string $first_name = null,
        public readonly ?string $last_name = null,
        public readonly ?string $bio = null,
        public readonly ?string $location = null,
        public readonly ?string $website = null,
        public readonly ?string $birth_date = null
    ) {}
}

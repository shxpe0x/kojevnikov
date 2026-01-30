<?php

declare(strict_types=1);

namespace App\DTOs\User;

use App\DTOs\DTO;

class RegisterUserDTO extends DTO
{
    public function __construct(
        public readonly string $username,
        public readonly string $email,
        public readonly string $password,
        public readonly string $first_name,
        public readonly string $last_name
    ) {}
}

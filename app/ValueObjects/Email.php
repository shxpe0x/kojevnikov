<?php

declare(strict_types=1);

namespace App\ValueObjects;

use InvalidArgumentException;

final readonly class Email
{
    public function __construct(
        public string $value
    ) {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email address');
        }
    }

    /**
     * Get string representation.
     */
    public function toString(): string
    {
        return $this->value;
    }

    /**
     * Get domain part.
     */
    public function domain(): string
    {
        return substr($this->value, strpos($this->value, '@') + 1);
    }

    /**
     * Get local part.
     */
    public function local(): string
    {
        return substr($this->value, 0, strpos($this->value, '@'));
    }
}

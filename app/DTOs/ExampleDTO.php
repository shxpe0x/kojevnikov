<?php

declare(strict_types=1);

namespace App\DTOs;

/**
 * Example Data Transfer Object with readonly properties
 * DTOs provide type-safe data transfer between layers
 */
final readonly class ExampleDTO
{
    public function __construct(
        public string $name,
        public int $value,
        public ?string $description = null,
    ) {}

    /**
     * Create DTO from array (e.g., from request)
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            value: (int) $data['value'],
            description: $data['description'] ?? null,
        );
    }

    /**
     * Convert DTO to array
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'value' => $this->value,
            'description' => $this->description,
        ];
    }
}

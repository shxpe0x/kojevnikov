<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\ExampleDTO;
use App\Repositories\ExampleRepository;

/**
 * Example Service demonstrating business logic layer
 * Services coordinate between repositories and contain complex logic
 */
final readonly class ExampleService
{
    public function __construct(
        private ExampleRepository $repository,
    ) {}

    public function process(ExampleDTO $dto): array
    {
        // Complex business logic goes here
        $data = $dto->toArray();
        
        // Example: Transform data
        $data['processed'] = true;
        $data['processed_at'] = now()->toIso8601String();

        // Example: Save to database
        // $this->repository->create($data);

        return $data;
    }

    public function getById(int $id): ?array
    {
        return $this->repository->findById($id);
    }

    public function getAll(int $page = 1, int $perPage = 20): array
    {
        $offset = ($page - 1) * $perPage;
        $items = $this->repository->findAll($perPage, $offset);

        return [
            'data' => $items,
            'meta' => [
                'page' => $page,
                'per_page' => $perPage,
                'total' => $items->count(),
            ],
        ];
    }
}

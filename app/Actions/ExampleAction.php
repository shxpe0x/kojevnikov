<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTOs\ExampleDTO;
use App\Services\ExampleService;

/**
 * Example Action demonstrating single-purpose business logic
 * Actions are invokable classes that perform one specific task
 */
final readonly class ExampleAction
{
    public function __construct(
        private ExampleService $exampleService,
    ) {}

    public function execute(ExampleDTO $dto): array
    {
        // Validate business rules
        if ($dto->value < 0) {
            throw new \InvalidArgumentException('Value must be non-negative');
        }

        // Execute business logic
        $result = $this->exampleService->process($dto);

        return [
            'success' => true,
            'data' => $result,
        ];
    }
}

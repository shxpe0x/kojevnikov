<?php

declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;

/**
 * Example Repository implementing data access patterns
 * Repositories abstract database operations from business logic
 */
final readonly class ExampleRepository
{
    // In real implementation, inject your Model here
    // public function __construct(private ExampleModel $model) {}

    public function findById(int $id): ?array
    {
        // Example implementation
        // return $this->model->find($id)?->toArray();
        return null;
    }

    public function findAll(int $limit = 20, int $offset = 0): Collection
    {
        // Example implementation
        // return $this->model->limit($limit)->offset($offset)->get();
        return new Collection();
    }

    public function create(array $data): array
    {
        // Example implementation
        // return $this->model->create($data)->toArray();
        return $data;
    }

    public function update(int $id, array $data): bool
    {
        // Example implementation
        // return $this->model->where('id', $id)->update($data);
        return true;
    }

    public function delete(int $id): bool
    {
        // Example implementation
        // return $this->model->destroy($id) > 0;
        return true;
    }

    public function exists(int $id): bool
    {
        // Example implementation
        // return $this->model->where('id', $id)->exists();
        return false;
    }
}

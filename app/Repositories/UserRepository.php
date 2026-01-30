<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository extends BaseRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->findBy('email', $email);
    }

    public function findByUsername(string $username): ?User
    {
        return $this->findBy('username', $username);
    }

    public function search(string $query, int $limit = 20): Collection
    {
        return $this->model
            ->where('username', 'like', "%{$query}%")
            ->orWhere('first_name', 'like', "%{$query}%")
            ->orWhere('last_name', 'like', "%{$query}%")
            ->limit($limit)
            ->get();
    }

    public function updateLastSeen(User $user): void
    {
        $user->update(['last_seen_at' => now()]);
    }
}

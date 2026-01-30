<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Like;
use Illuminate\Database\Eloquent\Model;

class LikeRepository extends BaseRepository
{
    public function __construct(Like $model)
    {
        parent::__construct($model);
    }

    public function findByUserAndLikeable(int $userId, Model $likeable): ?Like
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('likeable_id', $likeable->id)
            ->where('likeable_type', get_class($likeable))
            ->first();
    }

    public function toggle(int $userId, Model $likeable): bool
    {
        $like = $this->findByUserAndLikeable($userId, $likeable);

        if ($like) {
            $like->delete();
            return false;
        }

        $this->create([
            'user_id' => $userId,
            'likeable_id' => $likeable->id,
            'likeable_type' => get_class($likeable),
        ]);

        return true;
    }
}

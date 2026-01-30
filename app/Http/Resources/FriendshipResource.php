<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;

class FriendshipResource extends BaseResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'user' => new UserResource($this->whenLoaded('user')),
            'friend' => new UserResource($this->whenLoaded('friend')),
            'accepted_at' => $this->accepted_at?->toIso8601String(),
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}

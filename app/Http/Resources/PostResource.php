<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;

class PostResource extends BaseResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'content' => $this->content,
            'type' => $this->type,
            'visibility' => $this->visibility,
            'status' => $this->status,
            'likes_count' => $this->likes_count,
            'comments_count' => $this->comments_count,
            'shares_count' => $this->shares_count,
            'is_liked' => $this->when($request->user(), fn() => $this->isLikedBy($request->user())),
            'author' => new UserResource($this->whenLoaded('user')),
            'media' => PostMediaResource::collection($this->whenLoaded('media')),
            'published_at' => $this->published_at?->toIso8601String(),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}

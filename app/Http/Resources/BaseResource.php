<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class BaseResource extends JsonResource
{
    /**
     * Add metadata to response.
     */
    protected function withMeta(array $meta): array
    {
        return ['meta' => $meta];
    }

    /**
     * Transform the resource into an array.
     */
    abstract public function toArray(Request $request): array;
}

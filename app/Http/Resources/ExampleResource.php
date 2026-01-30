<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Example API Resource for response transformation
 * Resources provide consistent API response formatting
 */
final class ExampleResource extends JsonResource
{
    /**
     * Transform the resource into an array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'value' => $this->value,
            'description' => $this->description,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
            
            // Conditional attributes
            $this->mergeWhen($request->user()?->isAdmin(), [
                'internal_data' => $this->internal_data,
            ]),
            
            // Nested resources
            'related' => $this->whenLoaded('related'),
        ];
    }

    /**
     * Get additional data that should be returned with the resource array
     */
    public function with(Request $request): array
    {
        return [
            'meta' => [
                'version' => '1.0',
            ],
        ];
    }
}

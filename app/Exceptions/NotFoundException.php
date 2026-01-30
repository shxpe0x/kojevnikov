<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotFoundException extends BusinessException
{
    public function __construct(
        string $message = 'Resource not found',
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, 404, $previous);
    }

    public function render(Request $request): JsonResponse
    {
        return response()->json([
            'message' => $this->getMessage(),
            'error' => 'not_found',
        ], 404);
    }
}

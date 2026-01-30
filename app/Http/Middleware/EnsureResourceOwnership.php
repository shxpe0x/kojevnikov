<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class EnsureResourceOwnership
{
    /**
     * Handle an incoming request.
     *
     * Logs suspicious attempts to access resources without proper authorization.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Log 403 responses as potential security issues
        if ($response->status() === 403) {
            Log::warning('Unauthorized resource access attempt', [
                'user_id' => $request->user()?->id,
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
            ]);
        }

        return $response;
    }
}

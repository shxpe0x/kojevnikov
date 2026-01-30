<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiVersion
{
    /**
     * Handle API versioning.
     */
    public function handle(Request $request, Closure $next, string $version = 'v1'): Response
    {
        $request->merge(['api_version' => $version]);

        return $next($request);
    }
}

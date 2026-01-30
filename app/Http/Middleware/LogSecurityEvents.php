<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogSecurityEvents
{
    /**
     * List of routes that should trigger security logging
     */
    private const SECURITY_ROUTES = [
        'login',
        'register',
        'logout',
        'password/reset',
        'password/email',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Log failed authentication attempts
        if ($this->isAuthRoute($request) && $this->isFailedResponse($response)) {
            $this->logSecurityEvent('failed_authentication', $request, $response);
        }

        // Log successful logins
        if ($request->is('api/login') && $this->isSuccessResponse($response)) {
            $this->logSecurityEvent('successful_login', $request, $response);
        }

        // Log logout events
        if ($request->is('api/logout')) {
            $this->logSecurityEvent('logout', $request, $response);
        }

        // Log registration attempts
        if ($request->is('api/register')) {
            $this->logSecurityEvent('registration_attempt', $request, $response);
        }

        // Log suspicious activity (multiple failed requests from same IP)
        if ($this->isSuspiciousActivity($request, $response)) {
            $this->logSecurityEvent('suspicious_activity', $request, $response);
        }

        return $response;
    }

    /**
     * Check if the route is an authentication route
     */
    private function isAuthRoute(Request $request): bool
    {
        foreach (self::SECURITY_ROUTES as $route) {
            if ($request->is("*{$route}*")) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if response indicates failure
     */
    private function isFailedResponse(Response $response): bool
    {
        return in_array($response->getStatusCode(), [401, 403, 422, 429]);
    }

    /**
     * Check if response indicates success
     */
    private function isSuccessResponse(Response $response): bool
    {
        return $response->getStatusCode() >= 200 && $response->getStatusCode() < 300;
    }

    /**
     * Detect suspicious activity patterns
     */
    private function isSuspiciousActivity(Request $request, Response $response): bool
    {
        // Log 403 Forbidden (unauthorized access attempts)
        if ($response->getStatusCode() === 403) {
            return true;
        }

        // Log 429 Too Many Requests (rate limit exceeded)
        if ($response->getStatusCode() === 429) {
            return true;
        }

        return false;
    }

    /**
     * Log security event with context
     */
    private function logSecurityEvent(string $type, Request $request, Response $response): void
    {
        $context = [
            'type' => $type,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'status_code' => $response->getStatusCode(),
            'user_id' => $request->user()?->id,
            'timestamp' => now()->toIso8601String(),
        ];

        // Log to security channel
        Log::channel('stack')->warning("Security Event: {$type}", $context);
    }
}
